<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicle_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('vehicles', $data);
        } else {
            $this->db->insert('vehicles', $data);
            return $this->db->insert_id();
        }
    }

    public function get($id = null) {
        $this->db->select()->from('vehicles');
        if ($id != null) {
            $this->db->where('vehicles.id', $id);
        } else {
            $this->db->order_by('vehicles.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }

    public function getDetails($id) {
        $query = $this->db->select('vehicles.*')->where('id', $id)->get('vehicles');
        return $query->row_array();
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('vehicles');
    }

    public function addCallAmbulance($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('ambulance_call', $data);
        } else {
            $this->db->insert('ambulance_call', $data);
            return $this->db->insert_id();
        }
    }

    public function getCallAmbulance() {
        $query = $this->db->select('ambulance_call.*,vehicles.vehicle_no,vehicles.vehicle_model')
                ->join('vehicles', 'vehicles.id = ambulance_call.vehicle_no')
                ->get('ambulance_call');
        return $query->result_array();
    }

    public function getCallDetails($id) {
        $query = $this->db->select('ambulance_call.*')->where('id', $id)->get('ambulance_call');
        return $query->row_array();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('ambulance_call');
    }

}
?>