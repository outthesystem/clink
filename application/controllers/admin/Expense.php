<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('Customlib');
        $this->config->load("payroll");
        $this->load->model("report_model");
        $this->search_type = $this->config->item('search_type');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('expense', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'finance');
        $this->session->set_userdata('sub_menu', 'expense/index');
        $data['title'] = 'Add Expense';
        $data['title_list'] = 'Recent Expenses';
        $this->form_validation->set_rules('exp_head_id', 'Expense Head', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $data = array(
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name' => $this->input->post('name'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'amount' => $this->input->post('amount'),
                'invoice_no' => $this->input->post('invoice_no'),
                'note' => $this->input->post('description'),
                'documents' => $this->input->post('documents')
            );
            $insert_id = $this->expense_model->add($data);
            if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/hospital_expense/" . $img_name);
                $data_img = array('id' => $insert_id, 'documents' => 'uploads/hospital_expense/' . $img_name);
                $this->expense_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Expense added successfully</div>');
            redirect('admin/expense/index');
        }
        $expense_result = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseList', $data);
        $this->load->view('layout/footer', $data);
    }

    function add() {
        $data['title'] = 'Add Expense';
        $data['title_list'] = 'Recent Expenses';
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exdate', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'exp_head_id' => form_error('exp_head_id'),
                'name' => form_error('name'),
                'date' => form_error('date'),
                'amount' => form_error('amount'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array(
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name' => $this->input->post('name'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('exdate'))),
                'amount' => $this->input->post('amount'),
                'invoice_no' => $this->input->post('invoice_no'),
                'note' => $this->input->post('description'),
                'documents' => $this->input->post('documents')
            );
            $insert_id = $this->expense_model->add($data);
            if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/hospital_expense/" . $img_name);
                $data_img = array('id' => $insert_id, 'documents' => 'uploads/hospital_expense/' . $img_name);
                $this->expense_model->add($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function download($documents) {
        $this->load->helper('download');
        $filepath = "./uploads/hospital_expense/" . $this->uri->segment(6);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(6);
        force_download($name, $data);
    }

    function view($id) {
        if (!$this->rbac->hasPrivilege('expense', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $expense = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $this->load->view('layout/header', $data);
        $this->load->view('expense/expenseShow', $data);
        $this->load->view('layout/footer', $data);
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('expense', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->expense_model->remove($id);
        redirect('admin/expense/index');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('expense', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('expense', 'Fees Master', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('expense/expenseCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'expense' => $this->input->post('expense'),
            );
            $this->expense_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Expense added successfully</div>');
            redirect('expense/index');
        }
    }

    function getDataByid($id) {

        $data['title'] = 'Edit Fees Master';
        $data['id'] = $id;
        $expense = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $data['title_list'] = 'Fees Master List';
        $expnseHead = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;

        $this->load->view('admin/expense/editexpenseModal', $data);
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('expense', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Fees Master';
        $data['id'] = $id;
        $expense = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $data['title_list'] = 'Fees Master List';
        $expense_result = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'exp_head_id' => form_error('exp_head_id'),
                'amount' => form_error('amount'),
                'name' => form_error('name'),
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $exdate = $this->input->post('date');

            $data = array(
                'id' => $id,
                'exp_head_id' => $this->input->post('exp_head_id'),
                'name' => $this->input->post('name'),
                'invoice_no' => $this->input->post('invoice_no'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($exdate)),
                'amount' => $this->input->post('amount'),
                'note' => $this->input->post('description'),
            );
            $insert_id = $this->expense_model->add($data);
            if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
                $fileInfo = pathinfo($_FILES["documents"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["documents"]["tmp_name"], "./uploads/hospital_expense/" . $img_name);
                $data_img = array('id' => $id, 'documents' => 'uploads/hospital_expense/' . $img_name);
                $this->expense_model->add($data_img);
            }
            $array = array('status' => 'success', 'error' => '', 'message' => "Expense Edit Successfully");
        }
        echo json_encode($array);
    }

    function expenseSearch() {
        if (!$this->rbac->hasPrivilege('search_expense', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/expense/expensesearch');
        $data['title'] = 'Search Expense';
        $select = 'expenses.id,expenses.date,expenses.invoice_no,expenses.name,expenses.amount,expenses.documents,expenses.note,expense_head.exp_category,expenses.exp_head_id';
        $join = array('JOIN expense_head ON expenses.exp_head_id = expense_head.id');
        $table_name = "expenses";


        $search_type = $this->input->post("search_type");
        if (isset($search_type)) {
            $search_type = $this->input->post("search_type");
        } else {
            $search_type = "this_month";
        }

        if (empty($search_type)) {

            $search_type = "";
            $listMessage = $this->report_model->getReport($select, $join, $table_name);
        } else {

            $search_table = "expenses";
            $search_column = "date";
            $additional = array();
            $additional_where = array();
            $listMessage = $this->report_model->searchReport($select, $join, $table_name, $search_type, $search_table, $search_column);
        }
        $data['resultList'] = $listMessage;
        $data["searchlist"] = $this->search_type;
        $data["search_type"] = $search_type;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseSearch', $data);
        $this->load->view('layout/footer', $data);
    }

}

?>