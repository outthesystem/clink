<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
        <form id="update_prescription" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
            <div class="row">
                <?php foreach ($prescription_list as $pkey => $pvalue) {
                    ?>
                    <input type="hidden" name="previous_pres_id[]" value="<?php echo $pvalue['id'] ?>">
                <?php } ?>
                <div style="max-height: 300px;overflow-x: hidden;;">   
                    <table style="width: 100%;" id="edittableID">

                        <?php
                        $i = 0;
                        foreach ($prescription_list as $key => $value) {
                            ?>
                            <tr id="row<?php echo $i ?>">
                                <td>                                
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>
                                                <?php echo $this->lang->line('medicine'); ?></label> 
                                            <input type="text" value="<?php echo $value['medicine'] ?>" name="medicine[]" class="form-control" id="report_type" />
                                            <input type="hidden" value="<?php echo $value['id'] ?>" name="prescription_id[]" class="form-control" id="report_type" />

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('dosage'); ?></label> 
                                            <input type="text" class="form-control" value="<?php echo $value['dosage'] ?>" name="dosage[]" id="report_document" />
                                        </div> 
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('instruction'); ?></label> 
                                            <textarea name="instruction[]" class="form-control" style="height: 28px;" id="instruction[]"><?php echo $value['instruction'] ?></textarea>

                                        </div> 
                                    </div>
                                </td>
                                <?php if ($i != 0) { ?>
                                    <td><button type='button' onclick="delete_row('<?php echo $i ?>')" class='modaltableclosebtn'><i class='fa fa-remove'></i></button></td>
                                <?php } else { ?>
                                    <td><button type="button" onclick="edit_more()" style="color: #2196f3" class="modaltableclosebtn"><i class="fa fa-plus"></i></button></a></td>
                                <?php } ?>
                            </tr>
                            <?php $i++;
                        }
                        ?>
                    </table>
                </div>
                <div class="add_row">

                </div>
                <!--div class="col-sm-12">
                   <a href="#" class="pull-right" onclick="edit_more()"><?php echo $this->lang->line('add_more'); ?></a>
                </div-->


                <div class="col-sm-12">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('header_note'); ?></label> 
                        <textarea name="header_note" class="form-control" id="compose-textarea" style="height:50px"><?php echo $result["header_note"] ?></textarea>
                        <input type="hidden" name="opd_id" value="<?php echo $result['opd_id'] ?>">
                    </div> 
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('footer_note'); ?></label> 
                        <textarea name="footer_note" class="form-control" id="compose-textareas" style="height:50px"><?php echo $result["footer_note"] ?></textarea>
                    </div> 
                </div>

            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </form>
                </div>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#compose-textarea,#compose-textareas").wysihtml5();
    });
    function edit_more() {
        var div = "<div id=row1><div class=col-sm-4><div class=form-group><input type=text name='medicine[]' class=form-control id=report_type /></div></div><div class=col-sm-4><div class=form-group><input type=text class=form-control name='dosage[]' id=report_document /><input type=hidden class=form-control value='0' name='prescription_id[]'  /></div></div><div class=col-sm-4><div class=form-group><textarea name='instruction[]' style='height:28px;' class=form-control id=description></textarea></div></div></div>";

        var table = document.getElementById("edittableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td>" + div + "</td><td><div class=form-group><button type='button' onclick='delete_row(" + id + ")' class='modaltableclosebtn'><i class='fa fa-remove'></i></button></div></td></tr>";
    }

    $(document).ready(function (e) {
        $("#update_prescription").on('submit', (function (e) {
            //var student_id = $("#student_id").val();
            //alert("hii");
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/update_prescription',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function () {
                    //alert("Fail")
                }
            });
        }));
    });
</script>                        