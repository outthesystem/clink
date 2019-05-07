<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


// Config variables

$config['mailsms'] = array(
    'opd_patient_registration' => lang('opd_patient_registration'),
    'ipd_patient_registration' => lang('ipd_patient_registration'),
    'patient_discharged' => lang('patient_discharged'),
    'patient_revisit' => lang('patient_revisit'),
    'login_credential' => lang('login_credential'),
    'appointment' => lang('appointment')
);


$config['attendence'] = array(
    'present' => 1,
    'late_with_excuse' => 2,
    'late' => 3,
    'absent' => 4,
    'holiday' => 5,
    'half_day' => 6
);
$config['perm_category'] = array('can_view', 'can_add', 'can_edit', 'can_delete');

$config['bloodgroup'] = array('1' => 'O+', '2' => 'A+', '3' => 'B+', '4' => 'AB+', '5' => 'O-', '6' => 'A-', '7' => 'B-', '8' => 'AB-');
