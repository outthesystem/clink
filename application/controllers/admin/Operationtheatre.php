<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Operationtheatre extends Admin_Controller {

    function __construct() {
        parent::__construct();
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
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('operation_name', $this->lang->line('operation') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');

        $this->form_validation->set_rules('consultant_doctor', $this->lang->line('consultant') . " " . $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        $this->form_validation->set_rules('code', $this->lang->line('code'), 'required');
        $this->form_validation->set_rules('standard_charge', $this->lang->line('standard') . " " . $this->lang->line('charge'), 'required');
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'patient_name' => form_error('patient_name'),
                'date' => form_error('date'),
                'operation_name' => form_error('operation_name'),
                'consultant_doctor' => form_error('consultant_doctor'),
                'charge_category_id' => form_error('charge_category_id'),
                'code' => form_error('code'),
                'standard_charge' => form_error('standard_charge'),
                'apply_charge' => form_error('apply_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $opd_ipd_patient_type = $this->input->post('opd_ipd_patient_type');
            $opd_ipd_no = $this->input->post('opd_ipd_no');
            if (($opd_ipd_patient_type != 'opd') && ($opd_ipd_patient_type != 'ipd')) {
                $check_patient_id = $this->patient_model->getMaxId();
                $patient_id = $check_patient_id + 1;
                $patient_data = array(
                    'patient_unique_id' => $patient_id,
                    'patient_type' => 'OT',
                    'patient_name' => $this->input->post('patient_name'),
                    'gender' => $this->input->post('gender'),
                    'age' => $this->input->post('age'),
                    'month' => $this->input->post('month'),
                    'mobileno' => $this->input->post('mobileno'),
                    'guardian_name' => $this->input->post('guardian_name'),
                    'organisation' => $this->input->post('organisation'),
                    'guardian_address' => $this->input->post('guardian_address'),
                    'is_active' => 'yes',
                );
                $patient_info = $this->operationtheatre_model->add_patient($patient_data);
                if ($patient_info) {
                    $date = $this->input->post("date");

                    $operation_detail = array(
                        'patient_id' => $patient_info,
                        'customer_type' => $this->input->post('customer_type'),
                        'operation_name' => $this->input->post('operation_name'),
                        'Date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($date)),
                        'operation_type' => $this->input->post('operation_type'),
                        'consultant_doctor' => $this->input->post('consultant_doctor'),
                        'ass_consultant_1' => $this->input->post('ass_consultant_1'),
                        'ass_consultant_2' => $this->input->post('ass_consultant_2'),
                        'anesthetist' => $this->input->post('anesthetist'),
                        'anaethesia_type' => $this->input->post('anaethesia_type'),
                        'ot_technician' => $this->input->post('ot_technician'),
                        'ot_assistant' => $this->input->post('ot_assistant'),
                        'charge_id' => $this->input->post('code'),
                        'result' => $this->input->post('result'),
                        'remark' => $this->input->post('remark'),
                        'apply_charge' => $this->input->post('apply_charge')
                    );
                    $this->operationtheatre_model->operation_detail($operation_detail);
                }
            } else {
                $patient_id = $this->input->post('patient_id');
                $date = $this->input->post("date");

                $operation_detail = array(
                    'patient_id' => $patient_id,
                    'customer_type' => $this->input->post('customer_type'),
                    'operation_name' => $this->input->post('operation_name'),
                    'Date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($date)),
                    'operation_type' => $this->input->post('operation_type'),
                    'consultant_doctor' => $this->input->post('consultant_doctor'),
                    'ass_consultant_1' => $this->input->post('ass_consultant_1'),
                    'ass_consultant_2' => $this->input->post('ass_consultant_2'),
                    'anesthetist' => $this->input->post('anesthetist'),
                    'anaethesia_type' => $this->input->post('anaethesia_type'),
                    'ot_technician' => $this->input->post('ot_technician'),
                    'ot_assistant' => $this->input->post('ot_assistant'),
                    'charge_id' => $this->input->post('code'),
                    'result' => $this->input->post('result'),
                    'remark' => $this->input->post('remark'),
                    'apply_charge' => $this->input->post('apply_charge')
                );
                $this->operationtheatre_model->operation_detail($operation_detail);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function otsearch($id = '') {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'operation_theatre');
        if (!empty($id)) {
            $data["id"] = $id;
        }
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $data['charge_category'] = $this->operationtheatre_model->getChargeCategory();
        $data['resultlist'] = $this->operationtheatre_model->searchFullText();
        $data['organisation'] = $this->Organisation_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/operationtheatre/otsearch.php', $data);
        $this->load->view('layout/footer');
    }

    public function getDetails() {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("patient_id");
        $result = $this->operationtheatre_model->getDetails($id);
        if (($result['patient_type'] == 'Inpatient') || ($result['patient_type'] == 'Outpatient')) {
            $opd_ipd_no = $this->operationtheatre_model->getopdipdDetails($id, $result['patient_type']);
            $result['opd_ipd_no'] = $opd_ipd_no;
        }
        $result['admission_date'] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['admission_date']));
        $result['date'] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));

        echo json_encode($result);
    }

    public function getOtPatientDetails() {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("id");
        $result = $this->operationtheatre_model->getOtPatientDetails($id);
        $result['admission_date'] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['admission_date']));
        $result['date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['date']));
        echo json_encode($result);
    }

    public function update() {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('apply_charge', $this->lang->line('applied') . " " . $this->lang->line('charge'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('patient_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('operation_name', $this->lang->line('operation') . " " . $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('consultant_doctor', $this->lang->line('consultant') . " " . $this->lang->line('doctor'), 'required');
        $this->form_validation->set_rules('charge_category_id', $this->lang->line('charge') . " " . $this->lang->line('category'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'patient_name' => form_error('patient_name'),
                'date' => form_error('date'),
                'operation_name' => form_error('operation_name'),
                'consultant_doctor' => form_error('consultant_doctor'),
                'charge_category_id' => form_error('charge_category_id'),
                'charge_category_id' => form_error('apply_charge'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $patient_data = array(
                'id' => $id,
                'patient_name' => $this->input->post('patient_name'),
                'gender' => $this->input->post('gender'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'guardian_name' => $this->input->post('guardian_name'),
                'organisation' => $this->input->post('organisation'),
                'mobileno' => $this->input->post('mobileno'),
                'guardian_address' => $this->input->post('guardian_address'),
            );
            $patient_update = $this->operationtheatre_model->update_patient($patient_data);
            $charge_category_id = $this->input->post('charge_category_id');
            $date = $this->input->post("date");

            $otid = $this->input->post('otid');
            $operation_detail = array(
                'id' => $otid,
                'patient_id' => $id,
                'operation_name' => $this->input->post('operation_name'),
                'date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($date)),
                'operation_type' => $this->input->post('operation_type'),
                'consultant_doctor' => $this->input->post('consultant_doctor'),
                'ass_consultant_1' => $this->input->post('ass_consultant_1'),
                'ass_consultant_2' => $this->input->post('ass_consultant_2'),
                'anesthetist' => $this->input->post('anesthetist'),
                'anaethesia_type' => $this->input->post('anaethesia_type'),
                'ot_technician' => $this->input->post('ot_technician'),
                'ot_assistant' => $this->input->post('ot_assistant'),
                'charge_id' => $charge_category_id,
                'result' => $this->input->post('result'),
                'remark' => $this->input->post('remark'),
                'apply_charge' => $this->input->post('apply_charge'),
            );
            $this->operationtheatre_model->update_operation_detail($operation_detail);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->operationtheatre_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record deleted Successfully');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function add_ot_consultant_instruction() {
        if (!$this->rbac->hasPrivilege('ot_consultant_instruction', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('date[]', $this->lang->line('applied') . " " . $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('doctor[]', $this->lang->line('consultant'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('instruction[]', $this->lang->line('instruction'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('insdate[]', $this->lang->line('instruction') . " " . $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date' => form_error('date[]'),
                'doctor' => form_error('doctor[]'),
                'instruction' => form_error('instruction[]'),
                'insdate' => form_error('insdate[]'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $date = $this->input->post('date[]');
            $ins_date = $this->input->post('insdate[]');

            $patient_id = $this->input->post('patient_id');
            $doctor = $this->input->post('doctor[]');
            $instruction = $this->input->post('instruction[]');
            $data = array();
            $i = 0;
            foreach ($date as $key => $value) {


                $details = array(
                    'date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($date[$i])),
                    'patient_id' => $patient_id,
                    'ins_date' => date('Y-m-d', $this->customlib->datetostrtotime($ins_date[$i])),
                    'cons_doctor' => $doctor[$i],
                    'instruction' => $instruction[$i],
                );
                $data[] = $details;
                $i++;
            }
            $this->operationtheatre_model->add_ot_consultantInstruction($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getConsultantBatch() {
        if (!$this->rbac->hasPrivilege('ot_consultant_instruction', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("patient_id");
        $data["id"] = $id;
        $result = $this->operationtheatre_model->getConsultantBatch($id);
        $data["result"] = $result;
        $this->load->view('admin/operationtheatre/patientConsultantDetail', $data);
    }

    public function OtReport() {
        if (!$this->rbac->hasPrivilege('ot_patient', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/operationtheatre/otreport');
        $select = 'operation_theatre.*,patients.id as pid,patients.patient_unique_id,patients.patient_name,patients.gender,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.code,charges.description,charges.standard_charge';
        $join = array(
            'JOIN patients ON operation_theatre.patient_id=patients.id',
            'JOIN staff ON staff.id = operation_theatre.consultant_doctor',
            'JOIN charges ON operation_theatre.charge_id = charges.id'
        );
        $where = array(
            "patients.is_active = 'yes' ",
            "operation_theatre.patient_id = patients.id "
        );
        $table_name = "operation_theatre";

        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }
        if (empty($search_type)) {
            $search_type = "";
            $resultlist = $this->report_model->getReport($select, $join, $table_name, $where);
        } else {

            $search_table = "operation_theatre";
            $search_column = "date";
            $resultlist = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column, $where);
        }

        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/operationtheatre/otReport.php', $data);
        $this->load->view('layout/footer');
    }

    public function deleteConsultant($id) {
        if (!empty($id)) {
            $this->operationtheatre_model->deleteConsultant($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record deleted Successfully');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

}
