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
        $this->db->where('DATE(start_time)', $date);  // Usar la fecha de inicio en formato DATE
        $this->db->where('workspace_id', $workspace_id);
        $query = $this->db->get('workspace_usage_logs');
        return $query->row();
    }


    public function get_all_usage_logs() {
        $this->db->select('workspace_usage_logs.*, timers.name as workspace_name');
        $this->db->from('workspace_usage_logs');
        $this->db->join('timers', 'workspace_usage_logs.workspace_id = timers.id');
        $this->db->order_by('start_time', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_usage_logs_by_day($date) {
        $this->db->select('
            DATE(start_time) AS fecha,
            timers.name AS workspace_name,
            MIN(start_time) AS hora_inicio,
            MAX(end_time) AS hora_fin,
            SUM(duration) AS tiempo_total,
            SUM(cost) AS dinero_generado
        ');
        $this->db->from('workspace_usage_logs');
        $this->db->join('timers', 'workspace_usage_logs.workspace_id = timers.id');
        $this->db->where('DATE(start_time)', $date);
        $this->db->group_by(['fecha', 'workspace_name']);
        $this->db->order_by('fecha', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
    

    public function get_usage_logs_by_week($date) {
        $this->db->select('
            DATE(start_time) AS fecha,
            timers.name AS workspace_name,
            MIN(start_time) AS hora_inicio,
            MAX(end_time) AS hora_fin,
            SUM(duration) AS tiempo_total,
            SUM(cost) AS dinero_generado
        ');
        $this->db->from('workspace_usage_logs');
        $this->db->join('timers', 'workspace_usage_logs.workspace_id = timers.id');
        $this->db->where("date(start_time) >= date('{$date}', 'weekday 0', '-6 days')", null, false);
        $this->db->where("date(start_time) <= date('{$date}', 'weekday 0')", null, false);
        $this->db->group_by(['fecha', 'workspace_name']);
        $this->db->order_by('fecha', 'DESC');
    
        $query = $this->db->get();
        return $query->result();
    }    
    
    
    public function get_usage_logs_by_month($date) {
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
    
        $this->db->select('
            DATE(start_time) AS fecha,
            timers.name AS workspace_name,
            MIN(start_time) AS hora_inicio,
            MAX(end_time) AS hora_fin,
            SUM(duration) AS tiempo_total,
            SUM(cost) AS dinero_generado
        ');
        $this->db->from('workspace_usage_logs');
        $this->db->join('timers', 'workspace_usage_logs.workspace_id = timers.id');
        $this->db->where("strftime('%Y', start_time) =", $year);
        $this->db->where("strftime('%m', start_time) =", $month);
        $this->db->group_by('DATE(start_time), workspace_name');
        $this->db->order_by('fecha', 'DESC');
    
        $query = $this->db->get();
        return $query->result();
    }
}
    






