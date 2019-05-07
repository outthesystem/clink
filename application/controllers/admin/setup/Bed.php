<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bed extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("Bedgroup_Model");
    }

    function index() {
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/setup/bed');
        $this->session->set_userdata('sub_menu', 'bed');
        $data['bedtype_list'] = $this->Bedtype_Model->bedtype_list();
        $data['bedgroup_list'] = $this->Bedgroup_Model->bedgroup_list();
        $data['bed_list'] = $this->Bed_Model->bed_list();
        $this->load->view('layout/header');
        $this->load->view('setup/Bed', $data);
        $this->load->view('layout/footer');
    }

    function getbed_categore_type($table_name) {
        $data['list'] = $this->Bed_Model->bedcategorie($table_name);
        $this->load->view('setup/DdlCat', $data);
    }

    function add() {
       
        $this->form_validation->set_rules(
                'name', $this->lang->line('name'), array('required',
            array('check_exists', array($this->Bed_Model, 'valid_bed'))
                )
        );
        $this->form_validation->set_rules('bed_type', $this->lang->line('bed') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bed_group', $this->lang->line('bed') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'name' => form_error('name'),
                'bed_type' => form_error('bed_type'),
                'bed_group' => form_error('bed_group'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $bed = array(
                'name' => $this->input->post('name'),
                'bed_type_id' => $this->input->post('bed_type'),
                'bed_group_id' => $this->input->post('bed_group'),
                'is_active' => 'yes'
            );

            $this->Bed_Model->savebed($bed);



            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    function update() {
    
        $this->form_validation->set_rules(
                'name', $this->lang->line('name'), array('required',
            array('check_exists', array($this->Bed_Model, 'valid_bed'))
                )
        );
        $this->form_validation->set_rules('bed_type', $this->lang->line('bed') . " " . $this->lang->line('type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bed_group', $this->lang->line('bed') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'name' => form_error('name'),
                'bed_type' => form_error('bed_type'),
                'bed_group' => form_error('bed_group'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $bed = array(
                'id' => $this->input->post('bedid'),
                'name' => $this->input->post('name'),
                'bed_type_id' => $this->input->post('bed_type'),
                'bed_group_id' => $this->input->post('bed_group'),
            );

            $this->Bed_Model->savebed($bed);

            $msg = "Bed Updated Successfully";

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        echo json_encode($array);
    }

    public function get($id) {

        $result = $this->Bed_Model->bed_list($id);
        echo json_encode($result);
    }

    public function getbedbybedgroup() {
        $bed_group = $this->input->post('bed_group');
        $active = $this->input->post('active');
        $result = $this->Bed_Model->getbedbybedgroup($bed_group, $active);
        echo json_encode($result);
    }

    public function delete($id) {
        if (!empty($id)) {
            $this->Bed_Model->delete($id);
        }
        redirect('admin/setup/bed');
    }

    public function status() {
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/setup/bed/status');
        $this->session->set_userdata('sub_menu', 'bed');
        $data['bedtype_list'] = $this->Bedtype_Model->bedtype_list();
        $data['bedgroup_list'] = $this->Bedgroup_Model->bedgroup_list();
        $result = $this->Bed_Model->getBedStatus();
        $data["bed_list"] = $result;
        $this->load->view('layout/header');
        $this->load->view('setup/bedStatus', $data);
        $this->load->view('layout/footer');
    }

}
?>