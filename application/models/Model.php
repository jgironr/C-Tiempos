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
        return $this->db->get('timers')->row(); 
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

    
    public function get_all_intervals() {
        $query = $this->db->get('intervals');
        return $query->result(); 
    }

    public function get_interval_by_id($id) {
        return $this->db->where('id', $id)->get('intervals')->row();
    }

    public function insert_interval($data) {
        return $this->db->insert('intervals', $data);
    }

    public function update_interval($id, $data) {
        return $this->db->where('id', $id)->update('intervals', $data);
    }

    public function delete_interval($id) {
        return $this->db->where('id', $id)->delete('intervals');
    }


    public function get_daily_usage_by_workspace($date, $workspace_id) {
        $this->db->select('SUM(duration) as total_duration, SUM(cost) as total_cost');
        $this->db->where('DATE(start_time)', $date);  
        $this->db->where('workspace_id', $workspace_id);
        $query = $this->db->get('workspace_usage_logs');
        return $query->row();
    }
    
   public function get_all_alerts() {
    return $this->db->get('sound_alerts')->result();
}


    public function get_alert_by_id($id) {
        return $this->db->where('id', $id)->get('sound_alerts')->row();
    }

    public function insert_alert($data) {
        $this->db->insert('sound_alerts', $data);
        return $this->db->insert_id(); 
    }

    public function update_alert($id, $data) {
        return $this->db->where('id', $id)->update('sound_alerts', $data);
    }

    public function delete_alert($id) {
        return $this->db->where('id', $id)->delete('sound_alerts');
    }
}
    