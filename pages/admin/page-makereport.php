<?php

Trace::add_step(__FILE__,"Loading Sub Page: admin -> makereport");

include_once PATH_CLASSES."Operation.class.php";
$Op = new Operation();

//Load all amlah types:
Trace::add_step(__FILE__,"Loading needed tables");
$Page->variable("all-am-types", $Page::$conn->get("amlah_type"));
$Page->variable("all-am-groups", $Page::$conn->get("amlah_group"));
$Page->variable("all-am-status", $Page::$conn->get("amlah_status"));

//Get user privs:
Trace::add_step(__FILE__,"Loading needed user info");
$Page->variable("god", $User->is_god());
$Page->variable("all-units-priv", $User->list_privs());

//Get unit list:
Trace::add_step(__FILE__,"Loading needed units arrays");
$Page->variable("all-units", $User->get_user_units());

//Log:
Trace::reg_var("units of user",$Page->variable("all-units"));
Trace::reg_var("units of user priv",$Page->variable("all-units-priv"));
Trace::reg_var("user_god",$Page->variable("god"));
Trace::reg_var("all-am-types",$Page->variable("all-am-types"));
Trace::reg_var("all-am-groups",$Page->variable("all-am-groups"));
Trace::reg_var("all-am-status",$Page->variable("all-am-status"));
?>
<h2><?php Lang::P("page_makereport_title"); ?></h2>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="makereport_unit_set" style='margin-bottom:0px;'>
                <?php Lang::P("page_makereport_select_unit_label"); ?>&nbsp;&nbsp;
                <select class="" dir='rtl' lang='he' style='width:200px;' id="makereport_unit_set" placeholder="">
                    <?php
                        foreach ($Page->variable("all-units") as $unit) {
                            echo "<option value='".$unit["unit_id"]."'>".$unit["unit_type"]." - ".$unit["unit_name"]."</option>";
                        }
                    ?>
                </select>
            </label>
        </div>
    </div>
</div>
<div class="makerep_prev_report">
    <div class="col-sm-12">
        <form id="make_rep_edit_rep"class="form-inline">
            <div class="form-group">
                <label for="makereport_unit_set" style='margin-bottom:0px;'>
                    <?php Lang::P("page_makereport_select_rep_label"); ?>&nbsp;&nbsp;
                    <select class="" dir='rtl' lang='he' style='width:250px;' id="makereport_report_select" placeholder="">
                        <?php
                            foreach ($Page->variable("all-units") as $unit) {
                                echo "<option value='".$unit["unit_id"]."'>".$unit["unit_type"]." - ".$unit["unit_name"]."</option>";
                            }
                        ?>
                    </select>
                </label>
            </div>
            <button type="button" class="btn btn-primary" onclick="">
                      <?php Lang::P("page_makereport_but_edit_rep"); ?>
            </button>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
<div class="makerep_new_report">
    <div class="col-sm-12">
    </div>
    <div class="clearfix"></div>
</div>
<script>
    
    //Unit select box:
    $("#makereport_unit_set").select2();
    $("#makereport_report_select").select2();
</script>