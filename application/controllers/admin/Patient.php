<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class patient extends Admin_Controller {
  function __construct() {
    parent::__construct();
      $this->config->load("payroll");
      $this->load->library('Enc_lib');
      $this->load->library('mailsmsconf');
      $this->marital_status = $this->config->item('marital_status');
      $this->payment_mode = $this->config->item('payment_mode');
      $this->search_type = $this->config->item('search_type');
      $this->blood_group = $this->config->item('bloodgroup');    
      $this->load->model("bed_model");
      $this->load->model("payment_model");
      $this->load->model("report_model");
      $this->load->model("bedgroup_model");
      $this->load->model("floor_model");
      $this->load->model("printing_model");
      $this->charge_type = $this->config->item('charge_type');
      $data["charge_type"] = $this->charge_type;
      $this->patient_login_prefix = "pat";
  }
  public function unauthorized(){
    $data = array();
    $this->load->view('layout/header', $data);
    $this->load->view('unauthorized', $data);
    $this->load->view('layout/footer', $data);
  }
  public function getPatientType(){
    $opd_ipd_patient_type = $this->input->post('opd_ipd_patient_type');
    $opd_ipd_no = $this->input->post('opd_ipd_no');
    if($opd_ipd_patient_type == 'opd'){
      if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
        }
        $result = $this->patient_model->getOpdPatient($opd_ipd_no);
    }elseif ($opd_ipd_patient_type == 'ipd'){
      if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
      }
      $result = $this->patient_model->getIpdPatient($opd_ipd_no );
    }
    echo json_encode($result);
  } 
  public function add_revisit(){ 
    if (!$this->rbac->hasPrivilege('revisit', 'can_add')){
                    access_denied();
    }
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('appointment_date', $this->lang->line('appointment')." ".$this->lang->line('date'),'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
          $msg = array(
           'firstname' => form_error('name'), 
           'appointment_date' => form_error('appointment_date'),
           'amount' => form_error('amount'),        
          );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
         $check_patient_id = $this->patient_model->getMaxOPDId();
          if(empty($check_patient_id)){
            $check_patient_id = 0 ;
          }
          
         $opdn_id = $check_patient_id+1;  


        $patient_id = $this->input->post('id');
          $patient_data = array(
            'id' => $this->input->post('id'),
            'patient_name' => $this->input->post('name'),
            'mobileno' => $this->input->post('contact'),         
            'email' => $this->input->post('email'),
            'gender' => $this->input->post('gender'),
            'marital_status' => $this->input->post('marital_status'),
            'guardian_name' => $this->input->post('guardian_name'),
            'blood_group' => $this->input->post('blood_group'),
            'address' => $this->input->post('address'),
            'age' => $this->input->post('age'),
            'month' => $this->input->post('month'),
            'old_patient' => $this->input->post('old_patient'),
          );
          $this->patient_model->add($patient_data);
          $appointment_date = $this->input->post('appointment_date');
          $opd_data = array(
            'patient_id' => $this->input->post('id'),
            'appointment_date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($appointment_date)),
            'opd_no' => 'OPDN'.$opdn_id,
            'height' => $this->input->post('height'),
            'weight' => $this->input->post('weight'),
            'bp' => $this->input->post('bp'),
            'case_type' => $this->input->post('revisit_case'),
            'symptoms' => $this->input->post('symptoms'),
            'known_allergies' => $this->input->post('known_allergies'),
            'refference' => $this->input->post('refference'),
            'cons_doctor' => $this->input->post('consultant_doctor'),
            'amount' => $this->input->post('amount'),
            'casualty' => $this->input->post('casualty'),
            'payment_mode' => $this->input->post('payment_mode'),
            'note_remark' => $this->input->post('note_remark'),
          ); 
      $opd_id = $this->patient_model->add_opd($opd_data);
        $sender_details = array('patient_id' => $patient_id,'opd_no' =>'OPDN'.$opdn_id, 'contact_no' => $this->input->post('contact'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('opd_patient_registration', $sender_details);
      $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
    }
    echo json_encode($array);    
  }
  public function getPatientId(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
    $result = $this->patient_model->getPatientId();
    $data["result"] = $result ;
    echo json_encode($result);
  }
  public function index() {
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_add')){
                    access_denied();
    }
    $patient_type = $this->customlib->getPatienttype();
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('appointment_date', $this->lang->line('appointment')." ".$this->lang->line('date'),'trim|required|xss_clean');
    $this->form_validation->set_rules('consultant_doctor',$this->lang->line('consultant')." ".$this->lang->line('doctor'),'trim|required|xss_clean');
     $this->form_validation->set_rules('amount',$this->lang->line('amount'),'trim|required|xss_clean');
    
      if ($this->form_validation->run() == FALSE){
        $msg = array(
          'name' => form_error('name'), 
          'appointment_date' => form_error('appointment_date'),
          'consultant_doctor' => form_error('consultant_doctor'), 
           'amount' => form_error('amount'),         
           );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      }else{
       $check_patient_id = $this->patient_model->getMaxId();
       $check_opd_id = $this->patient_model->getMaxOPDId();
          if(empty($check_patient_id)){
            $check_patient_id = 1000 ;
            $check_opd_id = 0 ;
          }
          
         $patient_id = $check_patient_id+1;  
         $opdnoid = $check_opd_id+1;  
        
            $patient_data = array(
                'patient_name' => $this->input->post('name'),
                'mobileno' => $this->input->post('contact'),  
                'marital_status' => $this->input->post('marital_status'),       
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'blood_group' => $this->input->post('blood_group'),
                'address' => $this->input->post('address'),
                'patient_unique_id' => $patient_id,
                'note' => $this->input->post('note'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'is_active' => 'yes',
                'patient_type'=>$patient_type['outpatient'],
                'old_patient' => $this->input->post('old_patient'),
                'organisation' => $this->input->post('organisation'),
            );
          $insert_id=$this->patient_model->add($patient_data);
          $appointment_date = $this->input->post('appointment_date');
          $opd_data = array(
            'appointment_date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($appointment_date)),
            'case_type' => $this->input->post('case'),
            'opd_no'=>'OPDN'.$opdnoid ,
            'symptoms' => $this->input->post('symptoms'),
            'known_allergies' => $this->input->post('known_allergies'),
            'refference' => $this->input->post('refference'),
            'cons_doctor' => $this->input->post('consultant_doctor'),
            'amount' => $this->input->post('amount'),
            'tax' => '0',
            'height' => $this->input->post('height'),
            'weight' => $this->input->post('weight'),
            'bp' => $this->input->post('bp'),
            'patient_id' => $insert_id,
            'casualty' => $this->input->post('casualty'),
            'payment_mode' => $this->input->post('payment_mode'),
          ); 
          $opd_id = $this->patient_model->add_opd($opd_data);
           $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
           $data_patient_login = array(
                'username' => $this->patient_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id' => $insert_id,
                'role' => 'patient'
            );
            $this->user_model->add($data_patient_login);
          $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])){
              $fileInfo = pathinfo($_FILES["file"]["name"]);
              $img_name = $insert_id . '.' . $fileInfo['extension'];
              move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
              $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/' . $img_name);
              $this->patient_model->add($data_img);
            }
              $sender_details = array('patient_id' => $insert_id,'opd_no' => 'OPDN'.$opdnoid, 'contact_no' => $this->input->post('contact'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('opd_patient_registration', $sender_details);
            $patient_login_detail = array('id' => $insert_id, 'credential_for' => 'patient', 'username' => $this->patient_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('contact'), 'email' => $this->input->post('email'));
           $this->mailsmsconf->mailsms('login_credential', $patient_login_detail);
    }
    echo json_encode($array);
  }
  public function handle_upload() {
    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
      
      $allowedExts = array('jpg', 'jpeg', 'png');
      $temp = explode(".", $_FILES["file"]["name"]);
      $extension = end($temp);
      if ($_FILES["file"]["error"] > 0) {
          $error .= "Error opening the file<br />";
        }
      if ($_FILES["file"]["type"] != 'image/gif' &&
        $_FILES["file"]["type"] != 'image/jpeg' &&
        $_FILES["file"]["type"] != 'image/png') {
        $this->form_validation->set_message('handle_upload', 'File type not allowed');
        return false;
      }
      if (!in_array($extension, $allowedExts)) {
        $this->form_validation->set_message('handle_upload', 'Extension not allowed');
        return false;
      }
      if ($_FILES["file"]["size"] > 102400) {
        $this->form_validation->set_message('handle_upload', 'File size shoud be less than 100 kB');
        return false;
      }
      return true;
    } else {
      return true;
    }
  } 
  public function getOldPatient(){
    if (!$this->rbac->hasPrivilege('old_patient', 'can_view')){
                    access_denied();
    }
    $this->session->set_userdata('top_menu', 'OPD_Out_Patient');
    $setting = $this->setting_model->get();
    $data['setting'] = $setting;
    $data['title'] = 'old_patient';
    $opd_month = $setting[0]['opd_record_month'];
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors;
    $resultlist = $this->patient_model->searchFullText( $opd_month,''); 
    $data['organisation'] = $this->Organisation_model->get();
    $i = 0;
    foreach($resultlist as $visits){
      $patient_id = $visits["id"];
      $total_visit = $this->patient_model->totalVisit($patient_id);
      $last_visit = $this->patient_model->lastVisit($patient_id);
      $resultlist[$i]["total_visit"] = $total_visit["total_visit"];
      $resultlist[$i]["last_visit"] = $last_visit["last_visit"];
      $i++;
    }
    $data["resultlist"] = $resultlist;
    $this->load->view('layout/header');
    $this->load->view('admin/patient/search.php',$data);
    $this->load->view('layout/footer');
  }
  public function search(){
      
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
    $data["title"] = 'opd_patient';
    $this->session->set_userdata('top_menu', 'OPD_Out_Patient');
    $setting = $this->setting_model->get();
    $data['setting'] = $setting;
    $opd_month = $setting[0]['opd_record_month'];
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors ;
    
    if($opd_month){
      $resultlist = $this->patient_model->searchByMonth($opd_month,''); 
    }else{
      $resultlist = $this->patient_model->searchFullText('1',''); 
    }
    $data['organisation'] = $this->Organisation_model->get();
    $i = 0;
    foreach($resultlist as $visits){
      $patient_id = $visits["id"];
      $total_visit = $this->patient_model->totalVisit($patient_id);
      $last_visit = $this->patient_model->lastVisit($patient_id);
      $resultlist[$i]["total_visit"] = $total_visit["total_visit"];
      $resultlist[$i]["last_visit"] = $last_visit["last_visit"];
      $i++;
    }
    $data["resultlist"] = $resultlist;
    $this->load->view('layout/header');
    $this->load->view('admin/patient/search.php',$data);
    $this->load->view('layout/footer');
  }
  public function ipdsearch($bedid='',$bedgroupid=''){
    if (!$this->rbac->hasPrivilege('ipd_patient', 'can_view')){
                    access_denied();
    }
    if(!empty($bedgroupid)){
    $data["bedid"] = $bedid;
    $data["bedgroupid"] = $bedgroupid;
      
    }
    
    $this->session->set_userdata('top_menu', 'IPD_in_patient');
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $data['bed_list'] = $this->bed_model->bedNoType();
    $data['floor_list'] = $this->floor_model->floor_list();
    $data['bedlist'] = $this->bed_model->bed_list();
    $data['bedgroup_list'] = $this->bedgroup_model->bedGroupFloor();
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors ;
    $setting = $this->setting_model->get();
   
    $data['setting'] = $setting;
    
    $data['resultlist']=$this->patient_model->search_ipd_patients('');
    $i =0 ;
    foreach ($data['resultlist'] as $key => $value) {
    $charges = $this->patient_model->getCharges($value["id"]); 
    $data['resultlist'][$i]["charges"] = $charges['charge'];
    $payment = $this->patient_model->getPayment($value["id"]);
    $data['resultlist'][$i]["payment"] = $payment['payment'];   
        $i++;
    }
    $data['organisation'] = $this->Organisation_model->get();
    //print_r($data['resultlist'][1])."<br>";die;
    $this->load->view('layout/header');
    $this->load->view('admin/patient/ipdsearch.php',$data);
    $this->load->view('layout/footer');
  }

  public function discharged_patients(){
    if (!$this->rbac->hasPrivilege('discharged patients', 'can_view')){
                    access_denied();
    }
    $this->session->set_userdata('top_menu', 'IPD_in_patient');
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $data['bed_list'] = $this->bed_model->bedNoType();
    $data['bedgroup_list'] = $this->bedgroup_model->bedGroupFloor();
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors ;
    $setting = $this->setting_model->get();
    $data['setting'] = $setting;

    $data['resultlist']=$this->patient_model->search_ipd_patients('',$active='no');
    $i =0 ;
    foreach ($data['resultlist'] as $key => $value) {
    $charges = $this->patient_model->getCharges($value["id"]); 
    $data['resultlist'][$i]["charges"] = $charges['charge'];
    $payment = $this->patient_model->getPayment($value["id"]);
    $data['resultlist'][$i]["payment"] = $payment['payment'];   
    $discharge_details = $this->patient_model->getIpdBillDetails($value["id"]);
     $data['resultlist'][$i]["discharge_date"] = $discharge_details['date'];
     $data['resultlist'][$i]["other_charge"] = $discharge_details['other_charge'];
     $data['resultlist'][$i]["tax"] = $discharge_details['tax'];
     $data['resultlist'][$i]["discount"] = $discharge_details['discount'];
     $data['resultlist'][$i]["net_amount"] = $discharge_details['net_amount']+ $payment['payment'];
        $i++;
    }
    $data['organisation'] = $this->Organisation_model->get();
    $this->load->view('layout/header');
    $this->load->view('admin/patient/dischargedPatients.php',$data);
    $this->load->view('layout/footer');
  }
  public function profile($id){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
   
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $data["id"] = $id ;
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors ;
    $result  = array();
    $diagnosis_details  = array();
    $opd_details  = array();
    $timeline_list  = array();
    if(!empty($id)){
      $result = $this->patient_model->getDetails($id);
      $opd_details = $this->patient_model->getOPDetails($id);
      $diagnosis_details = $this->patient_model->getDiagnosisDetails($id);
      $timeline_list = $this->timeline_model->getPatientTimeline($id, $timeline_status='');

    }
    $data["result"] = $result;
    $data["diagnosis_detail"]  = $diagnosis_details;
    //$data["prescription_detail"]  = $prescription_details;
    $data["opd_details"] = $opd_details;    
    $data["timeline_list"] = $timeline_list;
    $data['organisation'] = $this->Organisation_model->get();
    $this->load->view("layout/header");
    $this->load->view("admin/patient/profile",$data);
    $this->load->view("layout/footer");
  }
  public function ipdprofile($id,$active='yes'){

   
    if (!$this->rbac->hasPrivilege('ipd_patient', 'can_view')){
          access_denied();
    }
    $data['bed_list'] = $this->bed_model->bedNoType();
    $data['bedgroup_list'] = $this->bedgroup_model->bedGroupFloor();
    $data["marital_status"] = $this->marital_status ;
    $data["payment_mode"] = $this->payment_mode ;
    $data["bloodgroup"] = $this->blood_group ;
    $data['organisation'] = $this->Organisation_model->get();
    $data["id"] = $id ;
    $doctors = $this->staff_model->getStaffbyrole(3);
    $data["doctors"] = $doctors ;
    $result  = array();
    $diagnosis_details  = array();
    $opd_details  = array();
    $timeline_list  = array();
    $charges = array();
    if(!empty($id)){
      $result = $this->patient_model->getIpdDetails($id,$active);
      if($result['status'] == 'paid'){
        $generate = $this->patient_model->getBillInfo($result["id"]);
        $data["bill_info"] = $generate ;
      }
     
      $diagnosis_details = $this->patient_model->getDiagnosisDetails($id);
      $timeline_list = $this->timeline_model->getPatientTimeline($id, $timeline_status='');
      $prescription_details = $this->prescription_model->getPatientPrescription($id);
      $consultant_register = $this->patient_model->getPatientConsultant($id);
      $charges =$this->charge_model->getCharges($id);
      $paymentDetails = $this->payment_model->paymentDetails($id);
      $paid_amount = $this->payment_model->getPaidTotal($id);
      $data["paid_amount"] = $paid_amount["paid_amount"] ;
      $balance_amount = $this->payment_model->getBalanceTotal($id);
      $data["balance_amount"] = $balance_amount["balance_amount"];
      $data["payment_details"] = $paymentDetails;
      $data["consultant_register"] = $consultant_register;     
      $data["result"] = $result;
      $data["diagnosis_detail"] = $diagnosis_details;
      $data["prescription_detail"] = $prescription_details;
      $data["opd_details"] = $opd_details;
      $data["timeline_list"] = $timeline_list;
      $data["charge_type"] = $this->charge_type;
      $data["charges"] = $charges;
    }
    $this->load->view("layout/header");
    $this->load->view("admin/patient/ipdprofile",$data);
    $this->load->view("layout/footer");
  }
  public function deleteIpdPatientCharge($pateint_id,$id){
    if (!$this->rbac->hasPrivilege('charges', 'can_delete')){
                    access_denied();
    }
    $this->charge_model->deleteIpdPatientCharge($id);
    $this->session->set_flashdata('msg', '<div class="alert alert-success">Patient Charges deleted successfully</div>');
      redirect('admin/patient/ipdprofile/'.$pateint_id.'#charges');
  }
  public function deleteIpdPatientConsultant($pateint_id,$id){
   if (!$this->rbac->hasPrivilege('consultant register', 'can_add')){
                    access_denied();
    }
    $this->patient_model->deleteIpdPatientConsultant($id);
    $this->session->set_flashdata('msg', '<div class="alert alert-success">Patient Consultant deleted successfully</div>');
      //redirect('admin/patient/ipdprofile/'.$pateint_id);
  }
  public function deleteIpdPatientDiagnosis($pateint_id,$id){
    if (!$this->rbac->hasPrivilege('ipd diagnosis', 'can_delete')){
                    access_denied();
    }
    $this->patient_model->deleteIpdPatientDiagnosis($id);
    $this->session->set_flashdata('msg', '<div class="alert alert-success">Patient Diagnosis deleted successfully</div>');
      redirect('admin/patient/ipdprofile/'.$pateint_id.'#diagnosis');
  }
  public function deleteIpdPatientPayment($pateint_id,$id){
    if (!$this->rbac->hasPrivilege('payment', 'can_delete')){
                    access_denied();
    }
    $this->payment_model->deleteIpdPatientPayment($id);
    $this->session->set_flashdata('msg', '<div class="alert alert-success">Patient Payment deleted successfully</div>');
      redirect('admin/patient/ipdprofile/'.$pateint_id.'#payment');
  }
  public function deleteOpdPatientDiagnosis($pateint_id,$id){
    if (!$this->rbac->hasPrivilege('opd diagnosis', 'can_delete')){
                    access_denied();
    }
    $this->patient_model->deleteIpdPatientDiagnosis($id);
    
      //redirect('admin/patient/profile/'.$pateint_id.'#diagnosis');
  }
  public function report_download($doc){
    $this->load->helper('download');
      $filepath = "./" .$this->uri->segment(4)."/".$this->uri->segment(5)."/".$this->uri->segment(6);
      $data = file_get_contents($filepath);
      $name = $this->uri->segment(6);
      force_download($name, $data);
  }
  public function getDetails(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
    $id = $this->input->post("patient_id");
    $opdid = $this->input->post("opd_id");
    $result = $this->patient_model->getDetails($id,$opdid);
    $appointment_date = date($this->customlib->getSchoolDateFormat(true,true), strtotime($result['appointment_date']));
   
    $result["appointment_date"] = $appointment_date;
    echo json_encode($result);
  }
  public function getIpdDetails(){
    if (!$this->rbac->hasPrivilege('ipd_patient', 'can_view')){
                    access_denied();
    }
    $id = $this->input->post("recordid");
    $active = $this->input->post("active");
    $result = $this->patient_model->getIpdDetails($id,$active);
   
    $result['date'] = date($this->customlib->getSchoolDateFormat(true,true), strtotime($result['date']));
    echo json_encode($result);
  }
  public function update(){
    if(!$this->rbac->hasPrivilege('opd_patient', 'can_edit')){
                    access_denied();
    }
    $patient_type = $this->customlib->getPatienttype();
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $msg = array(
               'firstname' => form_error('name'),          
        );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
            $id = $this->input->post('updateid');
            $patient_data = array(
              'id' => $this->input->post('updateid'),
              'patient_name' => $this->input->post('name'),
              'mobileno' => $this->input->post('contact'),
              'marital_status' => $this->input->post('marital_status'),
              'blood_group' => $this->input->post('blood_group'),         
              'email' => $this->input->post('email'),
              'gender' => $this->input->post('gender'),
              'guardian_name' => $this->input->post('guardian_name'),
              'address' => $this->input->post('address'),
              'note' => $this->input->post('note'),
              'age' => $this->input->post('age'),
              'month' => $this->input->post('month'),
              'organisation' => $this->input->post('organisation'),
              'credit_limit' =>$this->input->post('credit_limit'),
              'is_active' => 'yes',
           
            );
          $this->patient_model->add($patient_data);
          $array = array('status' => 'success', 'error' => '', 'message' => "Record Updated Successfully");
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])){
              $fileInfo = pathinfo($_FILES["file"]["name"]);
              $img_name = $id . '.' . $fileInfo['extension'];
              move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
              $data_img = array('id' => $id, 'image' => 'uploads/patient_images/' . $img_name);
              $this->patient_model->add($data_img);
            }
        }

    echo json_encode($array);        
  }
  public function ipd_update(){
    if (!$this->rbac->hasPrivilege('ipd_patient', 'can_edit')){
                    access_denied();
    }
    $patient_type = $this->customlib->getPatienttype();
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('blood_group',$this->lang->line('blood')." ".$this->lang->line('group'),'trim|required|xss_clean');
    //$this->form_validation->set_rules('bed_no', 'Bed', 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $msg = array(
             'firstname' => form_error('name'),          
             'gender' => form_error('gender'),
             'blood_group' =>form_error('blood_group'),      
             // 'bed_no' => form_error('bed_no'),
            );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
          $id = $this->input->post('updateid'); 
          $appointment_date = $this->input->post('appointment_date');
         
          $patient_data = array(
                'id' => $this->input->post('updateid'),
                'patient_name' => $this->input->post('name'),
                'mobileno' => $this->input->post('contact'), 
                'marital_status' => $this->input->post('marital_status'),        
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'blood_group' => $this->input->post('blood_group'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('note'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'is_active' => 'yes',
                'old_patient' => $this->input->post('old_patient'),
                'organisation' => $this->input->post('organisation'),
                'credit_limit' =>$this->input->post('credit_limit'),
          );
          $this->patient_model->add($patient_data);
          $ipd_data = array(
                'id' => $this->input->post('ipdid'),
                'date' =>  date('Y-m-d H:i:s', $this->customlib->datetostrtotime($appointment_date)),
                'bed' => $this->input->post('bed_no'),
                'bed_group_id' =>$this->input->post('bed_group_id'),
                'height' => $this->input->post('height'),
                'bp' => $this->input->post('bp'),
                'weight' => $this->input->post('weight'),
                'case_type' => $this->input->post('case_type'),
                'symptoms' => $this->input->post('symptoms'),
                'known_allergies' => $this->input->post('known_allergies'),
                'refference' => $this->input->post('refference'),
                'cons_doctor' => $this->input->post('cons_doctor'),
                'casualty' => $this->input->post('casualty'),
              ); 
              $bed_data = array('id' =>$this->input->post('bed_no'),'is_active' => 'no' );
              $this->bed_model->savebed($bed_data);
              $ipd_id = $this->patient_model->add_ipd($ipd_data);
            $array = array('status' => 'success', 'error' => '', 'message' => "Patient Updated Successfully");
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])){
              $fileInfo = pathinfo($_FILES["file"]["name"]);
              $img_name = $id . '.' . $fileInfo['extension'];
              move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
              $data_img = array('id' => $id, 'image' => 'uploads/patient_images/' . $img_name);
              $this->patient_model->add($data_img);
            }
        }
    echo json_encode($array);
  }
  public function opd_detail_update(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_edit')){
                    access_denied();
    }
    $id = $this->input->post('opdid');
     $this->form_validation->set_rules('appointment_date', $this->lang->line('appointment')." ".$this->lang->line('date'), 'trim|required|xss_clean');
     $this->form_validation->set_rules('consultant_doctor', $this->lang->line('consultant')." ".$this->lang->line('doctor'), 'trim|required|xss_clean');
       $this->form_validation->set_rules('opdid', $this->lang->line('opd')." ".$this->lang->line('id'), 'trim|required|xss_clean');
       $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
     if ($this->form_validation->run() == TRUE){
   $appointment_date = $this->input->post('appointment_date');
  
      $patient_data  = array('id' => $this->input->post('patientid'),
                          'organisation' => $this->input->post('organisation'),
                          'old_patient' => $this->input->post('old_patient'),
                           );
      $opd_data = array(
            'id' => $this->input->post('opdid'),
            'appointment_date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($appointment_date)),
            'case_type' => $this->input->post('case'),
            'symptoms' => $this->input->post('symptoms'),
            'known_allergies' => $this->input->post('known_allergies'),
            'refference' => $this->input->post('refference'),
            'cons_doctor' => $this->input->post('consultant_doctor'),
            'amount' => $this->input->post('amount'),
            'bp' => $this->input->post('bp'),
            'height' => $this->input->post('height'),
            'weight' => $this->input->post('weight'),
            'tax' => $this->input->post('tax'),
            'casualty' => $this->input->post('casualty'),
            'payment_mode' => $this->input->post('payment_mode'),
          );    
   //   print_r($opd_data);
      //exit();
        $opd_id = $this->patient_model->add_opd($opd_data);
          $this->patient_model->add($patient_data);

        $array = array('status' => 'success', 'error' => '', 'message' => "Record Updated Successfully");
    }else{

          $msg = array(
             'appointment_date' =>form_error('appointment_date'),
             'consultant_doctor' =>form_error('consultant_doctor'),
             'opdid' =>form_error('opdid'), 
              'amount' =>form_error('amount'),          
            );
        $array = array('status' => 'fail', 'error' =>$msg, 'message' => '');
      }
    echo json_encode($array);
  }

  public function opd_details(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
    $id = $this->input->post("recordid");
    $opdid = $this->input->post("opdid");
    $result = $this->patient_model->getOPDetails($id,$opdid); 
     $appointment_date = date($this->customlib->getSchoolDateFormat(true,true), strtotime($result['appointment_date']));
    $result["appointment_date"] = $appointment_date ;
    echo json_encode($result);
  }
  public function add_diagnosis(){
    $this->form_validation->set_rules('report_type', $this->lang->line('report')." ".$this->lang->line('type'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE){
        $msg = array(
             'report_type' =>form_error('report_type'),
             'description' =>form_error('description'),          
            );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        }else{
          $report_date = $this->input->post('report_date');
            $data = array(
                      'report_type' => $this->input->post("report_type"),
                      'report_date' => date('Y-m-d', $this->customlib->datetostrtotime($report_date)),
                      'patient_id' => $this->input->post("patient"),
                      'description' => $this->input->post("description"),
                    );  
            $insert_id =  $this->patient_model->add_diagnosis($data);
            if (isset($_FILES["report_document"]) && !empty($_FILES['report_document']['name'])){
              $fileInfo = pathinfo($_FILES["report_document"]["name"]);
              $img_name = $insert_id . '.' . $fileInfo['extension'];
              move_uploaded_file($_FILES["report_document"]["tmp_name"], "./uploads/patient_images/" . $img_name);
              $data_img = array('id' => $insert_id, 'document' => 'uploads/patient_images/' . $img_name);
              $this->patient_model->add_diagnosis($data_img);
            }
          $array = array('status' => 'success', 'error' =>'', 'message' => 'Record Added Successfully.');
        }
    echo json_encode($array);
  }  
  public function add_prescription(){
    if (!$this->rbac->hasPrivilege('prescription', 'can_add')){
                    access_denied();
    }
    $this->form_validation->set_rules('medicine[]', $this->lang->line('medicine'), 'trim|required|xss_clean');
    if ($this->form_validation->run() == FALSE) {
      $msg = array(
               'medicine' => form_error('medicine[]'),          
              );
      $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
    } else {
        $opd_id=$this->input->post('opd_no');
        $medicine = $this->input->post("medicine[]");
        $dosage = $this->input->post("dosage[]");
        $instruction = $this->input->post("instruction[]");
        $header_note = $this->input->post("header_note");
        $footer_note = $this->input->post("footer_note");
        $data_array  = array();
        $i = 0 ;
          foreach ($medicine as $key => $value){
            $inst = '';
            $do = '';
            if(!empty($dosage[$i])){
              $do = $dosage[$i];
            }
            if(!empty($instruction[$i])){
              $inst = $instruction[$i];
            }
            $data = array('opd_id'=>$opd_id, 'medicine' => $value,'dosage' => $do,'instruction' => $inst);    
            $data_array[] = $data ;
                $i++;
          }
          $opd_array = array('id' => $opd_id, 'header_note' => $header_note ,'footer_note' => $footer_note); 
        $this->patient_model->add_prescription($data_array);
        $this->patient_model->add_opd($opd_array);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
      }
    echo json_encode($array);
  }
  public function update_prescription(){
    if (!$this->rbac->hasPrivilege('prescription', 'can_edit')){
                    access_denied();
    }
    $this->form_validation->set_rules('medicine[]', $this->lang->line('medicine'), 'trim|required|xss_clean');
    if ($this->form_validation->run() == FALSE) {
      $msg = array(
               'medicine' => form_error('medicine[]'),          
              );
      $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
    } else {
        $opd_id=$this->input->post('opd_id');
        $medicine = $this->input->post("medicine[]");
        $prescription_id = $this->input->post("prescription_id[]");
        $previous_pres_id = $this->input->post("previous_pres_id[]");
      
        $dosage = $this->input->post("dosage[]");
        $instruction = $this->input->post("instruction[]");
        $header_note = $this->input->post("header_note");
        $footer_note = $this->input->post("footer_note");
        // print_r($prescription_id);
        // print_r($previous_pres_id);
   
        $data_array  = array();
         $delete_arr  = array();
              foreach ($previous_pres_id as $pkey => $pvalue) {
            if(in_array($pvalue, $prescription_id)){
           
            }else{
           $delete_arr[]  = array('id' => $pvalue, );
            }
        }

        $i = 0 ;
          foreach ($medicine as $key => $value) {
            $inst = '';
            $do = '';
            if(!empty($dosage[$i])){
              $do = $dosage[$i];
            }
            if(!empty($instruction[$i])){
              $inst = $instruction[$i];
            }
            if($prescription_id[$i] == 0 ){
             $add_data = array('opd_id'=>$opd_id, 'medicine' => $value,'dosage' => $do,'instruction' => $inst); 

             $data_array[] = $add_data ;
           
            }else{

              
              $update_data = array('id'=>$prescription_id[$i],'opd_id'=>$opd_id, 'medicine' => $value,'dosage' => $do,'instruction' => $inst);
         
               $this->prescription_model->update_prescription($update_data);
            }
              
            
                $i++;
          }
          $opd_array = array('id' => $opd_id, 'header_note' => $header_note ,'footer_note' => $footer_note); 

            if(!empty($data_array)){
              $this->patient_model->add_prescription($data_array);
            }
             if(!empty($delete_arr)){
             
              $this->prescription_model->delete_prescription($delete_arr);
            }
            $this->patient_model->add_opd($opd_array);
           
         $array = array('status' => 'success', 'error' => '', 'message' => 'Prescription Added Successfully');
      }
    echo json_encode($array);
  }
  public function add_inpatient(){
    if (!$this->rbac->hasPrivilege('ipd_patient', 'can_add')){
                    access_denied();
    }
    $patient_type = $this->customlib->getPatienttype();
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('appointment_date',$this->lang->line('appointment')." ".$this->lang->line('date'),'trim|required|xss_clean');
    $this->form_validation->set_rules('bed_no', $this->lang->line('bed'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('consultant_doctor', $this->lang->line('consultant')." ".$this->lang->line('doctor'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $msg = array(
             'firstname' => form_error('name'),          
             'gender' => form_error('gender'),
             'appointment_date' =>form_error('appointment_date'),           
             'bed_no' => form_error('bed_no'),
             'consultant_doctor' => form_error('consultant_doctor'),
            );
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
          $check_patient_id = $this->patient_model->getMaxId();
          if(empty($check_patient_id)){
            $check_patient_id = 1000 ;
          }
          $patient_id = $check_patient_id+1;  
          $appointment_date = $this->input->post('appointment_date');
          $patient_data = array(
                'patient_name' => $this->input->post('name'),
                'mobileno' => $this->input->post('contact'),
                'marital_status' => $this->input->post('marital_status'),         
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'guardian_name' => $this->input->post('guardian_name'),
                'blood_group' => $this->input->post('blood_group'),
                'address' => $this->input->post('address'),
                'patient_unique_id' => $patient_id,
                'note' => $this->input->post('note'),
                'age' => $this->input->post('age'),
                'month' => $this->input->post('month'),
                'is_active' => 'yes',
                'patient_type'=>$patient_type['inpatient'],
                'old_patient' => $this->input->post('old_patient'),
                'organisation' => $this->input->post('organisation'),
                'credit_limit' =>$this->input->post('credit_limit'),
          );
          $insert_id=$this->patient_model->add($patient_data);
          $ipd_data = array(
                'date' => date('Y-m-d H:i:s', $this->customlib->datetostrtotime($appointment_date)),
                'ipd_no'=> "IPDN".$patient_id,
                'bed' => $this->input->post('bed_no'),
                'bed_group_id' =>$this->input->post('bed_group_id'),
                'height' => $this->input->post('height'),
                'weight' => $this->input->post('weight'),
                'bp' => $this->input->post('bp'),
                'case_type' => $this->input->post('case'),
                'symptoms' => $this->input->post('symptoms'),
                'known_allergies' => $this->input->post('known_allergies'),
                'refference' => $this->input->post('refference'),
                'cons_doctor' => $this->input->post('consultant_doctor'),
                'patient_id' => $insert_id,
                'casualty' => $this->input->post('casualty'),
                         ); 
              $bed_data = array('id' =>$this->input->post('bed_no'),'is_active' => 'no' );
              $this->bed_model->savebed($bed_data);
              $ipd_id = $this->patient_model->add_ipd($ipd_data);
            $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
           $data_patient_login = array(
                'username' => $this->patient_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id' => $insert_id,
                'role' => 'patient'
                );

            $this->user_model->add($data_patient_login);
        
            $array = array('status' => 'success', 'error' => '', 'message' => "Patient Added Successfully");
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])){
              $fileInfo = pathinfo($_FILES["file"]["name"]);
              $img_name = $insert_id . '.' . $fileInfo['extension'];
              move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
              $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/' . $img_name);
              $this->patient_model->add($data_img);
            }

             $sender_details = array('patient_id' => $insert_id, 'opd_no' =>'', 'contact_no' => $this->input->post('contact'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('ipd_patient_registration', $sender_details);
            $patient_login_detail = array('id' => $insert_id, 'credential_for' => 'patient', 'username' => $this->patient_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('contact'), 'email' => $this->input->post('email'));
            $this->mailsmsconf->mailsms('login_credential', $patient_login_detail);
        }
    echo json_encode($array);
  }
  public function add_consultant_instruction(){
    if (!$this->rbac->hasPrivilege('consultant register', 'can_add')){
                    access_denied();
    }
    $this->form_validation->set_rules('date[]', $this->lang->line('applied')." ".$this->lang->line('date'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('doctor[]', $this->lang->line('consultant'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('instruction[]', $this->lang->line('instruction'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('insdate[]', $this->lang->line('instruction')." ".$this->lang->line('date'), 'trim|required|xss_clean');
      if ($this->form_validation->run() == FALSE) {
        $msg = array(
             'date' => form_error('date[]'),          
             
              'doctor' => form_error('doctor[]'),
              'instruction' => form_error('instruction[]'),
              'datee'=>form_error('insdate[]')
           );           
        
        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
      } else {
          $date = $this->input->post('date[]');
          $ins_date = $this->input->post('insdate[]');
          //$ins_time = $this->input->post('instime[]');
          $patient_id = $this->input->post('patient_id');
          $doctor = $this->input->post('doctor[]');
          $instruction = $this->input->post('instruction[]');
          $data = array();
          $i =0 ;
          foreach ($date as $key => $value) {
            $details  = array(
                        'date' =>date('Y-m-d H:i:s', $this->customlib->datetostrtotime($date[$i])),
                        'patient_id' => $patient_id,
                        'ins_date' => date('Y-m-d', $this->customlib->datetostrtotime($ins_date[$i])),
                        //'ins_time' => date("h:i s",strtotime($ins_time[$i])),    
                        'cons_doctor' => $doctor[$i],
                        'instruction' => $instruction[$i],
                       );  
            $data[] = $details ;
            $i++;
          }
          $this->patient_model->add_consultantInstruction($data);
          $array = array('status' => 'success', 'error' => '', 'message' => 'Record Added Successfully');
        }
    echo json_encode($array);
  }
  public function ipdCharge(){
    $code = $this->input->post('code');
    $org_id = $this->input->post('organisation_id');
    $patient_charge = $this->patient_model->ipdCharge($code,$org_id);
    $data['patient_charge'] = $patient_charge;
    echo json_encode($patient_charge);
  }
  public function opd_report(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_view')){
                    access_denied();
    }
 
    $this->session->set_userdata('top_menu', 'Reports');
    $this->session->set_userdata('sub_menu', 'admin/patient/opd_report');
    $this->session->set_userdata('top_menu', 'Reports');
    $select = 'opd_details.*,staff.name,staff.surname,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.address,patients.admission_date,patients.gender,patients.mobileno,patients.age';   
    $join  = array(
              'JOIN staff ON opd_details.cons_doctor = staff.id',
              'JOIN patients ON opd_details.patient_id = patients.id'
            );
    $table_name = "opd_details";
    //$this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');

         $search_type = $this->input->post("search_type");
    if(isset($search_type)){
      $search_type = $this->input->post("search_type");
    }else{
      $search_type = "this_month";
    } 
      //if ($this->form_validation->run() == FALSE) {
      if(empty($search_type)){

        $search_type = "";  
        $resultlist =  $this->report_model->getReport($select,$join,$table_name);
      }else{

        $search_table =  "opd_details";
        $search_column =  "appointment_date";
        $resultlist = $this->report_model->searchReport($select,$join,$table_name,$search_type,$search_table,$search_column);
      }

    $data["searchlist"] = $this->search_type ;
    $data["search_type"] = $search_type;
    $data["resultlist"] = $resultlist;     

    $this->load->view('layout/header');
    $this->load->view('admin/patient/opdReport.php',$data);
    $this->load->view('layout/footer');
  }
 public function ipdReport(){
    if (!$this->rbac->hasPrivilege('ipd_patinet', 'can_view')){
                    access_denied();
    }
    $this->session->set_userdata('top_menu', 'Reports');
    $this->session->set_userdata('sub_menu', 'admin/patient/ipdreport');
    $select = 'ipd_details.*,payment.paid_amount,staff.name,staff.surname,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.address,patients.admission_date,patients.gender,patients.mobileno,patients.age';
    $join  = array(
              'JOIN staff ON ipd_details.cons_doctor = staff.id',
              'JOIN patients ON ipd_details.patient_id = patients.id',
              'JOIN payment ON payment.patient_id = patients.id',
            );
    $table_name = "ipd_details";
       $search_type = $this->input->post("search_type");
     if(isset($search_type)){
      $search_type = $this->input->post("search_type");
    }else{
      $search_type = "this_month";
    } 
  //  $this->form_validation->set_rules('search_type', 'Search Type', 'trim|required|xss_clean');
     // if ($this->form_validation->run() == FALSE) {
    if(empty($search_type)){
        $search_type = "";  
        $resultlist =  $this->report_model->getReport($select,$join,$table_name);
      }else{
     
        $search_table =  "ipd_details";
        $search_column =  "date";
        $resultlist = $this->report_model->searchReport($select,$join,$table_name,$search_type,$search_table,$search_column);
      }
        $resultList2 =  $this->report_model->searchReport($select='ipd_details.*,ipd_billing.net_amount as paid_amount,staff.name,staff.surname,patients.id as pid,patients.patient_name,patients.patient_unique_id,patients.guardian_name,patients.address,patients.admission_date,patients.gender,patients.mobileno,patients.age',$join=array(
                           'JOIN staff ON ipd_details.cons_doctor = staff.id',
                          'JOIN patients ON ipd_details.patient_id = patients.id',
                          'JOIN payment ON payment.patient_id = patients.id',
                          'JOIN ipd_billing ON ipd_billing.patient_id = patients.id',
                         ),$table_name='ipd_details',$search_type,$search_table='ipd_billing',$search_column='date');
        if(!empty($resultList2)){
        array_push($resultlist, $resultList2[0]);  
        }
        
    $data["searchlist"] = $this->search_type ;
    $data["search_type"] = $search_type;
    $data["resultlist"] = $resultlist;     
    $this->load->view('layout/header');
    $this->load->view('admin/patient/ipdReport.php',$data);
    $this->load->view('layout/footer');
  }
  public function revertBill(){
    $patient_id = $this->input->post('patient_id');
    $bill_id = $this->input->post('bill_id');
    $bed_id = $this->input->post('bed_id');
    if((!empty($patient_id)) && (!empty($bill_id))){
        $patient_data = array('id' =>$patient_id ,'is_active' =>'yes');
       $this->patient_model->add($patient_data);
        $bed_data = array('id' =>$bed_id ,'is_active' =>'no');
       $this->bed_model->savebed($bed_data);
      $revert = $this->payment_model->revertBill($patient_id,$bill_id);
      $array = array('status' => 'success', 'error' =>'', 'message' => 'Record Updated Successfully.');
    }else{
      $array = array('status' => 'fail', 'error' =>'', 'message' => 'Record Not Updated.');
    }
    echo json_encode($array);
  }

  public function deleteOPD(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_delete')){
                    access_denied();
    }
    $opdid = $this->input->post('opdid');
    if(!empty($opdid)){
      $this->patient_model->deleteOPD($opdid);
       $array = array('status' => 'success', 'error' =>'', 'message' => 'Record Deleted Successfully.');
    }else{
       $array = array('status' => 'fail', 'error' =>'', 'message' => '');
    }
    echo json_encode($array);
  }
   public function deleteOPDPatient(){
    if (!$this->rbac->hasPrivilege('opd_patient', 'can_delete')){
                    access_denied();
    }
    $id = $this->input->post('id');
    if(!empty($id)){
      $this->patient_model->deleteOPDPatient($id);
       $array = array('status' => 'success', 'error' =>'', 'message' => 'Record Deleted Successfully.');
    }else{
       $array = array('status' => 'fail', 'error' =>'', 'message' => '');
    }
    echo json_encode($array);
  }
  public function patientCredentialReport(){
    $this->session->set_userdata('top_menu', 'Reports');
    $this->session->set_userdata('sub_menu', 'admin/patient/patientcredentialreport');
    $credential = $this->patient_model->patientCredentialReport();
    $data["credential"] = $credential;
    $this->load->view("layout/header");
    $this->load->view("admin/patient/patientcredentialreport",$data);
    $this->load->view("layout/footer");
  }

  public function deleteIpdPatient($id)
  {
    if(!empty($id)){
      $this->patient_model->deleteIpdPatient($id);
         $array = array('status' => 'success', 'error' =>'', 'message' => 'Record Deleted Successfully.');
    }else{
         $array = array('status' => 'fail', 'error' =>'', 'message' => '');
    }
    echo json_encode($array);
  }

  public function getBedStatus()
  {
     $floor_list = $this->floor_model->floor_list();
    $bedlist = $this->bed_model->bed_list();
    $bedgroup_list = $this->bedgroup_model->bedGroupFloor();
    $data["floor_list"] = $floor_list;
    $data["bedlist"] = $bedlist;
    $data["bedgroup_list"] = $bedgroup_list;
    $this->load->view("layout/bedstatusmodal",$data);
  }

}