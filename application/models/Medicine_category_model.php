<?php
class Medicine_category_model extends CI_model {

    public function valid_medicine_category($str) {
        $medicine_category = $this->input->post('medicine_category');
        $id = $this->input->post('medicinecategoryid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_category_exists($medicine_category, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getMedicineCategory($id = null) {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('medicine_category');
            return $query->row_array();
        } else {
            $query = $this->db->get("medicine_category");
            return $query->result_array();
        }
    }

    public function check_category_exists($name, $id) {
        if ($id != 0) {
            $data = array('id != ' => $id, 'medicine_category' => $name);
            $query = $this->db->where($data)->get('medicine_category');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->db->where('medicine_category', $name);
            $query = $this->db->get('medicine_category');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function addMedicineCategory($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('medicine_category', $data);
        } else {
            $this->db->insert('medicine_category', $data);
            return $this->db->insert_id();
        }
    }

    public function getall() {
        $this->datatables->select('id,medicine_category');
        $this->datatables->from('medicine_category');
        $this->datatables->add_column('view', '<a href="' . site_url('admin/medicinecategory/edit/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"> <i class="fa fa-pencil"></i></a><a href="' . site_url('admin/medicinecategory/delete/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>', 'id');
        return $this->datatables->generate();
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete("medicine_category");
    }
}
?>