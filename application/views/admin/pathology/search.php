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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('pathology') . " " . $this->lang->line('test'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('pathology test', 'can_add')) { ?>   
                                <a data-toggle="modal" onclick="holdModal('myModal')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('pathology') . " " . $this->lang->line('test'); ?></a> 
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('pathology test report', 'can_view')) { ?>   
                                <a href="<?php echo base_url(); ?>admin/pathology/getTestReportBatch" class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('test') . " " . $this->lang->line('report'); ?></a> 
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('pathology') . " " . $this->lang->line('test'); ?></div>
                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('test') . " " . $this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('category'); ?></th>
                                    <th><?php echo $this->lang->line('sub') . " " . $this->lang->line('category'); ?></th>
                                    <th><?php echo $this->lang->line('method'); ?></th>
                                    <th><?php echo $this->lang->line('report') . " " . $this->lang->line('days'); ?></th>
                                    <th class="text-right" ><?php echo $this->lang->line('charge') . " (" . $currency_symbol . ")"; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($resultlist)) {
                                    ?>
                                      <!-- <tr>
                                        <td colspan="12" class="text-danger text-center"><?php //echo $this->lang->line('no_record_found');   ?></td>
                                      </tr> -->
                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($resultlist as $student) {
                                        ?>
                                        <tr class="">
                                            <td>
                                                <a href="#" data-toggle="tooltip" title="<?php echo $this->lang->line('show'); ?>"
                                                   onclick="viewDetail('<?php echo $student['id'] ?>')"><?php echo $student['test_name']; ?></a> 
                                                <div class="rowoptionview">
                                                    <?php if ($this->rbac->hasPrivilege('pathology test report', 'can_add')) { ?>
                                                        <a href="#" onclick="addTestReport('<?php echo $student['id'] ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('add_patient_report'); ?>">
                                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                        </a> 
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('pathology test', 'can_view')) { ?>  
                                                        <a href="#" data-toggle="tooltip" 
                                                           onclick="viewDetail('<?php echo $student['id'] ?>')"
                                                           class="btn btn-default btn-xs" 
                                                           title="<?php echo $this->lang->line('show'); ?>" >
                                                            <i class="fa fa-reorder"></i>
                                                        </a> 
                                                    <?php } ?>
                                                </div>  
                                            </td>
                                            <td><?php echo $student['short_name']; ?></td>
                                            <td><?php echo $student['test_type']; ?></td>
                                            <td><?php echo $student['category_name']; ?></td>
                                            <td><?php echo $student['sub_category']; ?></td>
                                            <td><?php echo $student['method']; ?></td>
                                            <td><?php echo $student['report_days']; ?></td>
                                            <td class="text-right" ><?php echo $student['standard_charge']; ?></td>
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
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('test') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formadd" accept-charset="utf-8"  method="post" class="ptt10" >
                            <div class="row">
                                <!--div class="col-sm-4">
                                  <div class="form-group">
                                    <label>
                                    Patient ID</label> 
                                    <input id="patient_id" name="patient_id" placeholder="" type="text" class="form-control"  value="<?php echo set_value('roll_no'); ?>" />
                                    <span class="text-danger"><?php echo form_error('patient_id'); ?></span>
                                  </div>
                                </div-->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="test_name" class="form-control">
                                        <span class="text-danger"><?php echo form_error('test_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="short_name" class="form-control">
                                        <span class="text-danger"><?php echo form_error('short_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('type'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="test_type" class="form-control">
                                        <span class="text-danger"><?php echo form_error('test_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('category') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <div>
                                            <select class="form-control select2" style="width: 100%" name='pathology_category_id' >
                                                <option value="<?php echo set_value('pathology_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($categoryName as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["category_name"] ?></option>   
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('pathology_category_id'); ?></span>
                                    </div>
                                </div>      
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('unit'); ?></label>

                                        <input type="text" name="unit" class="form-control">
                                        <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('sub') . " " . $this->lang->line('category'); ?></label>
                                        <input type="text" name="sub_category" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="Method"><?php echo $this->lang->line('method'); ?></label>

                                        <input name="method" type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('report') . " " . $this->lang->line('days'); ?></label>
                                        <input type="text" name="report_days" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>
                                        <small class="req">*</small> 
                                        <div>
                                            <select class="form-control" onchange="getchargecode(this.value, 'standard_charge')" name='charge_category_id' >
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

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('code'); ?></label>
                                        <small class="req">*</small> 
                                        <div>
                                            <select class="form-control select2" style="width: 100%" name='code' onchange="getchargeDetails(this.value, 'standard_charge')" id="code" >
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('code'); ?></span>
                                    </div>
                                </div> 

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge'); ?></label><?php echo ' (' . $currency_symbol . ')'; ?>
                                        <small class="req">*</small> 
                                        <div>
                                            <input readonly class="form-control" name='standard_charge' id="standard_charge" >

                                        </div>
                                        <span class="text-danger"><?php echo form_error('code'); ?></span>
                                    </div>
                                </div> 

                            </div><!--./row-->   

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?> </button>
                    </form> 
                </div>
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
                <h4 class="box-title"><?php echo $this->lang->line('edit') . " " . $this->lang->line('test') . " " . $this->lang->line('information'); ?></h4> 
            </div>

            <div 
                class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form  id="formedit" accept-charset="utf-8"  method="post" class="ptt10">
                            <div class="row">
                                <input type="hidden" name="id" id="id" value="<?php echo set_value('id'); ?>">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="test_name" id="test_name" class="form-control" value="<?php echo set_value('test_name'); ?>">
                                        <span class="text-danger"><?php echo form_error('test_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?>
                                        </label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="short_name" id="short_name" class="form-control" value="<?php echo set_value('short_name'); ?>">
                                        <span class="text-danger"><?php echo form_error('short_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('type'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="test_type" id="test_type" class="form-control" value="<?php echo set_value('test_type'); ?>">
                                        <span class="text-danger"><?php echo form_error('test_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('category') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <div>
                                            <select class="form-control select2" name='pathology_category_id' id="pathology_category_id" style="width: 100%">
                                                <option value="<?php echo set_value('pathology_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($categoryName as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["category_name"] ?></option>   
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>      
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('unit'); ?></label>

                                        <input type="text" name="unit" id="unit" class="form-control" value="<?php echo set_value('unit'); ?>">
                                        <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('sub') . " " . $this->lang->line('category'); ?></label>
                                        <input type="text" name="sub_category" id="sub_category" class="form-control" value="<?php echo set_value('sub_category'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="Method"><?php echo $this->lang->line('method'); ?></label>

                                        <input name="method" type="text" id="method" class="form-control" value="<?php echo set_value('sub_category'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('report') . " " . $this->lang->line('days'); ?></label>
                                        <input type="text" name="report_days" id="report_days" class="form-control" value="<?php echo set_value('report_days'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
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
                                        <span class="text-danger"><?php echo form_error('charge_category_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('code'); ?></label>
                                        <small class="req">*</small> 
                                        <div>
                                            <select class="form-control select2" style="width: 100%" onchange="getchargeDetails(this.value, 'edit_standard_charge')" name='code' id="edit_code" >
                                                <option value="<?php echo set_value('charge_category_id'); ?>"><?php echo $this->lang->line('select') ?></option>

                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('charge_category_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('charge'); ?></label><?php echo ' (' . $currency_symbol . ')'; ?>
                                        <small class="req">*</small> 
                                        <div>
                                            <input readonly class="form-control" name='standard_charge' id="edit_standard_charge" >

                                        </div>
                                        <span class="text-danger"><?php echo form_error('code'); ?></span>
                                    </div>
                                </div> 

                            </div><!--./row-->   

                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="submit" class="btn btn-info pull-right" >Save</button>
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
                        <a href="#"  data-target="#editModal" data-toggle="modal" title="" data-original-title="Edit"><i class="fa fa-pencil"></i></a>

                        <a href="#" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('test') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="view" accept-charset="utf-8" method="get" class="ptt10">
                            <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id='test_names'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id="short_names"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('test') . " " . $this->lang->line('type'); ?></th>
                                        <td width="35%"><span id='test_types'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('category') . " " . $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id="pathology_category_ids"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('unit') ?></th>
                                        <td width="35%"><span id='units'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('sub') . " " . $this->lang->line('category'); ?></th>
                                        <td width="35%"><span id="sub_categorys"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('report') . " " . $this->lang->line('days'); ?></th>
                                        <td width="35%"><span id='report_dayss'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('method'); ?></th>
                                        <td width="35%"><span id="methods"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></th>
                                        <td width="35%"><span id='charge_categorys'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('code') . " (" . $this->lang->line('description') . ")"; ?></th>
                                        <td width="35%"><span id="codes"></span><span id="description"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge'); ?></th>
                                        <td width="35%"><span id='stdcharge'></span></td>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>           
                    </div><!--./col-md-12-->       
                </div><!--./row--> 
            </div>
            <div class="box-footer">
                <div class="pull-right paddA10">
                <!--  <a  onclick="saveEnquiry()" class="btn btn-info pull-right"><?php //echo $this->lang->line('save');   ?></a> -->
                </div>
            </div>
        </div>
    </div>    
</div>
<div class="modal fade" id="addTestReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('patient') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formbatch" enctype="multipart/form-data" accept-charset="utf-8"  method="post" class="ptt10" >
                            <input type="hidden" name="pathology_id" id="patho_id" >
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('customer') . " " . $this->lang->line('type'); ?></label>
                                        <div>
                                            <select class="form-control" name='customer_type' id='customer_type' >
                                                <<option><?php echo $this->lang->line('select') ?></option>
                                                <option value="<?php echo "direct"; ?>" selected><?php echo $this->lang->line('direct'); ?></option>
                                                <option value="<?php echo "opd"; ?>"><?php echo $this->lang->line('opd'); ?></option>   
                                                <option value="<?php echo "ipd"; ?>"><?php echo $this->lang->line('ipd'); ?></option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('opd_ipd_no'); ?></label>
                                        <input type="text" name="opd_ipd_no" class="form-control" onchange="getPatientIdName(this.value)">
                                        <input type="hidden" name="patient_id" id="patient_id">
                                    </div>
                                </div>  


                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></label>
                                        <small class="req"> *</small> 
                                        <input type="text" name="patient_name" class="form-control" id="patient_name">
                                        <span class="text-danger"><?php echo form_error('patient_name'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('reporting') . " " . $this->lang->line('date'); ?></label>
                                        <input type="text" id="report_date" name="reporting_date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('reporting_date'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('refferal') . " " . $this->lang->line('doctor'); ?></label>
                                        <div>
                                            <select class="form-control select2" style="width:100%" name='consultant_doctor' id="consultant_doctor">
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
                                        <textarea name="description" class="form-control" ></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?>
                                        </span>
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('test') . " " . $this->lang->line('report'); ?></label>
                                        <input type="file" class="filestyle form-control" data-height="40"  name="pathology_report">
                                        <span class="text-danger"><?php echo form_error('pathology_report'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>

                                        <input type="text" id="charge_category_html" class="form-control" readonly>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code'); ?></label>

                                        <input type="text" id="code_html" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></label>

                                        <input type="text" id="charge_html" class="form-control" readonly>
                                    </div>
                                </div> 
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?>
                                            <small class="req"> *</small>
                                        </label>
                                        <input type="text" name="apply_charge" id="apply_charge" class="form-control" >
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
    $(function () {
        $('#easySelectable').easySelectable();
//stopPropagation();
    })
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
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
    $(document).ready(function (e) {
        $("#formadd").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/pathology/add',
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
                url: '<?php echo base_url(); ?>admin/pathology/update',
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
        $('#myModaledit').modal();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pathology/getDetails',
            type: "POST",
            data: {pathology_id: id},
            dataType: 'json',
            success: function (data) {
                $("#id").val(data.id);
                $("#test_name").val(data.test_name);
                $("#short_name").val(data.short_name);
                $("#test_type").val(data.test_type);
                $("#unit").val(data.unit);
                $("#sub_category").val(data.sub_category);
                $("#report_days").val(data.report_days);
                $("#edit_charge_category").val(data.charge_category);
                editchargecode(data.charge_category, data.charge_id);
                $("#method").val(data.method);
                $("#edit_standard_charge").val(data.standard_charge);
                $("#updateid").val(id);
                $('select[id="pathology_category_id"] option[value="' + data.pathology_category_id + '"]').attr("selected", "selected");
                $('select[id="charge_category_id"] option[value="' + data.charge_category_id + '"]').attr("selected", "selected");
                $("#pathology_category_id").select2().select2('val', data.pathology_category_id);
                $("#viewModal").modal('hide');
            },
        })
    }

    function getchargecode(charge_category) {
        var div_data = "";

        $("#code").select2("val", '');

        $('#code').html("<option value=''>Select</option>");

        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/getchargeDetails',
            type: "POST",
            data: {charge_category: charge_category},
            dataType: 'json',
            success: function (res) {
                //alert(res)
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.code + " - " + obj.description + "</option>";

                });

                $('#code').append(div_data);
            }
        });
    }

    function editchargecode(charge_category, charge_id) {
        var div_data = "";
        $("#edit_code").select2("val", " ");
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
                        // sel = "selected";
                    }
                    div_data += "<option value='" + obj.id + "' " + sel + ">" + obj.code + " - " + obj.description + "</option>";
                });
                $('#edit_code').append(div_data);
                $("#edit_code").select2().select2('val', charge_id);
            }
        });
    }

    function getPatientIdName(opd_ipd_no) {
        $('#patient_id').val("");
        $('#patient_name').val("");
        var opd_ipd_patient_type = $("#customer_type").val();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getPatientType',
            type: "POST",
            data: {opd_ipd_patient_type: opd_ipd_patient_type, opd_ipd_no: opd_ipd_no},
            dataType: 'json',
            success: function (data) {
                $('#patient_id').val(data.patient_id);
                $('#patient_name').val(data.patient_name);
                //$('#consultant_doctor').val(data.doctorname + ' ' +data.surname);
            }
        });
    }
    // function getPatientIdName(opd_ipd_no) {
    //   var opd_ipd_patient_type = $('input[name=opd_ipd_patient_type]:checked').val();
    //     $.ajax({
    //         url: '<?php echo base_url(); ?>admin/patient/getPatientType',
    //         type: "POST",     
    //         data: {opd_ipd_patient_type:opd_ipd_patient_type, opd_ipd_no:opd_ipd_no}, 
    //         dataType: 'json',
    //         success: function(data) {
    //           $('#patient_id').val(data.patient_id);
    //           $('#patient_name').val(data.patient_name);
    //         }
    //     }); 
    // }

    function viewDetail(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pathology/getDetails',
            type: "POST",
            data: {pathology_id: id},
            dataType: 'json',
            success: function (data) {
                $("#test_names").html(data.test_name);
                $("#short_names").html(data.short_name);
                $("#test_types").html(data.test_type);
                $("#pathology_category_ids").html(data.category_name);
                $("#units").html(data.unit);
                $("#sub_categorys").html(data.sub_category);
                $("#report_dayss").html(data.report_days);
                $("#methods").html(data.method);
                $("#charge_categorys").html(data.charge_category);
                $("#codes").html(data.code);
                $("#description").html(" (" + data.description + ")");
                $("#stdcharge").html('<?php echo $currency_symbol; ?>' + data.standard_charge);
                $('#edit_delete').html("<a href='#'' onclick='getRecord(" + id + ")' data-target='#myModaledit' data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><a onclick='delete_record(" + id + ")'  href='#'  data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a>");
                holdModal('viewModal');
            },
        });
    }

    function delete_record(id) {
        if (confirm('<?php echo $this->lang->line('delete_conform') ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/pathology/delete/' + id,
                type: "POST",
                data: {opdid: ''},
                dataType: 'json',
                success: function (data) {
                    successMsg('<?php echo $this->lang->line('success_message'); ?>');
                    window.location.reload(true);
                }
            })
        }
    }

    function addTestReport(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pathology/getPathology',
            type: "POST",
            data: {pathology_id: id},
            dataType: 'json',
            success: function (data) {
                $("#patho_id").val(id);
                $("#charge_category_html").val(data.charge_category);
                $("#code_html").val(data.code);
                $("#charge_html").val(data.standard_charge);
                $("#apply_charge").val(data.standard_charge);
                holdModal('addTestReportModal');
            },
        })
    }

    $(document).ready(function (e) {
        $("#formbatch").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/pathology/testReportBatch',
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
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
    function getchargeDetails(id, htmlid) {

        $('#' + htmlid).val("");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/getDetails',
            type: "POST",
            data: {charges_id: id, organisation: ''},
            dataType: 'json',
            success: function (res)
            {

                $('#' + htmlid).val(res.standard_charge);
            }
        })
    }
</script>

