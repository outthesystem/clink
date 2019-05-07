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
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('patient') . " " . $this->lang->line('report'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('radiology') . " " . $this->lang->line('test') . " " . $this->lang->line('reports'); ?></div>
                        <table class="table table-striped table-bordered table-hover example" id="testreport" cellspacing="0" width="100%">
                            <thead >
                                <tr>
                                    <th><?php echo $this->lang->line('reporting') . " " . $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('refference') . " " . $this->lang->line('doctor'); ?></th>
                                    <th><?php echo $this->lang->line('description'); ?></th>
                                    <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></th>
                                    <th class="text-right" ><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('action') ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($result)) {
                                    ?>

                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($result as $detail) {
                                        ?>
                                        <tr class="">
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($detail->reporting_date));
                                        ?> </td>
                                            <td><?php echo $detail->patient_name; ?></td>
                                            <td><?php echo $detail->test_name; ?></td>
                                            <td><?php echo $detail->short_name; ?></td>
                                            <td><?php echo $detail->name . " " . $detail->surname; ?></td>
                                            <td><?php echo $detail->description; ?></td>
                                            <td><?php echo $detail->charge_category; ?></td>
                                            <td class="text-right"><?php
                                                if (!empty($detail->apply_charge)) {
                                                    echo $detail->apply_charge;
                                                } else {
                                                    echo $detail->standard_charge;
                                                }
                                                ?></td>
                                            <td class="text-right">
        <?php if (!empty($detail->radiology_report)) { ?>  
                                                    <a href="<?php echo base_url(); ?>admin/radio/download/<?php echo $detail->radiology_report; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
        <?php } ?>
        <?php if ($this->rbac->hasPrivilege('add_patient_test_reprt', 'can_edit')) { ?>
                                                    <a href="#" onclick="editTestReport('<?php echo $detail->id ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit_test_report'); ?>">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a> 
                                                <?php } ?>


        <?php if (!empty($detail->id)) { ?>
            <?php if ($this->rbac->hasPrivilege('add_patient_test_reprt', 'can_delete')) { ?>
                                                        <a  class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_recordById('<?php echo base_url(); ?>admin/radio/deleteTestReport/<?php echo $detail->id; ?>', '<?php echo $this->lang->line('delete_message') ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>    
            <?php }
        }
        ?>
                                            </td>
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
<div class="modal fade" id="editTestReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit') . " " . $this->lang->line('test') . " " . $this->lang->line('report'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="updatetest" enctype="multipart/form-data" accept-charset="utf-8"  method="post" class="ptt10" >
                            <input type="hidden" name="id" id="report_id" >
                            <div class="row">
                                <!-- <div class="col-sm-4">
                                  <div class="form-group">
                                    <label class="radio-inline">
                                      <input type="radio" name="opd_ipd_patient_type" value="opd">Opd Patient
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="opd_ipd_patient_type" value="ipd">Ipd Patient
                                    </label>
                                  </div>
                                </div> -->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
<?php echo $this->lang->line('customer') . " " . $this->lang->line('type'); ?></label>
                                        <div>
                                            <input class="form-control" type="text" name="customer_type" id='customer_types' readonly>
                                            <!-- <select class="form-control" name='customer_type' id='customer_types' >
                                              <<option><?php echo $this->lang->line('select') ?></option>
                                              <option value="<?php echo "direct"; ?>" selected><?php echo $this->lang->line('direct'); ?></option>
                                              <option value="<?php echo "opd"; ?>"><?php echo $this->lang->line('opd'); ?></option>   
                                              <option value="<?php echo "ipd"; ?>"><?php echo $this->lang->line('ipd'); ?></option> 
                                            </select> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-sm-3">
                                  <div class="form-group">
                                      <label><?php echo $this->lang->line('opd_ipd_no'); ?></label>
                                      <input type="text" name="opd_ipd_no" class="form-control" id="opdipd" onchange="getPatientIdName(this.value)">
                                      <input type="hidden" name="patient_id" id="patient_id">
                                  </div>
                                </div>   -->

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="patient_name" class="form-control" id="edit_patient_name">
                                        <span class="text-danger"><?php echo form_error('patient_name'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('reporting') . " " . $this->lang->line('date'); ?></label>
                                        <input type="text" id="edit_report_date" name="reporting_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('reporting_date'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                                <?php echo $this->lang->line('refferal') . " " . $this->lang->line('doctor'); ?></label>
                                        <div>
                                            <select class="form-control select2" style="width: 100%" name='consultant_doctor' id="edit_consultant_doctor">
                                                <option value="<?php echo set_value('consultant_doctor'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>   
<?php } ?>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="description"><?php echo $this->lang->line('description'); ?></label> 
                                      <!--   <small class="req"> *</small>  -->
                                        <textarea name="description" id="edit_description" class="form-control" ></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('report'); ?></label>
                                        <input type="file" class="filestyle form-control" data-height="40" name="radiology_report">
                                        <span class="text-danger"><?php echo form_error('pathology_report'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>
                                        <input type="text" class="form-control" readonly="" id="charge_category_html" >

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code'); ?></label>
                                        <input type="text" readonly="" class="form-control" id="code_html" >
                                        <span class="text-danger" ></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></label>
                                        <input type="text" readonly="" class="form-control" id="charge_html" >
                                        <span class="text-danger" ></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('apply') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></label>
                                        <input type="text" name="apply_charge" class="form-control" id="apply_charge" >
                                    </div>
                                </div> 
                            </div><!--./row-->   

                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>
            <div class="box-footer">
                <div class="pull-right">

                    <button type="submit" class="btn btn-info pull-right" ><?php echo $this->lang->line('save'); ?></button>
                    </form>  
                </div>
            </div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
    function editTestReport(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/getRadiologyReport',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                $("#report_id").val(data.id);
                $("#charge_category_html").val(data.charge_category);
                $("#code_html").val(data.code);
                $("#charge_html").val(data.standard_charge);
                if (data.apply_charge == "") {
                    $("#apply_charge").val(data.standard_charge);
                } else {
                    $("#apply_charge").val(data.apply_charge);
                }

                $("#customer_types").val(data.customer_type);
                $("#opdipd").val(data.opd_ipd_no);
                $("#edit_patient_name").val(data.patient_name);
                $("#edit_report_date").val(data.reporting_date);
                $('select[id="edit_consultant_doctor"] option[value="' + data.consultant_doctor + '"]').attr("selected", "selected");
                $("#edit_description").val(data.description);
                $("#edit_consultant_doctor").select2().select2('val', data.consultant_doctor);
                holdModal('editTestReportModal');
            },
        })
    }
    $(document).ready(function (e) {
        $("#updatetest").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/updateTestReport',
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
                    //  alert("Fail")
                }
            });
        }));
    });

</script>

