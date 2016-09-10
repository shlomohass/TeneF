<?php
/****************************** secure api include ***********************************/
if (!isset($conf)) { die("E:01"); }

/****************************** Build API ********************************************/
$Api = new api( $conf );

/****************************** Needed Values ****************************************/
$inputs = $Api->Func->synth($_POST, array('type'));

/****************************** Building response ***********************************/
$success = "general";
$results = false;

/****************************** API Logic  ***********************************/
if ( $inputs['type'] !== '' ) {
    
    switch (strtolower($inputs['type'])) {
        
        /**** Show A unit List of Amlah: ****/
        case "listamlahofunit":  
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('unit'),false);
            
            //Validation:
            if (
                    empty($get['unit'])
                ||  !is_numeric($get['unit'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            
            //Test Priv on unit:
            $userGod = $User->is_god();
            $userPrivsOnUnit = $User->check_privs_on_unit($get['unit']);
            $unitList = false;
            if ($userGod || $userPrivsOnUnit['has']) {
                $unitList = $Op->get_unit_am_list($get['unit'], $Api::$conn);
            } else {
                $Api->error("no-priv");
            }
            //Output:
            if (is_array($unitList)) {
               $results = array(
                   "amlist" => $unitList,
                   "ofunit" => $get['unit'],
                   "editPriv" => ($userGod || ($userPrivsOnUnit['has'] && $userPrivsOnUnit['am']))?true:false
                );
                $success = "with-results";
            } else {
                $Api->error("results-false");
            }
        break;
        
        case "listmanageunits":
            $get = $Api->Func->synth($_REQUEST, array('draw','columns','order','start','length','search'),false);
            $get["order"] = (isset($_REQUEST["order"]) && is_array($_REQUEST["order"]))?$_REQUEST["order"]:"";
            $get["search"] = (isset($_REQUEST["search"]) && is_array($_REQUEST["search"]))?$_REQUEST["search"]:"";
            $columns = array( 
                // datatable column index  => database column name
                0 => 'unit_id',
                1 => 'unit_name', 
                2 => 'unit_type',
                3 => 'unit_of',
                4 => 'unit_location',
                5 => 'unit_info'
            );
            
            if (empty($get['order'])) {
                $get['order'] = array(
                    array( "column" => 0, "dir" => "asc")
                );
            }
            
            //User privs:
            $userGod = $User->is_god();
            $userListPriv = $User->list_privs();
            
            //The Data set
            $where = false;
            if( !empty($get['search']['value']) ) {
               $where = array();
               foreach($columns as $col) {
                   $where[] = "`".$col."` LIKE '".$Api::$conn->filter($get['search']['value'])."%' ";
               }
               $where = implode("OR ", $where);
            }
            $data = $Api::$conn->get_joined(
                array(
                   array('LEFT', 'unit_list.unit_location','location.loc_id')
                ), 
                " `unit_id`,`unit_name`,`unit_type`,`unit_of`,`loc_name`,`unit_info` ",
                $where,
                false,
                array(
                    $get['order'][0]['dir'],
                    array($columns[$get['order'][0]['column']])
                ),
                array(
                    $get['start'],
                    $get['length']
                )
            );
            
            //Filter by priv
            if (!$userGod && is_array($data)) {
                foreach ($data as $key => $unit) {
                    if ($unit['unit_id'])
                } 
            }
            
            //The count:
            $totalData = $Api::$conn->num_rows("SELECT * FROM `unit_list`");
            $totalFiltered = $totalData;
            
            $where = false;
            if( !empty($get['search']['value']) ) {
               $where = array();
               foreach($columns as $col) {
                   $where[] = "`".$col."` LIKE '".$Api::$conn->filter($get['search']['value'])."%' ";
               }
               $where = implode("OR ", $where);
            }
            $data = $Api::$conn->get_joined(
                    array(
                       array('LEFT', 'unit_list.unit_location','location.loc_id')
                    ), 
                    " `unit_id`,`unit_name`,`unit_type`,`unit_of`,`loc_name`,`unit_info` ",
                    $where,
                    false,
                    array(
                        $get['order'][0]['dir'],
                        array($columns[$get['order'][0]['column']])
                    ),
                    array(
                        $get['start'],
                        $get['length']
                    )
                );
            $final_data = array();
            if (is_array($data)) {
                
                $all_units = $Api::$conn->get("unit_list");
                
                foreach ($data as $key => $dunit) {
                    
                    $of_unit = $Api->Func->search_by_value_pair($all_units, "unit_id", $dunit['unit_of'], "unit_name");
                    $final_data[] = array(
                        $dunit['unit_id'],
                        $dunit['unit_name'],
                        $dunit['unit_type'],
                        !empty($of_unit)?$of_unit:NULL,
                        $dunit['loc_name'],
                        $dunit['unit_info'],
                        NULL
                    );
                }
            } else {
                //Error:
            }
            $results = array(
                "draw"            => intval( $get['draw'] ), 
                "recordsTotal"    => intval( $totalData ),  // total number of records
                "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data"            => $final_data   // total data array
			);
            echo json_encode($results); 
            die();
        break;
        //Unknown type - error:
        default : 
            $Api->error("bad-who");
        
    }
    
    //Run Response generator:
    $Api->response($success, $results);
    
} else {
    $Api->error("not-secure");
}

//Kill Page.
exit;