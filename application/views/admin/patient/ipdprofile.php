<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$genderList = $this->customlib->getGender();
//echo $this->customlib->getSchoolDateFormat(true,true);
//exit();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="row">
                <div class="box-body pb0">             
                    <div class="col-lg-2 col-md-2 col-sm-3 text-center">
                        <?php
                        //echo "<pre>";
                        //print_r($result);
                        $image = $result['image'];
                        if (!empty($image)) {
                            $file = $result['image'];
                        } else {
                            $file = "uploads/patient_images/no_image.png";
                        }
                        ?>

                        <img width="115" height="115" class="" src="<?php echo base_url() . $file ?>" alt="No Image">


                        <div class="editviewdelete-icon pt8">
                            <a class="" href="#" onclick="getRecord('<?php echo $result['id'] ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('profile') ?>"><i class="fa fa-reorder"></i>
                            </a>
                            <?php
                            if ($this->rbac->hasPrivilege('ipd_patient', 'can_edit')) {
                                if ($result['is_active'] != 'no') {
                                    ?>
                                    <a class="" href="#" onclick="getEditRecord('<?php echo $result['id'] ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('edit') . " " . $this->lang->line('profile') ?>"><i class="fa fa-pencil"></i>
                                    </a> 
                                    <a class="" href="#" onclick="deletePatient('<?php echo $result['id'] ?>')"   data-toggle="tooltip" title="<?php echo $this->lang->line('delete') . " " . $this->lang->line('patient') ?>"><i class="fa fa-trash"></i>
                                    </a> 
                                    <?php
                                }
                            }
                            // print_r($result);die;
                            ?>
                        </div>  

                    </div>

                    <div class="col-md-10 col-lg-10 col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-striped mb0 font13">
                                <tbody>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('name'); ?></th>
                                        <td class="bozerotop"><?php echo $result['patient_name'] ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('guardian_name'); ?></th>
                                        <td class="bozerotop"><?php echo $result['guardian_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('gender'); ?></th>
                                        <td class="bozerotop"><?php echo $result['gender']; ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('age'); ?></th>
                                        <td class="bozerotop">
                                            <?php
                                            if (!empty($result['age'])) {
                                                echo $result['age'] . " " . $this->lang->line('year') . " ";
                                            } if (!empty($result['month'])) {
                                                echo $result['month'] . " " . $this->lang->line('month');
                                            }
                                            ?>   
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('phone'); ?></th>
                                        <td class="bozerotop"><?php echo $result['mobileno']; ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")";
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo $result['credit_limit']; ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <td class="bozerotop"><?php echo $result['patient_unique_id']; ?></td>
                                        <th class="bozerotop"><?php echo $this->lang->line('ipd_no'); ?></th>
                                        <td class="bozerotop"><?php
                                            echo $result['ipd_no'];
                                            if ($result['is_active'] != 'yes') {
                                                echo " <span class='label label-warning'>" . $this->lang->line("discharged") . "</span>";
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bozerotop"><?php echo $this->lang->line('admission_date');
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo date($this->customlib->getSchoolDateFormat($result['admission_date'])) ?>
                                        </td>
                                        <th class="bozerotop"><?php echo $this->lang->line('bed');
                                            ;
                                            ?></th>
                                        <td class="bozerotop"><?php echo $result['bed_name']."-".$result['bedgroup_name'] . "-" . $result['floor_name']?>
                                        </td>

                                    </tr>    
<?php if ($result['is_active'] != 'yes') { ?>
                                        <tr>
                                            <th class="bozerotop"><?php echo $this->lang->line('discharged') . " " . $this->lang->line('date');
    ;
    ?></th>
                                            <td class="bozerotop"><?php echo date($this->customlib->getSchoolDateFormat($bill_info['discharge_date'])) ?>
                                            </td>     
                                        </tr>      
<?php } ?>           
                                </tbody>
                            </table>
                        </div>
                    </div>           
                </div>
            </div>
            <div>
                <div class="box border0">
                    <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-top:5px;"></div>
                    <div class="nav-tabs-custom border0" id="tabs">

                        <ul class="nav nav-tabs">
                            <?php if ($this->rbac->hasPrivilege('consultant register', 'can_view')) { ?>

                                <li class="active">
                                    <a href="#consultant_register"  data-toggle="tab" aria-expanded="true"><i class="fas fa-file-prescription"></i> <?php echo $this->lang->line('consultant') . " " . $this->lang->line('register'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_view')) { ?>

                                <li>
                                    <a href="#diagnosis" data-toggle="tab" aria-expanded="true"><i class="fas fa-diagnoses"></i> <?php echo $this->lang->line('diagnosis'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('ipd timeline', 'can_view')) { ?>

                                <li>
                                    <a href="#timeline" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('timeline'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('charges', 'can_view')) { ?>

                                <li>
                                    <a href="#charges" data-toggle="tab" aria-expanded="true"><i class="fas fa-donate"></i> <?php echo $this->lang->line('charges'); ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('payment', 'can_view')) { ?>

                                <li>
                                    <a href="#payment" data-toggle="tab" aria-expanded="true"><i class="fas fa-hand-holding-usd"></i> <?php echo $this->lang->line('payment'); ?></a>
                                </li>
                            <?php } ?>
<?php if ($this->rbac->hasPrivilege('bill', 'can_view')) { ?>

                                <li>
                                    <a href="#bill" class="bill" data-toggle="tab" aria-expanded="true"><i class="fas fa-file-invoice-dollar"></i> <?php echo $this->lang->line('bill'); ?></a>
                                </li>
                            <?php } ?>
                        </ul>   

                        <div class="tab-content">
                            <?php
                            $charge_total = 0;
                            $bill_amount = 0;
                            foreach ($charges as $charge) {
                                $charge_total += $charge["apply_charge"];
                                $bill_amount = $charge_total - $paid_amount;
                            }
                            ?>   <?php if (($bill_amount != 0) && ($bill_amount >= $result["credit_limit"])) { ?>            
                                <div class="alert alert-info">Patient bill amount is crossed to credit limit, please make payment immediately</div>
                                <?php } ?>


                            <!-- Consultant Register -->
                            <div class="tab-pane active" id="consultant_register">
<?php
if ($this->rbac->hasPrivilege('consultant register', 'can_add')) {

    if ($result['status'] != 'paid') {
        ?>
                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="holdModal('add_instruction')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('consultant') . " " . $this->lang->line('instruction'); ?></a>
                                        </div><!--./impbtnview-->
    <?php }
}
?>    
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('doctor'); ?></th>
                                        <th><?php echo $this->lang->line('instruction'); ?></th>
                                        <th><?php echo $this->lang->line('instruction') . " " . $this->lang->line('date'); ?></th>

                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>
<?php
if (!empty($consultant_register)) {
    foreach ($consultant_register as $consultant_key => $consultant_value) {
        ?>  
                                                    <tr>
                                                        <td><?php echo $appointment_date = date($this->customlib->getSchoolDateFormat(true, true), strtotime($consultant_value['date'])); ?></td>
                                                        <td><?php echo $consultant_value["name"] . " " . $consultant_value["surname"] ?></td>
                                                        <td><?php echo $consultant_value["instruction"] ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($consultant_value['ins_date'])); ?></td>

                                                        <td class="text-right">
                                                    <?php if ($this->rbac->hasPrivilege('consultant register', 'can_delete')) { ?><a 
                                                                    class="btn btn-default btn-xs"  data-toggle="tooltip" title="" onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientConsultant/<?php echo $consultant_value['patient_id']; ?>/<?php echo $consultant_value['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
        <?php } ?>
                                                        </td>
                                                    </tr>
                                    <?php }
                                }
                                ?> 
                                        </tbody>
                                    </table>
                                </div> 
                            </div>  
                            <!-- diagnosis -->
                            <div class="tab-pane" id="diagnosis">
                                <?php
                                if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_add')) {
                                    if ($result['status'] != 'paid') {
                                        ?>

                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="holdModal('add_diagnosis')" data-toggle="modal"><i class="fas fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('diagnosis'); ?></a>
                                        </div><!--./impbtnview-->
    <?php }
}
?>
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('report') . " " . $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>
                                                    <?php
                                                    if (!empty($diagnosis_detail)) {
                                                        foreach ($diagnosis_detail as $diagnosis_key => $diagnosis_value) {
                                                            ?>  
                                                    <tr>
                                                        <td><?php echo $diagnosis_value["report_type"] ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($diagnosis_value['report_date'])) ?></td>
                                                        <td><?php echo $diagnosis_value["description"] ?></td>
                                                        <td class="text-right">
                                                    <?php if (!empty($diagnosis_value["document"])) { ?>
                                                                <a class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download') ?>" href="<?php echo base_url() . "admin/patient/report_download/" . $diagnosis_value["document"] ?>" ><i class="fa fa-download"></i></a>
                                                    <?php } ?>
        <?php if ($this->rbac->hasPrivilege('ipd diagnosis', 'can_delete')) { ?>
                                                                <a  onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientDiagnosis/<?php echo $diagnosis_value['patient_id']; ?>/<?php echo $diagnosis_value['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
        <?php } ?>
                                                        </td>
                                                    </tr>
    <?php }
}
?> 
                                        </tbody>
                                    </table>
                                </div> 
                            </div>        
                            <!-- Timeline -->
                            <div class="tab-pane" id="timeline">
<?php if ($this->rbac->hasPrivilege('ipd timeline', 'can_add')) { ?>
                                    <div class="impbtnview">

                                        <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="holdModal('myTimelineModal')" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('timeline'); ?></a>
                                    </div><!--./impbtnview-->
                                            <?php } ?>
                                <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('ipd') . " " . $this->lang->line('details'); ?></div>
                                <div class="timeline-header no-border">
                                    <div id="timeline_list">
                                                    <?php if (empty($timeline_list)) { ?>
                                            <br/>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php } else { ?>
                                            <ul class="timeline timeline-inverse">
                                                        <?php
                                                        foreach ($timeline_list as $key => $value) {
                                                            ?>      
                                                    <li class="time-label">
                                                        <span class="bg-blue">    
                                                            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date'])); ?></span>
                                                    </li> 
                                                    <li>
                                                        <i class="fa fa-list-alt bg-blue"></i>
                                                        <div class="timeline-item">
        <?php if ($this->rbac->hasPrivilege('ipd timeline', 'can_delete')) { ?>
                                                                <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a></span>
                                                    <?php } ?>
                                                    <?php if (!empty($value["document"])) { ?>
                                                                <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "admin/timeline/download_patient_timeline/" . $value["id"] . "/" . $value["document"] ?>" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a></span>
        <?php } ?>
                                                            <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                                                            <div class="timeline-body">
        <?php echo $value['description']; ?> 
                                                            </div>
                                                        </div>
                                                    </li>
                                    <?php } ?> 
                                                <li><i class="fa fa-clock-o bg-gray"></i></li> 
                                <?php } ?>  
                                        </ul>
                                    </div>
                                </div>
                            </div>  


                            <!--Charges-->
                            <div class="tab-pane" id="charges">
<?php
if ($this->rbac->hasPrivilege('charges', 'can_add')) {
    if ($result['status'] != 'paid') {
        ?>

                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="holdModal('myChargesModal')" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('charges'); ?></a>
                                        </div>
                                                <?php }
                                            }
                                            ?>                        
                                <div class="download_label"><?php echo $this->lang->line('charges'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?> </th>
                                        <th class="text-right"><?php echo $this->lang->line('organisation') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')';
                                            ;
                                            ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>
<?php
$total = 0;
if (!empty($charges)) {

    foreach ($charges as $charge) {

        $total += $charge["apply_charge"];
        ?>
                                                    <tr>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($charge['date'])); ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charge["charge_type"] ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $charge["charge_category"] ?></td>
                                                        <td class="text-right"><?php echo $charge["standard_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charge["org_charge"] ?></td>
                                                        <td class="text-right"><?php echo $charge["apply_charge"] ?></td>
                                                        <td class="text-right"> 
        <?php if ($this->rbac->hasPrivilege('charges', 'can_delete')) { ?>
                                                                <a onclick="delete_record('<?php echo base_url(); ?>admin/patient/deleteIpdPatientCharge/<?php echo $charge['patient_id']; ?>/<?php echo $charge['id']; ?>', '<?php echo $this->lang->line('delete_message') ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a> 
        <?php } ?>   
                                                        </td>
                                                    </tr>
    <?php } ?>  

<?php } ?>
                                        </tbody>


                                        <tr class="box box-solid total-bg">
                                            <td colspan='6' class="text-right"><?php echo $this->lang->line('total') . " : " . $currency_symbol . "" . $total ?> <input type="hidden" id="charge_total" name="charge_total" value="<?php echo $total ?>">
                                            </td><td></td>
                                        </tr>
                                    </table>
                                </div> 
                            </div>

                            <div class="tab-pane" id="bill">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="box-title mt0"><?php echo $this->lang->line('charges'); ?></h4>
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">

                                            <table class="nobordertable table table-striped">
                                                <tr>
                                                    <th width="16%" ><?php echo $this->lang->line('charges'); ?> </th>
                                                    <th width="16%" ><?php echo $this->lang->line('category') ?></th>
                                                    <th width="19%"><?php echo $this->lang->line('date') ?></th> 
                                                    <th width="16%" class="pttright reborder"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?> </th>
                                                </tr>
                                                <?php
                                                $j = 0;
                                                $total = 0;
                                                foreach ($charges as $key => $charge) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $charge["charge_type"]; ?></td> 
                                                        <td><?php echo $charge["charge_category"]; ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($charge['created_at'])) ?></td>
                                                        <td class="pttright reborder"><?php echo $charge["apply_charge"]; ?></td>
                                                    </tr>


    <?php
    $total += $charge["apply_charge"];
    ?>

    <?php
    $j++;
}
?>
                                                <tr class="box box-solid total-bg">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-right"><?php echo $this->lang->line('total') . " : "; ?>  <?php echo $currency_symbol . $total ?></td>

                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-1"></div> -->
                                    <div class="col-md-6">
                                        <h4 class="box-title mt0"><?php echo $this->lang->line('payment'); ?></h4>
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">

                                            <table class="nobordertable table table-striped">
                                                <tr>
                                                    <th width="20%" class=""><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                                    <th width="16%" class=""><?php echo $this->lang->line('payment') . " " . $this->lang->line('date'); ?></th>
                                                    <th width="16%" class="text-right"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?> </th>
                                                </tr>

                                                <?php
                                                $k = 0;
                                                $total_paid = 0;
                                                foreach ($payment_details as $key => $payment) {
                                                    ?>
                                                    <tr>
                                                        <td class="pttleft" style="text-transform: capitalize;"><?php echo $payment["payment_mode"]; ?></td>
                                                        <td class=""><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payment['date'])); ?></td>
                                                        <td class="text-right"><?php echo $payment["paid_amount"]; ?></td>

                                                    </tr>
    <?php
    $total_paid += $payment["paid_amount"];
}
?>
                                                <tr class="box box-solid total-bg">
                                                    <td></td>
                                                    <td></td>

                                                    <td class="text-right"><?php echo $this->lang->line('total') . "  : " ?>  <?php echo $currency_symbol . $total_paid ?></td>

                                                </tr>
                                            </table>

                                        </div><!--./table-responsive-->
                                        <h4 class="box-title ptt10"><?php echo $this->lang->line('bill') . " " . $this->lang->line('summary'); ?></h4>                    
                                        <div class="table-responsive" style="border: 1px solid #dadada;border-radius: 2px; padding: 10px;">
                                            <table class="nobordertable table table-striped table-responsive">
                                                <form class="" method="post" id="add_bill" action="<?php echo base_url() ?>admin/payment/addbill"  enctype="multipart/form-data">
                                                    <input type="hidden" name="status" id="status" value="<?php echo $result["is_active"] ?>">
<?php if ($result['status'] != 'paid') { ?> 
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php
    if (!empty($paid_amount)) {
        echo $paid_amount;
    } else {
        echo "0";
    }
    ?>
                                                                <input type="hidden" value="<?php echo $total - $paid_amount ?>" id="total_amount" name="total_amount" style="width: 30%" class="form-control">
                                                                <input type="hidden" value="<?php echo $result['bed'] ?>" id="bed_no" name="bed_no" style="width: 30%; float: right" class="form-control">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') . " (" . $this->lang->line('balance') . " " . $this->lang->line('amount') . ")" . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total - $paid_amount ?></td>
                                                        </tr>
                                                        <tr>

                                                            <td colspan="2"><input type="hidden" id="gross_total" value="<?php echo $total - $paid_amount ?>" name="gross_total" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable">
                                                                <input type="hidden" name="patient_id" value="<?php echo $result["id"] ?>">
                                                                <input type="text" id="discount" value="<?php
                                                            if (!empty($result["discount"])) {
                                                                echo $result["discount"];
                                                            } else {
                                                                echo "0";
                                                            }
    ?>" name="discount" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable"><input type="text" id="other_charge" value="<?php
                                                            if (!empty($result["other_charge"])) {
                                                                echo $result["other_charge"];
                                                            } else {
                                                                echo "0";
                                                            }
    ?>" name="other_charge" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right ipdbilltable"><input type="text" name="tax" value="<?php
                                                            if (!empty($result["tax"])) {
                                                                echo $result["tax"];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?>" id="tax" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . "(" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">

                                                                <span id="net_amount_span" class="">0</span><input type="hidden" name="net_amount" value="<?php
                                                            if (!empty($result["net_amount"])) {
                                                                echo $result["net_amount"];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?>" id="net_amount" style="width: 30%; float: right" class="form-control"></td>
                                                        </tr>
<?php } else { ?> 
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('total') . " " . $this->lang->line('payment') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $paid_amount; ?>


                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('gross') . " " . $this->lang->line('total') . " (" . $this->lang->line('balance') . " " . $this->lang->line('amount') . ")" . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $total - $paid_amount ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['discount'] ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('any_other_charges') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['other_charge'] ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20"><?php echo $result['tax'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('net_payable') . " " . $this->lang->line('amount') . "(" . $this->lang->line('paid') . ") (" . $currency_symbol . ")"; ?></th> 
                                                            <td class="text-right fontbold20">
                                                <?php echo $result['net_amount'] ?>
                                                            </td>
                                                        </tr>

<?php } ?>

                                            </table>

<?php if ($result['status'] != 'paid') { ?> 
    <?php if ($this->rbac->hasPrivilege('calculate_bill', 'can_view')) { ?>
                                                    <input type="button" onclick="calculate()" id="cal_btn"  name="" value="<?php echo $this->lang->line('calculate'); ?>" class="btn btn-sm btn-info">
                                    <?php } if ($this->rbac->hasPrivilege('generate_bill_discharge_patient', 'can_view')) { ?>
                                                    <input type="submit" style="display:none" id="save_button" name="" value="<?php echo $this->lang->line('generate_bill_discharge_patient') ?>" class="btn btn-sm btn-info"/>
                                    <?php } ?>
                                                <a href="#" style="display:none" class="btn btn-sm btn-info" id="printBill" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $result["id"] ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>
<?php } else { ?>
                                                <span class="pull-right"><?php echo $this->lang->line('bill_generated_by') . " :" . $bill_info["name"] . " " . $bill_info["surname"] . " (" . $bill_info["employee_id"] . ")"; ?></span>
    <?php if ($this->rbac->hasPrivilege('revert_generated_bill', 'can_view')) { ?>
                                                    <input type="button" onclick="revert('<?php echo $result['id'] ?>', '<?php echo $result['bill_id'] ?>', '<?php echo $result['bed_id'] ?>')" id="revert_btn"  name="" value="<?php echo $this->lang->line('revert') . " " . $this->lang->line('generated') . " " . $this->lang->line('bill'); ?>" class="btn btn-sm btn-info">
                                    <?php } ?>
                                                <a href="#"  class="btn btn-sm btn-info" onclick="printBill('<?php echo $result["id"] ?>', '<?php echo $result["id"] ?>')"><?php echo $this->lang->line('print') . " " . $this->lang->line('bill') ?></a>

<?php } ?> 

                                        </div>
                                    </div>
                                    </form>

                                </div>
                            </div> 


                            <div class="tab-pane" id="payment">
                                            <?php
                                            if ($this->rbac->hasPrivilege('payment', 'can_add')) {
                                                if ($result['status'] != 'paid') {
                                                    ?>

                                        <div class="impbtnview">
                                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle" onclick="addpaymentModal()" data-toggle='modal'><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></a>
                                        </div><!--./impbtnview-->
                                                <?php }
                                            }
                                            ?>
                                <div class="download_label"><?php echo $this->lang->line('payment'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('paid') . " " . $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>

                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                        </thead>
                                        <tbody>

                                                    <?php
                                                    if (!empty($payment_details)) {
                                                        $total = 0;
                                                        foreach ($payment_details as $payment) {
                                                            if (!empty($payment['paid_amount'])) {
                                                                $total += $payment['paid_amount'];
                                                            }
                                                            ?>
                                                    <tr>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payment['date'])); ?></td>
                                                        <td><?php echo $payment["note"] ?></td>
                                                        <td style="text-transform: capitalize;"><?php echo $payment["payment_mode"] ?></td>
                                                        <td class="text-right"><?php echo $payment["paid_amount"] ?></td>
                                                       <!--  <td><?php echo $payment["balance_amount"] ?></td> -->
                                                        <td class="text-right">
                                                <?php if (!empty($payment["document"])) { ?>
                                                                <a onclick="delte_record('<?php echo base_url(); ?>admin/payment/download/<?php echo $payment["document"]; ?>', '<?php echo $this->lang->line('delete_message'); ?>')"  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
        <?php } ?>
        <?php if ($this->rbac->hasPrivilege('payment', 'can_delete')) { ?>
                                                                <a href="<?php echo base_url(); ?>admin/patient/deleteIpdPatientPayment/<?php echo $payment['patient_id']; ?>/<?php echo $payment['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>   
        <?php } ?>
                                                        </td>
                                                    </tr>

    <?php } ?> 
                                                <tr class="box box-solid total-bg">

                                                    <td></td>
                                                    <td></td>
                                                    <td></td> <td  class="text-right"><?php echo $this->lang->line('total') . " : " . $currency_symbol . "" . $total; ?>
                                                    </td><td></td> 
                                                </tr>

                                            </tbody>

<?php } ?>

                                    </table>
                                </div> 
                            </div>
                            <!-- Bill payment -->  
                        </div>
                    </div>
                </div> 
            </div> <!-- /.box-body -->
        </div><!--./box box-primary-->





    </section>
</div>


<!-- Add Diagnosis -->
<div class="modal fade" id="add_diagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('diagnosis'); ?> </h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="form_diagnosis"   accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
<?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?> 
                                            <small class="req"> *</small>
                                        </label> 
                                        <input type="text" name="report_type" class="form-control" id="report_type" />
                                        <input type="hidden" value="<?php echo $id ?>" name="patient" class="form-control" id="patient" />    
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('report') . " " . $this->lang->line('date') ?> </label><input type="text" class="form-control date" name="report_date" />
                                    </div> 
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('document') ?> </label><input type="file" class="form-control filestyle" name="report_document" id="report_document" />
                                    </div> 
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('description') ?> </label> <textarea name="description" class="form-control" id="description"></textarea>
                                    </div> 
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save') ?></button>
                        </form>
                    </div>
                </div>
            </div>    
        </div></div> </div>
<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('add') . " " . $this->lang->line('timeline'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $id ?>">
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?>
                                            <small class="req"> *</small>
                                        </label>
                                        <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date"  />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div class="" style="margin-top:-5px; border:0; outline:none;">
                                            <input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="<?php echo set_value('timeline_doc'); ?>" />
                                            <span class="text-danger">
<?php echo form_error('timeline_doc'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('visible'); ?></label>
                                        <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox"   />
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </form>
                    </div>
                </div>
            </div>    
        </div></div> </div>
<!-- Add Prescription -->
<div class="modal fade" id="add_prescription" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('prescription'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="form_prescription" accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <table id="tableID">
                                    <tr id="row0">
                                        <td>                                
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('medicine'); ?> </label> 
                                                    <input type="text" name="medicine[]" class="form-control" id="report_type" />
                                                    <input type="hidden" value="<?php echo $id ?>" name="patient" class="form-control" id="patient" />    
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('dosage'); ?> </label> 
                                                    <input type="text" class="form-control" name="dosage[]" id="report_document" />
                                                </div> 
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('instruction'); ?> </label> 
                                                    <textarea name="description" class="form-control" id="instruction[]"></textarea>
                                                </div> 
                                            </div>
                                        </td>
                                        <td><button type="button" onclick="edit_more()" style="color: #2196f3" class="closebtn"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                </table>
                                <div class="add_row">
                                </div>
                                <!-- <div class="col-sm-12">
                                    <a href="#" class="pull-right" onclick="add_more()"><?php echo $this->lang->line('add_more') ?></a>
                                </div> -->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('opd') . " " . $this->lang->line('no'); ?></label> 
                                        <select name="opd_no" class="form-control" id="opd_no">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($opd_details as $opdkey => $opdvalue) { ?>
                                                <option value="<?php echo $opdvalue["id"] ?>"><?php echo $opdvalue["id"] ?></option>
<?php } ?>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label> 
                                        <textarea name="note" class="form-control" id="note"></textarea>
                                    </div> 
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </form>
                    </div>
                </div>
            </div>    
        </div></div> </div>
<!-- -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('patient') . " " . $this->lang->line('information'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formrevisit"   accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>

                                        <th width="15%"><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id="patient_name"></span>
                                        </td>
                                        <th width="15%"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <td width="35%"><span id='patients_id'></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('guardian_name'); ?></th>
                                        <td width="35%"><span id='guardian_name'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                                        <td width="35%"><span id='gen'></span></td>


                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('marital_status'); ?></th>
                                        <td width="35%"><span id="marital_status"></span>
                                        </td>

                                        <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                        <td width="35%"><span id="contact"></span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('email'); ?></th>
                                        <td width="35%"><span id='email' style="text-transform: none"></span></td>
                                        <th width="15%"><?php echo $this->lang->line('address'); ?></th>
                                        <td width="35%"><span id='patient_address'></span></td>
                                    </tr>
                                    <tr>  
                                        <th width="15%"><?php echo $this->lang->line('age'); ?></th>
                                        <td width="35%"><span id="age"></span>
                                        </td>
                                        <th width="15%"><?php echo $this->lang->line('blood_group'); ?></th>
                                        <td width="35%"><span id="blood_group"></span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('height'); ?></th>
                                        <td width="35%"><span id='height'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('weight'); ?></th>
                                        <td width="35%"><span id="weight"></span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('bp'); ?></th>
                                        <td width="35%"><span id='patient_bp'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('symptoms'); ?></th>
                                        <td width="35%"><span id='symptoms'></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('known_allergies'); ?></th>
                                        <td width="35%"><span id="known_allergies"></span>
                                        </td>
                                        <th width="15%"><?php echo $this->lang->line('admission') . " " . $this->lang->line('date'); ?></th>
                                        <td width="35%"><span id="admission_date"></span>
                                        </td> 
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('case'); ?></th>
                                        <td width="35%"><span id='case'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('casualty'); ?></th>
                                        <td width="35%"><span id="casualty"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('old') . " " . $this->lang->line('patient'); ?></th>
                                        <td width="35%"><span id='old_patient'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('organisation'); ?></th>
                                        <td width="35%"><span id="organisation"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('refference'); ?></th>
                                        <td width="35%"><span id="refference"></span>
                                        </td>
                                        <th width="15%"><?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></th>
                                        <td width="35%"><span id='doc'></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('bed') . " " . $this->lang->line('group'); ?></th>
                                        <td width="35%"><span id="bed_group"></span>
                                        </td>
                                        <th width="15%"><?php echo $this->lang->line('bed') . " " . $this->lang->line('number'); ?></th>
                                        <td width="35%"><span id='bed_name'></span></td>
                                    </tr>

                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        </div></div> </div>
<!-- -->
<div class="modal fade" id="prescriptionview" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('prescription'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription"></div>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="add_payment" accept-charset="utf-8" method="post" class="ptt10" >
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small class="req"> *</small> 
                                        <input type="text" name="amount" id="amount" class="form-control">    
                                        <input type="hidden" name="patient_id" id="payment_patient_id" class="form-control">
                                        <input type="hidden" name="total" id="total" class="form-control">
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label> 
                                        <select class="form-control" name="payment_mode">

<?php foreach ($payment_mode as $key => $value) {
    ?>
                                                <option value="<?php echo $key ?>" <?php
    if ($key == 'cash') {
        echo "selected";
    }
    ?>><?php echo $value ?></option>
<?php } ?>
                                        </select>    
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small> 
                                        <input type="text" name="payment_date" id="date" class="form-control date">
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file" class="filestyle form-control"   name="document">
                                        <span class="text-danger"><?php echo form_error('document'); ?></span> 
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label> 
                                        <input type="text" name="note" id="note" class="form-control"/>
                                    </div>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div> 
</div>
<!-- -->
<!--Add Charges-->
<div class="modal fade" id="myChargesModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('charges') ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="add_charges" accept-charset="utf-8"  method="post" class="ptt10" >
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small> 
                                        <input id="charge_date" name="date" placeholder="" type="text" class="form-control date" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('type') ?></label><small class="req"> *</small> 

                                        <select name="charge_type" onchange="getcharge_category(this.value)" class="form-control">
                                            <option value="">Select</option>
<?php foreach ($charge_type as $key => $value) {
    ?>
                                                <option value="<?php echo $key ?>">
    <?php echo $value ?>
                                                </option>
<?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('category') ?></label><small class="req"> *</small> 

                                        <select name="charge_category" id="charge_category" style="width: 100%" class="form-control select2" onchange="getchargecode(this.value)">
                                            <option value="">Select</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code') ?></label><small class="req"> *</small> 

                                        <select name="code" id="code" style="width: 100%" class="form-control select2" onchange="get_Charges(this.value, '<?php echo $result['organisation'] ?>')">
                                            <option value="">Select</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('code'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label>
                                        <input type="text" readonly name="standard_charge" id="standard_charge" class="form-control" value="<?php echo set_value('standard_charge'); ?>"> 
                                        <input type="hidden" name="patient_id" value="<?php echo $result["id"] ?>">
                                        <input type="hidden" name="charge_id" id="charge_id">
                                        <input type="hidden" name="org_id" id="org_id">
                                        <span class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('organisation') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label>
                                        <input type="text" readonly name="schedule_charge" id="schedule_charge" placeholder="" class="form-control" value="<?php echo set_value('schedule_charge'); ?>">    
                                        <span class="text-danger"><?php echo form_error('schedule_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . " (" . $currency_symbol . ")" ?></label><small class="req"> *</small><input type="text" name="apply_charge" id="apply_charge" class="form-control">    
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save') ?></button>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div> 
</div>
<!-- -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('patient') . " " . $this->lang->line('information') ?></h4> 
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                        <form id="formeditrecord" accept-charset="utf-8"  enctype="multipart/form-data" method="post" >
                            <div class="row row-eq">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="row ptt10">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('patient') . " " . $this->lang->line('id') ?></label> 
                                                <input id="patients_ids" disabled name="patient_unique_id" placeholder="" type="text" class="form-control"  value="<?php echo set_value('patient_unique_id'); ?>" />
                                                <span class="text-danger"><?php echo form_error('patient_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small> 
                                                <input id="patient_names" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                                <input type="hidden" id="updateid" name="updateid">
                                                <input type="hidden" id="ipdid" name="ipdid">
                                                   <!-- <input type="hidden" id="opdid" name="opdid"> -->
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('guardian_name') ?></label>
                                                <input type="text" id="guardian_names" name="guardian_name" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small> 
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
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('marital_status'); ?></label>
                                                <select name="marital_status" id="marital_statuss" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($marital_status as $mkey => $mvalue) {
    ?>
                                                        <option value="<?php echo $mkey ?>"><?php echo $mvalue ?></option>
<?php } ?>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                                <input id="contacts" autocomplete="off" name="contact" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact'); ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('patient') . " " . $this->lang->line('photo') ?></label>
                                                <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' id="patient_image" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        </div> 
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('email'); ?></label>
                                                <input type="text" id="emails" value="<?php echo set_value('email'); ?>" name="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="email"><?php echo $this->lang->line('address'); ?></label> 
                                                <input type="text" id="edit_patient_address" value="<?php echo set_value('address'); ?>" name="address" class="form-control">
                                            </div> 
                                        </div> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('age') ?></label>
                                                <div style="clear: both;overflow: hidden;">
                                                    <input type="text" placeholder="<?php echo $this->lang->line('year'); ?>" name="age" id="ages" class="form-control" value="<?php echo set_value('age'); ?>" style="width: 40%; float: left;">
                                                    <input type="text" placeholder="<?php echo $this->lang->line('month'); ?>" name="month"  id="months" value="<?php echo set_value('month'); ?>" class="form-control" style="width: 56%;float: left; margin-left: 5px;">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('blood_group'); ?></label><small class="req"> *</small> 
                                                <select class="form-control" id="bloodgroups" name="blood_group">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('height'); ?></label> 
                                                <input name="height" id="patient_height" type="text" class="form-control"  value="<?php echo set_value('height'); ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('weight'); ?></label> 
                                                <input name="weight" id="patient_weight" type="text" class="form-control"  value="<?php echo set_value('weight'); ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('bp') ?></label> 
                                                <input name="bp" type="text" id="bp" class="form-control"  value="<?php echo set_value('bp'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email"><?php echo $this->lang->line('symptoms') ?></label> 
                                                <textarea name="symptoms" id="patient_symptoms" class="form-control" ><?php echo set_value('address'); ?></textarea>
                                            </div> 
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email"><?php echo $this->lang->line('any_known_allergies') ?></label> <textarea name="known_allergies" id="patient_allergies" class="form-control" ><?php echo set_value('address'); ?></textarea>
                                            </div> 
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('note'); ?></label> 
                                                <textarea name="note" id="patient_note" class="form-control" ><?php echo set_value('note'); ?></textarea>
                                            </div>
                                        </div>   
                                    </div><!--./row--> 
                                </div><!--./col-md-8--> 
                                <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('admission') . " " . $this->lang->line('date') ?></label>
                                                <input id="edit_admission_date"  name="appointment_date" placeholder="" type="text" class="form-control datetime"   />
                                                <span class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('case'); ?></label>
                                                <div><input class="form-control" type='text' id="patient_case" name='case_type' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('casualty'); ?></label>
                                                <div>
                                                    <select name="casualty" id="patient_casualty" class="form-control">

                                                        <option value="<?php echo $this->lang->line('yes') ?>"><?php echo $this->lang->line('yes') ?></option>
                                                        <option value="<?php echo $this->lang->line('no') ?>" selected><?php echo $this->lang->line('no') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div> 
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('old') . " " . $this->lang->line('patient'); ?></label>
                                                <div>
                                                    <select name="old_patient" id="old" class="form-control">
                                                        <option value="<?php echo $this->lang->line('yes') ?>"><?php echo $this->lang->line('yes') ?></option>
                                                        <option value="<?php echo $this->lang->line('yes') ?>" selected><?php echo $this->lang->line('no') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span></div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")"; ?></label>
                                                <input type="text" id="credits_limits" value="<?php echo set_value('credit_limit'); ?>" name="credit_limit" class="form-control">
                                            </div>
                                        </div>                                  
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('organisation'); ?></label>
                                                <div><select class="form-control" name='organisation' id="organisations">
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
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('refference'); ?></label>
                                                <div><input class="form-control" type='text' name='refference' id="patient_refference" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></label>
                                                <div>
                                                    <select class="form-control select2" name='cons_doctor' id="patient_consultant" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($doctors as $dkey => $dvalue) {
    ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>   
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('cons_doctor'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('bed') . " " . $this->lang->line('group'); ?></label>
                                                <div>
                                                    <select class="form-control" name='bed_group_id' id='bed_group_id' onchange="getBed(this.value, '', 'yes')">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($bedgroup_list as $key => $bedgroup) {
    ?>
                                                            <option value="<?php echo $bedgroup["id"] ?>"><?php echo $bedgroup["name"] . " - " . $bedgroup["floor_name"] ?></option>
<?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>  

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
<?php echo $this->lang->line('bed') . " " . $this->lang->line('no'); ?></label><small class="req"> *</small> 
                                                <div><select class="form-control select2" style="width:100%" name='bed_no' id='bed_nos'>
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>

                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('bed_no'); ?></span></div>
                                        </div>  
                                    </div><!--./row-->    
                                </div><!--./col-md-4-->
                            </div><!--./row-->   
                            <div class="row">            
                                <div class="box-footer">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-info pull-right"> <?php echo $this->lang->line('save'); ?></button>
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


<!-- add consultant -->

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
                        <form id="consultant_register_form"  accept-charset="utf-8"  enctype="multipart/form-data" method="post" class="ptt10">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input name="patient_id" placeholder="" id="ins_patient_id" value="<?php echo $result["id"] ?>" type="hidden" class="form-control"   />

                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" id="constableID">
                                        <tr>
                                            <th><?php echo $this->lang->line('applied') . " " . $this->lang->line('date'); ?>

                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('consultant'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('instruction'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                            <th><?php echo $this->lang->line('instruction') . " " . $this->lang->line('date'); ?>
                                                <small class="req"> *</small>
                                            </th>
                                        </tr>
                                        <tr id="row0">
                                            <td><input type="text" name="date[]" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(true, true))); ?>" class="form-control datetime"></td>
                                            <td><select name="doctor[]" style="width: 100%" class="form-control select2">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
<?php foreach ($doctors as $key => $value) {
    ?>
                                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["name"] . " " . $value["surname"] ?></option>
<?php } ?>
                                                </select></td>
                                            <td><textarea name="instruction[]" style="height:28px" class="form-control"></textarea></td>
                                            <td><input type="text"  name="insdate[]" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" class="form-control date"></td>
                                           <!--  <td><input type="text" name="instime[]" class="form-control instime"></td> -->
                                            <td><button type="button" onclick="add_consultant_row()" style="color: #2196f3" class="closebtn"><i class="fa fa-plus"></i></button></td>
                                        </tr>
                                    </table>
                                      <!--   <a href="#" class="" onclick="add_consultant_row()"><?php echo $this->lang->line('add_more'); ?></a> -->
                                </div>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>             
<!-- -->     
<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
        //      $('.insdate').datepicker();
        // $('.date').datetimepicker();

    });
    function addpaymentModal() {
        var total = $("#charge_total").val();
        var patient_id = '<?php echo $result["id"] ?>';
        $("#total").val(total);
        $("#payment_patient_id").val(patient_id);
        holdModal('myPaymentModal');
    }

    function getRecord(id) {
        console.log(id);
        var active = '<?php echo $result['is_active'] ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
            type: "POST",
            data: {recordid: id, active: active},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#patients_id").html(data.patient_unique_id);
                $("#patient_name").html(data.patient_name);
                $("#contact").html(data.mobileno);
                $("#email").html(data.email);
                var age = '';
                var month = '';
                if (data.age != '') {
                    age = data.age + ' Year ';
                }

                if (data.month != '') {
                    month = data.month + ' Month ';
                }
                $("#age").html(age + month);
                $("#gen").html(data.gender);

                $("#guardian_name").html(data.guardian_name);
                $("#admission_date").html(data.date);
                $("#case").html(data.case_type);
                $("#casualty").html(data.casualty);
                $("#symptoms").html(data.symptoms);
                $("#known_allergies").html(data.known_allergies);
                $("#refference").html(data.refference);
                $("#doc").html(data.name + ' ' + data.surname);
                $("#amount").html(data.amount);
                $("#tax").html(data.tax);
                $("#height").html(data.height);
                $("#weight").html(data.weight);
                $("#patient_bp").html(data.bp);
                $("#blood_group").html(data.blood_group);
                $("#old_patient").html(data.old_patient);
                $("#payment_mode").html(data.payment_mode);
                $("#organisation").html(data.organisation_name);
                $("#opdid").val(data.opdid);
                $("#patient_address").html(data.address);
                $("#marital_status").html(data.marital_status);
                $("#note").val(data.note);
                $("#bed_group").html(data.bedgroup_name + '-' + data.floor_name);
                $("#bed_name").html(data.bed_name);
                $("#updateid").val(id);
                holdModal('viewModal');
            },
        });
    }
    function getEditRecord(id) {
        $('#myModaledit').modal('show');
        var active = '<?php echo $result['is_active'] ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
            type: "POST",
            data: {recordid: id, active: active},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#patients_ids").val(data.patient_unique_id);
                $("#patient_names").val(data.patient_name);
                $("#edit_admission_date").val(data.date);
                $("#contacts").val(data.mobileno);
                $("#patient_image").val(data.image);
                $("#emails").val(data.email);
                $("#ages").val(data.age);
                $("#months").val(data.month);
                $("#patient_height").val(data.height);
                $("#patient_weight").val(data.weight);
                $("#bp").val(data.bp);
                $("#edit_patient_address").val(data.address);
                $("#patient_case").val(data.case_type);
                $("#patient_symptoms").val(data.symptoms);
                $("#patient_allergies").val(data.known_allergies);
                $("#patient_note").val(data.note);
                $("#patient_refference").val(data.refference);
                $("#bloodgroups").val(data.blood_group);
                $("#guardian_names").val(data.guardian_name);
                $("#credits_limits").val(data.credit_limit);
                $("#ipdid").val(data.ipdid);
                $("#bed_group_id").val(data.bed_group_id);
                getBed(data.bed_group_id, data.bed, '');
                $("#updateid").val(id);
                $('select[id="patient_consultant"] option[value="' + data.cons_doctor + '"]').attr("selected", "selected");
                $('select[id="patient_casualty"] option[value="' + data.casualty + '"]').attr("selected", "selected");
                $('select[id="old"] option[value="' + data.old_patient + '"]').attr("selected", "selected");
                $('select[id="genders"] option[value="' + data.gender + '"]').attr("selected", "selected");
                $('select[id="marital_statuss"] option[value="' + data.marital_status + '"]').attr("selected", "selected");
                $('select[id="organisations"] option[value="' + data.organisation + '"]').attr("selected", "selected");
                $(".select2").select2().select2('val', data.cons_doctor);


                holdModal('myModaledit');
            },
        });
    }


    $(document).ready(function (e) {
        $("#formeditrecord").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/ipd_update',
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

    function editRecord(id, opdid) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/opd_details',
            type: "POST",
            data: {recordid: id, opdid: opdid},
            dataType: 'json',
            success: function (data) {
                $("#patientid").val(data.patient_unique_id);
                $("#patientname").val(data.patient_name);
                $("#appointmentdate").val(data.appointment_date);
                $("#edit_case").val(data.case_type);
                $("#edit_symptoms").val(data.symptoms);
                $("#edit_casualty").val(data.casualty);
                $("#edit_knownallergies").val(data.known_allergies);
                $("#edit_refference").val(data.refference);
                $("#edit_consdoctor").val(data.cons_doctor);
                $("#edit_amount").val(data.amount);
                $("#edit_tax").val(data.tax);
                $("#edit_paymentmode").val(data.payment_mode);
                $("#edit_opdid").val(opdid);
            },
        });
    }

    $(document).ready(function (e) {
        $("#add_payment").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/payment/create',
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
                }, error: function () {}
            });
        }));
    });


    $(document).ready(function (e) {
        $("#formedit").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/opd_detail_update',
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
                }, error: function () {}
            });
        }));
    });
    $(document).ready(function (e) {
        $("#add_charges").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/charges/add_ipdcharges',
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
    function getBed(bed_group, bed = '', active) {

        var div_data = "";
        $("#bed_nos").html("<option value=''>Select</option>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/setup/bed/getbedbybedgroup',
            type: "POST",
            data: {bed_group: bed_group, active: active},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    if (bed == obj.id) {
                        sel = "selected";
                    }
                    div_data += "<option " + sel + " value=" + obj.id + ">" + obj.name + "</option>";
                });
                $('#bed_nos').append(div_data);
                $("#bed_nos").select2().select2('val', bed);
            }
        });
    }
    $(document).ready(function (e) {
        $("#form_prescription").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_prescription',
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
    $(document).ready(function (e) {
        $("#form_diagnosis").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_diagnosis',
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
    function add_more() {
        var div = "<div id=row1><div class=col-sm-4><div class=form-group><label><?php echo $this->lang->line('medicine'); ?> :</label><input type=text name='medicine[]' class=form-control id=report_type /></div></div><div class=col-sm-4><div class=form-group><label><?php echo $this->lang->line('dosage'); ?> :</label><input type=text class=form-control name='dosage[]' id=report_document /></div></div><div class=col-sm-4><div class=form-group><label><?php echo $this->lang->line('instruction'); ?> :</label><textarea name='instruction[]' style='height:28px;' class=form-control id=description></textarea></div></div></div>";
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td>" + div + "</td><td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }
    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
        //table.deleteRow(id);
    }
    $(document).ready(function (e) {
        $("#add_timeline").on('submit', (function (e) {
            var patient_id = $("#patient_id").val();
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/timeline/add_patient_timeline") ?>",
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
                        $.ajax({
                            url: '<?php echo base_url(); ?>admin/timeline/patient_timeline/' + patient_id,
                            success: function (res) {
                                $('#timeline_list').html(res);
                                $('#myTimelineModal').modal('toggle');
                            },
                            error: function () {
                                alert("Fail")
                            }
                        });
                        //window.location.reload(true);
                    }

                },
                error: function (e) {
                    alert("Fail");
                    console.log(e);
                }
            });
        }));
    });

    $(document).ready(function (e) {
        $("#add_bill").on('submit', (function (e) {

            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/payment/addbill") ?>",
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
                        window.location.href = '<?php echo base_url(); ?>admin/patient/discharged_patients';
                    }

                },
                error: function (e) {
                    alert("Fail");
                    console.log(e);
                }
            });
        }));
    });


    function delete_timeline(id) {
        var patient_id = $("#patient_id").val();
        if (confirm('<?php echo $this->lang->line("delete_conform") ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/timeline/delete_patient_timeline/' + id,
                success: function (res) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/timeline/patient_timeline/' + patient_id,
                        success: function (res) {
                            $('#timeline_list').html(res);
                            successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                        },
                        error: function () {
                            alert("Fail")
                        }
                    });
                }, error: function () {
                    alert("Fail")
                }
            });
        }
    }
    $(document).ready(function (e) {

        $(function () {
            var hash = window.location.hash;
            hash && $('ul.nav-tabs a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });


    });
    function view_prescription(id, opdid) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/prescription/getPrescription/' + id + '/' + opdid,
            success: function (res) {
                $("#getdetails_prescription").html(res);
            },
            error: function () {
                alert("Fail")
            }
        });
        holdModal('prescriptionview');
    }
    
    function getcharge_category(id) {
        var div_data = "";
        $("#charge_category").select2().select2('val', '');
        $("#charge_category").html("<option value=''>Select</option>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/get_charge_category',
            type: "POST",
            data: {charge_type: id},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.name + "'>" + obj.name + "</option>";
                });
                $('#charge_category').append(div_data);
            }
        });
    }


    function get_Charges(code, orgid) {
        $("#standard_charge").html("standard_charge");
        $("#schedule_charge").html("schedule_charge");

        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/ipdCharge',
            type: "POST",
            data: {code: code, organisation_id: orgid},
            dataType: 'json',
            success: function (res) {
                console.log(res);
                if (res) {

                    $('#standard_charge').val(res.standard_charge);
                    $('#schedule_charge').val(res.org_charge);
                    $('#charge_id').val(res.id);
                    $('#org_id').val(res.org_charge_id);

                    if (res.org_charge == null) {
                        $('#apply_charge').val(res.standard_charge);
                    } else {
                        $('#apply_charge').val(res.org_charge);
                    }
                } else {
                    //  $('#standard_charge').val('0');
                    // $('#schedule_charge').val('0');
                    // $('#charge_id').val('0');
                    // $('#org_id').val('0');
                }
            }
        });
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
                $('#standard_charge').val('');
                $('#apply_charge').val('');
            }
        });
    }
    function calculate() {

        var total_amount = $("#total_amount").val();
        var discount = $("#discount").val();
        var other_charge = $("#other_charge").val();
        //var gross_total = $("#gross_total").val();
        var tax = $("#tax").val();
        // var net_amount = $("#net_amount").val();
        var gross_total = parseInt(total_amount) + parseInt(other_charge) + parseInt(tax);
        var net_amount = parseInt(total_amount) + parseInt(other_charge) + parseInt(tax) - parseInt(discount);
        $("#gross_total").val(gross_total);
        $("#net_amount").val(net_amount);
        $("#net_amount_span").html(net_amount);
        $("#save_button").show();
        $("#printBill").show();
    }

    function revert(patient_id, billid, bedid) {

        // window.location.reload(true);
        // $( "#tabs" ).tabs({ active: 'charges' });
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/revertBill',
            type: "POST",
            data: {patient_id: patient_id, bill_id: billid, bed_id: bedid},
            dataType: 'json',
            success: function (res) {
                if (res.status == "fail") {
                    var message = "";
                    errorMsg(res.message);
                } else {
                    successMsg(res.message);
                    window.location.href = '<?php echo base_url() ?>admin/patient/ipdsearch';
                }
            }
        });
    }


    $(document).ready(function (e) {
        $("#consultant_register_form").on('submit', (function (e) {
//var student_id = $("#student_id").val();
//alert("hii");
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_consultant_instruction',
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


    function add_consultant_row() {



        var table = document.getElementById("constableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);

        var div = "<td><input type='text' name='date[]' class='form-control datetime'></td><td><select name='doctor[]'  class='select2' style='width:100%'><option value=''><?php echo $this->lang->line('select') ?></option><?php foreach ($doctors as $key => $value) { ?><option value='<?php echo $value["id"] ?>'><?php echo $value["name"] . " " . $value["surname"] ?></option><?php } ?></select></td><td><textarea name='instruction[]' style='height:28px' class='form-control'></textarea></td><td><input type='text' name='insdate[]' class='form-control date'></td>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div + "<td><button type='button' onclick='delete_consultant_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";

        $('.select2').select2();


    }

    function delete_consultant_row(id) {

        var table = document.getElementById("constableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
//table.deleteRow(id);
    }

</script>
<script type="text/javascript">

    function deletePatient(id) {
        if (confirm('Are you sure')) {
            $.ajax({
                url: base_url + 'admin/patient/deleteIpdPatient/' + id,
                type: 'POST',
                data: {patient_id: id},
                success: function (data) {
                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.href = '<?php echo base_url() . "admin/patient/ipdsearch" ?>';

                    }
                }
            });
        }
    }
    function printBill(patientid, ipdid) {
        var total_amount = $("#total_amount").val();
        var discount = $("#discount").val();
        var other_charge = $("#other_charge").val();
        var gross_total = $("#gross_total").val();
        var tax = $("#tax").val();
        var net_amount = $("#net_amount").val();
        var status = $("#status").val();
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payment/getBill/',
            type: 'POST',
            data: {patient_id: patientid, ipdid: ipdid, total_amount: total_amount, discount: discount, other_charge: other_charge, gross_total: gross_total, tax: tax, net_amount: net_amount, status: status},
            success: function (result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }
    function popup(data)
    {
        var base_url = '<?php echo base_url() ?>';
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
        return true;
    }
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

    function delete_record(url, Msg) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_conform') . "'"; ?>)) {
            $.ajax({
                url: url,
                success: function (res) {
                    successMsg(Msg);
                    window.location.reload(true);
                }
            })
        }
    }
</script>