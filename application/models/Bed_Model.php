<?php

Class Bed_Model extends CI_Model {

    public function bedcategorie($table_name) {
        $this->db->select('id, name');
        $this->db->from($table_name);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function valid_bed($str) {
        $name = $this->input->post('name');
        if ($this->check_floor_exists($name)) {
            $this->form_validation->set_message('check_exists', 'Bed already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_floor_exists($name) {
        $bedid = $this->input->post("bedid");
        if ($bedid != 0) {
            $data = array('name' => $name, 'id !=' => $bedid);
            $query = $this->db->where($data)->get('bed');

            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->where('name', $name);
            $query = $this->db->get('bed');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function bedNoType() {
        $query = $this->db->select('bed.*,bed_type.id as btid,bed_type.name as bed_type')
                ->join('bed_type', 'bed.bed_type_id = bed_type.id')
                ->where('bed.is_active', 'yes')
                ->get('bed');
        return $query->result_array();
    }

    public function bed_list($id = null) {
        $this->db->select('bed.*, bed_type.name as bed_type_name,floor.name as floor_name, bed_group.name as bedgroup,bed_group.id as bedgroupid,patients.id as pid,patients.patient_unique_id,patients.patient_name,patients.gender,patients.guardian_name,patients.mobileno,ipd_details.date,staff.name as staff,staff.surname')->from('bed');
        $this->db->join('bed_type', 'bed.bed_type_id = bed_type.id');
        $this->db->join('bed_group', 'bed.bed_group_id = bed_group.id');
        $this->db->join('floor', 'floor.id = bed_group.floor');
        $this->db->join('ipd_details', 'bed.id = ipd_details.bed', 'left');
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', 'left');
        $this->db->join('patients', 'patients.id = ipd_details.patient_id', 'left');
        $this->db->order_by('bed.id', 'asc');
        if ($id != null) {
            $this->db->where('bed.id', $id);
        } else {
            $this->db->order_by('bed.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function savebed($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("bed", $data);
        } else {
            $this->db->insert("bed", $data);
        }
    }

    public function getbedbybedgroup($bed_group, $active = '') {
        $this->db->select('bed.*, bed_type.name as bed_type_name,bed_group.name as bedgroup ')->from('bed');
        $this->db->join('bed_type', 'bed.bed_type_id = bed_type.id');
        $this->db->join('bed_group', 'bed.bed_group_id = bed_group.id');
        if (!empty($active)) {
            $this->db->where('bed.is_active', $active);
        }

        $this->db->where('bed.bed_group_id', $bed_group);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("bed");
    }

    public function getBedStatus() {
        $this->db->select('bed.*, bed_type.name as bed_type_name,bed_group.name as bedgroup,floor.name as floor_name')->from('bed');
        $this->db->join('bed_type', 'bed.bed_type_id = bed_type.id');
        $this->db->join('bed_group', 'bed.bed_group_id = bed_group.id');
        $this->db->join('floor', 'floor.id = bed_group.floor');
        $query = $this->db->get();
        return $query->result_array();
    }
}
