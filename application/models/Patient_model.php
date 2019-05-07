<?php

class Patient_model extends CI_Model {

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('patients', $data);
        } else {
            $this->db->insert('patients', $data);
            return $this->db->insert_id();
        }
    }

    public function add_opd($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('opd_details', $data);
        } else {
            $this->db->insert('opd_details', $data);
            return $this->db->insert_id();
        }
    }

    public function add_ipd($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('ipd_details', $data);
        } else {
            $this->db->insert('ipd_details', $data);
            return $this->db->insert_id();
        }
    }

    public function adddoc($data) {
        $this->db->insert('student_doc', $data);
        return $this->db->insert_id();
    }

    public function searchAll($searchterm) {

        $this->db->select('patients.*')
                ->from('patients')
                ->like('patients.patient_name', $searchterm)
                ->or_like('patients.guardian_name', $searchterm)
                ->or_like('patients.patient_type', $searchterm)
                ->or_like('patients.address', $searchterm)
                ->or_like('patients.patient_unique_id', $searchterm);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientList() {

        $this->db->select('patients.*,users.username,users.id as user_tbl_id,users.is_active as user_tbl_active')
                ->join('users', 'users.user_id = patients.id')
                ->from('patients');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchFullText($opd_month, $searchterm, $carray = null) {

        $last_date = date("Y-m-t 23:59:59.993", strtotime("-" . $opd_month . " month"));
       
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.case_type,staff.name,staff.surname
                  ')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('opd_details.appointment_date < ', $last_date);
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('patients.id', 'desc');
        $this->db->group_by('opd_details.patient_id');
        $query = $this->db->get();
       
        return $query->result_array();
    }

    public function searchByMonth($opd_month, $searchterm, $carray = null) {

        
        $first_date = date('Y-m' . '-01', strtotime("-" . $opd_month . " month"));
        $last_date = date('Y-m' . '-' . date('t', strtotime($first_date)) . ' 23:59:59.993');
      
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,staff.name,staff.surname
                  ')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('opd_details.appointment_date >', $first_date);
        $this->db->where('opd_details.appointment_date <', $last_date);
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('patients.id', 'desc');
        $this->db->group_by('opd_details.patient_id');
        $query = $this->db->get();
       
        return $query->result_array();
    }

    public function totalVisit($patient_id) {
        $query = $this->db->select('count(opd_details.patient_id) as total_visit')
                ->where('patient_id', $patient_id)
                ->get('opd_details');
        return $query->row_array();
    }

    public function lastVisit($patient_id) {
        $query = $this->db->select('max(opd_details.appointment_date) as last_visit')
                ->where('patient_id', $patient_id)
                ->get('opd_details');
        return $query->row_array();
    }

    public function patientProfile($id, $active = 'yes') {

        $query = $this->db->where("id", $id)->get("patients");
        $result = $query->row_array();

        if ($result["patient_type"] == "Outpatient") {
            $data = $this->getDetails($id);
        } else if ($result["patient_type"] == "Inpatient") {

            $data = $this->getIpdDetails($id, $active);
        }
        return $data;
    }

    public function getDetails($id, $opdid = '') {
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.id as opdid,opd_details.casualty,opd_details.cons_doctor,opd_details.refference,opd_details.opd_no,opd_details.known_allergies,opd_details.amount,opd_details.height,opd_details.weight,opd_details.bp,opd_details.symptoms,opd_details.tax,opd_details.payment_mode,opd_details.note_remark,organisation.organisation_name,organisation.id as orgid,staff.name,staff.surname')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->where('patients.is_active', 'yes');
        $this->db->where('patients.id', $id);
        if (!empty($opdid)) {
            $this->db->where('opd_details.id', $opdid);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getIpdDetails($id, $active = 'yes') {
        $this->db->select('patients.*,ipd_details.date,ipd_details.case_type,ipd_details.ipd_no,ipd_details.id as ipdid,ipd_details.casualty,ipd_details.height,ipd_details.weight,ipd_details.bp,ipd_details.cons_doctor,ipd_details.refference,ipd_details.known_allergies,ipd_details.amount,ipd_details.symptoms,ipd_details.tax,ipd_details.bed,ipd_details.bed_group_id,ipd_details.bed,ipd_details.bed_group_id,ipd_details.payment_mode,ipd_billing.status,ipd_billing.gross_total,ipd_billing.discount,ipd_billing.tax,ipd_billing.net_amount,ipd_billing.total_amount,ipd_billing.other_charge,ipd_billing.generated_by,ipd_billing.id as bill_id,staff.name,staff.surname,organisation.organisation_name,bed.name as bed_name,bed.id as bed_id,bed_group.name as bedgroup_name,floor.name as floor_name')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "left");
        $this->db->join('ipd_billing', 'patients.id = ipd_billing.patient_id', "left");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('organisation', 'organisation.id = patients.organisation', "left");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', $active);
        $this->db->where('patients.id', $id);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getPatientId() {
        $this->db->select('patients.*,opd_details.appointment_date,opd_details.case_type,opd_details.id as opdid,opd_details.casualty,opd_details.cons_doctor,opd_details.refference,opd_details.known_allergies,opd_details.amount,opd_details.symptoms,opd_details.tax,opd_details.payment_mode')->from('patients');
        $this->db->join('opd_details', 'patients.id = opd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->where('patients.is_active', 'yes');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getOPDetails($id, $opdid = null) {
        if (!empty($opdid)) {
            $this->db->where("opd_details.id", $opdid);
        }
        $this->db->select('opd_details.*,patients.organisation,patients.old_patient,staff.name,staff.surname')->from('opd_details');
        $this->db->join('staff', 'staff.id = opd_details.cons_doctor', "inner");
        $this->db->join('patients', 'patients.id = opd_details.patient_id', "inner");
        $this->db->where('opd_details.patient_id', $id);
        $this->db->order_by('opd_details.id', 'desc');
        $query = $this->db->get();
        if (!empty($opdid)) {
            return $query->row_array();
        } else {

            $result = $query->result_array();
            $i = 0;
            foreach ($result as $key => $value) {
                $opd_id = $value["id"];
                $check = $this->db->where("opd_id", $opd_id)->get('prescription');
                if ($check->num_rows() > 0) {
                    $result[$i]['prescription'] = 'yes';
                } else {
                    $result[$i]['prescription'] = 'no';
                }
                $i++;
            }
            return $result;
        }
    }

    function add_diagnosis($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("diagnosis", $data);
        } else {
            $this->db->insert("diagnosis", $data);
            return $this->db->insert_id();
        }
    }

    function getDiagnosisDetails($id) {
        $query = $this->db->where("patient_id", $id)->get("diagnosis");
        return $query->result_array();
    }

    public function deleteIpdPatientDiagnosis($id) {
        $query = $this->db->where('id', $id)
                ->delete('diagnosis');
    }

    function add_prescription($data_array) {
        $this->db->insert_batch("prescription", $data_array);
    }

    function getMaxId() {
        $query = $this->db->select('max(patient_unique_id) as patient_id')->get("patients");
        $result = $query->row_array();
        return $result["patient_id"];
    }

    function getMaxOPDId() {
        $query = $this->db->select('max(id) as patient_id')->get("opd_details");
        $result = $query->row_array();
        return $result["patient_id"];
    }

    function search_ipd_patients($searchterm, $active = 'yes') {
        $this->db->select('patients.*,bed.name as bed_name,bed_group.name as bedgroup_name, floor.name as floor_name,ipd_details.date,ipd_details.case_type,staff.name,staff.surname
              ')->from('patients');
        $this->db->join('ipd_details', 'patients.id = ipd_details.patient_id', "inner");
        $this->db->join('staff', 'staff.id = ipd_details.cons_doctor', "inner");
        $this->db->join('bed', 'ipd_details.bed = bed.id', "left");
        $this->db->join('bed_group', 'ipd_details.bed_group_id = bed_group.id', "left");
        $this->db->join('floor', 'floor.id = bed_group.floor', "left");
        $this->db->where('patients.is_active', $active);
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->or_like('patients.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('patients.id');
        $this->db->group_by('ipd_details.patient_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    function add_consultantInstruction($data) {
        $this->db->insert_batch("consultant_register", $data);
    }

    public function deleteIpdPatientConsultant($id) {
        $query = $this->db->where('id', $id)
                ->delete('consultant_register');
    }

    function getPatientConsultant($id) {
        $query = $this->db->select('consultant_register.*,staff.name,staff.surname')->join('staff', 'staff.id = consultant_register.cons_doctor', "inner")->where("patient_id", $id)->get("consultant_register");
        return $query->result_array();
    }

    public function ipdCharge($code, $orgid) {
        if (!empty($orgid)) {
            $this->db->select('charges.*,organisations_charges.id as org_charge_id, organisations_charges.org_id, organisations_charges.org_charge ');
            $this->db->join('organisations_charges', 'charges.id = organisations_charges.charge_id');
            $this->db->where('organisations_charges.org_id', $orgid);
        }
        $this->db->where('charges.id', $code);
        $query = $this->db->get('charges');
        return $query->row_array();
    }

    public function getDataAppoint($id) {
        $query = $this->db->where('patients.id', $id)->get('patients');
        return $query->row_array();
    }

    public function search($id) {
        $this->db->select('appointment.*,staff.id as sid,staff.name,staff.surname,patients.id as pid,patients.patient_unique_id');
        $this->db->join('staff', 'appointment.doctor = staff.id', "inner");
        $this->db->join('patients', 'appointment.patient_id = patients.id', 'inner');
        $this->db->where('`appointment`.`doctor`=`staff`.`id`');
        $this->db->where('appointment.patient_id = patients.id');
        $this->db->where('appointment.patient_id=' . $id);
        $query = $this->db->get('appointment');
        return $query->result_array();
    }

    public function getOpdPatient($opd_ipd_no) {
        $query = $this->db->select('opd_details.patient_id,opd_details.opd_no,patients.id as pid,patients.patient_name,patients.age,patients.guardian_name,patients.guardian_address,patients.admission_date,patients.gender,staff.name as doctorname,staff.surname')
                ->join('patients', 'opd_details.patient_id = patients.id')
                ->join('staff', 'staff.id = opd_details.cons_doctor', "inner")
                ->where('opd_no', $opd_ipd_no)
                ->get('opd_details');
        return $query->row_array();
    }

    public function getIpdPatient($opd_ipd_no) {
        $query = $this->db->select('ipd_details.patient_id,ipd_details.ipd_no,patients.id as pid,patients.patient_name,patients.age,patients.guardian_name,patients.guardian_address,patients.admission_date,patients.gender,staff.name as doctorname,staff.surname')
                ->join('patients', 'ipd_details.patient_id = patients.id')
                ->join('staff', 'staff.id = ipd_details.cons_doctor', "inner")
                ->where('ipd_no', $opd_ipd_no)
                ->get('ipd_details');
        return $query->row_array();
    }

    public function getAppointmentDate() {
        $query = $this->db->select('opd_details.appointment_date')->get('opd_details');
    }

    public function deleteOPD($opdid) {
        $this->db->where("id", $opdid)->delete("opd_details");
    }

    public function deleteOPDPatient($id) {
        $this->db->where("patient_id", $id)->delete("opd_details");
        $this->db->where("patient_id", $id)->delete("pathology_report");
        $this->db->where("patient_id", $id)->delete("radiology_report");
        $this->db->where("user_id", $id)->where("role", 'patient')->delete("users");
        $this->db->where("id", $id)->delete("patients");
    }

    public function getCharges($patient_id) {
        $query = $this->db->select("sum(apply_charge) as charge")->where("patient_id", $patient_id)->get("patient_charges");
        return $query->row_array();
    }

    public function getPayment($patient_id) {
        $query = $this->db->select("sum(paid_amount) as payment")->where("patient_id", $patient_id)->get("payment");
        return $query->row_array();
    }

    public function patientCredentialReport() {
        $query = $this->db->select('patients.*,users.id as uid,users.user_id,users.username,users.password')
                ->join('users', 'patients.id = users.user_id')
                ->get('patients');
        return $query->result_array();
    }

    public function getPaymentDetail($patient_id) {
        $SQL = 'select patient_charges.amount_due,payment.amount_deposit from (SELECT sum(paid_amount) as `amount_deposit` FROM `payment` WHERE patient_id=' . $this->db->escape($patient_id) . ') as payment ,(SELECT sum(apply_charge) as `amount_due` FROM `patient_charges` WHERE patient_id=' . $this->db->escape($patient_id) . ') as patient_charges';
        $query = $this->db->query($SQL);

        return $query->row();
    }

    public function getIpdBillDetails($id) {
        $query = $this->db->where("patient_id", $id)->get("ipd_billing");
        return $query->row_array();
    }

    public function getDepositAmountBetweenDate($start_date, $end_date) {
        $opd_query = $this->db->select('*')->get('opd_details');
        $bloodbank_query = $this->db->select('*')->get('blood_issue');
        $pharmacy_query = $this->db->select('*')->get('pharmacy_bill_basic');

        $opd_result = $opd_query->result();
        $bloodbank_result = $bloodbank_query->result();

        $result_value = $opd_result;

        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findObjectById($result_value, $st_date, $ed_date);

                if (!empty($return)) {
                    foreach ($return as $r_key => $r_value) {
                        $a = array();
                        $a['amount'] = $r_value->amount;
                        $a['date'] = $r_value->appointment_date;
                        $a['amount_discount'] = 0;
                        $a['amount_fine'] = 0;
                        $a['description'] = '';
                        $a['payment_mode'] = $r_value->payment_mode;
                        $a['inv_no'] = $r_value->patient_id;
                        $return_array[] = $a;
                    }
                }
            }
        }

        return $return_array;
    
    }

    function findObjectById($array, $st_date, $ed_date) {

        $sarray = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($array as $row_key => $row_value) {
                $appointment_date = date("Y-m-d", strtotime($row_value->appointment_date));
                if ($appointment_date == $find) {
                    $sarray[] = $row_value;
                }
            }
        }
        return $sarray;
    }

    public function getEarning($field, $module, $search_field = '', $search_value = '', $search = '') {

        if ((!empty($search_field)) && (!empty($search_value))) {

            $this->db->where($search_field, $search_value);
        }
        if (!empty($search)) {

            $this->db->where($search);
        }

        $query = $this->db->select('sum(' . $field . ') as amount')->get($module);
 
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getPathologyEarning($search = '') {
        if (!empty($search)) {

            $this->db->where($search);
        }
        $query = $this->db->select('sum(charges.standard_charge) as amount')
                ->join('pathology', 'pathology.charge_id = charges.id')
                ->join('pathology_report', 'pathology_report.pathology_id = pathology.id')
                ->where('pathology_report.customer_type', 'direct')
                ->get('charges');
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getRadiologyEarning($search = '') {
        if (!empty($search)) {

            $this->db->where($search);
        }

        $query = $this->db->select('sum(charges.standard_charge) as amount')
                ->join('radio', 'radio.charge_id = charges.id')
                ->join('radiology_report', 'radiology_report.radiology_id = radio.id')
                ->where('radiology_report.customer_type', 'direct')
                ->get('charges');
        $result = $query->row_array();
        return $result["amount"];
    }

    public function getOTEarning($search = '') {
        if (!empty($search)) {

            $this->db->where($search);
        }

        $query = $this->db->select('sum(operation_theatre.apply_charge) as amount')
                ->join('operation_theatre', 'operation_theatre.charge_id = charges.id')
                ->where('operation_theatre.customer_type', 'direct')
                ->get('charges');
        $result = $query->row_array();

        return $result["amount"];
    }

    public function deleteIpdPatient($id) {
        $query = $this->db->select('bed.id')
                        ->join('ipd_details', 'ipd_details.bed = bed.id')
                        ->where("ipd_details.patient_id", $id)->get('bed');

        $result = $query->row_array();
        $bed_id = $result["id"];
        $this->db->where("id", $bed_id)->update('bed', array('is_active' => 'yes'));

        $this->db->where("id", $id)->delete('patients');
        $this->db->where("user_id", $id)->where("role", 'patient')->delete('users');
        $this->db->where("patient_id", $id)->delete('ipd_details');
        $this->db->where("patient_id", $id)->delete('patient_charges');
        $this->db->where("patient_id", $id)->delete('payment');
        $this->db->where("patient_id", $id)->delete('ipd_billing');
    }

    public function getIncome($date_from, $date_to) {
        $object = new stdClass();

        $query1 = $this->getEarning($field = 'amount', $module = 'opd_details', $search_field = '', $search_value = '', $search = array('appointment_date >=' => $date_from, 'appointment_date <=' => $date_to));
        $amount1 = $query1;


        $query2 = $this->getEarning($field = 'paid_amount', $module = 'payment', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount2 = $query2;



        $query3 = $this->getEarning($field = 'net_amount', $module = 'pharmacy_bill_basic', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount3 = $query3;

        $query4 = $this->getEarning($field = 'amount', $module = 'blood_issue', $search_field = '', $search_value = '', $search = array('date_of_issue >=' => $date_from, 'date_of_issue <=' => $date_to . " 23:59:59.993"));
 
        $amount4 = $query4;


        $query5 = $this->getEarning($field = 'amount', $module = 'ambulance_call', $search_field = '', $search_value = '', $search = array('created_at >=' => $date_from, 'created_at <=' => $date_to));
        $amount5 = $query5;

        $query6 = $this->getPathologyEarning(array('pathology_report.reporting_date >=' => $date_from, 'pathology_report.reporting_date <=' => $date_to));
        $amount6 = $query6;


        $query7 = $this->getRadiologyEarning(array('radiology_report.reporting_date >=' => $date_from, 'radiology_report.reporting_date <=' => $date_to));
        $amount7 = $query7;

        $query8 = $this->getOTEarning(array('operation_theatre.date >=' => $date_from, 'operation_theatre.date <=' => $date_to));
        $amount8 = $query8;

        $query9 = $this->getEarning($field = 'amount', $module = 'income', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount9 = $query9;
        $query10 = $this->getEarning($field = 'net_amount', $module = 'ipd_billing', $search_field = '', $search_value = '', $search = array('date >=' => $date_from, 'date <=' => $date_to));
        $amount10 = $query10;

        $amount = $amount1 + $amount2 + $amount3 + $amount4 + $amount5 + $amount6 + $amount7 + $amount8 + $amount9 + $amount10;

        $object->amount = $amount;
        return $object;
    }

    public function getBillInfo($id) {
        $query = $this->db->select('staff.name,staff.surname,staff.employee_id,ipd_billing.date as discharge_date')
                ->join('ipd_billing', 'staff.id = ipd_billing.generated_by')
                ->where('ipd_billing.patient_id', $id)
                ->get('staff');
        $result = $query->row_array();
        return $result;
    }

    public function getStatus($id) {
        $query = $this->db->where("id", $id)->get("patients");
        $result = $query->row_array();
        return $result;
    }

    public function searchPatientNameLike($searchterm) {
        $this->db->select('patients.*')->from('patients');
        $this->db->group_start();
        $this->db->like('patients.patient_name', $searchterm);
        $this->db->group_end();
        $this->db->where('patients.is_active', 'yes');
        $this->db->order_by('patients.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientEmail() {

        $query = $this->db->select("patients.email,patients.id,patients.mobileno")
                ->join("users", "patients.id = users.user_id")
                ->where("users.role", "patient")
                ->where("patients.is_active", "yes")
                ->get("patients");
        return $query->result_array();
    }

}
?>