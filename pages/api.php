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
        
            
        /**** List Locations: ****/
        case "listlocations":  
            
            //Logic:
            $Op = new Operation();
            
            $locList = $Op->get_location_list($Api::$conn);
            
            //Output:
            if (is_array($locList)) {
               $results = array(
                   "locations" => $locList,
                );
                $success = "with-results";
            } else {
                $Api->error("results-false");
            }
            
        break;
        
        /**** List Locations: ****/
        case "listparts":  
            
            //Logic:
            $Op = new Operation();
            
            $partList = $Op->get_parts_list($Api::$conn);
            
            //Output:
            if (is_array($partList)) {
               $results = array(
                   "parts" => $partList,
                );
                $success = "with-results";
            } else {
                $Api->error("results-false");
            }
            
        break;
            
        /**** Set new Part: ****/
        case "addnewpartcat":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('partnum','partname','partdesc'),false);
            
            //Validation input:
            if (
                empty($get['partnum']) || 
                empty($get['partname'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            $newPart = array();
            
            //validate:
            if (!$Op->validate_part_unique($Api::$conn, $get['partnum'])) {
                $Api->error("not-uni");
            }
            
            //Create:
            $newPart = $Op->add_new_part($Api::$conn,$get['partnum'],$get['partname'],$get['partdesc'],$User->user_info);
            
            //Output:
            if (!empty($newPart)) {
               $results = array(
                   "newPart" => array(
                        "partnum"  => strtoupper(trim($get['partnum'])),
                        "partname" => trim($get['partname']),
                        "partdesc" => trim($get['partdesc'])
                   )
                );
                $success = "with-results";
            } else {
                $Api->error("query");
            }
        break;
            
        /**** Set new Location: ****/
        case "addlocation":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('locname','locisbase','lociscivil','locisterrain','locisborder'),false);
            
            //Validation input:
            if (
                    empty($get['locname'])
            ) {
                $Api->error("not-legal");
            }
            
            //Logic:
            $Op = new Operation();
            $newLoc = array();
            
            //validate:
            if (!$Op->validate_location_unique($Api::$conn, $get['locname'])) {
                $Api->error("not-uni");
            }
            
            //Create:
            $newLoc = $Op->add_new_location($Api::$conn,$get['locname'],$get['locisbase'],$get['lociscivil'],$get['locisterrain'],$get['locisborder']);
            
            //Output:
            if (!empty($newLoc)) {
               $results = array(
                   "newloc" => array(
                        "locname" => trim($get['locname']),
                        "locid"   => $newLoc  
                   ),
                   "from" => array(
                       "locname" => $get['locname'], 
                       "locisbase" => $get['locisbase'],
                       "lociscivil" => $get['lociscivil'],
                       "locisterrain" => $get['locisterrain'],
                       "locisborder" => $get['locisborder']
                   )
                );
                $success = "with-results";
            } else {
                $Api->error("query");
            }
        break;
            
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
        
        /**** Get Unit List for user display: ****/
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
            
            //The Data set:
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
            $temp_buf = array();
            if (!$userGod && is_array($data)) {
                foreach ($data as $key => $unit) {
                    $has_unit = $Api->Func->search_by_value_pair($userListPriv, "priv_on_unit", $unit['unit_id'], "priv_user");
                    if ($has_unit) {
                        $temp_buf[] = $unit;
                    }
                } 
                $data = $temp_buf;
            }
            
            //The count:
            $totalData = $Api::$conn->num_rows("SELECT * FROM `unit_list`");
            $totalFiltered = $totalData;
            
            //Create the data set:
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
                "data"            => $final_data,   // total data array
                "user_priv"       => $userListPriv,
                "user_god"        => $userGod
			);
            echo json_encode($results); 
            die();
        break;

        /**** Add amlah to unit: ****/
        case "addamtounit":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('amnum','amtype','amyeud','unit_id'),false);
            //Validation:
            if (
                    empty($get['amnum'])
                ||  !is_numeric($get['amnum'])
                ||  empty($get['amtype'])
                ||  !is_numeric($get['amtype'])
                ||  empty($get['unit_id'])
                ||  !is_numeric($get['unit_id'])
                ||  empty($get['amyeud'])
            ) {
                $Api->error("not-legal");
            }
            
            //User privs:
            $userGod = $User->is_god();
            $amTypes = $Api::$conn->get("amlah_type");
            
            //More validation - user privs:
            if (!$userGod) {
                $userPrivsOnUnit = $User->check_privs_on_unit($get['unit_id']);
                if (!$userPrivsOnUnit['has'] || !$userPrivsOnUnit['am']) {
                    $Api->error("no-priv");
                } 
            }
            //More validation - data types:
            
            //Params:
            $added = false;
            $Op = new Operation();
            //Logic:
            $added = $Op->add_am_to_unit($Api::$conn, $get['amnum'], $get['amtype'], $get['amyeud'], $get['unit_id'], $User->user_info);
            
            //Output:
            if ($added) {
               $results = array(
                   "amAdded" => $get,
                   "ofunit" => $get['unit_id']
                );
                $success = "with-results";
            } else {
                $Api->error("query");
            }
            
        break;
        
        /**** get amlist of unit for report: ****/
        case "loadunittoreportscreen":
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('unit_id'),false);
            //Validation:
            if (
                    empty($get['unit_id'])
                ||  !is_numeric($get['unit_id'])
            ) {
                $Api->error("not-legal");
            }
            
            $Op = new Operation();
            
            //Test Priv on unit:
            $userGod = $User->is_god();
            $userPrivsOnUnit = $User->check_privs_on_unit($get['unit_id']);
            
            $amList = false;
            $prevList = false;
            
            if ($userGod || $userPrivsOnUnit['has']) {
                
                $amList = $Op->get_unit_am_list_for_report($get['unit_id'], $Api::$conn);
                $prevList = $Op->get_unit_prev_reports($get['unit_id'], $Api::$conn);
                $Unit = $Op->get_unit_info($get['unit_id'], $Api::$conn);
                
            } else {
                $Api->error("no-priv");
            }
            
            //Output:
            if (is_array($amList) && is_array($prevList)) {
               $results = array(
                   "amlist"     => $amList,
                   "repList"    => $prevList,
                   "ofunit"     => isset($Unit[0]) ? $Unit[0] : array()
                );
                $success = "with-results";
            } else {
                $Api->error("query");
            }
        break;
            
        /**** save bew report: ****/
        case "createnewreport":
            
            //Synth needed:
            $get = $Api->Func->synth($_REQUEST, array('set'),false);
            
            //Validation:
            if (
                !is_array($get['set'])
            ) {
                $Api->error("not-legal");
            }
            //User privs:
            $userGod = $User->is_god();
            $userListPriv = $User->list_privs();
            
            //Logic:
            $Op = new Operation();
            
            //Get current user name:
            $user = $Api::$conn->get("users",$User->user_info);
            
            //First create a report:
            $rep = $Op->create_report_new($Api::$conn, $get['set'][0]['ofUnit'], $User->user_info, $user["username"]);
            if (empty($rep)) {
                $Api->error("query");
            }
            
            //Push rep logs:
            $logs = $Op->create_report_logs($Api::$conn,$rep[0]["report_id"],$get['set']);
            if (!$logs) {
                $Api->error("query");
            }
            
            //Set current amList:
            $list = $Op->update_amlist_withset($Api::$conn,$rep[0]["report_id"],$get['set']);
            if (!$list) {
                $Api->error("query");
            }
            
            //Output:
            if (is_array($rep)) {
               $results = array(
                   "repRow"     => $rep
                );
                $success = "with-results";
            } else {
                $Api->error("query");
            }
            
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