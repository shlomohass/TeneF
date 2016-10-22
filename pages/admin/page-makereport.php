<?php

Trace::add_step(__FILE__,"Loading Sub Page: admin -> makereport");

include_once PATH_CLASSES."Operation.class.php";
$Op = new Operation();

//Load all amlah types:
Trace::add_step(__FILE__,"Loading needed tables");
$Page->variable("all-am-types",  $Page::$conn->get("amlah_type"));
$Page->variable("all-am-groups", $Page::$conn->get("amlah_group"));
$Page->variable("all-am-status", $Page::$conn->get("amlah_status"));
$Page->variable("all-am-dereg",  $Page::$conn->get("repare_deg"));

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
Trace::reg_var("all-am-dereg",$Page->variable("all-am-status"));

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
                <button type="button" class="btn btn-primary" onclick="window.teneReport.createNewReport(this)">
                          <?php Lang::P("page_makereport_but_create_report"); ?>
                </button>
                <button type="button" class="btn btn-primary" onclick="">
                          <?php Lang::P("page_makereport_but_save_report"); ?>
                          <span> jshgjhg </span>
                </button>
            </form>
            <table class="new_rep_table">
                <thead>
                    <tr class="prevent-search add-master-table-style">
                        <th>#</th>
                        <th>מס צ'</th>
                        <th>סוג</th>
                        <th>שייכות / ייעוד</th>
                        <th>מיקום</th>
                        <th>כשירות</th>
                        <th>פירוט מצב כשירות</th>
                        <th>היסטוריית כשירות</th>
                        <th>חלפים</th>
                        <th>דרג תיקון</th>
                        <th>ברישום מתאריך</th>
                        <th>צפי תיקון</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="add-master-table-style prevent-search">
                        <td>
                            <span class="glyphicon glyphicon-remove tene-reset-all-filters" aria-hidden="true"></span>
                        </td>
                        <td>
                            <div class="form-group" style="max-width:95px;">
                                <input type="text" class="tene-filter tene-filter-rows-intext form-control" id="tene-filter-amnum" placeholder="000000" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="tene-filter tene-filter-rows-indata form-control" id="tene-filter-amtype">
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
                                <input type="text" class="tene-filter tene-filter-rows-intext form-control" id="tene-filter-yeud" placeholder="סנן יעוד" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="tene-filter tene-filter-rows-intext form-control" id="tene-filter-location" data-provide="typeahead" autocomplete="off" placeholder="סנן מיקום" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="tene-filter tene-filter-rows-inselect form-control" id="tene-filter-amstatus">
                                    <option value="-1">ללא</option>
                                    <?php
                                        foreach ($Page->variable("all-am-status") as $status) {
                                            echo "<option value='".$status["am_status_id"]."' data-setcolor='".$status["am_status_color"]."'>".$status["am_status_name"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="tene-filter tene-filter-rows-inarea form-control" id="tene-filter-exp" placeholder="חפש פירוט" />
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="form-group">
                                <select class="tene-filter tene-filter-rows-inselect form-control" id="tene-filter-amdereg" style='min-width: 70px;'>
                                    <option value="-1">ללא</option>
                                    <?php
                                        foreach ($Page->variable("all-am-dereg") as $status) {
                                            echo "<option value='".$status["deg_id"]."' data-setcolor='".$status["deg_color"]."'>".$status["deg_name"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
<!-- manage amlah location modal -->
<div id="manage_am_location" class='modalTeneF' data-locrowedit="-1">
    <div class="modalTeneF_wrap">
        <div class='modalTeneF_head'><?php Lang::P("page_makereport_modal_header"); ?><span class="highlighted_name"></span></div>
        <div class='modalTeneF_bodyFixed'>
            <div class="add_location_form">
                <h4><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <?php Lang::P("page_makereport_modal_header_add"); ?>
                </h4>
                <form id="add_loc_form"class="form-inline">
                  <div class="form-group">
                    <input type="text" class="form-control" name="locname" id="locname" placeholder="<?php Lang::P("page_makereport_modal_loc_name_place"); ?>" style="width:250px;" />
                  </div>
                  <button type="button" class="btn btn-primary addlocbutton" onclick="window.teneReport.setNewLocation(this)">
                      <?php Lang::P("page_makereport_modal_but_loc_new"); ?>
                  </button>
                  <br />
                  <div class="checkbox" style="margin-left: 10px;">
                    <label>
                      <input type="checkbox" name="locisbase"  style="margin-left: 5px;" /><?php Lang::P("page_makereport_label_base"); ?>
                    </label>
                  </div>
                  <div class="checkbox" style="margin-left: 10px;">
                    <label>
                      <input type="checkbox" name="lociscivil"  style="margin-left: 5px;" /><?php Lang::P("page_makereport_label_civil"); ?>
                    </label>
                  </div>
                  <div class="checkbox" style="margin-left: 10px;">
                    <label>
                      <input type="checkbox" name="locisterrain"  style="margin-left: 5px;" /><?php Lang::P("page_makereport_label_terrain"); ?>
                    </label>
                  </div>
                  <div class="checkbox" style="margin-left: 10px;">
                    <label>
                      <input type="checkbox" name="locisborder"  style="margin-left: 5px;" /><?php Lang::P("page_makereport_label_border"); ?>
                    </label>
                  </div>
                </form>
                <span class='text-danger hide'><?php Lang::P("page_makereport_modal_loc_exists"); ?></span>
            </div>
            <br />
            <h4><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                <?php Lang::P("page_makereport_modal_select_loc_head"); ?>
            </h4>
            <form id="select_loc_form"class="form-inline">
                  <div class="form-group">
                      <select class="form-control" id="locselect" style="width:250px;"></select>
                  </div>
                  <button type="button" class="btn btn-primary selectlocbutton" onclick="window.teneReport.setSelectedLocation(this)">
                      <?php Lang::P("page_makereport_modal_but_loc_select"); ?>
                  </button>
            </form>
        </div>
        <div class='modalTeneF_foot'>
            <button type="button" class="btn btn-warning" onclick="window.teneReport.disLocationModal();">
                <?php Lang::P("page_makereport_modal_but_loc_add"); ?>
            </button>
        </div>
    </div>
</div>

<!-- manage amlah parts modal -->
<div id="manage_am_parts" class='modalTeneF' data-partsrowedit="-1">
    <div class="modalTeneF_wrap">
        <div class='modalTeneF_head'><?php Lang::P("page_makereport_modal_parts_header"); ?><span class="highlighted_name"></span></div>
        <div class='modalTeneF_bodyFixed'>
            <div class="add_location_form">
                <h4><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    <?php Lang::P("page_makereport_modal_parts_header_add"); ?>
                </h4>
                <form id="add_parts_form"class="form-inline">
                  <div class="form-group">
                    <input type="text" class="form-control" name="partnum" id="partnum" placeholder="<?php Lang::P("page_makereport_modal_part_num_place"); ?>" style="width:250px;" />
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="partname" id="partname" placeholder="<?php Lang::P("page_makereport_modal_part_name_place"); ?>" style="width:250px;" />
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="partdesc" id="partdesc" placeholder="<?php Lang::P("page_makereport_modal_part_desc_place"); ?>" style="width:250px;" />
                  </div>
                  <button type="button" class="btn btn-primary addlocbutton" onclick="window.teneReport.createPartAndAdd(this)">
                      <?php Lang::P("page_makereport_modal_but_part_new"); ?>
                  </button>
                </form>
            </div>
            <br />
            <h4><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                <?php Lang::P("page_makereport_modal_select_parts_head"); ?>
            </h4>
            <form id="select_loc_form"class="form-inline">
                  <div class="form-group">
                      <select class="form-control" id="partsselect" style="width:450px;"></select>
                  </div>
                  <button type="button" class="btn btn-primary selectpartbutton" onclick="window.teneReport.selectAddPart(this)">
                      <?php Lang::P("page_makereport_modal_but_parts_select"); ?>
                  </button>
            </form>
            <br />
            <h4><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                <?php Lang::P("page_makereport_modal_update_parts_head"); ?>
            </h4>
            <div class="parts-list-for-am">
                <table>
                    <thead>
                        <tr>
                            <th>מסד</th>
                            <th>תאריך הזנה</th>
                            <th>מק"ט</th>
                            <th>חלף</th>
                            <th>כמות נדרשת</th>
                            <th>סטאטוס</th>
                            <th>התקבל</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class='modalTeneF_foot'>
            <button type="button" class="btn btn-primary" onclick="window.teneReport.saveAndHookParts(this)">
                <?php Lang::P("page_makereport_modal_but_parts_add"); ?>
            </button>
            <button type="button" class="btn btn-warning" onclick="window.teneReport.disPartsModal();">
                <?php Lang::P("page_makereport_modal_but_parts_close"); ?>
            </button>
        </div>
    </div>
</div>

<!-- page main logic script -->
<script>
    
    //Unit select box:
    $("#makereport_unit_set").select2({ 
        allowClear: true,
        placeholder: window.langHook("makerep_placeholder_select_unit")
    }).val(null).trigger("change");
    $("#makereport_report_select").select2({ 
        allowClear: true,
        placeholder: window.langHook("makerep_placeholder_select_prevrep")
    }).val(null).trigger("change");
    
    //Set filters:
    $(".tene-filter-rows-indata").change(function(){
        var $ele = $(this);
        var $td = $ele.closest('td');
        var $rows = $(".new_rep_table tr.amRow");
        var value = $ele.val();
        var eq = $td.parent().children().index($td);
        window.teneReport.resetFilters($ele);
        $rows.each(function(inde, e){
            var $e = $(e);
            var $cell = $e.find("td").eq(eq);
            var cellVal = $cell.data("search");
            if (cellVal != value && value != -1 && value != "") {
                $e.hide();
            } else {
                $e.show();
            }
        }); 
    });
    $(".tene-filter-rows-intext").change(function(){
        var $ele = $(this);
        var $td = $ele.closest('td');
        var $rows = $(".new_rep_table tr.amRow");
        var value = $ele.val();
        var eq = $td.parent().children().index($td);
        window.teneReport.resetFilters($ele);
        $rows.each(function(inde, e){
            var $e = $(e);
            var $cell = $e.find("td").eq(eq);
            var cellVal = $cell.text();
            if (cellVal != value && value != -1 && value != "") {
                $e.hide();
            } else {
                $e.show();
            }
        }); 
    });
    $(".tene-filter-rows-inselect").change(function(){
        var $ele = $(this);
        var $td = $ele.closest('td');
        var $rows = $(".new_rep_table tr.amRow");
        var value = $ele.val();
        var eq = $td.parent().children().index($td);
        window.teneReport.resetFilters($ele);
        $rows.each(function(inde, e){
            var $e = $(e);
            var $select = $e.find("td").eq(eq).find("select");
            var sellVal = $select.val();
            if (sellVal != value && value != -1 && value != "") {
                $e.hide();
            } else {
                $e.show();
            }
        }); 
    });
    $(".tene-filter-rows-inarea").change(function(){
        var $ele = $(this);
        var $td = $ele.closest('td');
        var $rows = $(".new_rep_table tr.amRow");
        var value = $ele.val();
        var eq = $td.parent().children().index($td);
        window.teneReport.resetFilters($ele);
        $rows.each(function(inde, e){
            var $e = $(e);
            var $area = $e.find("td").eq(eq).find("textarea");
            var arealVal = $area.val();
            if (arealVal != value && value != -1 && value != "") {
                $e.hide();
            } else {
                $e.show();
            }
        }); 
    });
    $(".tene-reset-all-filters").click(function(){
        window.teneReport.resetFilters(this);
        $(".new_rep_table tr.amRow").show();
    });
    
    //Set triggers:
    $(document).on("click", ".location-but-trigger", function(){
        window.teneReport.loadLocationModal(this);
    });
    $(document).on("click", ".parts-selector", function(){
        window.teneReport.loadPartsModal(this);
    });
    $(document).on("change", ".status-select-rep", function(){
        window.teneReport.validate_rows_for_changes(true);
    });
    $(document).on("change.validateRows", ".amList-status-exp", function(){
        window.teneReport.validate_rows_for_changes(true);
    });
    $(document).on("change.validateRows", ".dereg-select-rep", function(){
        window.teneReport.validate_rows_for_changes(true);
    });
    $(document).on("click", ".rowIndicator.changed", function() {
        window.teneReport.restore_row(this);
    });
    
    //Report Object:
     window["teneReport"] = {
        enableVlidateRows : false,
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
            if (data.unit_id == "" || data.unit_id == null) {
                window.alertModal(
                    "אזהרה",
                    window.langHook("makerep_warn_select_unit"),
                    function() {
                        $('#makereport_unit_set').data("select2").open();
                    }
                );
                return;
            }
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',             
                beforeSend: function() {
                    $but.prop("disabled", true);
                    $('#makereport_unit_set').prop("disabled", true);
                    window.teneReport.enableVlidateRows = false;
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        console.log(response.results);
                        
                        //Set unit title:
                        $(".makerep_loaded_unit_title span").text(
                            (typeof response.results.ofunit.unit_name !== "undefined") ? response.results.ofunit.unit_name : "לא מוגדר"
                        );

                        //Load prev:
                        $prev = $("#makereport_report_select");
                        $htmlPrev = [];
                        for (var i=0; i < response.results.repList.length; i++) {
                            $htmlPrev.push(
                                "<option value='" + response.results.repList[i].report_id + "'>" + response.results.repList[i].report_hour + " &nbsp; - &nbsp; " + response.results.repList[i].report_date + "</option>"
                            );
                        }
                        
                        $prev.html($htmlPrev.join("")).select2({ 
                            allowClear: true,
                            placeholder: window.langHook("makerep_placeholder_select_prevrep")
                        }).val(null).trigger("change");

                        //Load new:
                        var $table = $(".new_rep_table tbody").eq(0);
                        
                        //Rows and Elements
                        var $datein = $(
                            "<div class='input-group date date-widget-set date-indereg' id='startDate' style='direction:ltr;'>"
                                + "<div class='input-group-addon'>"
                                    + "<span class='glyphicon glyphicon-calendar'></span>"
                                + "</div>"
                                + "<input type='text' class='form-control dateindereg-box' name='dateindereg' />"
                            + "</div>"
                        );
                        var $forecast = $(
                            "<div class='input-group date date-widget-set date-forecast' id='startDate' style='direction:ltr;'>"
                                + "<div class='input-group-addon'>"
                                    + "<span class='glyphicon glyphicon-calendar'></span>"
                                + "</div>"
                                + "<input type='text' class='form-control dateforecast-box' name='dateforecast' />"
                            + "</div>"
                        );
                        var $htmlRows = [];
                        for (var i=0; i < response.results.amlist.length; i++) {
                            
                            //normalize:
                            response.results.amlist[i].am_list_status_exp = response.results.amlist[i].am_list_status_exp === null ? "" : response.results.amlist[i].am_list_status_exp;
                            response.results.amlist[i].am_list_status_exp_log = response.results.amlist[i].am_list_status_exp_log === null ? "" : response.results.amlist[i].am_list_status_exp_log;
                            response.results.amlist[i].am_list_number = response.results.amlist[i].am_list_number === null ? "" : response.results.amlist[i].am_list_number;
                            response.results.amlist[i].am_type_name = response.results.amlist[i].am_type_name === null ? "" : response.results.amlist[i].am_type_name;
                            response.results.amlist[i].am_list_yeud = response.results.amlist[i].am_list_yeud === null ? "" : response.results.amlist[i].am_list_yeud;
                            response.results.amlist[i].loc_name = response.results.amlist[i].loc_name === null ? "" : response.results.amlist[i].loc_name;
                            response.results.amlist[i].am_list_location = response.results.amlist[i].am_list_location === null ? 0 : response.results.amlist[i].am_list_location;
                            response.results.amlist[i].am_list_dereg = response.results.amlist[i].am_list_dereg === null ? -1 : response.results.amlist[i].am_list_dereg;
                            response.results.amlist[i].am_list_indereg_since = response.results.amlist[i].am_list_indereg_since === null ? "" : response.results.amlist[i].am_list_indereg_since;
                            response.results.amlist[i].am_list_forecast = response.results.amlist[i].am_list_forecast === null ? "" : response.results.amlist[i].am_list_forecast;
                            response.results.amlist[i].am_list_parts_req = response.results.amlist[i].am_list_parts_req === null ? "[]" : response.results.amlist[i].am_list_parts_req;
                            
                            //Set status box:
                            var $statusSelect = $("#tene-filter-amstatus").clone().attr("id","").removeClass("tene-filter tene-filter-rows-inselect").addClass("status-select-rep").find("option").prop("selected",false).end();
                            $statusSelect.find("option[value=" + response.results.amlist[i].am_list_status + "]").attr('selected',"selected");
                            
                            // Set Dereg Box:
                            var $deregSelect = $("#tene-filter-amdereg").clone().attr("id","").removeClass("tene-filter tene-filter-rows-inselect").addClass("dereg-select-rep").find("option").prop("selected",false).end();
                            $deregSelect.find("option[value=" + response.results.amlist[i].am_list_dereg + "]").attr('selected',"selected");
                            
                            //Set datepickers:
                            $datein.attr("value", response.results.amlist[i].am_list_indereg_since);
                            $forecast.attr("value", response.results.amlist[i].am_list_forecast);
                            
                            //Create parts object:
                            var partsList = {
                                arr : [],
                                json : "[]"
                            };
                            try {
                                partsList.arr = JSON.parse(response.results.amlist[i].am_list_parts_req);
                                partsList.json = response.results.amlist[i].am_list_parts_req;
                            } catch (error) {
                                console.log("Error parsing JSON parts of:", response.results.amlist[i].am_list_number);
                                partsList = {
                                    arr : [],
                                    json : "[]"
                                };
                            }
                            
                            $htmlRows.push(
                                $("<tr class='amRow add-master-table-style'>"
                                + "<td style='width:20px;' class='rowIndicator'></td>"
                                + "<td>" + response.results.amlist[i].am_list_number + "</td>"
                                + "<td data-search='" + response.results.amlist[i].am_type_id + "'>" + response.results.amlist[i].am_type_name + "</td>"
                                + "<td>" + response.results.amlist[i].am_list_yeud + "</td>"
                                + "<td><span class='location-display' data-locid='" + response.results.amlist[i].am_list_location + "'>" + response.results.amlist[i].loc_name + "</span><span class='glyphicon glyphicon-map-marker location-but-trigger noselect' aria-hidden='true'></span></td>"
                                + "<td>" + $statusSelect[0].outerHTML + "</td>"
                                + "<td style='line-height:1px;'><textarea class='form-control amList-status-exp'>" + response.results.amlist[i].am_list_status_exp + "</textarea></td>"
                                + "<td>" + response.results.amlist[i].am_list_status_exp_log + "</td>"
                                + "<td><span class='parts-display'>" + partsList.arr.length + "</span><span class='glyphicon glyphicon-th-list parts-selector' aria-hidden='true'></span></div></td>"
                                + "<td>" + $deregSelect[0].outerHTML + "</td>"
                                + "<td>" + $datein[0].outerHTML + "</td>"
                                + "<td>" + $forecast[0].outerHTML + "</td>"
                                + "</tr>")
                                .data("oldrow", response.results.amlist[i])
                                .data("newParts", partsList)
                                .data("changed", false)
                            );
                        }
                        $table.append($htmlRows);
                        
                        //Append Change Status:
                        $table.find(".status-select-rep, .dereg-select-rep").each(function(inde,ele){
                            var $ele = $(ele);
                            $ele.on("change.setColors",function(){
                                console.log("setColor triggered");
                                var $e = $(this);
                                var $td = $e.closest("td");
                                var $opt = $e.find("option:selected");
                                var color = $opt.data("setcolor");
                                if (typeof color !== 'string') {
                                    color = "transparent";
                                }
                                $td.css("backgroundColor",color);
                            });
                            $ele.change();
                        });
                        
                        //Load inputs:
                        window.teneReport.loadautocompletes();
                        
                        //Set datepikers:
                        $table.find(".date-widget-set").datetimepicker({
                                format: "YYYY-MM-DD HH:mm:ss",
                                toolbarPlacement:'bottom',
                                showClear:true,
                                showClose:true,
                                showTodayButton:true
                        });
                        
                        $table.find(".date-widget-set.date-indereg").each(function(ind, el){
                            var $el = $(el);
                            var value = $el.closest("tr.amRow").data("oldrow").am_list_indereg_since;
                            $el.data("DateTimePicker").date(value);
                        });
                        $table.find(".date-widget-set.date-forecast").each(function(ind, el){
                            var $el = $(el);
                            var value = $el.closest("tr.amRow").data("oldrow").am_list_forecast;
                            $el.data("DateTimePicker").date(value);
                        });
                        $table.find(".date-widget-set").on("dp.change",function(e){
                            window.teneReport.validate_rows_for_changes(true);
                        });
         
                        //Fade:
                        $(".makerep_loaded_unit_title").fadeIn(function(){
                            $(".makerep_prev_report, .makerep_new_report").fadeIn();
                        });
                        
                        //Enable validate rows:
                        window.teneReport.enableVlidateRows = true;
                        window.teneReport.validate_rows_for_changes(true);
                        
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
                $(".new_rep_table tbody .amRow").remove();
                window.teneReport.resetFilters();
            });
        },
        loadautocompletes : function() {
            var source_amnum    = [],
                source_yeud     = [],
                source_location = [],
                source_exp      = [];
            $rows = $(".new_rep_table tr").not(".prevent-search");
            $.each($rows, function(ind, ele) {
                source_amnum.push($(ele).find("td").eq(1).text());
                source_yeud.push($(ele).find("td").eq(3).text());
                source_location.push($(ele).find("td").eq(4).text());
                source_exp.push($(ele).find("td").eq(6).text());
            });
            var source_amnum_clean      = window.teneReport.arrayUnique(source_amnum);
            var source_yeud_clean       = window.teneReport.arrayUnique(source_yeud);
            var source_location_clean   = window.teneReport.arrayUnique(source_location);
            var source_exp_clean        = window.teneReport.arrayUnique(source_exp);
            if ($("#tene-filter-amnum").data("typeahead") !== null) {
                $("#tene-filter-amnum").typeahead("destroy");
            }
            if ($("#tene-filter-yeud").data("typeahead") !== null) {
                $("#tene-filter-yeud").typeahead("destroy");
            }
            if ($("#tene-filter-location").data("typeahead") !== null) {
                $("#tene-filter-location").typeahead("destroy");
            }
            if ($("#tene-filter-exp").data("typeahead") !== null) {
                $("#tene-filter-exp").typeahead("destroy");
            }
            $("#tene-filter-amnum").typeahead({ source:source_amnum_clean, fitToElement:true });
            $("#tene-filter-yeud").typeahead({ source:source_yeud_clean, fitToElement:true });
            $("#tene-filter-location").typeahead({ source:source_location_clean, fitToElement:true });
            $("#tene-filter-exp").typeahead({ source:source_exp_clean, fitToElement:true });
        },
        resetFilters : function($e) {
            $(".new_rep_table .tene-filter-rows-indata").not($e).val("-1");
            $(".new_rep_table .tene-filter-rows-intext").not($e).val("");
            $(".new_rep_table .tene-filter-rows-inselect").not($e).val("-1");
            $(".new_rep_table .tene-filter-rows-inarea").not($e).val("");
        },
        loadLocationModal : function(ele) {
            
            var $ele = $(ele);
            var $row = $ele.closest('tr.amRow');
            var rowData = $row.data("oldrow");
            var title = " " + rowData.am_list_number + " - " + rowData.am_type_name;
            var $modal = $("#manage_am_location");
            
            //Set Title:
            $modal.find('.highlighted_name').text(title);
            
            //Load Location list:
            var fire = window.teneReport.getLocationList();
            fire.success(function(response) {
                var locOp = [];
                if (typeof response.results.locations === "object") {
                    var locList = response.results.locations;
                    for (var i = 0; i < locList.length; i++) {
                        locOp.push({ id:locList[i].loc_id , text:locList[i].loc_name });
                    }
                }
                
                //Set Select 2:
                $("#locselect").select2({
                    data: locOp,
                    dir: "rtl",
                    placeholder: window.langHook("makerep_placeholder_select_loc"),
                    allowClear: true
                }).val(null).trigger("change");
                
                //Set row editing:
                $modal.data("locrowedit", $ele.closest("td").find("span.location-display"));
                
                //Load Modal:
                $modal.fadeIn();
            });
        },
        disLocationModal : function() {
            $("#manage_am_location").fadeOut();
        },
        getLocationList : function() {
            var data = {
                req     : "api",
                token   : $("#pagetoken").val(),
                type    : "listlocations"
            };
            return $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {

                    } else {
                        window.alertModal("שגיאה",window.langHook("makerep_error_load_loc"));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("makerep_error_load_loc"));
                },
            });  
        },
        getPartsList : function() {
            var data = {
                req     : "api",
                token   : $("#pagetoken").val(),
                type    : "listparts"
            };
            return $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {

                    } else {
                        window.alertModal("שגיאה",window.langHook("makerep_error_load_parts"));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    window.alertModal("שגיאה",window.langHook("makerep_error_load_parts"));
                },
            });  
        },
        setNewLocation : function(ele) {
            
            var $ele    = $(ele);
            var $modal  = $ele.closest("#manage_am_location");
            var $warn   = $modal.find(".text-danger").eq(0);
            var $form   = $modal.find("#add_loc_form");
            var name    = $modal.find("#locname").val().trim(); 
            
            //Set the insert data:
            var data = $form.serializeArray();
            data.push({name: "req", value: "api"});
            data.push({name: "token", value: $("#pagetoken").val()});
            data.push({name: "type", value: "addlocation"});

            //validate empty:
            if (name === "" || name === null) {
                window.alertModal(
                    "אזהרה",
                    window.langHook("makerep_warn_setlocname"),
                    function($tar){
                        $tar.focus();
                    },
                    [$modal.find("#locname").eq(0)]
                );
                return;
            }
            //validate exists:
            var test = false;
            $("#locselect option").each(function(inde, el){
                var $el = $(el);
                var text = $el.text().trim();
                if (text === name) {
                    test = true;
                    return false;
                }
            });
            if (test) {
                window.alertModal("אזהרה",window.langHook("makerep_warn_setlocname_exists"));
                window.alertModal(
                    "אזהרה",
                    window.langHook("makerep_warn_setlocname_exists"),
                    function(ser){
                        $("#locselect").data("select2").open();
                        $(".select2-container .select2-search__field").val(ser).trigger("keyup");
                    },
                    [name]
                );
                return;
            }
            
            //save to server:
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $ele.prop("disabled",true);
                },
                success: function(response) {
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {
                        var $target = $modal.data("locrowedit");
                        if ($target.length) {
                            
                            //Set new to Gui:
                            $target.text(response.results.newloc.locname);
                            $target.data("locid",response.results.newloc.locid);
                            
                            //Reset Form:
                            $modal.find("#locname").val("");
                            $modal.find("input[type='checkbox']").prop("checked", false);
                            $modal.find("#locselect").val(null).trigger("change");
                            
                            //close form:
                            window.teneReport.disLocationModal();
                        }
                    } else {
                        console.log(response);
                        if (
                            typeof response.code !== 'undefined' &&
                            response.code == "110"
                        ) {
                            window.alertModal("אזהרה",window.langHook("makerep_warn_setlocname_exists"));
                        } else {
                            window.alertModal("שגיאה",window.langHook("makerep_error_savenew_loc"));
                        }
                    }
                    $ele.prop("disabled",false);
                    window.teneReport.validate_rows_for_changes(true);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    $ele.prop("disabled",false);
                    window.alertModal("שגיאה",window.langHook("makerep_error_savenew_loc"));
                },
            });
        },
        setSelectedLocation : function(ele) {
                var $ele = $(ele);
                var $modal  = $ele.closest("#manage_am_location");
                var $target = $modal.data("locrowedit");
                var $select = $modal.find("#locselect");
                var $selected = $select.find("option:selected");
                var locid = $selected.val();
                var text = $selected.length ? $selected.text() : "";
            
                if (text !== "" && locid !== "") {
                    //Set new to Gui:
                    $target.text(text);
                    $target.data("locid",locid);

                    //Reset Form:
                    $modal.find("#locname").val("");
                    $modal.find("input[type='checkbox']").prop("checked", false);
                    $modal.find("#locselect").val(null).trigger("change");

                    //close form:
                    window.teneReport.disLocationModal();
                    window.teneReport.validate_rows_for_changes(true);
                } else {
                    window.alertModal(
                        "אזהרה",
                        window.langHook("makerep_warn_select_loc_empty"),
                        function($tar){
                            $tar.data("select2").open();
                        },
                        [$select]
                    );
                }
        },
        validate_rows_for_changes : function(mark) {
            if (!window.teneReport.enableVlidateRows) return;
            console.log("validate rows fired");
            var $set = $(".new_rep_table tr.amRow");
            if ($set.length) {
                $set.each(function(ind, el){
                    var $el = $(el);
                    var old = $el.data("oldrow");
                    var changed = false;
                    var newPlace        = $el.find(".location-display").eq(0).data("locid"),
                        newStatus       = $el.find(".status-select-rep").eq(0).val(),
                        newStatusExp    = $el.find(".amList-status-exp").eq(0).val(),
                        newDereg        = $el.find(".dereg-select-rep").eq(0).val(),
                        newDateDereg    = $el.find(".dateindereg-box").eq(0).val(),
                        newDateForecast = $el.find(".dateforecast-box").eq(0).val(),
                        parts           = $el.data("newParts").json;
                    if (
                        parseInt(newPlace)  !==  parseInt(old.am_list_location) ||
                        parseInt(newStatus) !==  parseInt(old.am_list_status)   ||
                        newStatusExp    !==  old.am_list_status_exp ||
                        parseInt(newDereg) !==  parseInt(old.am_list_dereg) ||
                        newDateDereg !== old.am_list_indereg_since ||
                        newDateForecast !== old.am_list_forecast ||
                        parts !== old.am_list_parts_req
                    ) {
                        $el.data("changed",true);
                        if (mark) $el.find("td.rowIndicator").addClass("changed");
                    } else {
                        if (mark) $el.find("td.rowIndicator").removeClass("changed");
                    }
                });
            }
        },
        restore_row : function(ele) {
            
            //Elements:
            var $ele = $(ele);
            var $row = $ele.closest(".amRow");
            var $loc = $row.find("span.location-display").eq(0);
            var $status = $row.find("select.status-select-rep").eq(0);
            var $statusExp = $row.find("textarea.amList-status-exp").eq(0);
            var $dereg = $row.find("select.dereg-select-rep").eq(0);
            var $sincein = $row.find(".date-indereg").eq(0);
            var $forecast = $row.find(".date-forecast").eq(0);
            var $partsDisplay = $row.find(".parts-display").eq(0);
            var old = $row.data("oldrow");
            
            //Reset From Old:
            $loc.text(old.loc_name);
            $loc.data("locid",old.am_list_location);
            $statusExp.val(old.am_list_status_exp);
            $dereg.val(old.am_list_dereg).trigger("change.setColors");
            $status.val(old.am_list_status).trigger("change.setColors");
            $sincein.data("DateTimePicker").date(
                old.am_list_indereg_since === "" ? null : old.am_list_indereg_since
            );
            $forecast.data("DateTimePicker").date(
                old.am_list_forecast === "" ? null : old.am_list_forecast
            );
            
            //reset parts:
            var partsList = {
                arr : [],
                json : "[]"
            };
            try {
                partsList.arr = JSON.parse(old.am_list_parts_req);
                partsList.json = old.am_list_parts_req;
            } catch (error) {
                partsList = {
                    arr : [],
                    json : "[]"
                };
            }
            $row.data("newParts", partsList);
            $partsDisplay.text(partsList.arr.length);
            
            //Run check again:
            window.teneReport.validate_rows_for_changes(true);
        },
        arrayUnique : function(a) {
            return a.reduce(function(p, c) {
                if (p.indexOf(c) < 0) p.push(c);
                return p;
            }, []);
        },
        getTodayDate: function(format,deli) {
            var currentDate = new Date();
            var day = currentDate.getDate();
            var month = currentDate.getMonth() + 1;
            var year = currentDate.getFullYear();
            day = day<10? '0'+day:''+day;
            month = month<10? '0'+month:''+month;
            year = ''+year;
            switch (format) {
                    case "ymd":
                        currentDate = year + deli + month + deli + day;
                    break;
                    case "ydm":
                        currentDate = year + deli + day + deli + month;
                    break;
                    case "dmy":
                        currentDate = day + deli + month + deli + year;
                    break;
                    case "dym":
                        currentDate = day + deli + year + deli + month;
                    break;
                    case "mdy":
                        currentDate = month + deli + day + deli + year;
                    break;
                    case "myd":
                        currentDate = month + deli + year + deli + day;
                    break;
                    default:
                        currentDate = day + deli + month + deli + year;
            }
            return currentDate;
        },
        loadPartsModal : function(ele) {
            var $ele = $(ele);
            var $row = $ele.closest('tr.amRow');
            var rowData = $row.data("oldrow");
            var rowParts = $row.data("newParts").arr;
            var title = " " + rowData.am_list_number + " - " + rowData.am_type_name;
            var $modal = $("#manage_am_parts");
            var $modalListParts = $modal.find(".parts-list-for-am tbody");
            
            //Set Title:
            $modal.find('.highlighted_name').text(title);
            
            //Load old Parts:
            $modalListParts.html("");
            if (rowParts.length === 0) {
                    $modalListParts.append(
                        $(
                            "<tr class='emptyPartRow'>"
                            + "<td colspan='7' style='text-align:center;'>" + window.langHook("makerep_no_parts_witing_row") + "</td>"
                            + "</tr>"
                        )
                    );
            } else {
                for (var i = 0; i < rowParts.length; i++) {
                    $modalListParts.append(
                        $(
                            "<tr class='partRow'>"
                            + "<td class='text-center partRow_index'>" + rowParts[i].i + "</td>"
                            + "<td class='text-center partRow_date'>" + rowParts[i].reqdate + "</td>"
                            + "<td class='text-center partRow_makat'>" + rowParts[i].makat + "</td>"
                            + "<td class='partRow_name'>" + rowParts[i].name + "</td>"
                            + "<td class='partRow_num'><input class='' type='number' value='" + rowParts[i].num + "' placeholder='" + window.langHook("makerep_set_part_num_req") + "' /></td>"
                            + "<td class='partRow_status'><input class='' type='text' placeholder='" + window.langHook("makerep_set_part_status_req") + "' value='" + rowParts[i].status + "' /></td>"
                            + "<td class='text-center partRow_supplied'><input class='' type='checkbox' " + ( rowParts[i].supplied ? "checked" : "" ) + " /></td>"
                        + "</tr>"
                        )
                    );
                }
            }
            
            //Load Parts list:
            var fire = window.teneReport.getPartsList();
            fire.success(function(response) {
                var partOp = [];
                if (typeof response.results.parts === "object") {
                    var partList = response.results.parts;
                    for (var i = 0; i < partList.length; i++) {
                        partOp.push({ id:partList[i].pcat_num , text:partList[i].pcat_num + " :: " + partList[i].pcat_name });
                    }
                }
                
                //Set Select 2:
                $("#partsselect").select2({
                    data: partOp,
                    dir: "rtl",
                    placeholder: window.langHook("makerep_placeholder_select_part"),
                    allowClear: true
                }).val(null).trigger("change");
                
                //Set row editing:
                $modal.data("partsrowedit", $ele.closest("td").find("span.parts-display"));
                
                //Load Modal:
                $modal.fadeIn();
            });
        },
        disPartsModal : function() {
            $("#manage_am_parts").fadeOut();
        },
        selectAddPart : function(ele) {
            var $ele = $(ele);
            var $modal = $("#manage_am_parts");
            var $modalListParts = $modal.find(".parts-list-for-am tbody");
            var $partRows = $modalListParts.find("tr.partRow");
            var $emptyPartRows = $modalListParts.find("tr.emptyPartRow");
            var $select = $modal.find("#partsselect").eq(0);
            var selected = $select.select2('data');
            if (selected !== null && selected.length > 0) {
                
                selected = selected[0];
                
                //validate unique:
                var validate = true;
                var foundAt = 0;
                for (var i = 0; i < $partRows.length; i++) {
                    if ($partRows.eq(i).find(".partRow_makat").eq(0).text().trim() === selected.id) {
                        validate = false;
                        foundAt = i + 0;
                        break;
                    }
                }
                if (!validate) {
                    window.alertModal(
                        "אזהרה",
                        window.langHook("makerep_warn_must_part_is_in"),
                        function(index) {
                            var $to = $("#manage_am_parts .partRow").eq(index).find(".partRow_num input").eq(0);
                            if ($to.length > 0) {
                                $to.focus();
                            }
                        },
                        [foundAt]
                    );
                    return;
                }
                
                //Check empty row:
                if ($emptyPartRows.length > 0) {
                    $emptyPartRows.remove();
                }
                
                //set index:
                var index = $partRows.length + 1;

                $modalListParts.append(
                    $(
                        "<tr class='partRow'>"
                            + "<td class='text-center partRow_index'>" + index + "</td>"
                            + "<td class='text-center partRow_date'>" + window.teneReport.getTodayDate("ymd","-") + "</td>"
                            + "<td class='text-center partRow_makat'>" + selected.id + "</td>"
                            + "<td class='partRow_name'>" + selected.text.substring(selected.text.indexOf("::") + 2).trim() + "</td>"
                            + "<td class='partRow_num'><input class='' type='number' value='1' placeholder='" + window.langHook("makerep_set_part_num_req") + "'/></td>"
                            + "<td class='partRow_status'><input class='' type='text' placeholder='" + window.langHook("makerep_set_part_status_req") + "' /></td>"
                            + "<td class='text-center partRow_supplied'><input class='' type='checkbox' /></td>"
                        + "</tr>"
                    )
                );
                
                //Reset select:
                $select.val(null).trigger("change");
                
            } else {
                window.alertModal("אזהרה",window.langHook("makerep_warn_must_select_part"),function(){
                    $("#partsselect").select2("open");
                });
            }
            
        },
        addNewPartServer : function(values, $trigger_but) {
            var data = {
                req      : "api",
                token    : $("#pagetoken").val(),
                type     : "addnewpartcat",
                partnum  : values.num,
                partname : values.name,
                partdesc : values.desc
            };
            return $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $trigger_but.prop("disabled",true);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    $trigger_but.prop("disabled",false);
                    window.alertModal("שגיאה",window.langHook("makerep_error_create_parts_server"));
                },
            });  
        },
        createPartAndAdd :function(ele) {
            
            var $el = $(ele);
            var $modal = $("#manage_am_parts");
            var $modalListParts = $modal.find(".parts-list-for-am tbody");
            var $partRows = $modalListParts.find("tr.partRow");
            var $emptyPartRows = $modalListParts.find("tr.emptyPartRow");
            var $select = $modal.find("#partsselect").eq(0);
            
            var $newNum = $modal.find("#partnum").eq(0);
            var $newName = $modal.find("#partname").eq(0);
            var $newDesc = $modal.find("#partdesc").eq(0);
            
            var toAdd = {
                num : $newNum.val().trim(),
                name : $newName.val().trim(),
                desc : $newDesc.val().trim()
            }
            
            //add to server server:
            var fire = window.teneReport.addNewPartServer(toAdd, $el);
            fire.success(function(response) {
                console.log(response);
                $el.prop("disabled",false);
                if (
                    typeof response === 'object' && 
                    typeof response.code !== 'undefined' &&
                    response.code == "202"
                ) {
                    
                    //Set the new part to options catalog:
                    $select.append(
                        "<option value='" + response.results.newPart.partnum + "'>" + response.results.newPart.partnum + " :: " + response.results.newPart.partname + "</option>"
                    ).val(response.results.newPart.partnum).trigger("change");
                    
                    //Trigger adding the part:
                    $modal.find(".selectpartbutton").eq(0).trigger("click");
                    $select.val(null).trigger("change");
                    
                    //Reset the adding form:
                    $newNum.val("");
                    $newName.val("");
                    $newDesc.val("");
                                       
                } else {
                    if (response.code == "108") { // not legal
                        
                        window.alertModal("אזהרה",window.langHook("makerep_error_create_parts_legal"),
                            function(num, name) {
                                if (num === "") {
                                    $("#manage_am_parts").find("#partnum").eq(0).focus();
                                } else {
                                    $("#manage_am_parts").find("#partname").eq(0).focus();
                                }
                            },[toAdd.num, toAdd.name]
                        );
                        
                    } else if (response.code == "110") { // not unique
                        
                        //Reset the adding form:
                        $newNum.val("");
                        $newName.val("");
                        $newDesc.val("");
                        window.alertModal("אזהרה",window.langHook("makerep_error_create_parts_uni"),
                            function(num) {
                                $("#partsselect").data("select2").open();
                                $(".select2-container .select2-search__field").val(num).trigger("keyup");
                            },[toAdd.num]
                        );
                        
                    } else if (response.code == "104") { // query
                        
                        window.alertModal("שגיאה",window.langHook("makerep_error_create_parts_server"));
                        
                    } else {
                        
                        window.alertModal("שגיאה",window.langHook("makerep_error_create_parts_server"));
                        
                    }
                }
            });
        },
        saveAndHookParts : function(ele) {
            var $el = $(ele);
            var $modal = $("#manage_am_parts");
            var $modalListParts = $modal.find(".parts-list-for-am tbody");
            var $partRows = $modalListParts.find("tr.partRow");
            var $select = $modal.find("#partsselect").eq(0);
            var $amRowPartsDisplay = $modal.data("partsrowedit");
            var $amRow = $amRowPartsDisplay.closest("tr.amRow");
            
            //General Object of parts:
            var partsObj = {
                arr  : [],
                json : ""
            };
            
            //Parse Request table:
            if ($partRows.length > 0) {
                $partRows.each(function(ind, el) {
                    var $el = $(el);
                    partsObj.arr.push({
                        i            : (ind + 1),
                        reqdate      : $el.find(".partRow_date").eq(0).text(),
                        makat        : $el.find(".partRow_makat").eq(0).text(),
                        name         : $el.find(".partRow_name").eq(0).text(),
                        num          : $el.find(".partRow_num input").eq(0).val(),
                        status       : $el.find(".partRow_status input").eq(0).val(),
                        supplied     : $el.find(".partRow_supplied input").eq(0).is(":checked") ? true : false
                    });
                });
            }
            
            //Hook JSON:
            partsObj.json = JSON.stringify(partsObj.arr);
            
            //Set display:
            $amRowPartsDisplay.text(partsObj.arr.length);
            
            //Set row save:
            $amRow.data("newParts",partsObj);
            
            window.teneReport.validate_rows_for_changes(true);
            
            window.teneReport.disPartsModal();
            
        },
        getRowSaveSet : function($row, forSaveCreate) {
            
            var set = {
                changed   : false,
                amNum     : "",
                parts     : "[]",
                rowId     : 0,
                ofUnit    : 0,
                place     : 0,
                status    : -1,
                statusExp : "",
                dereg     : -1,
                indereg   : "",
                forecast  : ""
            };
            
            if ($row.length) {
                var oldRow      = $row.data("oldrow");
                set.changed     = $row.data("changed");
                set.amNum       = parseInt(oldRow.am_list_number);
                set.amType       = parseInt(oldRow.am_type_id);
                set.rowId       = parseInt(oldRow.am_list_id);
                set.ofUnit      = parseInt(oldRow.am_list_of_unit);
                set.parts       = $row.data("newParts").json;
                set.place       = $row.find(".location-display").eq(0).data("locid");
                set.status      = parseInt($row.find(".status-select-rep").eq(0).val());
                set.statusExp   = $row.find(".amList-status-exp").eq(0).val();
                set.dereg       = parseInt($row.find(".dereg-select-rep").eq(0).val());
                set.indereg     = $row.find("input[name='dateindereg']").eq(0).val();
                set.forecast    = $row.find("input[name='dateforecast']").eq(0).val();
            }
            return set;
        },
        createNewReport : function(ele) {
            
            //Needed:
            var $ele = $(ele);
            var $rowSet = $(".new_rep_table tr.amRow");
            var amRows = [];
            
            //Reset filters:
            window.teneReport.resetFilters();
            $rowSet.show();
            
            //Validate changes all:
            window.teneReport.validate_rows_for_changes(true);
            
            //Proccess:
            $rowSet.each(function(index, row){
                var $row = $(row);
                var set = window.teneReport.getRowSaveSet($row,"create");
                amRows.push(set);
            });
            
            //Save to server:
            var data = {
                req      : "api",
                token    : $("#pagetoken").val(),
                type     : "createnewreport",
                set      : amRows
            };
            $.ajax({
                url: 'index.php',  //Server script to process data
                type: 'POST',
                data: data,
                // dataType: 'json',
                beforeSend: function() {
                    $ele.prop("disabled",true);
                },
                success : function(response) {
                    console.log(response);
                    $ele.prop("disabled",false);
                    if (
                        typeof response === 'object' && 
                        typeof response.code !== 'undefined' &&
                        response.code == "202"
                    ) {


                    } else {
                        
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                    $ele.prop("disabled",false);
                    window.alertModal("שגיאה",window.langHook("makerep_error_create_new_server"));
                },
            });  
        }
     };
</script>