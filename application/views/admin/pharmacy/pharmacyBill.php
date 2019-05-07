<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<style type="text/css">
    #easySelectable {/*display: flex; flex-wrap: wrap;*/}
    #easySelectable li {}
    #easySelectable li.es-selected {background: #2196F3; color: #fff;}
    .easySelectable {-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;}
    .printablea4{width: 100%;}
    /*.printablea4 p{margin-bottom: 0;}*/
    .printablea4>tbody>tr>th,
    .printablea4>tbody>tr>td{padding:2px 0; line-height: 1.42857143;vertical-align: top; font-size: 12px;}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('pharmacy') . " " . $this->lang->line('bill'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('pharmacy bill', 'can_add')) { ?>                
                                <a data-toggle="modal" onclick="holdModal('myModal')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('generate') . " " . $this->lang->line('bill'); ?></a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('medicine', 'can_view')) { ?>
                                <a href="<?php echo base_url(); ?>admin/pharmacy/search" class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('medicines'); ?></a>
                            <?php } ?>
                        </div> 
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('pharmacy') . " " . $this->lang->line('bill'); ?></div>
                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('bill') . " " . $this->lang->line('no'); ?></th>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('customer') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('customer') . " " . $this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('doctor') . " " . $this->lang->line('name'); ?></th>

                                    <th class="text-right"><?php echo $this->lang->line('total') . " " . '(' . $currency_symbol . ')'; ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
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
                                    foreach ($resultlist as $bill) {
                                        ?>
                                        <tr class="">
                                            <td >
                                                <?php if ($this->rbac->hasPrivilege('pharmacy bill', 'can_view')) { ?>   
                                                    <a href="#" onclick="viewDetail('<?php echo $bill['id'] ?>')"
                                                       data-toggle="tooltip"  title="<?php echo $this->lang->line('show'); ?>" ><?php echo $bill['bill_no']; ?></a> 
                                                   <?php } ?> 
                                            </td>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($bill['date'])) ?></td> 
                                            <td><?php echo $bill['customer_name']; ?></td>
                                            <td><?php echo $this->lang->line($bill['customer_type']); ?></td>
                                            <td><?php echo $bill['doctor_name']; ?></td>
                                            <td class="text-right"><?php echo $bill['net_amount']; ?></td>
                                            <td class="pull-right">
                                                <a href="#" 
                                                   onclick="viewDetail('<?php echo $bill['id'] ?>')"
                                                   class="btn btn-default btn-xs"  data-toggle="tooltip"
                                                   title="<?php echo $this->lang->line('show'); ?>" >
                                                    <i class="fa fa-reorder"></i>
                                                </a> </td>
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
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('generate') . " " . $this->lang->line('bill'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="bill" accept-charset="utf-8" method="post" class="ptt10">
                            <div class="row">
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('bill') . " " . $this->lang->line('no'); ?></label>
                                        <small class="req" style="color:red;"> *</small> 
                                        <input name="bill_no" id="billno" type="text" class="form-control"/>
                                        <span class="text-danger"><?php echo form_error('bill_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                                        <small class="req" style="color:red;"> *</small> 
                                        <input name="date"  type="text" value="<?php echo date($this->customlib->getSchoolDateFormat(true, true)) ?>" class="form-control datetime"/>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <th><?php echo $this->lang->line('customer') . " " . $this->lang->line('type'); ?></th></label>

                                        <div>
                                            <select class="form-control" name='customer_type' id='customer_type' >
                                                <option value="<?php echo "direct"; ?>" selected><?php echo $this->lang->line('direct'); ?></option>
                                                <option value="<?php echo "opd"; ?>"><?php echo $this->lang->line('opd'); ?></option>   
                                                <option value="<?php echo "ipd"; ?>"><?php echo $this->lang->line('ipd'); ?></option> 
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('customer_type'); ?></span>
                                    </div>
                                </div> 
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label> <th><?php echo $this->lang->line('opd_ipd_no'); ?></th></label>
                                        <input name="opd_ipd_no" onchange="getPatientIdName(this.value)" type="text" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label> <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th></label>
                                        <small class="req" style="color:red;"> *</small> 
                                        <input name="customer_name" id="patient_name" type="text" class="form-control"/>
                                        <input name="patient_id" id="patient_id" type="hidden" class="form-control"/>
                                        <span class="text-danger"><?php echo form_error('customer_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('doctor') . " " . $this->lang->line('name'); ?></label>

                                        <input name="doctor_name" id="doctor_name" type="text" class="form-control"/>
                                        <span class="text-danger"><?php echo form_error('doctor_name'); ?></span>
                                    </div>
                                </div>
                               <!--  <input name="pharmacy_bill_basic_id" id="pharmacy_bill_basic_id" type="hidden" class="form-control"   />  -->
                                <div class="col-md-12" style="clear: both;">
                                    <div class="">
                                        <table class="table table-striped table-bordered table-hover" id="tableID">
                                            <tr style="font-size: 13">
                                                <th><?php echo $this->lang->line('medicine') . " " . $this->lang->line('category'); ?><small class="req" style="color:red;"> *</small></th>
                                                <th width="15%"><?php echo $this->lang->line('medicine') . " " . $this->lang->line('name'); ?><small class="req" style="color:red;"> *</small></th>
                                                <th><?php echo $this->lang->line('batch') . " " . $this->lang->line('no'); ?><small style="color:red;"> *</small></th>
                                                <th><?php echo $this->lang->line('expire') . " " . $this->lang->line('date'); ?><small class="req" style="color:red;"> *</small></th>
                                                <th class="text-right"><?php echo $this->lang->line('quantity'); ?><small class="req" style="color:red;"> *</small> <?php echo " | " . $this->lang->line('available') . " " . $this->lang->line('qty'); ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('sale_price') . " " . ' (' . $currency_symbol . ')'; ?><small class="req" style="color:red;"> *</small></th>
                                                <th class="text-right"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?><small class="req" style="color:red;"> *</small></th>
                                            </tr>
                                            <tr id="row0">
                                                <td width="16%">      
                                                    <select class="form-control" name='medicine_category_id[]'  onchange="getmedicine_name(this.value, '0')">
                                                        <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                        </option>
                                                        <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                            </option>   
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('medicine_category_id[]'); ?>
                                                    </span>
                                                </td>
                                                <td width="24%">
                                                    <select class="form-control select2" style="width:100%" onchange="getbatchnolist(this.value, 0)" id="medicine_name0" name='medicine_name[]'>
                                                        <option value=""><?php echo $this->lang->line('select') ?>
                                                        </option>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('medicine_name[]'); ?>
                                                </td>
                                                <td width="16%"> 
                                                 <!-- <input type="text" name="batch_no[]" onchange="getExpire(0)" placeholder="" class="form-control" id="batch_no0" > -->
                                                    <select class="form-control" id="batch_no0" name="batch_no[]" onchange="getExpire(0)">
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('batch_no[]'); ?></span>
                                                </td>
                                                <td width="8%">
                                                    <input type="text" readonly="" name="expire_date[]"  id="expire_date0" class="form-control">
                                                    <span class="text-danger"><?php echo form_error('medicine_name[]'); ?>
                                                    </span>
                                                </td>

                                                <td>
                                                 <!--  <input type="text" name="quantity[]" placeholder="" class="form-control text-right" id="quantity0" onchange="multiply(0)" onfocus="getQuantity(0)">
                                                  <span id="totalqty0" class="text-danger"><?php echo form_error('quantity[]'); ?></span> -->
                                                    <div class="input-group">
                                                        <input type="text" name="quantity[]" onchange="multiply(0)" onfocus="getQuantity(0)" id="quantity0" class="form-control text-right">
                                                        <span class="input-group-addon text-danger" style="font-size: 10pt"  id="totalqty0">&nbsp;&nbsp;</span>
                                                    </div>
                                                    <input type="hidden" name="available_quantity[]" id="available_quantity0">
                                                    <input type="hidden" name="id[]" id="id0">
                                                </td>
                                                <td class="text-right">

                                                    <input type="text" name="sale_price[]" onchange="multiply(0)" id="sale_price0" placeholder="" class="form-control text-right">
                                                    <span class="text-danger"><?php echo form_error('sale_price[]'); ?></span>
                                                </td>

                                                <td class="text-right">
                                                    <input type="text" name="amount[]" id="amount0" placeholder="" class="form-control text-right">
                                                    <span class="text-danger"><?php echo form_error('net_amount[]'); ?></span>
                                                </td>
                                                <td><button type="button" onclick="addMore()" style="color: #2196f3" class="closebtn"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                        </table>
                                    </div>  
                                    <div class="divider"></div>    
                                    <!--    <div class="col-sm-8">
                                     <div class="form-group">
                                       <input type="button" onclick="addTotal()" value="Calculate" class="btn btn-info pull-right"/>
                                     </div>
                                   </div> -->
                                    <div class="row">  
                                        <div class="col-sm-6">
                                            <table class="printablea4" width="100%">
                                                <tr>
                                                    <th><?php echo $this->lang->line('note'); ?></th>
                                                    <td><textarea name="note" id="note" class="form-control"></textarea></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <table class="printablea4">
                                                <tr>
                                                    <th><?php echo $this->lang->line('total') . " (" . $currency_symbol . ")"; ?></th>
                                                    <td class="text-right ipdbilltable"><input type="text" placeholder="Total" value="0" name="total" id="total" style="width: 30%; float: right" class="form-control"/></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th>
                                                    <td class="text-right ipdbilltable"><input type="text" placeholder="Discount" value="0" name="discount" id="discount" style="width: 30%; float: right" class="form-control"/></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?></th>
                                                    <td class="text-right ipdbilltable"><input type="text" placeholder="Tax" name="tax" value="0" id="tax" style="width: 30%; float: right" class="form-control"/></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?></th>
                                                    <td class="text-right ipdbilltable"><input type="text" placeholder="Net Amount" value="0" name="net_amount" id="net_amount" style="width: 30%; float: right" class="form-control"/></td>
                                                </tr>
                                            </table>

                                            <!-- <div class="form-group">
                                             <label>Discount</label>
                                              <input type="text" placeholder="Total" name="total" id="total" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                             <label>Tax</label>
                                              <input type="text" placeholder="Total" name="total" id="total" class="form-control"/>
                                            </div> -->
                                        </div>

                                    </div><!--./row-->  
                                </div><!--./col-md-12-->
                                <!--  <div class="col-sm-offset-9 ">
                                   <label>Total</label>
                                   <input type="text" name="total" placeholder="Total">
                                 </div> -->

                            </div><!--./row-->  

                    </div><!--./col-md-12-->    
                </div><!--./row--> 
            </div><!--./modal-body-->
            <div class="box-footer" style="clear: both;">
                <div class="pull-right">
                    <input type="button" onclick="addTotal()" value="<?php echo $this->lang->line('calculate'); ?>" class="btn btn-info"/>&nbsp;
                    <button type="submit" style="display: none" id="billsave" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </div><!--./box-footer-->
            </form>
        </div>
    </div> 
</div>


<div class="modal fade" id="edit_bill" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('close'); ?>" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit') . " " . $this->lang->line('bill'); ?></h4> 
            </div>

            <div class="modal-body pt0 pb0" id="edit_bill_details">
            </div>    

        </div></div> </div>

<div class="modal fade" id="viewModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_deletebill'>
                        <a href="#"  data-target="#edit_prescription"  data-toggle="modal" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('bill') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="reportdata"></div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

    });
    function edit_bill(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pharmacy/editPharmacyBill/' + id,
            success: function (res) {
                $('#viewModal').modal('hide');
                $("#edit_bill_details").html(res);
                holdModal('edit_bill');
            },
            error: function () {
                alert("Fail")
            }
        });
    }


    function getmedicine_name(id, rowid) {
        var div_data = "";
        $('#medicine_name' + rowid).select2("val", '');
        $("#medicine_name" + rowid).html("<option value=''>Select</option>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pharmacy/get_medicine_name',
            type: "POST",
            data: {medicine_category_id: id},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value=" + obj.id + ">" + obj.medicine_name + "</option>";
                });
                $('#medicine_name' + rowid).append(div_data);
                //$('#medicine_name'+rowid).select2();
            }
        });
    }
    function addMore() {
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);

        var id = parseInt(table_len - 1);

        var div = "<td><select class='form-control' name='medicine_category_id[]' onchange='getmedicine_name(this.value," + id + ")'><option value='<?php echo set_value('medicine_category_id'); ?>'><?php echo $this->lang->line('select') ?></option><?php foreach ($medicineCategory as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["medicine_category"] ?></option><?php } ?></select></td><td><select class='form-control select2' style='width:100%' name='medicine_name[]' onchange='getbatchnolist(this.value," + id + ")' id='medicine_name" + id + "' ><option value='<?php echo set_value('medicine_name'); ?>'><?php echo $this->lang->line('select') ?></option></select></td><td><select name='batch_no[]' id='batch_no" + id + "' onchange='getExpire(" + id + ")' class='form-control'><option value='<?php echo set_value('batch_no'); ?>'><?php echo $this->lang->line('select') ?></option></select></td><td><input type='text' name='expire_date[]' readonly id='expire_date" + id + "' class='form-control expire_date'></td><td><div class='input-group'><input type='text' name='quantity[]' onchange='multiply(" + id + ")' onfocus='getQuantity(" + id + ")' id='quantity" + id + "' class='form-control text-right'><span class='input-group-addon text-danger' style='font-size:10pt'  id='totalqty" + id + "'>&nbsp;&nbsp;</span></div><input type='hidden' name='available_quantity[]' id='available_quantity" + id + "'><input type='hidden' name='id[]' id='id" + id + "'></td><td> <input type='text' onchange='multiply(" + id + ")' name='sale_price[]' id='sale_price" + id + "'  class='form-control text-right'></td><td><input type='text' name='amount[]' id='amount" + id + "'  class='form-control text-right'></td>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div + "<td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
        $('.select2').select2();

        var expire_date = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY',]) ?>';
        $('.expire_date').datepicker({
            format: "M/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
        });
    }

    function addTotal() {
        var total = 0;
        var sale_price = document.getElementsByName('amount[]');
        for (var i = 0; i < sale_price.length; i++) {
            var inp = sale_price[i];
            if (inp.value == '') {
                var inpvalue = 0;
            } else {
                var inpvalue = inp.value;
            }
            total += parseInt(inpvalue);
        }
        var tax = $("#tax").val();
        var discount = $("#discount").val();

        $("#total").val(total);
        var net_amount = parseInt(total) + parseInt(tax) - parseInt(discount);
        $("#net_amount").val(net_amount);
        $("#billsave").show();
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).remove();
    }

    $(document).ready(function (e) {

        var expire_date = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY',]) ?>';
        $('.expire_date').datepicker({
            format: "M/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
        });
    });

    $(document).ready(function (e) {
        $("#bill").on('submit', (function (e) {
            e.preventDefault();

            var table = document.getElementById("tableID");
            var rowCount = table.rows.length;

            for (var k = 0; k < rowCount; k++) {
                var quantityk = $('#quantity' + k).val();
                var availquantityk = $('#available_quantity' + k).val();
                if (parseInt(quantityk) > parseInt(availquantityk)) {
                    errorMsg('Order quantity should not be greater than available quantity');
                    return false;
                } else {
                }
            }
            $.ajax({
                url: '<?php echo base_url(); ?>admin/pharmacy/addBill',
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
            });   //alert(parseInt(quantity));



        }));
    });

    function viewDetail(id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/pharmacy/getBillDetails/' + id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdata').html(data);
                $('#edit_deletebill').html("<?php if ($this->rbac->hasPrivilege('pharmacy bill', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" + id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a> <?php } ?><?php if ($this->rbac->hasPrivilege('pharmacy bill', 'can_edit')) { ?><a href='#'' onclick='edit_bill(" + id + ")' data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php } ?><?php if ($this->rbac->hasPrivilege('pharmacy bill', 'can_edit')) { ?><a onclick='delete_bill(" + id + ")'  href='#'  data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>");
                holdModal('viewModal');
            },
        });
    }
    function getQuantity(id) {
        var batch_no = $('#batch_no' + id).val();
        if (batch_no != "") {
            $('#quantity').html("");
            $.ajax({
                type: "GET",
                url: base_url + "admin/pharmacy/getQuantity",
                data: {'batch_no': batch_no},
                dataType: 'json',
                success: function (data) {
                    $('#id' + id).val(data.id);
                    //$('#quantity').html(data.available_quantity);
                    $('#totalqty' + id).html(data.available_quantity);
                    $('#available_quantity' + id).val(data.available_quantity);
                    $('#sale_price' + id).val(data.sale_rate);
                }
            });
        }
    }

    function getExpire(id) {
        var batch_no = $("#batch_no" + id).val();
        $.ajax({
            type: "POST",
            url: base_url + "admin/pharmacy/getExpiryDate",
            data: {'batch_no': batch_no},
            dataType: 'json',
            success: function (res) {
                if (res != null) {
                    $('#expire_date' + id).val(res.expiry_date);
                    getQuantity(id);
                }
            }
        });
    }

    function getbatchnolist(id, rowid) {

        // var batch_no = $("#batch_no"+id).val();
        //$('#medicine_name'+rowid).select2("val", '');
        var div_data = "";
        //$('#quantity').html(data.available_quantity);
        $('#totalqty' + rowid).html("<span class='input-group-addon text-danger' style='font-size:10pt'  id='totalqty" + rowid + "'></span>");
        $('#available_quantity' + rowid).val('');
        $('#sale_price' + rowid).val('');
        $('#expire_date' + rowid).val('');
        $('#amount' + rowid).val('');
        $('#quantity' + rowid).val('');
        $("#batch_no" + rowid).html("<option value=''>Select</option>");
        $.ajax({
            type: "POST",
            url: base_url + "admin/pharmacy/getBatchNoList",
            data: {'medicine': id},
            dataType: 'json',
            success: function (res) {
                console.log(res);
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value='" + obj.batch_no + "'>" + obj.batch_no + "</option>";
                });
                $('#batch_no' + rowid).append(div_data);
            }
        });
    }


    function multiply(id) {

        var quantity = $('#quantity' + id).val();
        var availquantity = $('#available_quantity' + id).val();
        if (parseInt(quantity) > parseInt(availquantity)) {
            errorMsg('Order quantity should not be greater than available quantity');
        } else {
            //alert(parseInt(quantity));
        }
        var sale_price = $('#sale_price' + id).val();
        var amount = quantity * sale_price;
        $('#amount' + id).val(amount);
    }

    function generateBillNo() {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pharmacy/getBillNo',
            type: "POST",
            dataType: 'json',
            data: {id: 1},
            success: function (data) {
                //alert(data);
                $('#billno').val(data);
            }
        });

    }

    function getPatientIdName(opd_ipd_no) {
        //var opd_ipd_patient_type = $('select[name=customer_type]:selected').val();
        //alert(opd_ipd_patient_type);
        //alert($("#customer_type").val());
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
                $('#doctor_name').val(data.doctorname + ' ' + data.surname);
            }
        });
    }
// function add_instruction(id){
//     $('#ins_patient_id').val(id);
// }

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        generateBillNo()
    }
</script>
<script type="text/javascript">
    $(function () {
        $('#easySelectable').easySelectable();
        //stopPropagation();
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
