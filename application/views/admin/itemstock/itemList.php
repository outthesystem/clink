<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">



    <!-- Main content -->
    <section class="content">
        <div class="row">


            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('item_stock_list'); ?></h3>
                        <div class="box-tools pull-right"><?php if ($this->rbac->hasPrivilege('item_stock', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_item_stock'); ?></a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('issue_item', 'can_add')) { ?>
                                <a href="<?php echo base_url(); ?>admin/issueitem" class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('issue_item'); ?> </a>  
                            <?php
                            }
                            if ($this->rbac->hasPrivilege('item', 'can_add')) {
                                ?>
                                <a href="<?php echo base_url(); ?>admin/item" class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i> <?php echo $this->lang->line('item'); ?> </a> 
<?php } ?>



                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('item_stock_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('supplier'); ?></th>
                                        <th><?php echo $this->lang->line('store'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($itemlist)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($itemlist as $items) {

                                            //print_r($items);
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $items['name'] ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($items['description'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $items['description']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td class="mailbox-name">
        <?php echo $items['item_category']; ?>

                                                </td>

                                                <td class="mailbox-name">
        <?php echo $items['item_supplier']; ?>

                                                </td>

                                                <td class="mailbox-name">
        <?php echo $items['item_store']; ?>

                                                </td>
                                                <td class="mailbox-name">
        <?php echo $items['quantity']; ?>

                                                </td>
                                                <td class="mailbox-name">
        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($items['date'])); ?>

                                                </td>





                                                <td class="mailbox-date pull-right"">
                                                    <?php if ($items['attachment']) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/itemstock/download/<?php echo $items['attachment'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    <?php }
                                                    ?>
        <?php if ($this->rbac->hasPrivilege('item_stock', 'can_edit')) { ?> 
                                                        <a  onclick="get_data(<?php echo $items['id']; ?>)" class="btn btn-default btn-xs"  data-toggle="tooltip" data-target="#editmyModal" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
        <?php } if ($this->rbac->hasPrivilege('item_stock', 'can_delete')) { ?> 
                                                        <a  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordById('<?php echo base_url(); ?>admin/itemstock/delete/<?php echo $items['id'] ?>', '<?php echo $this->lang->line('delete_message'); ?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
        <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade"  id="myModal" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add_item_stock') ?></h4>
            </div>
            <div class="modal-body pt0 pb0" >
                <div class="row ptt10">
                    <form id="form1" action="<?php echo base_url() ?>admin/itemstock/add"  id="itemstockform" name="itemstockform" method="post" accept-charset="utf-8" enctype="multipart/form-data">




<?php echo $this->customlib->getCSRF(); ?>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('item_category'); ?></label><small class="req"> *</small>

                                <select autofocus="" id="item_category_id" name="item_category_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($itemcatlist as $item_category) {
                                        ?>
                                        <option value="<?php echo $item_category['id'] ?>"<?php
                                        if (set_value('item_category_id') == $item_category['id']) {
                                            echo "selected = selected";
                                        }
                                        ?>><?php echo $item_category['item_category'] ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('item_category_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('item'); ?></label><small class="req"> *</small>

                                <select  id="item_id" name="item_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>

                                </select>
                                <span class="text-danger"><?php echo form_error('item_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier'); ?></label>

                                <select  id="supplier_id" name="supplier_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($itemsupplier as $itemsup) {
                                        ?>
                                        <option value="<?php echo $itemsup['id'] ?>"<?php
                                        if (set_value('supplier_id') == $itemsup['id']) {
                                            echo "selected = selected";
                                        }
                                        ?>><?php echo $itemsup['item_supplier'] ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('supplier_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('store'); ?></label>

                                <select  id="store_id" name="store_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($itemstore as $itemstore) {
                                        ?>
                                        <option value="<?php echo $itemstore['id'] ?>"<?php
                                        if (set_value('store_id') == $itemstore['id']) {
                                            echo "selected = selected";
                                        }
                                        ?>><?php echo $itemstore['item_store'] ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('store_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('quantity'); ?></label><small class="req"> *</small>
                                <div class="">
                                    <span class="miplus">
                                        <select class="form-control" name="symbol">
                                            <option value="+">+</option>
                                            <option value="-">-</option>
                                        </select>
                                    </span>
                                    <input id="quantity" name="quantity" placeholder="" type="text" class="form-control miplusinput"  value="<?php echo set_value('quantity'); ?>" />
                                </div>

                                <span class="text-danger"><?php echo form_error('quantity'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label>
                                <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date'); ?>" readonly="readonly" />
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                <input id="item_photo" name="item_photo" placeholder="" type="file" class="filestyle form-control" data-height="40"  value="<?php echo set_value('item_photo'); ?>" />
                                <span class="text-danger"><?php echo form_error('item_photo'); ?></span>
                            </div>
                        </div>



                </div>


            </div>
            <div class="box-footer clear">
                <div class="pull-right">
                    <button type="submit"  class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade"  id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close"  data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit'); ?>  <?php echo $this->lang->line('item_stock'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0"  >
                <form id="editform1"   id="itemstockform" name="itemstockform" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                    <div class="row ptt10"  id="editstok">


                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                <input id="item_photo" name="item_photo" placeholder="" type="file" class="filestyle form-control" data-height="40"  value="<?php echo set_value('item_photo'); ?>" />
                                <span class="text-danger"><?php echo form_error('item_photo'); ?></span>
                            </div>
                        </div>
                    </div>
            </div><!-- /.box-body -->
            <div class="box-footer clear">
                <div class="pull-right">
                    <button type="submit"  class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>

        </div>


    </div>
</form>

</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        var item_id_post = '<?php echo set_value('item_id') ?>';
        item_id_post = (item_id_post != "") ? item_id_post : 0;
        var item_category_id_post = '<?php echo set_value('item_category_id'); ?>';
        item_category_id_post = (item_category_id_post != "") ? item_category_id_post : 0;
        populateItem(item_id_post, item_category_id_post);

        function populateItem(item_id_post, item_category_id_post) {
            if (item_category_id_post != "") {
                $('#item_id').html("");

                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: base_url + "admin/itemstock/getItemByCategory",
                    data: {'item_category_id': item_category_id_post},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var select = "";
                            if (item_id_post == obj.id) {
                                var select = "selected=selected";
                            }
                            div_data += "<option value=" + obj.id + " " + select + ">" + obj.name + "</option>";
                        });
                        $('#item_id').append(div_data);
                    }

                });
            }
        }



        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $('#date').datepicker({
           
            format: date_format,
            autoclose: true
        });

        


        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(document).on('change', '#item_category_id', function (e) {
            $('#item_id').html("");
            var item_category_id = $(this).val();
            populateItem(0, item_category_id);
        });

    });


    $(document).ready(function (e) {

        $('#form1').on('submit', (function (e) {

            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
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

    function get_data(id) {
        $('#editmyModal').modal('show');
       
        $.ajax({

            url: "<?php echo base_url() ?>admin/itemstock/edit/" + id,
            type: "POST",
            success: function (res) {
   
                $('#editstok').html(res);
            }
        });
    }
</script>