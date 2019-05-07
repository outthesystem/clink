<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tpamanagement extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        if (!$this->rbac->hasPrivilege('organisation', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'tpa_management');
        $data['title'] = 'TPA Management';
        $data['resultlist'] = $this->Organisation_model->get();

        $this->load->view('layout/header');
        $this->load->view('admin/tpamanagement/index', $data);
        $this->load->view('layout/footer');
    }

    function add_oragnisation() {
        if (!$this->rbac->hasPrivilege('organisation', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('contact_number', $this->lang->line('contact') . " " . $this->lang->line('number'), 'required');
         if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'name' => form_error('name'),
                'code' => form_error('code'),
                'contact_number' => form_error('contact_number'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $organigation = array(
                'organisation_name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'contact_no' => $this->input->post('contact_number'),
                'address' => $this->input->post('address'),
                'contact_person_name' => $this->input->post('contact_person_name'),
                'contact_person_phone' => $this->input->post('contact_person_phone'),
            );
            $this->Organisation_model->add($organigation);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    function get_data($id) {
        if (!$this->rbac->hasPrivilege('organisation', 'can_view')) {
            access_denied();
        }
        $org = $this->Organisation_model->get($id);
        $array = array(
            'id' => $org['id'],
            'ename' => $org['organisation_name'],
            'ecode' => $org['code'],
            'econtact_number' => $org['contact_no'],
            'eaddress' => $org['address'],
            'econtact_persion_name' => $org['contact_person_name'],
            'econtact_persion_phone' => $org['contact_person_phone'],
        );
        echo json_encode($array);
    }

    function edit() {
        if (!$this->rbac->hasPrivilege('organisation', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('ename', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('ecode', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('econtact_number', $this->lang->line('contact') . " " . $this->lang->line('number'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'e1' => form_error('ename'),
                'e2' => form_error('ecode'),
                'e3' => form_error('econtact_number'),
                'e4' => form_error('eaddress'),
                'e5' => form_error('econtact_persion_name'),
                'e6' => form_error('econtact_persion_phone'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $organigation = array(
                'id' => $this->input->post('org_id'),
                'organisation_name' => $this->input->post('ename'),
                'code' => $this->input->post('ecode'),
                'contact_no' => $this->input->post('econtact_number'),
                'address' => $this->input->post('eaddress'),
                'contact_person_name' => $this->input->post('econtact_persion_name'),
                'contact_person_phone' => $this->input->post('econtact_persion_phone'),
            );
            $this->Organisation_model->add($organigation);
            $array = array('status' => 'suucess', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        echo json_encode($array);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('organisation', 'can_delete')) {
            access_denied();
        }
        $this->Organisation_model->delete($id);
        redirect('admin/tpamanagement');
    }

}
?>