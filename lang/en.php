<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$Lang = array( 
    "lang" => "English",
    "dic"  => array(
    //GENERAL:
        "gen_title_prefix"              => "TeneF | ",
        "gen_title_for_display"         => "דיווח וניתוח כשירות",
        
    //Login page:
        "login_title"                   => "Login",
        "login_desc"                    => "",
        "login_keys"                    => "",
    
    //Admin nav right:
        "admin_nav_dashboard"           => "שולחן עבודה",
        "admin_nav_makereport"          => "דיווח כשירות",
        "admin_nav_showreport"          => "הפק דוח כשירות",
        "admin_nav_stats"               => "ניתוח כשירות",
        "admin_nav_inventory"           => "הגדר סדכ",
    
    //Page Dashboard:
        "page_dash_title"               => "שולחן עבודה",
        
    //Page Make Report:
        "page_makereport_title"             => "דיווח כשירות",
        "page_makereport_select_unit_label" => "בחר יחידה",
        "page_makereport_select_rep_label"  => "טען דוח קודם",
        "page_makereport_but_load_unit"     => "טען יחידה",
        "page_makereport_but_edit_rep"      => "טען לעריכה",
        "page_makereport_but_unload_unit"   => "בטל טעינה",
        "page_makereport_loaded_unit_title" => "יחידה: ",
        "page_makereport_newRep_title"      => "הוספת דוח חדש לתאריך: ",
        "page_makereport_but_create_report" => "הפק דוח כשירות",
        
        //Edit location Modal:
        "page_makereport_modal_header"      => "ערוך מיקום",
        "page_makereport_modal_header_add"  => "הוסף מיקום",
        "page_makereport_modal_loc_name_place"  => "הקלד שם",
        "page_makereport_modal_select_loc_head"  => "בחר מיקום",
        "page_makereport_modal_but_loc_new"  => "הוסף מיקום וסגור",
        "page_makereport_modal_but_loc_select"  => "בחר מיקום וסגור",
        "page_makereport_modal_but_loc_add"  => "סגור עריכת מיקום",
        "page_makereport_modal_loc_exists"  => "מיקום זה כבר מוזן - אנא בחר את המיקום מלמטה",
        "page_makereport_label_terrain"     => "שטח",
        "page_makereport_label_border"      => "גבול",
        "page_makereport_label_civil"       => "אזרחי",
        "page_makereport_label_base"        => "בסיס",
        
    //Page Dashboard:
        "page_showreport_title"         => "הפק דוח כשירות",
        
    //Page Dashboard:
        "page_stats_title"              => "ניתוח כשירות",
        
    //Page Inventory:
        //Unit table:
        "page_inventory_title"          => "הגדר סדכ",
        "inven_table_header_id"         => "מזהה",
        "inven_table_header_unit"       => "יחידה",
        "inven_table_header_type"       => "סוג",
        "inven_table_header_ofunit"     => "יחידת בת של",
        "inven_table_header_place"      => "מיקום",
        "inven_table_header_gen"        => "כללי",
        "inven_table_header_actions"    => "פעולות",
        
        //Edit sadac madal:
        "inven_modal_gen_header"                => 'עדכון סד"כ: ',
        "inven_modal_addam_header"              => 'הוסף:',
        "inven_modal_amlist_header"             => 'סד"כ מוזן:',
        "inven_modal_input_label_am_id"         => 'מס צ:',
        "inven_modal_input_placeholder_am_id"   => '000000',
        "inven_modal_input_label_am_type"       => 'סוג: ',
        "inven_modal_input_label_am_yeud"       => 'ייעוד: ',
        "inven_modal_input_placeholder_am_yeud" => 'ייעוד',
        "inven_modal_but_add_am"                => 'הוסף',
        "inven_modal_but_close_ammodal"         => 'סגור וחזור',
        
    //App Page:
        "home_title"                    => "TeneF Home",
        "home_desc"                     => "",
        "home_keys"                     => "",
        
    //Admin Pages:
        "admin_title"                    => "ניתוח ודיווח כשירות",
        "admin_desc"                     => "",
        "admin_keys"                     => ""
    ),
    
    "js" => array(
        "script-frontend" => array(

        ),
        "script-login" => array(

        ),
        "script-admin" => array(
            
            //MakeReport window:
            "makerep_placeholder_select_unit"       => "בחר יחידה לטעינה",
            "makerep_placeholder_select_prevrep"    => "בחר דוח קודם",
            "makerep_warn_select_unit"              => "עליך לבחור יחידה לפני טעינתה",
            "makerep_error_load_unit"               => "אירעה שגיאה בעת טעינת יחידה",
            "makerep_error_load_loc"                => "אירעה שגיאה בטיענת רשימת מיקומים",
            "makerep_placeholder_select_loc"        => "בחר מיקום",
            "makerep_warn_setlocname"               => "אנא הזן שם מיקום",
            "makerep_warn_setlocname_exists"        => "מיקום חדש כבר מוזן במערכת בחר מהרשימה מתחת - לא ניתן להזין אותו מיקום.",
            "makerep_error_savenew_loc"             => "אירעה שגיאה בעת שמירת מיקום חדש במערכת",
            "makerep_warn_select_loc_empty"         => "עליך לבחור מיקום מהרשימה הנפתחת טרם השמירה",
            
            //inventory window:
            "inven_modal_but_edit_sadac"            => 'נהל סד"כ',
            "inven_modal_but_erase_unit"            => 'מחק יחידה',
            "inven_modal_but_edit_unit"             => 'עדכן יחידה',
            
            //Amlah list in modal view:
            "header_table_units_amnum" => "מס צ",
            "header_table_units_amtype" => 'סוג אמלח',
            "header_table_units_amyeud" => "ייעוד / שייכות",
            "header_table_units_amactions" => "פעולות",
            "header_table_units_amnodata" => "לא מוזן סדכ ליחידה זאת",
            "err_save_units_amlist" => "אירעה שגיאה בעת הזנת אמלח ליחידה זאת",
            "err_load_units_amlist" => "אירעה שגיאה בעת טעינת רשימת אמלח ליחדיה זאת"

        )
    )
);
