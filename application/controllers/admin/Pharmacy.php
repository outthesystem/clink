<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pharmacy extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->config->load("payroll");
        $this->search_type = $this->config->item('search_type');
        $this->load->model("report_model");
        $this->load->model("Printing_model");
    }

    public function unauthorized() {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add() {
        if (!$this->rbac->hasPrivilege('medicine', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('medicine_name', $this->lang->line('medicine') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('medicine_category_id', $this->lang->line('medicine') . " " . $this->lang->line('category'), 'required');
        $this->form_validation->set_rules('medicine_company', $this->lang->line('medicine') . " " . $this->lang->line('company'), 'required');
        $this->form_validation->set_rules('medicine_composition', $this->lang->line('medicine') . " " . $this->lang->line('composition'), 'required');
        $this->form_validation->set_rules('medicine_group', $this->lang->line('medicine') . " " . $this->lang->line('group'), 'required');
        $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'required');
        $this->form_validation->set_rules('unit_packing', $this->lang->line('unit') . "/" . $this->lang->line('packing'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'medicine_name' => form_error('medicine_name'),
                'medicine_category_id' => form_error('medicine_category_id'),
                'medicine_company' => form_error('medicine_company'),
                'medicine_composition' => form_error('medicine_composition'),
                'medicine_group' => form_error('medicine_group'),
                'unit' => form_error('unit'),
                'unit_packing' => form_error('unit_packing')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $pharmacy = array('medicine_name' => $this->input->post('medicine_name'),
                'medicine_category_id' => $this->input->post('medicine_category_id'),
                'medicine_company' => $this->input->post('medicine_company'),
                'medicine_composition' => $this->input->post('medicine_composition'),
                'medicine_group' => $this->input->post('medicine_group'),
                'unit' => $this->input->post('unit'),
                'min_level' => $this->input->post('min_level'),
                'reorder_level' => $this->input->post('reorder_level'),
                'vat' => $this->input->post('vat'),
                'unit_packing' => $this->input->post('unit_packing'),
                'supplier' => $this->input->post('supplier'),
                'note' => $this->input->post('note'),
                'vat_ac' => $this->input->post('vat_ac')
            );
            $insert_id = $this->pharmacy_model->add($pharmacy);

            if (isset($_FILES["medicine_image"]) && !empty($_FILES['medicine_image']['name'])) {
                $fileInfo = pathinfo($_FILES["medicine_image"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["medicine_image"]["tmp_name"], "./uploads/medicine_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'medicine_image' => 'uploads/medicine_images/' . $img_name);
                $this->pharmacy_model->update($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function search() {
        if (!$this->rbac->hasPrivilege('medicine', 'can_view')) {
            access_denied();
        }

        $medicineCategory = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $resultlist = $this->pharmacy_model->searchFullText();
        $i = 0;
        foreach ($resultlist as $value) {
            $pharmacy_id = $value['id'];
            $available_qty = $this->pharmacy_model->totalQuantity($pharmacy_id);
            $totalAvailableQty = $available_qty['total_qty'];
            $resultlist[$i]["total_qty"] = $totalAvailableQty;
            $i++;
        }
        $result = $this->pharmacy_model->getPharmacy();
        $data['resultlist'] = $resultlist;
        $data['result'] = $result;
        $this->load->view('layout/header');
        $this->load->view('admin/pharmacy/search.php', $data);
        $this->load->view('layout/footer');
    }

    public function getDetails() {
        if (!$this->rbac->hasPrivilege('medicine', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("pharmacy_id");
        $result = $this->pharmacy_model->getDetails($id);
        echo json_encode($result);
    }

    public function update() {
        if (!$this->rbac->hasPrivilege('medicine', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('medicine_name', $this->lang->line('medicine') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('medicine_category_id', $this->lang->line('medicine') . " " . $this->lang->line('category') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('medicine_company', $this->lang->line('medicine') . " " . $this->lang->line('company'), 'required');
        $this->form_validation->set_rules('medicine_composition', $this->lang->line('medicine') . " " . $this->lang->line('composition'), 'required');
        $this->form_validation->set_rules('medicine_group', $this->lang->line('medicine') . " " . $this->lang->line('group'), 'required');
        $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'required');
        $this->form_validation->set_rules('unit_packing', $this->lang->line('unit') . "/" . $this->lang->line('packing'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'medicine_name' => form_error('medicine_name'),
                'medicine_category_id' => form_error('medicine_category_id'),
                'medicine_company' => form_error('medicine_company'),
                'medicine_composition' => form_error('medicine_composition'),
                'medicine_group' => form_error('medicine_group'),
                'unit' => form_error('unit'),
                'unit_packing' => form_error('unit_packing')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $pharmacy = array(
                'id' => $id,
                'medicine_name' => $this->input->post('medicine_name'),
                'medicine_category_id' => $this->input->post('medicine_category_id'),
                'medicine_company' => $this->input->post('medicine_company'),
                'medicine_composition' => $this->input->post('medicine_composition'),
                'medicine_group' => $this->input->post('medicine_group'),
                'unit' => $this->input->post('unit'),
                'min_level' => $this->input->post('min_level'),
                'reorder_level' => $this->input->post('reorder_level'),
                'vat' => $this->input->post('vat'),
                'unit_packing' => $this->input->post('unit_packing'),
                'supplier' => $this->input->post('supplier'),
                'vat_ac' => $this->input->post('vat_ac')
            );
            $this->pharmacy_model->update($pharmacy);
            if (isset($_FILES["medicine_image"]) && !empty($_FILES['medicine_image']['name'])) {
                $fileInfo = pathinfo($_FILES["medicine_image"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["medicine_image"]["tmp_name"], "./uploads/medicine_images/" . $img_name);
                $data_img = array('id' => $id, 'medicine_image' => 'uploads/medicine_images/' . $img_name);
                $this->pharmacy_model->update($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id) {
        if (!$this->rbac->hasPrivilege('medicine', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pharmacy_model->delete($id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getPharmacy() {
        if (!$this->rbac->hasPrivilege('medicine', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post('pharmacy_id');
        $result = $this->pharmacy_model->getPharmacy($id);
        echo json_encode($result);
    }

    public function medicineBatch() {
        if (!$this->rbac->hasPrivilege('medicine batch details', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('pharmacy_id', $this->lang->line('pharmacy') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('expiry_date', $this->lang->line('expiry') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('batch_no', $this->lang->line('batch') . " " . $this->lang->line('no'), 'required');
        $this->form_validation->set_rules('packing_qty', $this->lang->line('packing') . " " . $this->lang->line('qty'), 'required|numeric');
        $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'required|numeric');
        $this->form_validation->set_rules('mrp', $this->lang->line('mrp'), 'required|numeric');
        $this->form_validation->set_rules('sale_rate', $this->lang->line('sale') . " " . $this->lang->line('rate'), 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'pharmacy_id' => form_error('pharmacy_id'),
                'expiry_date' => form_error('expiry_date'),
                'batch_no' => form_error('batch_no'),
                'packing_qty' => form_error('packing_qty'),
                'quantity' => form_error('quantity'),
                'mrp' => form_error('mrp'),
                'sale_rate' => form_error('sale_rate'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('pharmacy_id');
            $inward_date = $this->input->post('inward_date');
            
            $medicine_batch = array(
                'pharmacy_id' => $id,
                'expiry_date' => $this->input->post('expiry_date'),
                'inward_date' => date('Y-m-d', $this->customlib->datetostrtotime($inward_date)),
                'batch_no' => $this->input->post('batch_no'),
                'packing_qty' => $this->input->post('packing_qty'),
                'purchase_rate_packing' => $this->input->post('purchase_rate_packing'),
                'quantity' => $this->input->post('quantity'),
                'mrp' => $this->input->post('mrp'),
                'sale_rate' => $this->input->post('sale_rate'),
                'amount' => $this->input->post('amount'),
                'available_quantity' => $this->input->post('quantity')
            );
            $this->pharmacy_model->medicineDetail($medicine_batch);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function getMedicineBatch() {
        if (!$this->rbac->hasPrivilege('add_medicine_stock', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("pharmacy_id");
        $result = $this->pharmacy_model->getMedicineBatch($id);
        $data["result"] = $result;
        $badstockresult = $this->pharmacy_model->getMedicineBadStock($id);
        $data["badstockresult"] = $badstockresult;

        $this->load->view('admin/pharmacy/medicineDetail', $data);
    }

    public function bill() {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'pharmacy');
        $data['resultlist'] = $this->pharmacy_model->getBillBasic();
        $data['medicineCategory'] = $this->medicine_category_model->getMedicineCategory();
        $data['medicineName'] = $this->pharmacy_model->getMedicineName();
        $this->load->view('layout/header');
        $this->load->view('admin/pharmacy/pharmacyBill.php', $data);
        $this->load->view('layout/footer');
    }

    public function get_medicine_name() {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $medicine_category_id = $this->input->post("medicine_category_id");
        $data = $this->pharmacy_model->get_medicine_name($medicine_category_id);
        echo json_encode($data);
    }

    public function addBill() {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('bill_no', $this->lang->line('bill') . " " . $this->lang->line('no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('customer_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('medicine_category_id[]', $this->lang->line('medicine') . " " . $this->lang->line('category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('medicine_name[]', $this->lang->line('medicine') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expire_date[]', $this->lang->line('expiry') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('batch_no[]', $this->lang->line('batch') . " " . $this->lang->line('no'), 'required');
        $this->form_validation->set_rules('quantity[]', $this->lang->line('quantity'), 'required|numeric');
        $this->form_validation->set_rules('sale_price[]', $this->lang->line('sale_price'), 'required|numeric');
        $this->form_validation->set_rules('amount[]', $this->lang->line('amount'), 'required|numeric');
        $this->form_validation->set_rules('total', $this->lang->line('total'), 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'bill_no' => form_error('bill_no'),
                'date' => form_error('date'),
                'customer_name' => form_error('customer_name'),
                'medicine_category_id' => form_error('medicine_category_id[]'),
                'medicine_name' => form_error('medicine_name[]'),
                'batch_no' => form_error('batch_no[]'),
                'expire_date' => form_error('expire_date[]'),
                'quantity' => form_error('quantity[]'),
                'sale_price' => form_error('sale_price[]'),
                'total' => form_error('total'),
                'amount' => form_error('amount[]')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $patient_id = $this->input->post('patient_id');
            $bill_date = $this->input->post("date");

            $data = array(
                'bill_no' => $this->input->post('bill_no'),
                'date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($bill_date)),
                'patient_id' => $patient_id,
                'customer_name' => $this->input->post('customer_name'),
                'customer_type' => $this->input->post('customer_type'),
                'doctor_name' => $this->input->post('doctor_name'),
                'opd_ipd_no' => $this->input->post('opd_ipd_no'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'tax' => $this->input->post('tax'),
                'net_amount' => $this->input->post('net_amount'),
                'note' => $this->input->post('note')
            );

            $bill_id = $this->pharmacy_model->addBill($data);
            if ($bill_id) {
                $pharmacy_bill_basic_id = $bill_id;
                $medicine_category_id = $this->input->post('medicine_category_id');
                $medicine_name = $this->input->post('medicine_name');
                $expire_date = $this->input->post('expire_date');
                $batch_no = $this->input->post('batch_no');
                $quantity = $this->input->post('quantity');
                $total_quantity = $this->input->post('available_quantity');
                $medicine_batch_details_id = $this->input->post('id');
                $sale_price = $this->input->post('sale_price');
                $amount = $this->input->post('amount');
                $data = array();
                $i = 0;
                foreach ($medicine_category_id as $key => $value) {
                    $detail = array(
                        'pharmacy_bill_basic_id' => $bill_id,
                        'medicine_category_id' => $medicine_category_id[$i],
                        'medicine_name' => $medicine_name[$i],
                        'expire_date' => $expire_date[$i],
                        'batch_no' => $batch_no[$i],
                        'quantity' => $quantity[$i],
                        'sale_price' => $sale_price[$i],
                        'amount' => $amount[$i],
                    );
                    $available_quantity[$i] = $total_quantity[$i] - $quantity[$i];
                    $update_quantity = array(
                        'id' => $medicine_batch_details_id[$i],
                        'available_quantity' => $available_quantity[$i],
                    );
                    $data[] = $detail;
                    $this->pharmacy_model->availableQty($update_quantity);
                    $i++;
                }
                $this->pharmacy_model->addBillBatch($data);
            } else {
                
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'insert_id' => $bill_id);
        }
        echo json_encode($array);
    }

    public function getBillDetails($id) {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_view')) {
            access_denied();
        }
        $data['id'] = $id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['print_details'] = $this->Printing_model->get('', 'pharmacy');
        $result = $this->pharmacy_model->getBillDetails($id);
        $data['result'] = $result;
        $detail = $this->pharmacy_model->getAllBillDetails($id);
        $data['detail'] = $detail;
        $this->load->view('admin/pharmacy/printBill', $data);
        
    }

    public function getQuantity() {
        if (!$this->rbac->hasPrivilege('medicine batch details', 'can_view')) {
            access_denied();
        }
        $batch_no = $this->input->get('batch_no');
        $data = $this->pharmacy_model->getQuantity($batch_no);
        echo json_encode($data);
    }

    public function billReport() {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/pharmacy/billreport');
        $select = 'pharmacy_bill_basic.*';
        $table_name = "pharmacy_bill_basic";
        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }
        if (empty($search_type)) {
            
            $search_type = "";
            $resultlist = $this->report_model->getReport($select, $join = array(), $table_name, $where = array());
        } else {
           
            $search_table = "pharmacy_bill_basic";
            $search_column = "date";
            $resultlist = $this->report_model->searchReport($select, $join = array(), $table_name, $search_type, $search_table, $search_column, $where = array());
        }
        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $data["resultlist"] = $resultlist;
        $this->load->view('layout/header');
        $this->load->view('admin/pharmacy/billReport.php', $data);
        $this->load->view('layout/footer');
    }

    public function editPharmacyBill($id) {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_view')) {
            access_denied();
        }
        $medicineCategory = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $medicine_category_id = $this->input->post("medicine_category_id");
        $data['medicine_category_id'] = $this->pharmacy_model->get_medicine_name($medicine_category_id);
        $data['medicine_category_id'] = $medicine_category_id;
        $result = $this->pharmacy_model->getBillDetails($id);
        $data['result'] = $result;
        $detail = $this->pharmacy_model->getAllBillDetails($id);
        $data['detail'] = $detail;
        $this->load->view("admin/pharmacy/editPharmacyBill", $data);
    }

    public function updateBill() {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('bill_no', $this->lang->line('bill') . " " . $this->lang->line('no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('customer_name', $this->lang->line('patient') . " " . $this->lang->line('name'), 'trim|required');
        $this->form_validation->set_rules('medicine_category_id[]', $this->lang->line('medicine') . " " . $this->lang->line('category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('medicine_name[]', $this->lang->line('medicine') . " " . $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expire_date[]', $this->lang->line('expiry') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('batch_no[]', $this->lang->line('batch') . " " . $this->lang->line('no'), 'required');
        $this->form_validation->set_rules('quantity[]', $this->lang->line('quantity'), 'required|numeric');
        $this->form_validation->set_rules('sale_price[]', $this->lang->line('sale_price'), 'required|numeric');
        $this->form_validation->set_rules('total', $this->lang->line('total'), 'required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'bill_no' => form_error('bill_no'),
                'date' => form_error('date'),
                'customer_name' => form_error('customer_name'),
                'medicine_category_id' => form_error('medicine_category_id[]'),
                'medicine_name' => form_error('medicine_name[]'),
                'expire_date' => form_error('expire_date[]'),
                'batch_no' => form_error('batch_no[]'),
                'quantity' => form_error('quantity[]'),
                'sale_price' => form_error('sale_price[]'),
                'total' => form_error('total')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('bill_basic_id');
            $bill_id = $this->input->post("bill_detail_id[]");
            $previous_bill_id = $this->input->post("previous_bill_id[]");

            $data_array = array();
            $delete_arr = array();
            foreach ($previous_bill_id as $pkey => $pvalue) {
                if (in_array($pvalue, $bill_id)) {
                    
                } else {
                    $delete_arr[] = array('id' => $pvalue,);
                }
            }
            $bill_date = $this->input->post("date");
            $data = array(
                'id' => $id,
                'bill_no' => $this->input->post('bill_no'),
                'date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($bill_date)),
                'customer_name' => $this->input->post('customer_name'),
                'customer_type' => $this->input->post('customer_type'),
                'doctor_name' => $this->input->post('doctor_name'),
                'opd_ipd_no' => $this->input->post('opd_ipd_no'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'tax' => $this->input->post('tax'),
                'net_amount' => $this->input->post('net_amount'),
            );
            $this->pharmacy_model->addBill($data);
            if (!empty($id)) {
                $pharmacy_bill_basic_id = $id;
                $bill_detail_id = $this->input->post('bill_detail_id');
                $medicine_batch_id = $this->input->post('medicine_batch_id');
                $medicine_category_id = $this->input->post('medicine_category_id');
                $medicine_name = $this->input->post('medicine_name');
                $expire_date = $this->input->post('expire_date');
                $batch_no = $this->input->post('batch_no');
                $quantity = $this->input->post('quantity');
                $total_quantity = $this->input->post('available_quantity');
                $amount = $this->input->post('amount');
                $sale_price = $this->input->post('sale_price');
                $data = array();
                $i = 0;
                foreach ($medicine_category_id as $key => $value) {
                    if ($bill_id[$i] == 0) {
                        $add_data = array(
                            'pharmacy_bill_basic_id' => $id,
                            'medicine_category_id' => $medicine_category_id[$i],
                            'medicine_name' => $medicine_name[$i],
                            'expire_date' => $expire_date[$i],
                            'batch_no' => $batch_no[$i],
                            'quantity' => $quantity[$i],
                            'sale_price' => $sale_price[$i],
                            'amount' => $amount[$i],
                        );
                        $data_array[] = $add_data;
                        $available_quantity[$i] = $total_quantity[$i] - $quantity[$i];
                        $add_quantity = array(
                            'id' => $medicine_batch_id[$i],
                            'available_quantity' => $available_quantity[$i],
                        );
                        $this->pharmacy_model->availableQty($add_quantity);
                    } else {
                        $detail = array(
                            'id' => $bill_detail_id[$i],
                            'pharmacy_bill_basic_id' => $id,
                            'medicine_category_id' => $medicine_category_id[$i],
                            'medicine_name' => $medicine_name[$i],
                            'expire_date' => $expire_date[$i],
                            'batch_no' => $batch_no[$i],
                            'quantity' => $quantity[$i],
                            'sale_price' => $sale_price[$i],
                            'amount' => $amount[$i],
                        );
                        $this->pharmacy_model->updateBillDetail($detail);
                        $available_quantity[$i] = $total_quantity[$i] - $quantity[$i];
                        $update_quantity = array(
                            'id' => $medicine_batch_id[$i],
                            'available_quantity' => $available_quantity[$i],
                        );
                        $this->pharmacy_model->availableQty($update_quantity);
                    }
                    $i++;
                }
            } else {
                
            }
            if (!empty($data_array)) {
                $this->pharmacy_model->addBillBatch($data_array);
            }
            if (!empty($delete_arr)) {
                $this->pharmacy_model->delete_bill_detail($delete_arr);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deletePharmacyBill($id) {
        if (!$this->rbac->hasPrivilege('pharmacy bill', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pharmacy_model->deletePharmacyBill($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function delete_medicine_batch($id) {
        if (!$this->rbac->hasPrivilege('medicine batch details', 'can_view')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pharmacy_model->delete_medicine_batch($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    public function getBillNo() {
        $result = $this->pharmacy_model->getBillNo();
     
        $id = $result["id"];
        if (!empty($result["id"])) {
            $bill_no = $id + 1;
        } else {
            $bill_no = 1;
        }
        echo json_encode($bill_no);
    }

    public function getExpiryDate() {
        $batch_no = $this->input->get_post('batch_no');
        $result = $this->pharmacy_model->getExpiryDate($batch_no);
       
        echo json_encode($result);
    }

    public function getBatchNoList() {
        $medicine = $this->input->get_post('medicine');
        $result = $this->pharmacy_model->getBatchNoList($medicine);
        echo json_encode($result);
    }

    public function addBadStock() {
        if (!$this->rbac->hasPrivilege('medicine batch details', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('pharmacy_id', $this->lang->line('pharmacy') . " " . $this->lang->line('id'), 'required');
        $this->form_validation->set_rules('expiry_date', $this->lang->line('expiry') . " " . $this->lang->line('date'), 'required');
        $this->form_validation->set_rules('batch_no', $this->lang->line('batch') . " " . $this->lang->line('no'), 'required');
        $this->form_validation->set_rules('packing_qty', $this->lang->line('packing') . " " . $this->lang->line('qty'), 'required|numeric');
       

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'pharmacy_id' => form_error('pharmacy_id'),
                'expiry_date' => form_error('expiry_date'),
                'batch_no' => form_error('batch_no'),
                'packing_qty' => form_error('packing_qty'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('pharmacy_id');
            $inward_date = $this->input->post('inward_date');
           
            $medicine_batch = array(
                'pharmacy_id' => $id,
                'expiry_date' => $this->input->post('expiry_date'),
                'outward_date' => date('Y-m-d', $this->customlib->datetostrtotime($inward_date)),
                'batch_no' => $this->input->post('batch_no'),
                'quantity' => $this->input->post('packing_qty'),
                'note' => $this->input->post('note'),
            );
            $batch_qty = $this->input->post('available_quantity');
            $packing_qty = $this->input->post('packing_qty');
            $available_quantity = $batch_qty - $packing_qty;
            $update_data = array('id' => $this->input->post('medicine_batch_id'), 'available_quantity' => $available_quantity);

            $this->pharmacy_model->addBadStock($medicine_batch);
            $this->pharmacy_model->updateMedicineBatch($update_data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function deleteBadStock($id) {
        if (!$this->rbac->hasPrivilege('medicine batch details', 'can_view')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->pharmacy_model->deleteBadStock($id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Deleted Successfully.');
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

}
?>