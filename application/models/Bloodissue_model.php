<?php

class Bloodissue_model extends CI_Model {

    public function add($bloodissue) {
        $this->db->insert('blood_issue', $bloodissue);
    }

    public function searchFullText() {
        $query = $this->db->order_by('id', 'desc')->get('blood_issue');
        return $query->result_array();
    }

    public function getDetails($id) {
        $this->db->select('blood_issue.*');
        $this->db->where('blood_issue.id', $id);
        $query = $this->db->get('blood_issue');
        return $query->row_array();
    }

    public function update($bloodissue) {
        $query = $this->db->where('id', $bloodissue['id'])
                ->update('blood_issue', $bloodissue);
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete('blood_issue');
    }

    public function getBloodIssue($id = null) {
        $query = $this->db->get('blood_issue');
        return $query->result_array();
    }

}
