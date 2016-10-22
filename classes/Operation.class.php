<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Operation {
    
    public function __construct() {
        Trace::add_trace('construct class',__METHOD__);  
    }
    
    /* Get all the saved location list:
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_location_list($conn) {
        $results = $conn->get("location");
        return (!empty($results))?$results:array();
    }
    
    /* Get all the saved parts catalog:
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_parts_list($conn) {
        $results = $conn->get("amlah_parts_cat");
        return (!empty($results))?$results:array();
    }
    
    /* Get A unit information provide unit ID:
     * @param $unitId -> Integer.
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_unit_info($unitId, $conn) {
        $results = $conn->get_joined(
            array(
                array("LEFT JOIN","unit_list.unit_location","location.loc_id")
            ), 
            "`unit_list`.`unit_id`, 
             `unit_list`.`unit_name`, 
             `unit_list`.`unit_type`, 
             `unit_list`.`unit_location`, 
             `unit_list`.`unit_info`, 
             `location`.`loc_name`, 
             `location`.`loc_is_border`, 
             `location`.`loc_is_base`, 
             `location`.`loc_is_terain`, 
             `location`.`loc_is_civilian`
            ",
            "`unit_list`.`unit_id` = ".$conn->filter($unitId)
        );
        return (!empty($results))?$results:array();
    }
    
    /* Get A unit amlah list provide unit ID:
     * @param $unitId -> Integer.
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_unit_am_list($unitId, $conn) {
        $results = $conn->get_joined(
            array(
                array("LEFT JOIN","amlah_list.am_list_of_type","amlah_type.am_type_id"), 
                array("LEFT JOIN","amlah_type.am_type_of_group","amlah_group.am_group_id")
            ), 
            "`amlah_list`.`am_list_id`, 
             `amlah_list`.`am_list_number`, 
             `amlah_list`.`am_list_yeud`, 
             `amlah_type`.`am_type_name`, 
             `amlah_type`.`am_type_of_group`, 
             `amlah_group`.`am_group_name`,
             `amlah_group`.`am_group_display_order`
            ",
            "`amlah_list`.`am_list_of_unit` = ".$conn->filter($unitId),
            false,
            array(
                array("amlah_group.am_group_display_order", "amlah_type.am_type_name"),
                "ASC"
            )
        );
        return (!empty($results))?$results:array();
    }
    
    /* Get A unit prev reports list:
     * @param $unitId -> Integer.
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_unit_prev_reports($unitId, $conn) {
        $results = $conn->select(
            "amlah_reports", 
            " * ",
            array(array("report_unit","=",$unitId)),
            false,
            array(
                "DESC",
                array("report_date")
            ),
            array(30)
        );
        return (!empty($results))?$results:array();
    }
    
    /* Get A unit amlah list provide unit ID -> extended:
     * @param $unitId -> Integer.
     * @param $conn -> DB connection.
     * @return Array()
     */
    public function get_unit_am_list_for_report($unitId, $conn) {
        $results = $conn->get_joined(
            array(
                array("LEFT JOIN","amlah_list.am_list_of_type","amlah_type.am_type_id"), 
                array("LEFT JOIN","amlah_list.am_list_location","location.loc_id"),
                array("LEFT JOIN","amlah_type.am_type_of_group","amlah_group.am_group_id"),
                array("LEFT JOIN","amlah_list.am_list_status","amlah_status.am_status_id"),
                array("LEFT JOIN","amlah_list.am_list_dereg","repare_deg.deg_id")
            ), 
            "`amlah_list`.`am_list_id`, 
             `amlah_list`.`am_list_number`, 
             `amlah_list`.`am_list_yeud`, 
             `amlah_list`.`am_list_location`, 
             `amlah_list`.`am_list_of_unit`,
             `location`.`loc_name`, 
             `location`.`loc_is_border`, 
             `location`.`loc_is_base`, 
             `location`.`loc_is_terain`, 
             `location`.`loc_is_civilian`,
             `amlah_list`.`am_list_status`, 
             `amlah_status`.`am_status_name`, 
             `amlah_status`.`am_status_color`, 
             `amlah_list`.`am_list_status_exp`, 
             `amlah_list`.`am_list_status_exp_log`,
             `amlah_list`.`am_list_parts_req`, 
             `amlah_list`.`am_list_dereg`,
             `repare_deg`.`deg_name`, 
             `amlah_list`.`am_list_indereg_since`, 
             `amlah_list`.`am_list_forecast`, 
             `amlah_type`.`am_type_id`,
             `amlah_type`.`am_type_name`, 
             `amlah_type`.`am_type_of_group`, 
             `amlah_group`.`am_group_name`, 
             `amlah_group`.`am_group_display_order` 
            ",
            "`amlah_list`.`am_list_of_unit` = ".$conn->filter($unitId),
            false,
            array(
                array("amlah_group.am_group_display_order", "amlah_type.am_type_name"),
                "ASC"
            )
        );
        return (!empty($results))?$results:array();
    }
    
    /* Save amlah to unit -> Creates a new amlah row:
     * @param $conn -> DB connection.
     * @param $am_num -> String.
     * @param $am_type -> Integer.
     * @param $am_yeud -> String.
     * @param $unit_id -> Integer.
     * @param $by_user -> Integer.
     * @return Boolean
     */
    public function add_am_to_unit($conn, $am_num, $am_type, $am_yeud, $unit_id, $by_user) {
        return $conn->insert_safe(
            "amlah_list",
            array(
                "am_list_number"    => $am_num,
                "am_list_yeud"      => $am_yeud,
                "am_list_of_type"   => $am_type,
                "am_list_status"    => 1,
                "am_list_of_unit"   => $unit_id,
                "am_list_added_by"  => $by_user
            )
        );
    }

    /* Check if a location name proposal is unique:
     * @param $conn -> DB connection.
     * @param $loc_name -> string.
     * @return Boolean
     */
    public function validate_location_unique($conn, $loc_name) {
        $test = $conn->select(
            "location", "* ", array(
                array(
                    'loc_name', '=', trim($loc_name)
                )
            )
        );
        if (empty($test)) {
            return true;
        }
        return false;
    }
    
    /* Check if a part num proposal is unique:
     * @param $conn -> DB connection.
     * @param $part_num -> string.
     * @return Boolean
     */
    public function validate_part_unique($conn, $part_num) {
        $test = $conn->select(
            "amlah_parts_cat", "* ", array(
                array(
                    'pcat_num', '=', strtoupper(trim($part_num))
                )
            )
        );
        if (empty($test)) {
            return true;
        }
        return false;
    }
    
    /* Save A new Location To DB:
     * @param $conn -> DB connection.
     * @param $loc_name -> string.
     * @param $loc_base -> string.
     * @param $loc_civil -> string.
     * @param $loc_terrain -> string.
     * @param $loc_border -> string.
     * @return Integer / Boolean
     */
    public function add_new_location($conn, $loc_name = "", $loc_base = "", $loc_civil = "", $loc_terrain = "", $loc_border = "") {
        if (!is_string($loc_name) || empty(trim($loc_name))) {
            return false;
        }
        $input = array(
            "loc_name"          => trim($loc_name),
            "loc_is_border"     => (empty($loc_border)?0:1),
            "loc_is_base"       => (empty($loc_base)?0:1),
            "loc_is_terain"     => (empty($loc_terrain)?0:1),
            "loc_is_civilian"   => (empty($loc_civil)?0:1)
        );
        $test = $conn->insert_safe("location", $input);
        if ($test) {
            return $conn->lastid();
        }
        return false;
    }
    
    /* Save A new Part To DB:
     * @param $conn -> DB connection.
     * @param $part_num -> string.
     * @param $part_name -> string.
     * @param $part_desc -> string.
     * @param $uid -> integer.
     * @return Boolean
     */
    public function add_new_part($conn, $part_num, $part_name, $part_desc = "", $uid = 'NULL') {
        
        $input = array(
            "pcat_num"      => strtoupper(trim($part_num)),
            "pcat_name"     => trim($part_name),
            "pcat_desc"     => trim($part_desc),
            "pcat_added_by" => $uid,
            "pcat_added_by" => $uid,
        );
        $test = $conn->insert_safe("amlah_parts_cat", $input);
        if ($test) {
            return true;
        }
        return false;
    }

    public function create_report_new($conn, $unit, $by, $by_name) {
        $test = $conn->insert_safe(
            "amlah_reports",
            array(
                "report_unit"       => intval($unit),
                "report_by"         => intval($by),
                "report_by_name"    => trim($by_name),
                "report_date"       => "CURDATE()",
                "report_hour"       => "CURTIME()"
            )
        );
        if ($test) {
            $last_row = $conn->lastid();
            return $conn->select(
                "amlah_reports",
                '* ',
                array(
                    array("report_id","=",$last_row)
                )
            );
        }
        return false;
    }
    
    public function create_report_logs($conn, $rep_id, $rows) {
        foreach ($rows as $key => $row) {
            $test = $conn->insert_safe(
                "amlah_rep_log",
                array(
                    "amrep_amlah_num"       => $row["amNum"],
                    "amrep_amlah_type"      => $row["amType"],
                    "amrep_amlah_status"    => $row["status"] == "-1" ? "NULL" : $row["status"],
                    "amrep_status_exp"      => $row["statusExp"],
                    "amrep_status_exp_log"  => "",
                    "amrep_parts_req"       => html_entity_decode($row["parts"]),
                    "amrep_dereg"           => $row["dereg"] == "-1" ? "NULL" : $row["dereg"],
                    "amrep_indereg_since"   => $row["indereg"] === "" ? "NULL" : $row["indereg"],
                    "amrep_forecast"        => $row["forecast"] === "" ? "NULL" : $row["forecast"],
                    "amrep_location"        => $row["place"] == "0" ? "NULL" : $row["place"],
                    "amrep_of_report"       => $rep_id,
                    "amrep_comment"         => "NULL"
                )
            );
            if (!$test) {
                return false;
            }
        }
        return true;
    }
    
    public function update_amlist_withset($conn, $rep_id, $rows) {
        foreach ($rows as $key => $row) {
            $test = $conn->update(
                "amlah_list",
                array(
                    "am_list_status"        => $row["status"] == "-1" ? "NULL" : $row["status"],
                    "am_list_status_exp"      => $row["statusExp"],
                    "am_list_status_exp_log"  => "",
                    "am_list_parts_req"       => html_entity_decode($row["parts"]),
                    "am_list_dereg"           => $row["dereg"] == "-1" ? "NULL" : $row["dereg"],
                    "am_list_indereg_since"   => $row["indereg"] === "" ? "NULL" : $row["indereg"],
                    "am_list_forecast"        => $row["forecast"] === "" ? "NULL" : $row["forecast"],
                    "am_list_location"        => $row["place"] == "0" ? "NULL" : $row["place"]
                ),
                array(
                    array("am_list_id","=",$row["rowId"])
                )
            );
            if (!$test) {
                return false;
            }
        }
        return true;
    }
}
