<?php
class Prescription extends Patient_Controller {

    function __construct() {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('Customlib');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->blood_group = $this->config->item('bloodgroup');
        $this->load->model('Printing_Model');
    }

    function getPrescription($id, $opdid) {
        $result = $this->prescription_model->get($id);
        if (!empty($result)) {
            $prescription_list = $this->prescription_model->getPrescriptionByOPD($opdid);
            $data["result"] = $result;
            $data["id"] = $id;
            $data["opdid"] = $opdid;
            $data["prescription_list"] = $prescription_list;
            $data["print_details"] = $this->Printing_Model->get('', 'opd');
            $this->load->view("patient/prescription", $data);
        } else {
            echo "No Record Found";
        }
    }

}
?>