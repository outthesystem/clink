<?php
class Pharmacy_model extends CI_Model {

    public function add($pharmacy) {
        $this->db->insert('pharmacy', $pharmacy);
        return $this->db->insert_id();
    }

    public function searchFullText() {
        $this->db->select('pharmacy.*,medicine_category.id as medicine_category_id,medicine_category.medicine_category');
        $this->db->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'left');
        $this->db->where('`pharmacy`.`medicine_category_id`=`medicine_category`.`id`');
        $this->db->order_by('pharmacy.medicine_name');
        $query = $this->db->get('pharmacy');
        return $query->result_array();
    }

    public function getDetails($id) {
        $this->db->select('pharmacy.*,medicine_category.id as medicine_category_id,medicine_category.medicine_category');
        $this->db->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'inner');
        $this->db->where('pharmacy.id', $id);
        $this->db->order_by('pharmacy.id', 'desc');
        $query = $this->db->get('pharmacy');
        return $query->row_array();
    }

    public function update($pharmacy) {
        $query = $this->db->where('id', $pharmacy['id'])
                ->update('pharmacy', $pharmacy);
    }

    public function delete($id) {
        $this->db->where("id", $id)->delete('pharmacy');
    }

    public function getPharmacy($id = null) {
        $query = $this->db->get('pharmacy');
        return $query->result_array();
    }

    public function medicineDetail($medicine_batch) {
        $this->db->insert('medicine_batch_details', $medicine_batch);
    }

    public function getMedicineBatch($pharm_id) {
        $this->db->select('medicine_batch_details.*, pharmacy.id as pharmacy_id, pharmacy.medicine_name');
        $this->db->join('pharmacy', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'inner');
        $this->db->where('pharmacy.id', $pharm_id);
        $query = $this->db->get('medicine_batch_details');
        return $query->result();
    }

    public function getMedicineName() {
        $query = $this->db->select('pharmacy.id,pharmacy.medicine_name')->get('pharmacy');
        return $query->result_array();
    }

    public function addBill($data) {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("pharmacy_bill_basic", $data);
        } else {
            $this->db->insert("pharmacy_bill_basic", $data);
            $Id = $this->db->insert_id();
            return $Id;
        }
    }

    public function addBillBatch($data) {
        $query = $this->db->insert_batch('pharmacy_bill_detail', $data);
    }

    public function updateBillBatch($data) {
        $this->db->where('pharmacy_bill_basic_id', $data['id'])->update('pharmacy_bill_detail');
    }

    public function updateBillDetail($data) {
        $this->db->where('id', $data['id'])->update('pharmacy_bill_detail', $data);
    }

    public function deletePharmacyBill($id) {
        $query = $this->db->where("pharmacy_bill_basic_id", $id)->delete("pharmacy_bill_detail");
        if ($query) {
            $this->db->where("id", $id)->delete("pharmacy_bill_basic");
        }
    }

    public function getBillBasic() {
        $query = $this->db->order_by('id', 'desc')->get('pharmacy_bill_basic');
        return $query->result_array();
    }

    public function get_medicine_name($medicine_category_id) {
        $query = $this->db->where("medicine_category_id", $medicine_category_id)->get("pharmacy");
        return $query->result_array();
    }

    public function getBillDetails($id) {
        $this->db->select('pharmacy_bill_basic.*');
        $this->db->where('pharmacy_bill_basic.id', $id);
        $query = $this->db->get('pharmacy_bill_basic');
        return $query->row_array();
    }

    public function getAllBillDetails($id) {
        $query = $this->db->select('pharmacy_bill_detail.*,pharmacy.medicine_name,pharmacy.unit,pharmacy.id as medicine_id')
                ->join('pharmacy', 'pharmacy_bill_detail.medicine_name = pharmacy.id')
                ->where('pharmacy_bill_basic_id', $id)
                ->get('pharmacy_bill_detail');
        return $query->result_array();
    }

    public function getQuantity($batch_no) {
        $query = $this->db->select('medicine_batch_details.id,medicine_batch_details.available_quantity,medicine_batch_details.quantity,medicine_batch_details.sale_rate')
                ->where('batch_no', $batch_no)
                ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function availableQty($update_quantity) {
        $query = $this->db->where('id', $update_quantity['id'])
                ->update('medicine_batch_details', $update_quantity);
    }

    public function totalQuantity($pharmacy_id) {
        $query = $this->db->select('sum(available_quantity) as total_qty')
                ->where('pharmacy_id', $pharmacy_id)
                ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function searchBillReport($date_from, $date_to) {
        $this->db->select('pharmacy_bill_basic.*');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get("pharmacy_bill_basic");
        return $query->result_array();
    }

    public function delete_medicine_batch($id) {
        $this->db->where("id", $id)->delete("medicine_batch_details");
    }

    public function delete_bill_detail($delete_arr) {
        foreach ($delete_arr as $key => $value) {
            $id = $value["id"];
            $this->db->where("id", $id)->delete("prescription");
        }
    }

    public function getBillNo() {
        $query = $this->db->select("max(id) as id")->get('pharmacy_bill_basic');

        return $query->row_array();
    }

    public function getExpiryDate($batch_no) {
        $query = $this->db->where("batch_no", $batch_no)->get('medicine_batch_details');
        return $query->row_array();
    }

    public function getBatchNoList($medicine) {
        $query = $this->db->where('pharmacy_id', $medicine)
                ->where('available_quantity >', 0)
                ->get('medicine_batch_details');
        return $query->result_array();
    }

    public function addBadStock($data) {
        $this->db->insert("medicine_bad_stock", $data);
    }

    public function updateMedicineBatch($data) {
        $this->db->where("id", $data["id"])->update("medicine_batch_details", $data);
    }

    public function getMedicineBadStock($id) {
        $query = $this->db->where("pharmacy_id", $id)->get("medicine_bad_stock");
        return $query->result();
    }

    public function deleteBadStock($id) {
        $this->db->where("id", $id)->delete("medicine_bad_stock");
    }

}
?>