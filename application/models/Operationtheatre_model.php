<?php
class Operationtheatre_model extends CI_Model {

    public function add_patient($patient_data) {
        $this->db->insert('patients', $patient_data);
        $Id = $this->db->insert_id();
        return $Id;
    }

    public function update_patient($patient_data) {
        $query = $this->db->where('id', $patient_data['id'])
                ->update('patients', $patient_data);
    }

    public function operation_detail($operation_detail) {
        $this->db->insert('operation_theatre', $operation_detail);
    }

    public function update_operation_detail($operation_detail) {
        $query = $this->db->where('id', $operation_detail['id'])
                ->update('operation_theatre', $operation_detail);
    }

    public function searchFullText() {
        $this->db->select('operation_theatre.*,patients.id as pid,patients.patient_unique_id,patients.patient_name,patients.gender,patients.mobileno,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.code,charges.description,charges.standard_charge')->from('operation_theatre');
        $this->db->join('patients', 'operation_theatre.patient_id=patients.id', "inner");
        $this->db->join('staff', 'staff.id = operation_theatre.consultant_doctor', "inner");
        $this->db->join('charges', 'operation_theatre.charge_id = charges.id');
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('operation_theatre.id', 'desc');
        $this->db->or_where('`operation_theatre`.`patient_id`=`patients`.`id`');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getDetails($id) {
        $this->db->select('operation_theatre.*,patients.id as pid,patients.patient_unique_id,patients.patient_name,patients.admission_date,patients.gender,patients.age,patients.month,patients.patient_type,patients.guardian_name,patients.mobileno,patients.guardian_address,patients.is_active,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.code,charges.standard_charge,charges.description,organisation.organisation_name')->from('operation_theatre');
        $this->db->join('patients', 'operation_theatre.patient_id=patients.id', "inner");
        $this->db->join('staff', 'staff.id = operation_theatre.consultant_doctor', "inner");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('charges', 'operation_theatre.charge_id = charges.id');
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('operation_theatre.patient_id', $id);
        $this->db->or_where('patients.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getopdipdDetails($id, $patient_type) {
        if ($patient_type == 'Inpatient') {
            $query = $this->db->where("patient_id", $id)->get("ipd_details");
            $result = $query->row_array();
            return $result['ipd_no'];
        }
    }

    public function getOtPatientDetails($otid) {
        $this->db->select('operation_theatre.*,patients.id as pid,patients.patient_unique_id,patients.patient_name,patients.admission_date,patients.gender,patients.age,patients.month,patients.patient_type,patients.guardian_name,patients.mobileno,patients.organisation,patients.guardian_address,patients.is_active,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.code,charges.standard_charge,charges.description')->from('operation_theatre');
        $this->db->join('patients', 'operation_theatre.patient_id=patients.id', "inner");
        $this->db->join('staff', 'staff.id = operation_theatre.consultant_doctor', "inner");
        $this->db->join('charges', 'operation_theatre.charge_id = charges.id');
        $this->db->where('operation_theatre.id', $otid);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function delete($id) {
        $query = $this->db->where('patient_id', $id)
                ->delete('operation_theatre');
        if ($query) {
            $this->db->where('id', $id)
                    ->delete('patients');
        }
    }

    public function add_ot_consultantInstruction($data) {
        $this->db->insert_batch("ot_consultant_register", $data);
    }

    public function getConsultantBatch($patient_id) {
        $this->db->select('ot_consultant_register.*,patients.id as pid,patients.patient_name,staff.name,staff.surname');
        $this->db->join('patients', 'ot_consultant_register.patient_id=patients.id', "inner");
        $this->db->join('staff', 'staff.id = ot_consultant_register.cons_doctor', "inner");
        $this->db->where('ot_consultant_register.patient_id', $patient_id);
        $this->db->order_by('ot_consultant_register.date');
        $query = $this->db->get('ot_consultant_register');

        return $query->result();
    }

    public function getChargeCategory() {
        $query = $this->db->select('charge_categories.*')
                ->where('charge_type', 'Operation Theatre')
                ->get('charge_categories');
        return $query->result_array();
    }

    public function deleteConsultant($id) {
        $this->db->where("id", $id)->delete("ot_consultant_register");
    }

}
?>