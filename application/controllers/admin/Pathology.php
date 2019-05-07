<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pathology extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->model("report_model");
        $this->search_type = $this->config->item('search_type');
    }

    public function unauthorized() {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add() {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('pathology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('standard_charge', $this->lang->line('standard') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'test_name' => form_error('test_name'),
                'short_name' => form_error('short_name'),
                'test_type' => form_error('test_type'),
                'pathology_category_id' => form_error('pathology_category_id'),
                'charge_category_id' => form_error('charge_category_id'),
                'code' => form_error('code'),
                'standard_charge' => form_error('standard_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $pathology = array(
                'test_name' => $this->input->post('test_name'),
                'short_name' => $this->input->post('short_name'),
                'test_type' => $this->input->post('test_type'),
                'pathology_category_id' => $this->input->post('pathology_category_id'),
                'unit' => $this->input->post('unit'),
                'sub_category' => $this->input->post('sub_category'),
                'report_days' => $this->input->post('report_days'),
                'method' => $this->input->post('method'),
                'charge_id' => $this->input->post('code')
            );
            $this->pathology_model->add($pathology);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function search() {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'pathology');
        $categoryName = $this->pathology_category_model->getcategoryName();
        $data["categoryName"] = $categoryName;
        $data['charge_category'] = $this->pathology_model->getChargeCategory();
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $data['resultlist'] = $this->pathology_model->searchFullText();
        $result = $this->pathology_model->getPathology();
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/pathology/search.php', $data);
        $this->load->view('layout/footer');
    }

    public function getDetails() {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("pathology_id");
        $result = $this->pathology_model->getDetails($id);
        echo json_encode($result);
    }

    public function update() {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('test_name', $this->lang->line('test') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('short_name', $this->lang->line('short') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('test_type', $this->lang->line('test') . " " . $this->lang->line('type'), 'required');
        $this->form_validation->set_rules('pathology_category_id', $this->lang->line('category') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        // $this->form_validation->set_rules('method', 'Method', 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'test_name' => form_error('test_name'),
                'short_name' => form_error('short_name'),
                'test_type' => form_error('test_type'),
                'pathology_category_id' => form_error('pathology_category_id'),
                'code' => form_error('code'),
                // 'method' => form_error('method'),
                'charge_category_id' => form_error('charge_category_id')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $charge_category_id = $this->input->post('charge_category_id');
            $pathology = array(
                'id' => $id,
                'test_name' => $this->input->post('test_name'),
                'short_name' => $this->input->post('short_name'),
                'test_type' => $this->input->post('test_type'),
                'pathology_category_id' => $this->input->post('pathology_category_id'),
                'unit' => $this->input->post('unit'),
                'sub_category' => $this->input->post('sub_category'),
                'report_days' => $this->input->post('report_days'),
                'method' => $this->input->post('method'),
                'charge_id' => $this->input->post('code')
            );

            $this->pathology_model->update($pathology);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pathology_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getPathology() {
        if (!$this->rbac->hasPrivilege('pathology test', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post('pathology_id');
        $result = $this->pathology_model->getPathology($id);
        echo json_encode($result);
    }

    public function getPathologyReport() {
        if (!$this->rbac->hasPrivilege('pathology_test_report', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post('id');
        $result = $this->pathology_model->getPathologyReport($id);
        $result['reporting_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['reporting_date']));
        echo json_encode($result);
    }

    public function updateTestReport() {
        if (!$this->rbac->hasPrivilege('pathology test report', 'can_edit')) {
            access_denied();
        }
        if (!empty($_FILES['pathology_report']['name'])) {
            $config['upload_path'] = 'uploads/pathology_report/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = $_FILES['pathology_report']['name'];
            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('pathology_report')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {
            $picture = '';
        }
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'id' => form_error('id'),
                'patient_name' => form_error('patient_name'),
                'apply_charge' => form_error('apply_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $reporting_date = $this->input->post("reporting_date");

            $id = $this->input->post('id');
            $report_batch = array(
                'id' => $id,
                'customer_type' => $this->input->post('customer_type'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date' => date('Y-m-d', $this->customlib->datetostrtotime($reporting_date)),
                'description' => $this->input->post('description'),
                'pathology_report' => $picture,
                'apply_charge' => $this->input->post('apply_charge'),
            );
            $this->pathology_model->updateTestReport($report_batch);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function testReportBatch() {
        if (!$this->rbac->hasPrivilege('pathology test report', 'can_add')) {
            access_denied();
        }
        if (!empty($_FILES['pathology_report']['name'])) {
            $config['upload_path'] = 'uploads/pathology_report/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = $_FILES['pathology_report']['name'];
            //Load upload library and initialize configuration
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('pathology_report')) {
                $uploadData = $this->upload->data();
                $picture = $uploadData['file_name'];
            } else {
                $picture = '';
            }
        } else {
            $picture = '';
        }
        $this->form_validation->set_rules('pathology_id', $this->lang->line('pathology') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'required');
        // $this->form_validation->set_rules('consultant_doctor', 'Refferal Doctor', 'required');
        // $this->form_validation->set_rules('reporting_date','Reporting Date','required');
        // $this->form_validation->set_rules('description','description','required');
        // if (empty($_FILES["pathology_report"]["name"]))
        //    {
        //        $this->form_validation->set_rules('pathology_report','Pathology Report','required');
        //    }
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'pathology_id' => form_error('pathology_id'),
                // 'customer_type'=> form_error('customer_type'),  
                'patient_name' => form_error('patient_name'),
                'apply_charge' => form_error('apply_charge'),
                    // 'reporting_date' => form_error('reporting_date'),
                    // 'description ' => form_error('description'),
                    // 'pathology_report ' => form_error('pathology_report')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('pathology_id');
            $patient_id = $this->input->post('patient_id');
            $reporting_date = $this->input->post("reporting_date");

            $report_batch = array(
                'pathology_id' => $id,
                'patient_id' => $patient_id,
                'customer_type' => $this->input->post('customer_type'),
                'patient_name' => $this->input->post('patient_name'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'reporting_date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($reporting_date)),
                'description' => $this->input->post('description'),
                'apply_charge' => $this->input->post('apply_charge'),
                'pathology_report' => $picture
            );
            $this->pathology_model->testReportBatch($report_batch);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getTestReportBatch() {
        if (!$this->rbac->hasPrivilege('pathology test report', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("id");
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $result = $this->pathology_model->getTestReportBatch($id);
        $data["result"] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/pathology/reportDetail', $data);
        $this->load->view('layout/footer');
    }

    public function download($doc) {
        $this->load->helper('download');
        $filepath = "./uploads/pathology_report/" . $doc;
        $data = file_get_contents($filepath);
        force_download($doc, $data);
    }

    public function deleteTestReport($id) {
        if (!$this->rbac->hasPrivilege('pathology test report', 'can_delete')) {
            access_denied();
        }
        $this->pathology_model->deleteTestReport($id);

        //redirect('admin/pathology/getTestReportBatch');
    }

    public function pathologyReport() {
        if (!$this->rbac->hasPrivilege('pathology test report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/pathology/pathologyreport');
        $select = 'pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge';
        $join = array(
            'JOIN pathology ON pathology_report.pathology_id = pathology.id',
            'JOIN staff ON pathology_report.consultant_doctor = staff.id',
            'JOIN charges ON charges.id = pathology.charge_id'
        );
        $table_name = "pathology_report";
        // $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
        // if ($this->form_validation->run() == FALSE) {
        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }
        if (empty($search_type)) {
            $search_type = "";
            $resultlist = $this->report_model->getReport($select, $join, $table_name);
        } else {
            // $search_type = $this->input->post("search_type");
            $search_table = "pathology_report";
            $search_column = "reporting_date";
            $resultlist = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column);
        }

        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/pathology/pathologyReport.php', $data);
        $this->load->view('layout/footer');
    }

}
