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
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('operation_theatre') . " " . $this->lang->line('patient'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('ot_patient', 'can_add')) { ?>   
                                <a data-toggle="modal" onclick="holdModal('myModal')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('patient'); ?></a> 
                            <?php } ?>
                        </div>  
                    </div><!-- /.box-header -->
                    <?php
                    if (isset($resultlist)) {
                        ?>
                        <div class="box-body">
                            <div class="download_label"><?php echo $this->lang->line('operation_theatre') . " " . $this->lang->line('patient'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('type'); ?></th>

                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('operation') . " " . $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('operation') . " " . $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('consultant'); ?></th>
                                        <th><?php echo $this->lang->line('operation') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")"; ?></th>
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

                                                <td>
                                                    <?php if ($this->rbac->hasPrivilege('ot_patient', 'can_view')) { ?>   
                                                        <a href="#" 
                                                           onclick="viewDetail('<?php echo $student['pid'] ?>')" data-toggle="tooltip" title="<?php echo $this->lang->line('detail'); ?>"
                                                           href="<?php echo base_url(); ?> student/view/<?php echo $student['id']; ?>">
                                                               <?php echo $student['patient_name']; ?>
                                                        </a>
                                                    <?php } ?>
                                                    <div class="rowoptionview">
                                                        <?php
                                                        if ($this->rbac->hasPrivilege('ot_consultant_instruction', 'can_add')) {
                                                            ?>
                                                            <a href="#" onclick="add_instruction('<?php echo $student['pid'] ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('consultant'); ?> <?php echo $this->lang->line('instruction'); ?> " >
                                                                <i class="fa fa-user-md"></i>
                                                            </a> 
                                                        <?php } ?> 
                                                        <?php if ($this->rbac->hasPrivilege('ot_patient', 'can_view')) { ?>         
                                                            <a href="#" 
                                                               onclick="viewDetail('<?php echo $student['pid'] ?>')"
                                                               class="btn btn-default btn-xs"  data-toggle="tooltip"
                                                               title="<?php echo $this->lang->line('show'); ?>" >
                                                                <i class="fa fa-reorder"></i>
                                                            </a>
                                                        <?php } ?>

                                                    </div>  
                                                </td>
                                                <td><?php echo $student["patient_unique_id"] ?></td>
                                                <td><?php echo $this->lang->line($student["customer_type"]) ?></td>
                                                <td><?php echo $student['gender']; ?></td>
                                                <td><?php echo $student['mobileno']; ?></td>
                                                <td><?php echo $student['operation_name']; ?></td>
                                                <td><?php echo $student['operation_type']; ?></td>
                                                <td><?php echo $student['name'] . " " . $student['surname']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($student['date'])) ?></td>
                                                <td><?php echo $student['apply_charge']; ?></td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>  
            </div>
        </div> 
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('patient') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="formadd" accept-charset="utf-8"  method="post">
                            <div class="row row-eq">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="row ptt10">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('customer') . " " . $this->lang->line('type'); ?></label>
                                                <div>
                                                    <select class="form-control" name='customer_type' id='customer_type' >
                                                        <option><?php echo $this->lang->line('select') ?></option>
                                                        <option value="<?php echo "direct"; ?>" selected><?php echo $this->lang->line('direct'); ?></option>
                                                        <option value="<?php echo "opd"; ?>"><?php echo $this->lang->line('opd'); ?></option>   
                                                        <option value="<?php echo "ipd"; ?>"><?php echo $this->lang->line('ipd'); ?></option> 
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('opd_ipd_no'); ?></label>
                                                <input type="text" name="opd_ipd_no" class="form-control" onchange="getPatientIdName(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></label><small class="req"> *</small>  
                                                <input  name="patient_id" id="patientsid" type="hidden" class="form-control" placeholder="patient id" /> 
                                                <input  name="patient_name" id="patientsname" type="text" class="form-control"/>
                                                <span class="text-danger"><?php echo form_error('patient_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('guardian_name'); ?></label>
                                                <input type="text" name="guardian_name" id="patientsguardian_name" class="form-control">

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('phone'); ?></label>
                                                <input  name="mobileno" type="text" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('gender'); ?> </label>
                                                <select class="form-control" id="patientsgender" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('age'); ?></label>
                                                <div style="clear: both;overflow: hidden;"><input type="text" placeholder="<?php echo $this->lang->line('year'); ?>" name="age" value="" class="form-control" style="width: 40%; float: left;">
                                                    <input type="text" placeholder="<?php echo $this->lang->line('month'); ?>" name="month" value="" class="form-control" style="width: 56%;float: left; margin-left: 5px;">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('address'); ?></label>
                                                <input type="text" name="guardian_address" id="patientsguardian_address" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('result'); ?></label>
                                                <input type="text" name="result" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="remark"><?php echo $this->lang->line('remarks'); ?></label> 
                                                <textarea name="remark" class="form-control" ></textarea>
                                                </span>
                                            </div> 
                                        </div>
                                    </div><!--./row--> 
                                </div><!--./col-md-6--> 
                                <div class="col-lg-6 col-md-6 col-sm-6 col-eq ptt10">
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="operation_name --r"><?php echo $this->lang->line('operation') . " " . $this->lang->line('name'); ?></label>
                                                <small class="req"> *</small> 
                                                <input id="number" autocomplete="off" name="operation_name" placeholder="" type="text" class="form-control"/>
                                                <span class="text-danger"><?php echo form_error('operation_name'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('operation') . " " . $this->lang->line('type'); ?></label>

                                                <input type="text" name="operation_type" class="form-control">
                                                <span class="text-danger"><?php echo form_error('operation_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('operation') . " " . $this->lang->line('date'); ?></label>
                                                <small class="req"> *</small> 
                                                <input type="text" value="<?php //echo set_value('email');   ?>" id="date" name="date" class="form-control date">
                                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></label>
                                                <small class="req"> *</small> 
                                                <div><select class="form-control select2" style="width:100%" name='consultant_doctor' >
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
                                                <label><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '1'; ?></label>

                                                <input type="text" name="ass_consultant_1" class="form-control">

                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '2'; ?></label>
                                                <input type="text" name="ass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('anesthetist'); ?></label>

                                                <input type="text" name="anesthetist" class="form-control">

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('anaethesia') . " " . $this->lang->line('type'); ?></label>
                                                <input type="text" name="anaethesia_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('ot') . " " . $this->lang->line('technician'); ?></label>

                                                <input type="text" name="ot_technician" class="form-control">

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('ot') . " " . $this->lang->line('assistent'); ?></label>

                                                <input type="text" value="<?php //echo set_value('email');   ?>" name="ot_assistant" class="form-control">

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('organisation'); ?></label>
                                                <div>
                                                    <select class="form-control" id="organisation" name='organisation' >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                            ?>
                                                            <option value="<?php echo $orgvalue["id"]; ?>"><?php echo $orgvalue["organisation_name"] ?></option>   
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('organisation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>
                                                <small class="req">*</small> 
                                                <div>
                                                    <select class="form-control" onchange="getchargecode(this.value)" name='charge_category_id' >
                                                        <option value="<?php echo set_value('charge_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($charge_category as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["name"]; ?>"><?php echo $dvalue["name"] ?></option>   
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('charge_category_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('code'); ?></label>
                                                <small class="req">*</small> 
                                                <div>
                                                    <select class="form-control" name='code' onchange="getchargeDetails(this.value, 'standard_charge')" id="code" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('standard') . ' ' . $this->lang->line('charge'); ?></label><?php echo ' (' . $currency_symbol . ')'; ?>
                                                <small class="req">*</small> 
                                                <div>
                                                    <input readonly="" class="form-control" name='standard_charge' id="standard_charge" >

                                                </div>
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('applied') . ' ' . $this->lang->line('charge'); ?></label><?php echo ' (' . $currency_symbol . ')'; ?>
                                                <small class="req">*</small> 
                                                <div>
                                                    <input class="form-control" type="text" name="apply_charge" id="apply_charge" />

                                                </div>
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                        </div>
                                    </div><!--./row-->
                                </div><!--./col-lg-6-->
                            </div><!--./row-->   
                            <div class="row">            
                                <div class="box-footer">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->  
                        </form>                       
                    </div><!--./col-md-12-->       

                </div><!--./row--> 

            </div>

        </div>
    </div>    
</div>
<!-- dd -->
<div class="modal fade" id="myModaledit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit') . " " . $this->lang->line('patient') . " " . $this->lang->line('information'); ?></h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="formedit" accept-charset="utf-8"  method="post">
                            <div class="row row-eq">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="row ptt10">
                                        <!--div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></label>
                                                <input id="admissions_date" name="admission_date" type="text" class="form-control date" value="<?php echo set_value('admission_date'); ?>" />
                                            </div>
                                        </div-->
                                        <div class="col-md-12">
                                            <div class="form-group">  
                                                <input name="otid" id="otid" type="hidden" class="form-control"  value="<?php echo set_value('id'); ?>" />
                                                <input id="patients_id" name="id" type="hidden" class="form-control"  value="<?php echo set_value('patient_id'); ?>" />
                                                <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></label><small class="req"> *</small> 
                                                <input id="patient_name" name="patient_name" type="text" class="form-control"  value="<?php echo set_value('patient_name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('patient_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('guardian_name'); ?></label>
                                                <input type="text" id="guardian_name" name="guardian_name" value="<?php echo set_value('guardian_name'); ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('phone'); ?></label>
                                                <input type="text" id="edit_mobileno" name="mobileno" value="<?php echo set_value('mobileno'); ?>" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('gender'); ?></label> 
                                                <select class="form-control" id="genders" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('age'); ?></label>
                                                <div style="clear: both;overflow: hidden;"><input type="text" placeholder="<?php echo $this->lang->line('year'); ?>" id="edit_age" name="age" value="<?php echo set_value('age'); ?>" class="form-control" style="width: 40%; float: left;">
                                                    <input type="text" placeholder="<?php echo $this->lang->line('month'); ?>" id="edit_month" name="month" value="<?php echo set_value('month'); ?>" class="form-control" style="width: 56%;float: left; margin-left: 5px;">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('address'); ?></label>
                                                <input type="text" name="guardian_address" id="edit_guardian_address" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('result'); ?></label>
                                                <input type="text" id="result" value="<?php echo set_value('result'); ?>" name="result" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="remark"><?php echo $this->lang->line('remarks'); ?></label> 

                                                <textarea name="remark" id="remark" class="form-control" ><?php echo set_value('remark'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('remark'); ?>
                                                </span>
                                            </div> 
                                        </div>
                                    </div><!--./row--> 
                                </div><!--./col-md-6--> 
                                <div class="col-lg-6 col-md-6 col-sm-6 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date'); ?></label>
                                                <small class="req"> *</small> 
                                                <input type="text" value="<?php echo set_value('date'); ?>" id="dates" name="date" class="form-control date" autocomplete="off">
                                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></label>
                                                <small class="req"> *</small> 
                                                <div><select class="form-control select2" style="width: 100%" name='consultant_doctor' id="cons_doctor" >
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
                                                <label for="operation_name --r"><?php echo $this->lang->line('operation') . " " . $this->lang->line('name'); ?></label>
                                                <small class="req"> *</small> 
                                                <input id="operation_name" autocomplete="off" name="operation_name" type="text" class="form-control"  value="<?php echo set_value('operation_name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('operation_name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('operation') . " " . $this->lang->line('type'); ?></label>

                                                <input type="text" id="operation_type"
                                                       value="<?php echo set_value('operation_type'); ?>" name="operation_type" class="form-control">
                                                <span class="text-danger"><?php echo form_error('operation_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '1'; ?></label>

                                                <input type="text" id="ass_consultant_1" value="<?php echo set_value('ass_consultant_1'); ?>" name="ass_consultant_1" class="form-control">
                                                <span class="text-danger"><?php echo form_error('ass_consultant_1'); ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '2'; ?></label>
                                                <input type="text" id="ass_consultant_2" value="<?php echo set_value('ass_consultant_2'); ?>" name="ass_consultant_2" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('anesthetist'); ?></label>

                                                <input type="text" id="anesthetist" value="<?php echo set_value('anesthetist'); ?>" name="anesthetist" class="form-control">
                                                <span class="text-danger"><?php echo form_error('anesthetist'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('anaethesia') . " " . $this->lang->line('type'); ?></label>
                                                <input type="text" id="anaethesia_type" value="<?php echo set_value('anaethesia_type'); ?>" name="anaethesia_type" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('ot') . " " . $this->lang->line('technician'); ?></label>

                                                <input type="text" id="ot_technician" value="<?php echo set_value('ot_technician'); ?>" name="ot_technician" class="form-control">
                                                <span class="text-danger"><?php echo form_error('ot_technician'); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('ot') . " " . $this->lang->line('assistent'); ?></label>

                                                <input type="text" id="ot_assistant" value="<?php echo set_value('ot_assistant'); ?>" name="ot_assistant" class="form-control">
                                                <span class="text-danger"><?php echo form_error('ot_assistant'); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('organisation'); ?></label>

                                                <div>
                                                    <select class="form-control" name='organisation' id="edit_organisation"  >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                            ?>
                                                            <option value="<?php echo $orgvalue["id"]; ?>"><?php echo $orgvalue["organisation_name"] ?></option>   
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('organisation'); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>
                                                <small class="req">*</small> 
                                                <div>
                                                    <select class="form-control" name='charge_category_id' id="edit_charge_category" onchange="editchargecode(this.value)" >
                                                        <option value="<?php echo set_value('charge_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($charge_category as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["name"]; ?>"><?php echo $dvalue["name"] ?></option>   
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('charge_category_id'); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('code') ?></label>
                                                <small class="req">*</small> 
                                                <div>
                                                    <select class="form-control" name='charge_category_id' onchange="getchargeDetails(this.value, 'edit_standard_charge')" id="edit_code" >
                                                        <option value="<?php echo set_value('charge_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>

                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('charge_category_id'); ?></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line("standard") . " " . $this->lang->line("charge") ?></label><?php echo '(' . $currency_symbol . ')'; ?>
                                                <small class="req">*</small> 
                                                <div>
                                                    <input class="form-control" readonly="" name='standard_charge' id="edit_standard_charge" >

                                                </div>
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line("applied") . " " . $this->lang->line("charge") ?></label><?php echo '(' . $currency_symbol . ')'; ?>
                                                <small class="req">*</small> 
                                                <div>
                                                    <input class="form-control" name='apply_charge' id="edit_apply_charge" >

                                                </div>
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                        </div> 
                                    </div><!--./row-->
                                </div><!--./col-lg-6-->
                            </div><!--./row-->
                            <div class="row">            
                                <div class="box-footer">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->  
                        </form>                      
                    </div><!--./col-md-12-->       

                </div><!--./row--> 

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

                        <a href="#" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" ><i class="fa fa-pencil"></i></a>
                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('operation') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="view" accept-charset="utf-8" method="get" class="ptt10">
                            <div class="table-responsive">
                                <div class="col-md-6">
                                    <table class="printablea4 examples">
                                        <tr>
                                            <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                            <td><span id='patients_name'></span></td>



                                        </tr>
                                        <tr>

                                            <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                            <td><span id='patientsids'></span> (<span id='patient_type'></span>)</td>

                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('opd_ipd_no'); ?></th>
                                            <td><span id="opd_ipd_no"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('gender') ?></th>
                                            <td><span id="genderes"></span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('age') ?></th>
                                            <td><span id="age_age"></span>

                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                            <td><span id='mobileno'></span></td>

                                        </tr>

                                        <tr>
                                            <th><?php echo $this->lang->line('guardian_name') ?></th>
                                            <td><span id='guardians_name'></span></td>


                                        </tr>

                                        <tr>
                                            <th><?php echo $this->lang->line('address') ?></th>
                                            <td><span id='guardians_address'></span></td>

                                        </tr>

                                        <tr>

                                            <th><?php echo $this->lang->line('result'); ?></th>
                                            <td><span id='results'></span></td>

                                        </tr>                             
                                        <tr>
                                            <th><?php echo $this->lang->line('remarks'); ?></th>
                                            <td><span id="remarks"></span>
                                            </td>

                                            </td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="printablea4 examples">
                                        <tr>
                                            <th><?php echo $this->lang->line('operation') . " " . $this->lang->line('name'); ?></th>
                                            <td><span id='operations_name'></span> (<span id="operations_type"></span>)</td>
                                        </tr>
                                        <tr>

                                            <th><?php echo $this->lang->line('operation') . " " . $this->lang->line('date') ?></th>
                                            <td><span id="date_s"></span>
                                            </td>
                                        </tr>
                                        <tr>                                  
                                            <th><?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></th>
                                            <td><span id='cons_doctors'></span></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '1'; ?></th>
                                            <td><span id="ass_consultants_1"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('assistent') . " " . $this->lang->line('consultant') . " " . '2'; ?></th>
                                            <td><span id='ass_consultants_2'></span></td>

                                        </tr>
                                        <tr>

                                            <th><?php echo $this->lang->line('anesthetist'); ?></th>
                                            <td><span id="anesthetists"></span>
                                            </td>

                                        </tr>
                                        <tr>

                                            <th><?php echo $this->lang->line('anaethesia') . " " . $this->lang->line('type'); ?></th>
                                            <td><span id='anaethesia_types'></span></td>

                                        </tr>

                                        <tr>

                                            <th><?php echo $this->lang->line('ot') . " " . $this->lang->line('technician'); ?></th>
                                            <td><span id="ot_techniciandata"></span>
                                            </td>
                                        </tr>

                                        <tr>                                   
                                            <th><?php echo $this->lang->line('ot') . " " . $this->lang->line('assistent'); ?></th>
                                            <td><span id='ot_assistent'></span></td>                                   
                                        </tr>
                                        <tr>                                   
                                            <th><?php echo $this->lang->line('organisation'); ?></th>
                                            <td><span id="organisation_name"></span>
                                            </td>                               
                                        </tr>
                                        <tr>
                                        <tr>                                   
                                            <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></th>
                                            <td><span id="charge_categorys"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('code') . " (" . $this->lang->line('description') . ")"; ?></th>
                                            <td><span id='codes'></span>
                                                <span id="description"></span>
                                            </td>


                                        </tr>

                                        <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></th>
                                        <td><span id='apply_chargeview'></span> (<span id="stdcharge"></span>)                                   
                                            </tr>
                                    </table>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
                <div id="reportdata"></div>
            </div>    
        </div></div></div>
<div class="modal fade" id="add_instruction" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('consultant') . " " . $this->lang->line('instruction'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="consultant_register"  accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input name="patient_id" placeholder="" id="ins_patient_id"  type="hidden" class="form-control"   />

                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" id="tableID">
                                        <tr>
                                            <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('date') ?><small style="color:red;"> *</small></th>
                                            <th><?php echo $this->lang->line('consultant') ?><small style="color:red;"> *</small></th>
                                            <th><?php echo $this->lang->line('instruction') ?><small style="color:red;"> *</small></th>
                                            <th><?php echo $this->lang->line('instruction') . " " . $this->lang->line('date'); ?><small style="color:red;"> *</small></th>
                                            <!-- <th>Instruction Time</th> -->
                                        </tr>
                                        <tr id="row0">
                                            <td>
                                                <input type="text" name="date[]" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(true, true))); ?>" class="form-control datetime">
                                                <span class="text-danger"><?php echo form_error('date'); ?>
                                                </span>
                                            </td>
                                            <td> 
                                                <select name="doctor[]" class="form-control select2" style="width: 100%">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["name"] . " " . $value["surname"] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('doctor[]'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <textarea style="height:28px" name="instruction[]" class="form-control"></textarea>
                                            </td>
                                            <td>
                                                <input value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>"  type="text"  name="insdate[]" class="form-control date">
                                            </td>

                                            <td><button type="button" onclick="add_more()" style="color: #2196f3" class="closebtn"><i class="fa fa-plus"></i></button></a></td>

                                        </tr>
                                    </table>

                                </div>

                                <button type="submit" class="btn btn-info pull-right" ><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>                          
<script type="text/javascript">

    function add_more() {

        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var div = "<td><input type='text' name='date[]' class='form-control datetime'></td><td><select name='doctor[]' class='form-control select2' style='width:100%'><option value=''><?php echo $this->lang->line('select') ?></option><?php foreach ($doctors as $key => $value) { ?><option value='<?php echo $value["id"] ?>'><?php echo $value["name"] . ' ' . $value["surname"] ?></option><?php } ?></select></td><td><textarea name='instruction[]' style='height:28px;' class='form-control'></textarea></td><td><input type='text' name='insdate[]' class='form-control date'></td>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div + "<td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
        $('.select2').select2();

        // $('.instime').timepicker();
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
//table.deleteRow(id);
    }
</script>
<script type="text/javascript">
    $(function () {
        $('#easySelectable').easySelectable();
        //stopPropagation();
    })
// $('#easySelectable').bind('click', function (e) { e.stopPropagation() })


            //  $(".dropdown-menu li"){
                    //         e.stopPropagation();
                            // };

                                    //        $(function() {
                                            //     $('.dropdown-menu').on({
                                                    //         "click": function(event) {
                                                            //           if ($(event.target).closest('.dropdown-toggle').length) {
                                                                    //             $(this).data('closable', true);
                                                                            //           } else {
                                                                                    //             $(this).data('closable', false);
                                                                                            //           }
                                                                                                    //         },
                                                                                                            //         "hide.bs.dropdown": function(event) {
                                                                                                                    //           hide = $(this).data('closable');
                                                                                                                            //           $(this).data('closable', true);
                                                                                                                                    //           return hide;
                                                                                                                                            //         }
                                                                                                                                                    //     });
                                                                                                                                                            // });

//         $(document).ready(function () {

//     $('.dropdown-menu li').click(function(e) {
// e.stopPropagation();
//         //$('.dropdown-menu li').removeClass('active2');
//         //$('.dropdown-menu li').attr('data-toggle'); 

//         // var $this = $(this);
//         // if (!$this.hasClass('active2')) {
//         //     $this.addClass('active2');
//         // }

//     });
// });

// $(document).ready(function () {   
//      $('.dropdown-menu li').each(function() {
//         var count = 0;
//         $(this).click(function(){
//          count++;
//         if (count === 1) {
//             $(this).addClass('on');
//         }
//         else if(count === 2){
//             $(this).removeClass('on');
//             $(this).addClass('absent');
//         }
//         else{
//             $(this).removeClass('absent');
//             count = 0;
//         }
//         });
//     });

// });



// $(".multi-level").click(function (e) {
//             e.stopPropagation();
//         });


// $("document").ready(function() {

//   $('.dropdown-menu li').on(function(e) {
//       if($(this).hasClass('multi-level')) {
//           e.stopPropagation();
//       }
//   });
// });
// $(function() {    
//     $('.dropdown-menu li').each(function() {
//         var count = 0;
//         $('this').click(function(){
//         count++;
//         if (count === 1) {
//             $(this).addClass('on');
//         }
//         else if(count === 2){
//             $(this).removeClass('on');
//             $(this).addClass('absent');
//         }
//         else{
//             $(this).removeClass('absent');
//             count = 0;
//         }
//         });
//     });

// });
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
                                                                                                                                                                    function getPatientIdName(opd_ipd_no) {
                                                                                                                                                                        var opd_ipd_patient_type = $("#customer_type").val();
                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/patient/getPatientType',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {opd_ipd_patient_type: opd_ipd_patient_type, opd_ipd_no: opd_ipd_no},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (data) {

                                                                                                                                                                                $('#patientsid').val(data.patient_id);
                                                                                                                                                                                $('.adm_date').val(data.admission_date);
                                                                                                                                                                                $('#patientsname').val(data.patient_name);
                                                                                                                                                                                $('#patientsage').val(data.age);
                                                                                                                                                                                $('#patientsguardian_name').val(data.guardian_name);
                                                                                                                                                                                // $('#edit_age').val(data.age);
                                                                                                                                                                                // $('#edit_month').val(data.month);
                                                                                                                                                                                $('#patientsguardian_address').val(data.guardian_address);
                                                                                                                                                                                $('select[id="patientsgender"] option[value="' + data.gender + '"]').attr("selected", "selected");
                                                                                                                                                                            }
                                                                                                                                                                        });
                                                                                                                                                                    }
                                                                                                                                                                    function getchargeDetails(id, htmlid) {
                                                                                                                                                                        var orgid = $("#organisation").val();

                                                                                                                                                                        $('#' + htmlid).val("");
                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/charges/getDetails',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {charges_id: id, organisation: orgid},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (res) {
                                                                                                                                                                                $('#' + htmlid).val(res.standard_charge);

                                                                                                                                                                                if (orgid != "") {
                                                                                                                                                                                    $('#apply_charge').val(res.org_charge);
                                                                                                                                                                                    $('#edit_apply_charge').val(res.org_charge);
                                                                                                                                                                                } else {
                                                                                                                                                                                    $('#apply_charge').val(res.standard_charge);
                                                                                                                                                                                    $('#edit_apply_charge').val(res.standard_charge);
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                        });
                                                                                                                                                                    }
                                                                                                                                                                    function getchargecode(charge_category) {
                                                                                                                                                                        var div_data = "";
                                                                                                                                                                        $('#code').html("<option value=''>Select</option>");
                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/charges/getchargeDetails',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {charge_category: charge_category},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (res) {
                                                                                                                                                                                $.each(res, function (i, obj) {
                                                                                                                                                                                    var sel = "";
                                                                                                                                                                                    div_data += "<option value='" + obj.id + "'>" + obj.code + " - " + obj.description + "</option>";
                                                                                                                                                                                });
                                                                                                                                                                                $('#code').append(div_data);
                                                                                                                                                                            }
                                                                                                                                                                        });
                                                                                                                                                                    }
                                                                                                                                                                    function editchargecode(charge_category, charge_id) {
                                                                                                                                                                        var div_data = "";
                                                                                                                                                                        $('#edit_code').html("<option value=''>Select</option>");
                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/charges/getchargeDetails',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {charge_category: charge_category},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (res) {
                                                                                                                                                                                $.each(res, function (i, obj) {
                                                                                                                                                                                    var sel = "";
                                                                                                                                                                                    if (charge_id == obj.id) {
                                                                                                                                                                                        sel = "selected";
                                                                                                                                                                                    }
                                                                                                                                                                                    div_data += "<option value='" + obj.id + "' " + sel + ">" + obj.code + " - " + obj.description + "</option>";
                                                                                                                                                                                });
                                                                                                                                                                                $('#edit_code').append(div_data);
                                                                                                                                                                            }
                                                                                                                                                                        });
                                                                                                                                                                    }
                                                                                                                                                                    $(document).ready(function (e) {
                                                                                                                                                                        $("#formadd").on('submit', (function (e) {
                                                                                                                                                                            e.preventDefault();
                                                                                                                                                                            $.ajax({
                                                                                                                                                                                url: '<?php echo base_url(); ?>admin/operationtheatre/add',
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
                                                                                                                                                                    $(document).ready(function (e) {
                                                                                                                                                                        $("#formedit").on('submit', (function (e) {
                                                                                                                                                                            e.preventDefault();
                                                                                                                                                                            $.ajax({
                                                                                                                                                                                url: '<?php echo base_url(); ?>admin/operationtheatre/update',
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
                                                                                                                                                                    function getRecord(id) {

                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/operationtheatre/getOtPatientDetails',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {id: id},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (data) {

                                                                                                                                                                                $("#otid").val(data.id);
                                                                                                                                                                                $("#patients_id").val(data.patient_id);
                                                                                                                                                                                $("#patientid").val(data.patient_unique_id);
                                                                                                                                                                                $("#admissions_date").val(data.admission_date);
                                                                                                                                                                                $("#patient_name").val(data.patient_name);
                                                                                                                                                                                $("#genders").val(data.gender);
                                                                                                                                                                                $("#edit_age").val(data.age);
                                                                                                                                                                                $("#edit_month").val(data.month);
                                                                                                                                                                                $("#guardian_name").val(data.guardian_name);
                                                                                                                                                                                $("#edit_guardian_address").val(data.guardian_address);
                                                                                                                                                                                $("#edit_mobileno").val(data.mobileno);
                                                                                                                                                                                $("#dates").val(data.date);
                                                                                                                                                                                $("#operation_name").val(data.operation_name);
                                                                                                                                                                                $("#operation_type").val(data.operation_type);
                                                                                                                                                                                $("#cons_doctor").val(data.consultant_doctor);
                                                                                                                                                                                $("#ass_consultant_1").val(data.ass_consultant_1);
                                                                                                                                                                                $("#ass_consultant_2").val(data.ass_consultant_2);
                                                                                                                                                                                $("#anesthetist").val(data.anesthetist);
                                                                                                                                                                                $("#anaethesia_type").val(data.anaethesia_type);
                                                                                                                                                                                $("#ot_technician").val(data.ot_technician);
                                                                                                                                                                                $("#ot_assistant").val(data.ot_assistant);
                                                                                                                                                                                $("#edit_charge_category").val(data.charge_category);
                                                                                                                                                                                editchargecode(data.charge_category, data.charge_id);
                                                                                                                                                                                $("#edit_standard_charge").val(data.standard_charge);
                                                                                                                                                                                $("#edit_apply_charge").val(data.apply_charge);
                                                                                                                                                                                $("#result").val(data.result);
                                                                                                                                                                                $("#remark").val(data.remark);
                                                                                                                                                                                $("#updateid").val(id);
                                                                                                                                                                                $('select[id="cons_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected", "selected");
                                                                                                                                                                                $('select[id="edit_organisation"] option[value="' + data.organisation + '"]').attr("selected", "selected");
                                                                                                                                                                                $('select[id="genders"] option[value="' + data.gender + '"]').attr("selected", "selected");
                                                                                                                                                                                $('select[id="charge_category_id"] option[value="' + data.charge_category_id + '"]').attr("selected", "selected");
                                                                                                                                                                                $(".select2").select2().select2('val', data.cons_doctor);
                                                                                                                                                                                $("#viewModal").modal('hide');

                                                                                                                                                                                holdModal('myModaledit');
                                                                                                                                                                            },
                                                                                                                                                                        })
                                                                                                                                                                    }
                                                                                                                                                                    $(function () {
                                                                                                                                                                        //Initialize Select2 Elements
                                                                                                                                                                        $('.select2').select2()
                                                                                                                                                                    });
                                                                                                                                                                    function viewDetail(id) {
                                                                                                                                                                        $.ajax({
                                                                                                                                                                            url: '<?php echo base_url(); ?>admin/operationtheatre/getDetails',
                                                                                                                                                                            type: "POST",
                                                                                                                                                                            data: {patient_id: id},
                                                                                                                                                                            dataType: 'json',
                                                                                                                                                                            success: function (data) {
                                                                                                                                                                                $.ajax({
                                                                                                                                                                                    url: '<?php echo base_url(); ?>admin/operationtheatre/getConsultantBatch',
                                                                                                                                                                                    type: "POST",
                                                                                                                                                                                    data: {patient_id: id},
                                                                                                                                                                                    success: function (data) {
                                                                                                                                                                                        $('#reportdata').html(data);
                                                                                                                                                                                    },
                                                                                                                                                                                });
                                                                                                                                                                                console.log(data.opd_ipd_no);
                                                                                                                                                                                $("#patientsids").html(data.patient_unique_id);
                                                                                                                                                                                $("#admit_date").html(data.admission_date);
                                                                                                                                                                                $("#patients_name").html(data.patient_name);
                                                                                                                                                                                $("#genderes").html(data.gender);
                                                                                                                                                                                $("#age_age").html(data.age + " Year " + data.month + " Month");
                                                                                                                                                                                $("#guardians_name").html(data.guardian_name);
                                                                                                                                                                                $("#guardians_address").html(data.guardian_address);
                                                                                                                                                                                $("#date_s").html(data.date);
                                                                                                                                                                                $("#operations_name").html(data.operation_name);
                                                                                                                                                                                $("#operations_type").html(data.operation_type);
                                                                                                                                                                                $("#organisation_name").html(data.organisation_name);
                                                                                                                                                                                $("#cons_doctors").html(data.name + "\n" + data.surname);
                                                                                                                                                                                $("#ass_consultants_1").html(data.ass_consultant_1);
                                                                                                                                                                                $("#ass_consultants_2").html(data.ass_consultant_2);
                                                                                                                                                                                $("#anesthetists").html(data.anesthetist);
                                                                                                                                                                                $("#anaethesia_types").html(data.anaethesia_type);
                                                                                                                                                                                $("#ot_technicians").html(data.ot_technician);
                                                                                                                                                                                $("#ot_assistants").html(data.ot_assistant);
                                                                                                                                                                                $("#charge_categorys").html(data.charge_category);
                                                                                                                                                                                $("#codes").html(data.code);
                                                                                                                                                                                $("#opd_ipd_no").html(data.opd_ipd_no);
                                                                                                                                                                                $("#description").html("(" + data.description + ")");
                                                                                                                                                                                $("#stdcharge").html(data.standard_charge);
                                                                                                                                                                                $("#results").html(data.result);
                                                                                                                                                                                $("#remarks").html(data.remark);
                                                                                                                                                                                $("#patient_type").html(data.customer_type);
                                                                                                                                                                                $("#ot_assistent").html(data.ot_assistant);
                                                                                                                                                                                $("#ot_techniciandata").html(data.ot_technician);
                                                                                                                                                                                $("#apply_chargeview").html(data.apply_charge);
                                                                                                                                                                                $("#mobileno").html(data.mobileno);
                                                                                                                                                                                $('#edit_delete').html("<?php if ($this->rbac->hasPrivilege('ot_patient', 'can_edit')) { ?><a href='#'' onclick='getRecord(" + data.id + ")' data-target='#editModal' data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php } if ($this->rbac->hasPrivilege('ot_patient', 'can_delete')) { ?><a href='#' data-toggle='tooltip'  onclick='delete_record(" + id + ")' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>");
                                                                                                                                                                                holdModal('viewModal');
                                                                                                                                                                            },
                                                                                                                                                                        })
                                                                                                                                                                    }
                                                                                                                                                                    $(document).ready(function (e) {
                                                                                                                                                                        $("#consultant_register").on('submit', (function (e) {
                                                                                                                                                                            e.preventDefault();
                                                                                                                                                                            $.ajax({
                                                                                                                                                                                url: '<?php echo base_url(); ?>admin/operationtheatre/add_ot_consultant_instruction',
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
                                                                                                                                                                                error: function () {}
                                                                                                                                                                            });
                                                                                                                                                                        }));
                                                                                                                                                                    });

                                                                                                                                                                    function add_instruction(id) {
                                                                                                                                                                        $('#ins_patient_id').val(id);
                                                                                                                                                                        holdModal('add_instruction');
                                                                                                                                                                    }

                                                                                                                                                                    function delete_record(id) {
                                                                                                                                                                        if (confirm('<?php echo $this->lang->line('delete_conform') ?>')) {
                                                                                                                                                                            $.ajax({
                                                                                                                                                                                url: '<?php echo base_url(); ?>admin/operationtheatre/delete/' + id,
                                                                                                                                                                                type: "POST",
                                                                                                                                                                                data: {id: id},
                                                                                                                                                                                dataType: 'json',
                                                                                                                                                                                success: function (data) {
                                                                                                                                                                                    successMsg('<?php echo $this->lang->line('delete_message') ?>');
                                                                                                                                                                                    window.location.reload(true);
                                                                                                                                                                                }
                                                                                                                                                                            })
                                                                                                                                                                        }
                                                                                                                                                                    }

                                                                                                                                                                    function holdModal(modalId) {
                                                                                                                                                                        $('#' + modalId).modal({
                                                                                                                                                                            backdrop: 'static',
                                                                                                                                                                            keyboard: false,
                                                                                                                                                                            show: true
                                                                                                                                                                        });
                                                                                                                                                                    }
<?php
if (isset($id)) {
    ?>
                                                                                                                                                                        viewDetail(<?php echo $id ?>);
    <?php
}
?>

</script>

