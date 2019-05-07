<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bloodbank extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->blood_group = $this->config->item('bloodgroup');
        $this->config->load("payroll");
        $this->search_type = $this->config->item('search_type');
        $this->load->model("report_model");
    }

    public function unauthorized() {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('donor_name', $this->lang->line('donor') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood') . " " . $this->lang->line('group'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'donor_name' => form_error('donor_name'),
                'age' => form_error('age'),
                'blood_group' => form_error('blood_group'),
                'gender' => form_error('gender'),
                'father_name' => form_error('father_name')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $blooddonor = array(
                'donor_name' => $this->input->post('donor_name'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'blood_group' => $this->input->post('blood_group'),
                'gender' => $this->input->post('gender'),
                'father_name' => $this->input->post('father_name'),
                'address' => $this->input->post('address'),
                'contact_no' => $this->input->post('contact_no')
            );
            $this->blooddonor_model->add($blooddonor);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function search() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $data["bloodgroup"] = $this->blood_group;
        $data['resultlist'] = $this->blooddonor_model->searchFullText();
        $result = $this->blooddonor_model->getBloodBank();
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/bloodbank/search.php', $data);
        $this->load->view('layout/footer');
    }

    public function getDetails() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("blood_donor_id");
        $result = $this->blooddonor_model->getDetails($id);

        echo json_encode($result);
    }

    public function update() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('donor_name', $this->lang->line('donor') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood') . " " . $this->lang->line('group'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'donor_name' => form_error('donor_name'),
                'age' => form_error('age'),
                'blood_group' => form_error('blood_group'),
                'gender' => form_error('gender'),
                'father_name' => form_error('father_name')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $blooddonor = array(
                'id' => $id,
                'donor_name' => $this->input->post('donor_name'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'blood_group' => $this->input->post('blood_group'),
                'gender' => $this->input->post('gender'),
                'father_name' => $this->input->post('father_name'),
                'address' => $this->input->post('address'),
                'contact_no' => $this->input->post('contact_no')
            );
            $this->blooddonor_model->update($blooddonor);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->blooddonor_model->deleteBloodDonor($id);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getBloodBank() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post('blood_donor_id');
        $result = $this->blooddonor_model->getBloodBank($id);
        echo json_encode($result);
    }

    public function addIssue() {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('date_of_issue', $this->lang->line('issue') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('recieve_to', $this->lang->line('receive') . " " . $this->lang->line('to'), 'required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood') . " " . $this->lang->line('group'), 'required');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date_of_issue' => form_error('date_of_issue'),
                'recieve_to' => form_error('recieve_to'),
                'blood_group' => form_error('blood_group'),
                'doctor' => form_error('doctor'),
                'amount' => form_error('amount')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $issue_date = $this->input->post('date_of_issue');
            $bloodissue = array(
                'date_of_issue' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($issue_date)),
                'recieve_to' => $this->input->post('recieve_to'),
                'blood_group' => $this->input->post('blood_group'),
                'gender' => $this->input->post('gender'),
                'doctor' => $this->input->post('doctor'),
                'institution' => $this->input->post('institution'),
                'technician' => $this->input->post('technician'),
                'amount' => $this->input->post('amount'),
                'donor_name' => $this->input->post('donor_name'),
                'lot' => $this->input->post('lot'),
                'bag_no' => $this->input->post('bag_no'),
                'remark' => $this->input->post('remark')
            );

            $this->bloodissue_model->add($bloodissue);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function issue() {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_view')) {
            access_denied();
        }

        $data["bloodgroup"] = $this->blood_group;
        $data['resultlist'] = $this->bloodissue_model->searchFullText();
        $result = $this->bloodissue_model->getBloodIssue();
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/bloodbank/bloodissue.php', $data);
        $this->load->view('layout/footer');
    }

    public function getIssueDetails() {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("bloodissue_id");
        $result = $this->bloodissue_model->getDetails($id);
        $result['date_of_issue'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['date_of_issue']));
        echo json_encode($result);
    }

    public function updateIssue() {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('date_of_issue', $this->lang->line('issue') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('recieve_to', $this->lang->line('receive') . " " . $this->lang->line('to'), 'required');
        $this->form_validation->set_rules('blood_group', $this->lang->line('blood') . " " . $this->lang->line('group'), 'required');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date_of_issue' => form_error('date_of_issue'),
                'recieve_to' => form_error('recieve_to'),
                'blood_group' => form_error('blood_group'),
                'doctor' => form_error('doctor'),
                'amount' => form_error('amount')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $issue_date = $this->input->post("date_of_issue");
            $bloodissue = array(
                'id' => $id,
                'date_of_issue' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($issue_date)),
                'recieve_to' => $this->input->post('recieve_to'),
                'blood_group' => $this->input->post('blood_group'),
                'gender' => $this->input->post('gender'),
                'doctor' => $this->input->post('doctor'),
                'institution' => $this->input->post('institution'),
                'technician' => $this->input->post('technician'),
                'amount' => $this->input->post('amount'),
                'donor_name' => $this->input->post('donor_name'),
                'lot' => $this->input->post('lot'),
                'bag_no' => $this->input->post('bag_no'),
                'remark' => $this->input->post('remark')
            );
            $this->bloodissue_model->update($bloodissue);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully.');
        }
        echo json_encode($array);
    }

    public function deleteIssue($id) {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {

            $this->bloodissue_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => '');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getBloodIssue() {
        if (!$this->rbac->hasPrivilege('blood_issue', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post('bloodissue_id');
        $result = $this->bloodissue_model->getBloodIssue($id);
        echo json_encode($result);
    }

    public function donorCycle() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('blood_donor_id', $this->lang->line('blood') . " " . $this->lang->line('donor') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('bag_no', $this->lang->line('bag_no'), 'required');
        $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required');
        $this->form_validation->set_rules('donate_date', $this->lang->line('donate') . " " . $this->lang->line('date'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'donate_date' => form_error('donate_date'),
                'blood_donor_id' => form_error('blood_donor_id'),
                'bag_no' => form_error('bag_no'),
                'quantity' => form_error('quantity'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('blood_donor_id');
            $donate_date = $this->input->post('donate_date');
            $donor_cycle = array(
                'blood_donor_id' => $id,
                'institution' => $this->input->post('institution'),
                'lot' => $this->input->post('lot'),
                'bag_no' => $this->input->post('bag_no'),
                'quantity' => $this->input->post('quantity'),
                'donate_date' => date('Y-m-d', $this->customlib->datetostrtotime($donate_date))
            );
            $this->blood_donorcycle_model->donorCycle($donor_cycle);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getDonorBloodBatch() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("blood_donor_id");
        $data["id"] = $id;
        $result = $this->blood_donorcycle_model->getDonorBloodBatch($id);
        $data["result"] = $result;
        $this->load->view('admin/bloodbank/donorbloodbatch', $data);
    }

    public function bloodDonorReport() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/bloodbank/blooddonorreport');
        $select = 'blood_donor_cycle.*,blood_donor.id as bdid,blood_donor.donor_name,blood_donor.age,blood_donor.blood_group,blood_donor.gender';
        $join = array(
            'JOIN blood_donor ON blood_donor_cycle.blood_donor_id =blood_donor.id',
        );
        $table_name = "blood_donor_cycle";

        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }

        if (empty($search_type)) {
            $search_type = "";
            $resultlist = $this->report_model->getReport($select, $join, $table_name, $where = array());
        } else {

            $search_table = "blood_donor_cycle";
            $search_column = "created_at";
            $resultlist = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column, $where = array());
        }
        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/bloodbank/blooddonorreport.php', $data);
        $this->load->view('layout/footer');
    }

    public function bloodIssueReport() {
        if (!$this->rbac->hasPrivilege('blood_donor', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/bloodbank/bloodissuereport');
        $select = 'blood_issue.*';
        $join = array();
        $table_name = "blood_issue";

        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }

        if (empty($search_type)) {
            $search_type = "";
            $resultlist = $this->report_model->getReport($select, $join, $table_name, $where = array());
        } else {

            $search_table = "blood_issue";
            $search_column = "created_at";
            $resultlist = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column, $where = array());
        }
        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/bloodbank/bloodissuereport.php', $data);
        $this->load->view('layout/footer');
    }

    public function deleteDonorCycle($id) {
        if (!empty($id)) {
            $this->blood_donorcycle_model->deleteCycle($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record deleted Successfully');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

}
