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
        <div class="form-group" style='display:inline-block;'>
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
        <button type="button" id="make_but_load_unit" class="btn btn-primary" onclick="window.teneReport.loadUnit(this);" style='display:inline-block;'>
            <?php Lang::P("page_makereport_but_load_unit"); ?>
        </button>
        <button type="button" class="btn btn-warning" onclick="window.teneReport.unloadUnit();" style='display:inline-block;'>
            <?php Lang::P("page_makereport_but_unload_unit"); ?>
        </button>
    </div>
    <div class="clearfix"></div>
    <h4 class='makerep_loaded_unit_title'><?php Lang::P("page_makereport_loaded_unit_title"); ?><span>84</span></h4>
</div>
<div class="makerep_prev_report">
    <div class="col-sm-12">
        <form id="make_rep_edit_rep"class="form-inline">
            <div class="form-group">
                <label for="makereport_report_select" style='margin-bottom:0px;'>
                    <?php Lang::P("page_makereport_select_rep_label"); ?>&nbsp;&nbsp;
                    <select class="" dir='rtl' lang='he' style='width:250px;' id="makereport_report_select" placeholder="">
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
        <?php Lang::P("page_makereport_newRep_title"); ?><span><?php echo date("d.m.Y"); ?></span>
        <div class="new_rep_container">
            <table class="new_rep_table">
                <thead>
                    <tr>
                        <th>מס צ'</th>
                        <th>סוג</th>
                        <th>שייכות / ייעוד</th>
                        <th>מיקום</th>
                        <th>כשירות</th>
                        <th>פירוט מצב כשירות</th>
                        <th>היסטוריית כשירות</th>
                        <th>הערה</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>654665</td>
                        <td>דוד</td>
                        <td>חפ"ק</td>
                        <td>מפגש</td>
                        <td>כשיר</td>
                        <td>בעיית גיר והנעה</td>
                        <td>היסטוריית כשירות</td>
                        <td>הערות הולכות כאן</td>
                    </tr>
                    <tr>
                        <td>654665</td>
                        <td>דוד</td>
                        <td>חפ"ק</td>
                        <td>מפגש</td>
                        <td>כשיר</td>
                        <td>בעיית גיר והנעה</td>
                        <td>היסטוריית כשירות</td>
                        <td>הערות הולכות כאן</td>
                    </tr>
                    <tr>
                        <td>654665</td>
                        <td>דוד</td>
                        <td>חפ"ק</td>
                        <td>מפגש</td>
                        <td>כשיר</td>
                        <td>בעיית גיר והנעה</td>
                        <td>היסטוריית כשירות</td>
                        <td>הערות הולכות כאן</td>
                    </tr>
                    <tr>
                        <td>654665</td>
                        <td>דוד</td>
                        <td>חפ"ק</td>
                        <td>מפגש</td>
                        <td>כשיר</td>
                        <td>בעיית גיר והנעה</td>
                        <td>היסטוריית כשירות</td>
                        <td>הערות הולכות כאן</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<script>
    
    //Unit select box:
    $("#makereport_unit_set").select2();
    $("#makereport_report_select").select2();
    
    //Report Object:
     window["teneReport"] = {
        loadUnit : function(t) {
            var $but = $(t);
            //unload:
            window.teneReport.unloadUnit();
            //Get ajax:
            var data = {
                 req:       "api",
                 token:     $("#pagetoken").val(),
                 type:      "loadunittoreportscreen",
                 unit_id:   $('#makereport_unit_set').val()
            };
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',             
                beforeSend: function() {
                    $but.prop("disabled", true);
                    $('#makereport_unit_set').prop("disabled", true);
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        console.log(response);
                        //Set unit title:
                        $(".makerep_loaded_unit_title span").text(
                            (typeof response.results.ofunit.unit_name !== "undefined") ? response.results.ofunit.unit_name : "לא מוגדר"
                        );

                        //Load prev:
                        $prev = $("#makereport_report_select");
                        $htmlPrev = [];
                        for (var i=0; i < response.results.repList.length; i++) {
                            $htmlPrev.push(
                                "<option value='" + response.results.repList[i].rep_group_num + "'>" + response.results.repList[i].rep_date + "</option>"
                            );
                        }
                        $prev.html($htmlPrev.join("")).select2();
                        //Load new:
                        
                        //Fade:
                        $(".makerep_loaded_unit_title").fadeIn(function(){
                            $(".makerep_prev_report, .makerep_new_report").fadeIn();
                        });
                    } else {
                        console.log("fail",response);
                        window.alertModal("שגיאה",window.langHook("makerep_error_load_unit"));
                        $('#makereport_unit_set').prop("disabled", false);
                        $but.prop("disabled", false);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    $but.prop("disabled", false);
                    $('#makereport_unit_set').prop("disabled", false);
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("makerep_error_load_unit"));
                },
            });
            
        },
        unloadUnit : function() {
            $('#make_but_load_unit').prop("disabled", false);
            $('#makereport_unit_set').prop("disabled", false);
            $(".makerep_prev_report, .makerep_new_report, .makerep_loaded_unit_title").fadeOut(function(){
            });
        }
     };
</script>