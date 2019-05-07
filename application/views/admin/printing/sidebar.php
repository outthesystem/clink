<div class="box border0">
    <ul class="tablists">
        <?php if ($this->rbac->hasPrivilege('opd_prescription_print_header_footer', 'can_view')) { ?>
            <li><a href="<?php echo site_url('admin/printing') ?>" class="<?php echo set_sidebar_Submenu('admin/printing'); ?>"><?php echo $this->lang->line('opd') . " " . $this->lang->line('prescription'); ?></a></li>
        <?php } if ($this->rbac->hasPrivilege('ipd_prescription_print_header_footer', 'can_view')) { ?>
            <li><a href="<?php echo site_url('admin/printing/ipdprinting') ?>" class="<?php echo set_sidebar_Submenu('admin/printing/ipdprinting'); ?>"><?php echo $this->lang->line('ipd') . " " . $this->lang->line('bill'); ?></a></li>
        <?php } if ($this->rbac->hasPrivilege('pharmacy_bill_print_header_footer', 'can_view')) { ?>
            <li><a href="<?php echo site_url('admin/printing/pharmacyprinting') ?>" class="<?php echo set_sidebar_Submenu('admin/printing/pharmacyprinting'); ?>"><?php echo $this->lang->line('pharmacy') . " " . $this->lang->line('bill'); ?></a></li>
        <?php } ?>
        <li><a href="<?php echo site_url('admin/printing/payslipprinting') ?>" class="<?php echo set_sidebar_Submenu('admin/printing/payslipprinting'); ?>"><?php echo $this->lang->line('payslip'); ?></a></li>

    </ul>
</div>
