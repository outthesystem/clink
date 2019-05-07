<?php

class Appointment_model extends CI_Model {

//========================================================================================
    function add($appointment) {
        $this->db->insert('appointment', $appointment);
    }

//=========================================================================================
    public function searchFullText() {
        $this->db->select('appointment.*,staff.name,staff.surname');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->where('`appointment`.`doctor`=`staff`.`id`');
        $this->db->order_by('`appointment`.`date`', 'desc');
        $query = $this->db->get('appointment');
        return $query->result_array();
    }

//==========================================================================================
    public function getDetails($id) {
        $this->db->select('appointment.*,staff.name,staff.surname');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->where('appointment.id', $id);
        $query = $this->db->get('appointment');
        return $query->row_array();
    }

//=========================================================================================
    public function update($appointment) {
        $query = $this->db->where('id', $appointment['id'])
                ->update('appointment', $appointment);
    }

//=========================================================================================
    public function delete($id) {
        $this->db->where("id", $id)->delete('appointment');
    }

//=========================================================================================
    public function getAppointment($id = null) {
        $query = $this->db->order_by('id', 'desc')->get('appointment');
        return $query->result_array();
    }

//=========================================================================================
    public function status($id, $data) {
        $this->db->where("id", $id)->update("appointment", $data);
    }

    public function getpatientDetails($id) {
        $query = $this->db->select('patients.*')
                ->join('opd_details', 'patients.id = opd_details.patient_id')
                ->where('patients.patient_unique_id', $id)
                ->get('patients');
        return $query->row_array();
    }

}
?>