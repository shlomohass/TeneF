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
            <form id="make_rep_edit_rep"class="form-inline">
                <button type="button" class="btn btn-primary" onclick="">
                          <?php Lang::P("page_makereport_but_edit_rep"); ?>
                </button>
            </form>
            <table class="new_rep_table">
                <thead>
                    <tr class="prevent-search">
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
                    <tr class="prevent-search">
                        <td>
                            <div class="form-group" style="max-width:95px;">
                                <input type="text" class="form-control" id="tene-filter-amnum" placeholder="000000" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control" id="tene-filter-amtype">
                                    <option value="-1">ללא</option>
                                    <?php
                                        foreach ($Page->variable("all-am-groups") as $group) {
                                            echo "<optgroup label='".$group["am_group_name"]."'>";
                                            foreach ($Page->variable("all-am-types") as $type) {
                                                if ($type["am_type_of_group"] === $group["am_group_id"]) {
                                                    echo "<option value='".$type["am_type_id"]."'>".$type["am_type_name"]."</option>";
                                                }
                                            }
                                            echo "</optgroup>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tene-filter-yeud" placeholder="סנן יעוד" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tene-filter-location" data-provide="typeahead" autocomplete="off" placeholder="סנן מיקום" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control" id="tene-filter-amstatus">
                                    <option value="-1">ללא</option>
                                    <?php
                                        foreach ($Page->variable("all-am-status") as $status) {
                                            echo "<option value='".$status["am_status_name"]."'>".$status["am_status_name"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tene-filter-exp" placeholder="חפש פירוט" />
                            </div>
                        </td>
                        <td>היסטוריית כשירות</td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="tene-filter-comment" placeholder="חפש הערות" />
                            </div>
                        </td>
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
                        
                        //Load inputs:
                        window.teneReport.loadautocompletes();
                        
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
        },
        loadautocompletes : function() {
            var source_amnum    = [],
                source_yeud     = [],
                source_location = [],
                source_exp      = [],
                source_com      = [];
            $rows = $(".new_rep_table tr").not(".prevent-search");
            $.each($rows, function(ind, ele) {
                source_amnum.push($(ele).find("td").eq(0).text());
                source_yeud.push($(ele).find("td").eq(2).text());
                source_location.push($(ele).find("td").eq(3).text());
                source_exp.push($(ele).find("td").eq(5).text());
                source_com.push($(ele).find("td").eq(7).text());
            });
            var source_amnum_clean      = window.teneReport.arrayUnique(source_amnum);
            var source_yeud_clean       = window.teneReport.arrayUnique(source_yeud);
            var source_location_clean   = window.teneReport.arrayUnique(source_location);
            var source_exp_clean        = window.teneReport.arrayUnique(source_exp);
            var source_com_clean        = window.teneReport.arrayUnique(source_com);
            $("#tene-filter-amnum").typeahead({ source:source_amnum_clean });
            $("#tene-filter-yeud").typeahead({ source:source_yeud_clean });
            $("#tene-filter-location").typeahead({ source:source_location_clean });
            $("#tene-filter-exp").typeahead({ source:source_exp_clean });
            $("#tene-filter-comment").typeahead({ source:source_com_clean });
        },
        arrayUnique : function(a) {
            return a.reduce(function(p, c) {
                if (p.indexOf(c) < 0) p.push(c);
                return p;
            }, []);
        }
     };
</script>