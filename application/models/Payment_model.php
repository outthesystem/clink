<?php
class Payment_model extends CI_Model {

    public function addPayment($data) {
        $this->db->insert("payment", $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function deleteIpdPatientPayment($id) {
        $query = $this->db->where('id', $id)
                ->delete('payment');
    }

    public function paymentDetails($id) {
        $query = $this->db->select('payment.*,patients.id as pid,patients.note as pnote')
                ->join("patients", "patients.id = payment.patient_id")->where("payment.patient_id", $id)
                ->get("payment");
        return $query->result_array();
    }

    public function paymentByID($id) {
        $query = $this->db->select('payment.*,patients.id as pid,patients.note as pnote')
                ->join("patients", "patients.id = payment.patient_id")->where("payment.id", $id)
                ->get("payment");
        return $query->row();
    }

    public function getBalanceTotal($id) {
        $query = $this->db->select("IFNULL(sum(balance_amount),'0') as balance_amount")->where("payment.patient_id", $id)->get("payment");
        return $query->row_array();
    }

    public function getPaidTotal($id) {
        $query = $this->db->select("IFNULL(sum(paid_amount), '0') as paid_amount")->where("payment.patient_id", $id)->get("payment");
        return $query->row_array();
    }

    public function add_bill($data) {
        $this->db->insert("ipd_billing", $data);
    }

    public function revertBill($patient_id, $bill_id) {
        $this->db->where("id", $bill_id)->delete("ipd_billing");
    }

    public function valid_amount($amount) {
        if ($amount <= 0) {

            $this->form_validation->set_message('check_exists', 'The payment amount must be greater than 0');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
?>