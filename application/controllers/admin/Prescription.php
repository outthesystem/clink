<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prescription extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->blood_group = $this->config->item('bloodgroup');
        $this->load->model('Printing_Model');
    }

    function getPrescription($id, $opdid) {
        $result = $this->prescription_model->get($id);
        $prescription_list = $this->prescription_model->getPrescriptionByOPD($opdid);
        $data["print_details"] = $this->Printing_Model->get('', 'opd');
        $data["result"] = $result;
        $data["id"] = $id;
        $data["opdid"] = $opdid;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data["prescription_list"] = $prescription_list;
        $this->load->view("admin/patient/prescription", $data);
    }

    function editPrescription($id, $opdid) {
        $result = $this->prescription_model->get($id);
        $prescription_list = $this->prescription_model->getPrescriptionByOPD($opdid);
        $data["result"] = $result;
        $data["id"] = $id;
        $data["opdid"] = $opdid;
        $data["prescription_list"] = $prescription_list;

        $this->load->view("admin/patient/edit_prescription", $data);
    }

    public function deletePrescription($id, $opdid) {
        if (!empty($opdid)) {
            $this->prescription_model->deletePrescription($opdid);
            $json = array('status' => 'success', 'error' => '', 'msg' => 'Record deleted');
            echo json_encode($json);
        }
    }

}
?>