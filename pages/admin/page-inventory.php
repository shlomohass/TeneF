<?php

Trace::add_step(__FILE__,"Loading Sub Page: admin -> inventory");

//Load all amlah types:
$Page->variable("all-am-types", $Page::$conn->get("amlah_type"));
$Page->variable("all-am-groups", $Page::$conn->get("amlah_group"));

//Get user privs:
$Page->variable("god", $User->is_god());
$Page->variable("all-units-priv", $User->list_privs());

?>
<h2><?php Lang::P("page_inventory_title"); ?></h2>

<table id="inventory-grid" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" width="100%">
    <thead>
        <tr>
            <th>מזהה</th>
            <th>יחידה</th>
            <th>סוג</th>
            <th>יחידת בת של</th>
            <th>מיקום</th>
            <th>כללי</th>
            <th>פעולות</th>
        </tr>
    </thead>
</table>

<div id="manage_sadac" class='modalTeneF'>
    <div class="modalTeneF_wrap">
        <div class='modalTeneF_head'>עדכון סד"כ: <span class="highlighted_name">יחידה</span></div>
        <div class='modalTeneF_bodyFixed'>
            <div class="add_sadac_form">
                <h4><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>הוסף:</h4>
                <form id="add_sadac_form"class="form-inline">
                  <div class="form-group">
                    <label for="zid">צ':</label>
                    <input type="text" class="form-control" id="zid" placeholder="999999">
                  </div>
                  <div class="form-group">
                    <label for="ztype" style='margin-bottom:0px;'>
                        סוג:
                        <select type="email" class="" dir='rtl' lang='he' style='min-width:150px;' id="ztype" placeholder="">
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
                    </label>
                  </div>
                    <div class="form-group">
                    <label for="zyeud">ייעוד:</label>
                    <input type="email" class="form-control" id="zyeud" placeholder="ייעוד">
                  </div>
                  <button type="submit" class="btn btn-primary">הוסף</button>
                </form>
            </div>
            <br />
            <h4><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>סד"כ מוזן:</h4>
        </div>
        <div class='modalTeneF_body'>
            <div class='show_sadac'>
                
            </div>
        </div>
        <div class='modalTeneF_foot'>
            <button type="button" class="btn btn-primary">הזן ושמור שינויים</button>
            <button type="button" class="btn btn-warning" onclick="window.manage_sadac.disManageModal()">סגור ואל תשמור</button>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript" >
    var dataTable = $('#inventory-grid').DataTable( {
        "processing": true,
        "serverSide": true,
        "language": {
            paginate: {
                previous: '‹ הקודם',
                next:     'הבא ›'
            },
            aria: {
                paginate: {
                    previous: 'Previous',
                    next:     'Next'
                }
            },
            info: "מציג _START_ עד _END_ מתוך _TOTAL_ רשומות",
            lengthMenu: "הצג _MENU_ רשומות",
            search:         "חפש: ",
        },
        "columnDefs": [{
            "targets": -1,
            "width":"200px",
            "data": null,
            "defaultContent": '<button class="manage_amlah_but">נהל סד"כ</button><button>מחק יחידה</button><button>עדכן יחידה</button>'
        }],
        "ajax":{
            url : "index.php",
            type: "post",
            data: function ( d ) {
                    return $.extend( {}, d, {
                            req:"api",
                            token:$("#pagetoken").val(),
                            type:"listmanageunits"
                        } ); 
            },
            error: function(err, ms){  // error handling
                console.log("error",err);
            }
        }
    } );
    
    window["manage_sadac"] = {
        
        loadManageModal : function($ele) {
            
            var $modal = $("#manage_sadac");
            
            //Unit Id:
            var unitId = parseInt($ele.closest('tr').find('td').eq(0).text());
            var unitName = $ele.closest('tr').find('td').eq(1).text();
            
            //Set Title:
            $modal.find('.highlighted_name').text(unitName);
            
            //Load Amlah list:
            window.manage_sadac.refreshManageAmlahList(
                unitId,
                function() {
                    $("#manage_sadac").fadeIn();
                }
            );
        },
        disManageModal : function() {
            $("#manage_sadac").fadeOut();
        },
        getAmlistHtmlRepresentation : function(obj, priv) {
            var retHtml = "<table class='amlist_quick_view'><tr><th>מס צ'</th><th>סוג</th><th>ייעוד / שייכות</th><th>פעולות</th></tr>";
            if (obj.length > 0) {
                for (var i = 0; i < obj.length; i++) {
                    retHtml += "<tr><td>" + obj[i].am_list_number + "</td><td>" + obj[i].am_type_name + "</td><td>" + obj[i].am_list_yeud + "</td>";
                    if (priv) {
                        retHtml += "<td><span class='glyphicon glyphicon-trash' onclick='' data-amid='" + obj[i].am_list_id + "'></span>" + 
                                    "<span class='glyphicon glyphicon-pencil' onclick='' data-amid='" + obj[i].am_list_id + "'></span></td></tr>";
                    } else {
                        retHtml += "<td></td></tr>";
                    }
                }
            } else {
                retHtml += "<tr><td colspan='4'>אין סדכ מוזן ליחידה זאת</td></tr>"
            }
            retHtml += "</table>";
            return retHtml;
        },
        refreshManageAmlahList : function(unitId, callAfter) {
            
            var data = {
                req     : "api",
                token   : $("#pagetoken").val(),
                type    : "listAmlahOfUnit",
                unit    : unitId
            };
            $.ajax({
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
                        $("#manage_sadac").find(".modalTeneF_body").html(
                            window.manage_sadac.getAmlistHtmlRepresentation(response.results.amlist, response.results.editPriv)
                        );
                        callAfter();
                    } else {
                        //Error:
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError);
                },
            });
        }
    };
    
    //Manage Sadac:
    $("#ztype").select2();
    
    $(document).on("click",".manage_amlah_but",function(){
        window.manage_sadac.loadManageModal($(this));
    });

</script>