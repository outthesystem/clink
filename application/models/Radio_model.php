<?php
class Radio_model extends CI_Model {

    public function add($radiology) {
        $this->db->insert('radio', $radiology);
    }

    public function searchFullText() {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name,charges.standard_charge');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('`radio`.`radiology_category_id`=`lab`.`id`');
        $this->db->order_by('lab.id', 'desc');
        $query = $this->db->get('radio');
        return $query->result_array();
    }

    public function getDetails($id) {
        $this->db->select('radio.*,lab.id as category_id,lab.lab_name, charges.id as charge_id, charges.code, charges.charge_category, charges.standard_charge, charges.description');
        $this->db->join('lab', 'radio.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'radio.charge_id = charges.id', 'left');
        $this->db->where('radio.id', $id);
        $query = $this->db->get('radio');
        return $query->row_array();
    }

    public function update($radiology) {
        $query = $this->db->where('id', $radiology['id'])
                ->update('radio', $radiology);
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete('radio');
    }

    public function getRadiology($id = null) {
        if (!empty($id)) {
            $this->db->where("radio.id", $id);
        }
        $query = $this->db->select('radio.*,charges.charge_category,charges.code,charges.standard_charge')->join('charges', 'radio.charge_id = charges.id')->order_by('radio.id', 'desc')->get('radio');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function testReportBatch($report_batch) {
        $this->db->insert('radiology_report', $report_batch);
    }

    public function getRadiologyReport($id) {
        $query = $this->db->select('radiology_report.*,radio.id as pid,radio.charge_id as cid,staff.name,staff.surname,charges.charge_category,charges.code,charges.standard_charge')
                ->join('radio', 'radiology_report.radiology_id = radio.id')
                ->join('charges', 'radio.charge_id = charges.id')
                ->join('staff', 'staff.id = radiology_report.consultant_doctor', "inner")
                ->where("radiology_report.id", $id)
                ->get('radiology_report');
        return $query->row_array();
    }

    public function updateTestReport($report_batch) {
        $this->db->where('id', $report_batch['id'])->update('radiology_report', $report_batch);
    }

    public function getTestReportBatch($radiology_id) {
        $this->db->select('radiology_report.*, radio.id as rid,radio.test_name, radio.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('radio', 'radiology_report.radiology_id = radio.id', 'inner');
        $this->db->join('staff', 'staff.id = radiology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = radio.charge_id');
        $this->db->order_by('radio.id', 'desc');
        $query = $this->db->get('radiology_report');
        return $query->result();
    }

    public function deleteTestReport($id) {
        $query = $this->db->where('id', $id)
                ->delete('radiology_report');
    }

    public function getChargeCategory() {
        $query = $this->db->select('charge_categories.*')
                ->where('charge_type', 'investigations')
                ->get('charge_categories');
        return $query->result_array();
    }

}
?>