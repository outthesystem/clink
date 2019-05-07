<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<style type="text/css">
    #easySelectable {/*display: flex; flex-wrap: wrap;*/}
    #easySelectable li {}
    #easySelectable li.es-selected {background: #2196F3; color: #fff;}
    .easySelectable {-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('blood_issue') . " " . $this->lang->line('details'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('blood_issue', 'can_add')) { ?> 
                                <a data-toggle="modal"  onclick="holdModal('myModal')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('issue_blood'); ?></a> 
                            <?php } ?>
                        </div> 
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('blood_issue') . " " . $this->lang->line('details'); ?></div>
                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr> 
                                    <th><?php echo $this->lang->line('issue_date'); ?></th>
                                    <th><?php echo $this->lang->line('recieved_to'); ?></th>
                                    <th><?php echo $this->lang->line('blood_group'); ?></th>
                                    <th><?php echo $this->lang->line('gender'); ?></th>
                                    <th><?php echo $this->lang->line('donor') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('bag_no'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($resultlist)) {
                                    ?>
                                    
                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($resultlist as $student) {
                                        ?>
                                        <tr class="">
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($student['date_of_issue'])) ?> 
                                            </td>
                                            <td>
                                                <a><?php echo $student['recieve_to']; ?></a> 
                                                <div class="rowoptionview">
                                                    <a href="#" 
                                                       onclick="viewDetail('<?php echo $student['id'] ?>')"
                                                       class="btn btn-default btn-xs"  data-toggle="modal"
                                                       title="<?php echo $this->lang->line('show'); ?>" >
                                                        <i class="fa fa-reorder"></i>
                                                    </a> 

                                                </div>  
                                            </td>
                                            <td><?php echo $student['blood_group']; ?></td>
                                            <td><?php echo $student['gender']; ?></td>
                                            <td><?php echo $student['donor_name']; ?></td>
                                            <td><?php echo $student['bag_no']; ?></td>
                                            <td class="text-right"><?php echo $student['amount']; ?></td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                                    
            </div>                                                                                                                                          
        </div>  
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('blood_issue') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formadd" accept-charset="utf-8" method="post" class="ptt10" >
                            <div class="row">
                                
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('issue') . " " . $this->lang->line('date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="date_of_issue" id="dates_of_issue" class="form-control datetime">
                                        <span class="text-danger"><?php echo form_error('date_of_issue'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('recieved_to'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="recieve_to" class="form-control">
                                        <span class="text-danger"><?php echo form_error('recieve_to'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('blood_group'); ?></label><small class="req"> *</small> 
                                        <select name="blood_group"  class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php
                                            foreach ($bloodgroup as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value; ?>" <?php if (set_value('blood_group') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>   
                                        <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('gender'); ?></label>
                                        <select class="form-control"  name="gender">
                                            <option value="<?php echo set_value('gender'); ?>"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($genderList as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('doctor'); ?></label>
                                        <small class="req">*</small> 
                                        <input type="text" name="doctor" class="form-control">
                                        <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('institution'); ?></label>
                                        <input type="text" name="institution" class="form-control">
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('technician'); ?></label>
                                        <input type="text" name="technician" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('donor') . " " . $this->lang->line('name'); ?></label>
                                        <input type="text" name="donor_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('lot'); ?></label>
                                        <input type="text" name="lot" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('bag_no'); ?></label>
                                        <input type="text" name="bag_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="amount"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label>
                                        <small class="req"> *</small> 
                                        <input name="amount" type="text" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                               
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="remark"><?php echo $this->lang->line('remarks'); ?></label> 
                                        <textarea name="remark" class="form-control" ></textarea>
                                        </span>
                                    </div> 
                                </div>
                            </div><!--./row-->   

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </form> 
                </div>
            </div>
        </div>
    </div>    
</div>
<!-- dd -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit') . " " . $this->lang->line('blood_issue') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form  id="formedit" accept-charset="utf-8"  method="post" class="ptt10">
                            <div class="row">
                                <input type="hidden" name="id" id="id" value="<?php echo set_value('id'); ?>">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('issue') . " " . $this->lang->line('date'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="date_of_issue" id="date_of_issue" value="" class="form-control datetime">
                                        <span class="text-danger"><?php echo form_error('date_of_issue'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('recieved_to'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="recieve_to" id="recieve_to" value="<?php echo set_value('recieve_to'); ?>" class="form-control">
                                        <span class="text-danger"><?php echo form_error('recieve_to'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('blood_group'); ?></label><small class="req"> *</small> 
                                        <select name="blood_group"  class="form-control" id="blood_group">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php
                                            foreach ($bloodgroup as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value; ?>" <?php if (set_value('blood_group') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>   
                                        <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('gender'); ?></label>
                                        <select class="form-control"  name="gender" id="gender">
                                            <option value="<?php echo set_value('gender'); ?>"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($genderList as $key => $value) {
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('doctor'); ?></label><small class="req"> *</small> 
                                        <input type="text" name="doctor" id="doctor" value="<?php echo set_value('doctor'); ?>" class="form-control">
                                    </div>
                                    <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('institution'); ?></label>
                                        <input type="text" name="institution" id="institution" value="<?php echo set_value('institution'); ?>" class="form-control">
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('technician'); ?></label>
                                        <input type="text" name="technician" id="technician" value="<?php echo set_value('recieve_to'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('donor') . " " . $this->lang->line('name'); ?></label>
                                        <input type="text" name="donor_name" id="donor_name" value="<?php echo set_value('donor_name'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('lot'); ?></label>
                                        <input type="text" name="lot" class="form-control" id="lot" value="<?php echo set_value('lot'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('bag_no'); ?></label>
                                        <input type="text" name="bag_no" class="form-control" id="bag_no" value="<?php echo set_value('bag_no'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="amount"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label>
                                        <small class="req"> *</small> 
                                        <input name="amount" type="text" id="amount" value="<?php echo set_value('amount'); ?>" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="remark"><?php echo $this->lang->line('remarks'); ?></label> 
                                        <textarea name="remark" id="remark" value="<?php echo set_value('remark'); ?>" class="form-control" ></textarea>
                                    </div> 
                                </div>
                            </div><!--./row-->   

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="box-footer">
                <div class="pull-right ">
                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </form>  
                </div>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_delete'>

                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('blood_issue') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">

                        <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
                            <table class="table mb0 table-striped table-bordered">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('issue') . " " . $this->lang->line('date'); ?></th>
                                    <td width="35%"><span id='issue_date_html'></span></td>
                                    <th width="15%"><?php echo $this->lang->line('recieved_to'); ?></th>
                                    <td width="35%"><span id="recieve_to_html"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('doctor'); ?></th>
                                    <td width="35%"><span id='doctor_name'></span></td>
                                    <th width="15%"><?php echo $this->lang->line('institution'); ?></th>
                                    <td width="35%"><span id='institutions'></span></td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('technician'); ?></th>
                                    <td width="35%"><span id="technician_html"></span>
                                    </td>
                                    <th width="15%"><?php echo $this->lang->line('donor') . " " . $this->lang->line('name'); ?></th>
                                    <td width="35%"><span id='donor_names'></span></td>

                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('blood_group'); ?></th>
                                    <td width="35%"><span id='blood_groups'></span></td>
                                    <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                                    <td width="35%"><span id="genders"></span>
                                    </td>
                                </tr>

                                <tr>

                                    <th width="15%"><?php echo $this->lang->line('lot'); ?></th>
                                    <td width="35%"><span id="lots"></span>
                                    </td>
                                    <th width="15%"><span><?php echo $this->lang->line('bag_no'); ?></span></th>
                                    <td width="35%"><span id='bag_nos'></span></td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('amount'); ?></th>
                                    <td width="35%"><span id="amount_html"></span>
                                    </td>
                                    <th width="15%"><?php echo $this->lang->line('remarks'); ?></th>
                                    <td width="35%"><span id="remark_html"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>


        </div>
    </div>    
</div>
<script type="text/javascript">
    $(function () {
        $('#easySelectable').easySelectable();

    })

</script>


<script type="text/javascript">
            /*
             Author: mee4dy@gmail.com
             */
                    (function ($) {
                        //selectable html elements
                        $.fn.easySelectable = function (options) {
                            var el = $(this);
                            var options = $.extend({
                                'item': 'li',
                                'state': true,
                                onSelecting: function (el) {

                                },
                                onSelected: function (el) {

                                },
                                onUnSelected: function (el) {

                                }
                            }, options);
                            el.on('dragstart', function (event) {
                                event.preventDefault();
                            });
                            el.off('mouseover');
                            el.addClass('easySelectable');
                            if (options.state) {
                                el.find(options.item).addClass('es-selectable');
                                el.on('mousedown', options.item, function (e) {
                                    $(this).trigger('start_select');
                                    var offset = $(this).offset();
                                    var hasClass = $(this).hasClass('es-selected');
                                    var prev_el = false;
                                    el.on('mouseover', options.item, function (e) {
                                        if (prev_el == $(this).index())
                                            return true;
                                        prev_el = $(this).index();
                                        var hasClass2 = $(this).hasClass('es-selected');
                                        if (!hasClass2) {
                                            $(this).addClass('es-selected').trigger('selected');
                                            el.trigger('selected');
                                            options.onSelecting($(this));
                                            options.onSelected($(this));
                                        } else {
                                            $(this).removeClass('es-selected').trigger('unselected');
                                            el.trigger('unselected');
                                            options.onSelecting($(this))
                                            options.onUnSelected($(this));
                                        }
                                    });
                                    if (!hasClass) {
                                        $(this).addClass('es-selected').trigger('selected');
                                        el.trigger('selected');
                                        options.onSelecting($(this));
                                        options.onSelected($(this));
                                    } else {
                                        $(this).removeClass('es-selected').trigger('unselected');
                                        el.trigger('unselected');
                                        options.onSelecting($(this));
                                        options.onUnSelected($(this));
                                    }
                                    var relativeX = (e.pageX - offset.left);
                                    var relativeY = (e.pageY - offset.top);
                                });
                                $(document).on('mouseup', function () {
                                    el.off('mouseover');
                                });
                            } else {
                                el.off('mousedown');
                            }
                        };
                    })(jQuery);

</script>



<script type="text/javascript">
            function getSectionByClass(class_id, section_id) {
                if (class_id != "" && section_id != "") {
                    $('#section_id').html("");
                    var base_url = '<?php echo base_url() ?>';
                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                    $.ajax({
                        type: "GET",
                        url: base_url + "sections/getByClass",
                        data: {'class_id': class_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, obj)
                            {
                                var sel = "";
                                if (section_id == obj.section_id) {
                                    sel = "selected";
                                }
                                div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                            });
                            $('#section_id').append(div_data);
                        }
                    });
                }
            }
            $(document).ready(function () {
                var class_id = $('#class_id').val();
                var section_id = '<?php echo set_value('section_id') ?>';
                getSectionByClass(class_id, section_id);
                $(document).on('change', '#class_id', function (e) {
                    $('#section_id').html("");
                    var class_id = $(this).val();
                    var base_url = '<?php echo base_url() ?>';
                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                    $.ajax({
                        type: "GET",
                        url: base_url + "sections/getByClass",
                        data: {'class_id': class_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, obj)
                            {
                                div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                            });
                            $('#section_id').append(div_data);
                        }
                    });
                });
            });
            $(document).ready(function (e) {
                $("#formadd").on('submit', (function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/bloodbank/addIssue',
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

                        }
                    });
                }));
            });
            $(document).ready(function (e) {
                $("#formedit").on('submit', (function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/bloodbank/updateIssue',
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

                        }
                    });
                }));
            });

            function getRecord(id) {

                $.ajax({
                    url: '<?php echo base_url(); ?>admin/bloodbank/getIssueDetails',
                    type: "POST",
                    data: {bloodissue_id: id},
                    dataType: 'json',
                    success: function (data) {

                        $("#id").val(data.id);
                        $("#date_of_issue").val(data.date_of_issue);
                        $("#recieve_to").val(data.recieve_to);
                        $("#blood_group").val(data.blood_group);
                        $("#gender").val(data.gender);
                        $("#doctor").val(data.doctor);
                        $("#institution").val(data.institution);
                        $("#technician").val(data.technician);
                        $("#amount").val(data.amount);
                        $("#donor_name").val(data.donor_name);
                        $("#lot").val(data.lot);
                        $("#bag_no").val(data.bag_no);
                        $("#remark").val(data.remark);
                        $("#updateid").val(id);
                        $('select[id="blood_group"] option[value="' + data.blood_group + '"]').attr("selected", "selected");
                        $('select[id="gender"] option[value="' + data.gender + '"]').attr("selected", "selected");
                        $("#viewModal").modal('hide');
                        holdModal('myModaledit');
                    },
                })
            }


            function viewDetail(id) {

                $.ajax({
                    url: '<?php echo base_url(); ?>admin/bloodbank/getIssueDetails',
                    type: "POST",
                    data: {bloodissue_id: id},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $("#issue_date_html").html(data.date_of_issue);
                        $("#recieve_to_html").html(data.recieve_to);
                        $("#blood_groups").html(data.blood_group);
                        $("#bag_nos").html(data.bag_no);
                        $("#genders").html(data.gender);
                        $("#doctor_name").html(data.doctor);
                        $("#institutions").html(data.institution);
                        $("#technician_html").html(data.technician);
                        $("#amount_html").html(data.amount);
                        $("#lots").html(data.lot);
                        $("#donor_names").html(data.donor_name);
                        $("#blood_bank_nos").html(data.blood_bank_no);
                        $("#remark_html").html(data.remark);
                        $("#edit_delete").html("<a href='#' onclick='getRecord(" + id + ")' data-toggle='tooltip' title='' data-original-title='Edit'><i class='fa fa-pencil'></i></a><a onclick='deleterecord(" + id + ")'  href='#'  data-toggle='tooltip'  data-original-title='Delete'><i class='fa fa-trash'></i></a>");
                        holdModal('viewModal');
                    },
                });
            }

            function deleterecord(id) {
                var url = '<?php echo base_url() ?>admin/bloodbank/deleteIssue/' + id;
                var msg = "<?php echo $this->lang->line('delete_message') ?>";
                delete_recordById(url, msg)
            }

            function holdModal(modalId) {
                $('#' + modalId).modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            }

</script>