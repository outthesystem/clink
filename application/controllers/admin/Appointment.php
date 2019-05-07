<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Appointment extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->load->library('Enc_lib');
        $this->appointment_status = $this->config->item('appointment_status');
    }

    public function unauthorized() {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add() {
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'required|numeric');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('appointment_status', $this->lang->line('appointment') . " " . $this->lang->line('status'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date' => form_error('date'),
                'patient_name' => form_error('patient_name'),
                'mobileno' => form_error('mobileno'),
                'doctor' => form_error('doctor'),
                'message' => form_error('message'),
                'appointment_status' => form_error('appointment_status')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $date = $this->input->post('date');

            $appointment = array(
                'patient_id' => $this->input->post('patient_id'),
                'date' => date("Y-m-d H:i:s", $this->customlib->datetostrtotime($date)),
                'patient_name' => $this->input->post('patient_name'),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'mobileno' => $this->input->post('mobileno'),
                'doctor' => $this->input->post('doctor'),
                'message' => $this->input->post('message'),
                'source' => 'Offline',
                'appointment_status' => $this->input->post('appointment_status')
            );
            $this->appointment_model->add($appointment);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully.');
        }
        echo json_encode($array);
    }

    public function search() {
        $this->session->set_userdata('top_menu', 'front_office');
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $data["appointment_status"] = $this->appointment_status;
        $data['resultlist'] = $this->appointment_model->searchFullText();
        $result = $this->appointment_model->getAppointment();
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/appointment/search.php', $data);
        $this->load->view('layout/footer');
    }

    public function getDetails() {
        $id = $this->input->post("appointment_id");
        $result = $this->appointment_model->getDetails($id);
        $result["date"] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));
        echo json_encode($result);
    }

    public function update() {
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'required|numeric');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('appointment_status', $this->lang->line('appointment') . " " . $this->lang->line('status'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date' => form_error('date'),
                'patient_name' => form_error('patient_name'),
                'mobileno' => form_error('mobileno'),
                'doctor' => form_error('doctor'),
                'message' => form_error('message'),
                'appointment_status' => form_error('appointment_status')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $status = $this->input->post('appointment_status');

            $date = $this->input->post('date');

            $appointment = array(
                'id' => $id,
                'patient_id' => $this->input->post('patient_id'),
                'date' => date("Y-m-d H:i:s", $this->customlib->datetostrtotime($date)),
                'patient_name' => $this->input->post('patient_name'),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'mobileno' => $this->input->post('mobileno'),
                'doctor' => $this->input->post('doctor'),
                'message' => $this->input->post('message'),
                'appointment_status' => $this->input->post('appointment_status')
            );
            $this->appointment_model->update($appointment);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully.');
        }
        echo json_encode($array);
    }

    public function delete($id) {
        if (!empty($id)) {
            $this->appointment_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function status($id) {
        $appointment_no = 'APPNO' . $id;
        $data = array('appointment_no' => $appointment_no, 'appointment_status' => 'approve');
        $this->appointment_model->status($id, $data);
        $appointment_details = $this->appointment_model->getDetails($id);

        $sender_details = array('appointment_id' => $id, 'contact_no' => $appointment_details["mobileno"], 'email' => $appointment_details["email"]);
        $this->mailsmsconf->mailsms('appointment', $sender_details);

        redirect('admin/appointment/search');
    }

    public function getpatientDetails() {
        $id = $this->input->post("patient_id");
        $result = $this->appointment_model->getpatientDetails($id);
        echo json_encode($result);
    }

}
