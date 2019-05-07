<?php
class Pathology_model extends CI_Model {

    public function add($pathology) {
        $this->db->insert('pathology', $pathology);
    }

    public function searchFullText() {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name,charges.standard_charge');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->where('`pathology`.`pathology_category_id`=`pathology_category`.`id`');
        $this->db->order_by('pathology_category.id', 'desc');
        $query = $this->db->get('pathology');
        return $query->result_array();
    }

    public function getDetails($id) {
        $this->db->select('pathology.*,pathology_category.id as category_id,pathology_category.category_name, charges.id as charge_id, charges.code, charges.charge_category, charges.standard_charge, charges.description');
        $this->db->join('pathology_category', 'pathology.pathology_category_id = pathology_category.id', 'left');
        $this->db->join('charges', 'pathology.charge_id = charges.id', 'left');
        $this->db->where('pathology.id', $id);
        $this->db->order_by('pathology.id', 'desc');
        $query = $this->db->get('pathology');
        return $query->row_array();
    }

    public function update($pathology) {
        $query = $this->db->where('id', $pathology['id'])
                ->update('pathology', $pathology);
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete('pathology');
    }

    public function getPathology($id = null) {
        if (!empty($id)) {
            $this->db->where("pathology.id", $id);
        }
        $query = $this->db->select('pathology.*,charges.charge_category,charges.code,charges.standard_charge')->join('charges', 'pathology.charge_id = charges.id')->get('pathology');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getPathologyReport($id) {
        $query = $this->db->select('pathology_report.*,pathology.id as pid,pathology.charge_id as cid,staff.name,staff.surname,charges.charge_category,charges.code,charges.standard_charge')
                ->join('pathology', 'pathology_report.pathology_id = pathology.id')
                ->join('charges', 'pathology.charge_id = charges.id')
                ->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner")
                ->where("pathology_report.id", $id)
                ->get('pathology_report');
        return $query->row_array();
    }

    public function testReportBatch($report_batch) {
        $this->db->insert('pathology_report', $report_batch);
    }

    public function updateTestReport($report_batch) {
        $this->db->where('id', $report_batch['id'])->update('pathology_report', $report_batch);
    }

    public function getTestReportBatch($pathology_id) {
        $this->db->select('pathology_report.*, pathology.id as pid,pathology.test_name,pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->order_by('pathology.id', 'desc');
        $query = $this->db->get('pathology_report');
        return $query->result();
    }

    public function deleteTestReport($id) {
        $query = $this->db->where('id', $id)
                ->delete('pathology_report');
    }

    public function getChargeCategory() {
        $query = $this->db->select('charge_categories.*')
                ->where('charge_type', 'investigations')
                ->get('charge_categories');
        return $query->result_array();
    }

    public function pathologyReport() {
        $this->db->select('pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $query = $this->db->get('pathology_report');
        return $query->result_array();
    }

    public function searchPathologyReport($date_from, $date_to) {
        $this->db->select('pathology_report.*, pathology.id, pathology.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category,charges.standard_charge');
        $this->db->join('pathology', 'pathology_report.pathology_id = pathology.id', 'inner');
        $this->db->join('staff', 'staff.id = pathology_report.consultant_doctor', "inner");
        $this->db->join('charges', 'charges.id = pathology.charge_id');
        $this->db->where('pathology_report.reporting_date >=', $date_from);
        $this->db->where('pathology_report.reporting_date <=', $date_to);
        $query = $this->db->get("pathology_report");
        return $query->result_array();
    }

}
?>