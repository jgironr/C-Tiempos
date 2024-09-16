<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model {     

    public function get_all() {
        return $this->db->get('timers')->result();
    }

    public function get_by_ids($ids) {
        if (empty($ids)) {
            return [];
        }
        $this->db->where_in('id', $ids);
        $query = $this->db->get('timers');
        return $query->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('timers');
        return $query->row();
    }

    public function insert($data) {
        return $this->db->insert('timers', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('timers', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('timers');
    }
}