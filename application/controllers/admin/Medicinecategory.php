<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicinecategory extends Admin_Controller {

    public function medicine() {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $medicinecategoryid = $this->input->post("medicinecategoryid");
        $data["title"] = "Add Medicine Category";
        $medicineCategory = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->form_validation->set_rules(
                'medicine_category', 'Medicine Category', array('required',
            array('check_exists', array($this->medicine_category_model, 'valid_medicine_category'))
                )
        );
        if ($this->form_validation->run()) {
            $medicineCategory = $this->input->post("medicine_category");
            $medicinecategoryid = $this->input->post("id");
            if (empty($medicinecategoryid)) {
                if (!$this->rbac->hasPrivilege('medicine_category', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('medicine_category', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($medicinecategoryid)) {
                $data = array('medicine_category' => $medicineCategory, 'id' => $medicinecategoryid);
            } else {

                $data = array('medicine_category' => $medicineCategory);
            }

            $insert_id = $this->medicine_category_model->addMedicineCategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Record added Successfully</div>');
            redirect("admin/medicinecategory/medicine");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/medicine_category", $data);
            $this->load->view("layout/footer");
        }
    }

    public function add() {
        if ((!$this->rbac->hasPrivilege('medicine_category', 'can_add')) || (!$this->rbac->hasPrivilege('medicine_category', 'can_edit'))) {
            access_denied();
        }
        $medicinecategoryid = $this->input->post("medicinecategoryid");
        $this->form_validation->set_rules(
                'medicine_category', $this->lang->line('medicine') . " " . $this->lang->line('category'), array('required',
            array('check_exists', array($this->medicine_category_model, 'valid_medicine_category'))
                )
        );
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'name' => form_error('medicine_category'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $medicineCategory = $this->input->post("medicine_category");
            if (!empty($medicinecategoryid)) {
                $data = array('medicine_category' => $medicineCategory, 'id' => $medicinecategoryid);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $data = array('medicine_category' => $medicineCategory);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
            $insert_id = $this->medicine_category_model->addMedicineCategory($data);
        }
        echo json_encode($array);
    }

    public function get() { //get product data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->medicine_category_model->getall();
    }

    public function edit($id) {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getMedicineCategory($id);
        $data["result"] = $result;
        $data["title"] = "Edit Category";
        $medicineCategory = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->load->view("layout/header");
        $this->load->view("admin/pharmacy/medicine_category", $data);
        $this->load->view("layout/footer");
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_delete')) {
            access_denied();
        }
        $this->medicine_category_model->delete($id);
        redirect('admin/medicinecategory/medicine');
    }

    public function get_data($id) {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getMedicineCategory($id);
        echo json_encode($result);
    }

}

?>