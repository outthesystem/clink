<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class dashboard extends Patient_Controller {

    public $school_name;
    public $school_setting;
    public $setting;
    public $payment_method;
    public $patient_data;

    public function __construct() {
        parent::__construct();
        $this->payment_method = $this->paymentsetting_model->getActiveMethod();
        $this->patient_data = $this->session->userdata('patient');
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->appointment_status = $this->config->item('appointment_status');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->search_type = $this->config->item('search_type');
        $this->blood_group = $this->config->item('bloodgroup');
        $this->load->model("payment_model");
        $this->load->model("report_model");
        $this->load->model("printing_model");
        $this->charge_type = $this->config->item('charge_type');
        $data["charge_type"] = $this->charge_type;
    }

    public function index() {
        $id = $this->patient_data['patient_id'];
        $data["id"] = $id;
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $result = array();
        $diagnosis_details = array();
        $opd_details = array();
        $timeline_list = array();
        if (!empty($id)) {
            $result = $this->patient_model->getDetails($id);
            $opd_details = $this->patient_model->getOPDetails($id);
            $diagnosis_details = $this->patient_model->getDiagnosisDetails($id);
            $timeline_list = $this->timeline_model->getPatientTimeline($id, $timeline_status = '');
            $prescription_details = $this->prescription_model->getPatientPrescription($id);
        }
        $data["result"] = $result;
        $data["diagnosis_detail"] = $diagnosis_details;
        $data["prescription_detail"] = $prescription_details;
        $data["opd_details"] = $opd_details;
        $data["timeline_list"] = $timeline_list;
        $this->load->view("layout/patient/header");
        $this->load->view("patient/profile", $data);
        $this->load->view("layout/patient/footer");
    }

    public function profile() {
        $this->session->set_userdata('top_menu', 'profile');
        $this->index();
    }

    public function getDetails() {
        $id = $this->patient_data['patient_id'];
        $active = $this->input->post('active');
        $result = $this->patient_model->patientProfile($id, $active);
        $appointment_date = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['appointment_date']));
        $result['appointment_date'] = $appointment_date;

        echo json_encode($result);
    }

    public function appointment() {
        $this->session->set_userdata('top_menu', 'myprofile');
        $id = $this->patient_data['patient_id'];
        $data["id"] = $id;
        $result = $this->patient_model->getDataAppoint($id);

        $data["result"] = $result;
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $data["appointment_status"] = $this->appointment_status;
        $data['resultlist'] = $this->patient_model->search($id);

        $this->load->view("layout/patient/header");
        $this->load->view("patient/appointment", $data);
        $this->load->view("layout/patient/footer");
    }

    public function bookAppointment() {

        $this->form_validation->set_rules('date', $this->lang->line("date"), 'required');
       // $this->form_validation->set_rules('doctor', $this->lang->line("doctor"), 'required');
        $this->form_validation->set_rules('message', $this->lang->line("message"), 'required');
        $this->form_validation->set_rules('appointment_status', $this->lang->line("appointment") . " " . $this->lang->line("status"), 'required');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'date' => form_error('date'),
                'patient_name' => form_error('patient_name'),
                'mobileno' => form_error('mobileno'),
                'doctor' => form_error('doctor'),
                'message' => form_error('message'),
                'appointment_status' => form_error('appointment_status'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $patient_id = $this->input->post('patient_id');
            $patient_name = $this->input->post('patient_name');
            $gender = $this->input->post('gender');
            $email = $this->input->post('email');
            $mobileno = $this->input->post('mobileno');
            $date = $this->input->post('date');

            $appointment = array(
                'patient_id' => $patient_id,
                'date' => date("Y-m-d H:i:s", $this->customlib->datetostrtotime($date)),
                'patient_name' => $patient_name,
                'gender' => $gender,
                'email' => $email,
                'mobileno' => $mobileno,
                'doctor' => $this->input->post('doctor'),
                'message' => $this->input->post('message'),
                'appointment_status' => $this->input->post('appointment_status'),
            );

            $this->appointment_model->add($appointment);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function bloodBankStatus() {
        $data['bloodGroup'] = $this->bloodbankstatus_model->getBloodGroup();
        $this->load->view("layout/patient/header");
        $this->load->view("patient/bloodBankStatus", $data);
        $this->load->view("layout/patient/footer");
    }

    public function ipdprofile() {
        $this->session->set_userdata('top_menu', 'profile');
        $id = $this->patient_data['patient_id'];
        $data['payment_method'] = $this->payment_method;
        $data["id"] = $id;
        $data["marital_status"] = $this->marital_status;
        $data["payment_mode"] = $this->payment_mode;
        $data["bloodgroup"] = $this->blood_group;
        $data['organisation'] = $this->Organisation_model->get();

        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $result = array();
        $diagnosis_details = array();
        $opd_details = array();
        $timeline_list = array();
        $charges = array();
        if (!empty($id)) {
            $status = $this->patient_model->getStatus($id);

            $result = $this->patient_model->getIpdDetails($id, $status["is_active"]);
            $diagnosis_details = $this->patient_model->getDiagnosisDetails($id);
            $timeline_list = $this->timeline_model->getPatientTimeline($id, $timeline_status = 'yes');
            $prescription_details = $this->prescription_model->getPatientPrescription($id);
            $consultant_register = $this->patient_model->getPatientConsultant($id);
            $charges = $this->charge_model->getCharges($id);
            $paymentDetails = $this->payment_model->paymentDetails($id);
            $paid_amount = $this->payment_model->getPaidTotal($id);
            $data["paid_amount"] = $paid_amount["paid_amount"];
            $balance_amount = $this->payment_model->getBalanceTotal($id);
            $data["balance_amount"] = $balance_amount["balance_amount"];
            $data["payment_details"] = $paymentDetails;
            $data["consultant_register"] = $consultant_register;
            $data["result"] = $result;
            $data["diagnosis_detail"] = $diagnosis_details;
            $data["prescription_detail"] = $prescription_details;
            $data["opd_details"] = $opd_details;
            $data["timeline_list"] = $timeline_list;
            $data["charge_type"] = $this->charge_type;
            $data["charges"] = $charges;
        }
        $this->load->view("layout/patient/header");
        $this->load->view("patient/ipdProfile", $data);
        $this->load->view("layout/patient/footer");
    }

    public function ipdBill() {
        $id = $this->input->post("patient_id");
        $data['total_amount'] = $this->input->post("total_amount");
        $data['discount'] = $this->input->post("discount");
        $data['other_charge'] = $this->input->post("other_charge");
        $data['gross_total'] = $this->input->post("gross_total");
        $data['tax'] = $this->input->post("tax");
        $data['net_amount'] = $this->input->post("net_amount");

        $data["print_details"] = $this->printing_model->get('', 'ipd');

        $status = $this->patient_model->getStatus($id);
        $result = $this->patient_model->getIpdDetails($id, $status["is_active"]);

        $charges = $this->charge_model->getCharges($id);
        $paymentDetails = $this->payment_model->paymentDetails($id);
        $paid_amount = $this->payment_model->getPaidTotal($id);
        $balance_amount = $this->payment_model->getBalanceTotal($id);
        $data["paid_amount"] = $paid_amount["paid_amount"];
        $data["balance_amount"] = $balance_amount["balance_amount"];
        $data["payment_details"] = $paymentDetails;
        $data["charges"] = $charges;
        $data["result"] = $result;
        $this->load->view("patient/ipdBill", $data);
    }

    public function download_patient_timeline($timeline_id, $doc) {
        $this->load->helper('download');
        $filepath = "./uploads/staff_timeline/" . $doc;
        $data = file_get_contents($filepath);
        $name = $doc;
        force_download($name, $data);
    }

    public function report_download($doc) {
        $this->load->helper('download');
        $filepath = "./" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function getIpdDetails() {
        $id = $this->input->post("recordid");
        $active = $this->input->post("active");
        $result = $this->patient_model->getIpdDetails($id, $active);
        $result['date'] = date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date']));
        echo json_encode($result);
    }

    public function deleteappointment($id) {
        if (!empty($id)) {
            $this->appointment_model->delete($id);
            $json_array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        } else {
            $json_array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($json_array);
    }

}
?>