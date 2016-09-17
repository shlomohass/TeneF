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
    
    public function get_unit_am_list_for_report($unitId, $conn) {
        $results = $conn->get_joined(
            array(
                array("LEFT JOIN","amlah_list.am_list_of_type","amlah_type.am_type_id"), 
                array("LEFT JOIN","amlah_type.am_type_of_group","amlah_group.am_group_id"),
                array("LEFT JOIN","amlah_list.am_list_location","location.loc_name")
            ), 
            "`amlah_list`.`am_list_id`, 
             `amlah_list`.`am_list_number`, 
             `amlah_list`.`am_list_yeud`, 
             `amlah_list`.`am_list_location`, 
             `location`.`loc_name`, 
             `location`.`loc_is_border`, 
             `location`.`loc_is_base`, 
             `location`.`loc_is_terain`, 
             `location`.`loc_is_civilian`,
             `amlah_list`.`am_list_status`, 
             `amlah_list`.`am_list_status_exp`, 
             `amlah_list`.`am_list_status_exp_log`, 
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
}
/*

*/