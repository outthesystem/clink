<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo form_error('Opd'); ?> 
                            <?php
                            echo $this->lang->line('patient') . " " . $this->lang->line('search') . " ";
                            // if(!empty(($date_from) && ($date_to)))
                            // { 
                            // //  echo  $date_from." "."To"." ".$date_to; 
                            //  }
                            ?>
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('patient') . " " . $this->lang->line('search'); ?></div>
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('age'); ?></th>
                                    <th><?php echo $this->lang->line('gender'); ?></th>
                                    <th><?php echo $this->lang->line('phone'); ?></th>
                                    <th><?php echo $this->lang->line('guardian_name'); ?></th>
                                    <th><?php echo $this->lang->line('address'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($resultlist)) {
                                    ?>

                                    <?php
                                } else {

                                    foreach ($resultlist as $report) {
                                        $patient_type = $report['patient_type'];
                                        $url = '#';
                                        if ($patient_type == "Outpatient") {
                                            $url = base_url() . 'admin/patient/profile/' . $report["id"];
                                            $type = 'opd';
                                        } elseif ($patient_type == "Inpatient") {
                                            $url = base_url() . 'admin/patient/ipdprofile/' . $report['id'] . "/" . $report['is_active'];
                                            $type = 'ipd';
                                        } elseif ($patient_type == "OT") {
                                            $type = "OT";
                                            $url = base_url() . 'admin/operationtheatre/otsearch/' . $report['id'];
                                        }

                                        if (!($this->rbac->hasPrivilege('opd_patient', 'can_view')) && ($patient_type == "Outpatient")) {
                                            
                                        } elseif (!($this->rbac->hasPrivilege('ipd_patient', 'can_view')) && ($patient_type == "Inpatient")) {
                                            # code...
                                        } elseif (!($this->rbac->hasPrivilege('ot_patient', 'can_view')) && ($patient_type == "OT")) {
                                            # code...
                                        } else {
                                            ?>      
                                            <tr>
                                                <!-- <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($report['appointment_date'])) ?></td> -->

                                                <td><?php echo $report['patient_unique_id']; ?></td>
                                                <td><?php echo $this->lang->line($type); ?></td>
                                                <td>
                                                    <a target="_blank" href="<?php echo $url; ?>"><?php echo $report['patient_name'] ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $report['age']; ?></td>
                                                <td><?php echo $report['gender']; ?></td>
                                                <td><?php echo $report['mobileno']; ?></td>
                                                <td><?php echo $report['guardian_name']; ?></td>
                                                <td><?php echo $report['address']; ?></td>
                                                <td class="text-right"><a href="<?php echo $url ?>" data-toggle="tooltip" target="_blank" class="btn btn-default btn-xs" 
                                                                          title="<?php echo $this->lang->line('show'); ?>" >
                                                        <i class="fa fa-reorder"></i>
                                                    </a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>

                            <?php } ?>
                        </table>

                    </div>
                </div>
            </div>  
        </div>   
</div>  
</section>
</div>


<script type="text/javascript">
    $(document).ready(function (e) {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date_from,#date_to').datepicker({
            format: date_format,
            autoclose: true
        });

        showdate('<?php echo $search_type; ?>');
    });

    function showdate(value) {

        if (value == 'period') {
            $('#fromdate').show();
            $('#todate').show();
        } else {
            $('#fromdate').hide();
            $('#todate').hide();
        }
    }
</script>