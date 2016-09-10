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
    
    public function get_unit_am_list($unitId, $conn) {
        $results = $conn->get_joined(
            array(array("LEFT","amlah_list.am_list_of_type","amlah_type.am_type_id")), 
            "`amlah_list`.`am_list_id`, `amlah_list`.`am_list_number`, `amlah_list`.`am_list_yeud`, `amlah_type`.`am_type_name`",
            "`amlah_list`.`am_list_of_unit` = ".$conn->filter($unitId),
            false,
            array(array("amlah_type.am_type_name"),"DESC")
        );
        return (!empty($results))?$results:array();
    }
}
