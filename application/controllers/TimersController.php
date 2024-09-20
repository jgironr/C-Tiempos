<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimersController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model');
        $this->load->library('session');
        $this->load->helper('form');

    }

    public function usage_summary() {
        $filter_type = $this->input->post('filter_type') ?? 'day';  
        $filter_date = $this->input->post('filter_date') ?? date('Y-m-d');  
    
        if (!strtotime($filter_date)) {
            $filter_date = date('Y-m-d'); 
        }
        $workspaces = $this->Model->get_all(); 
        $workspaces_usage = [];
    
        if ($filter_type == 'day') {
            foreach ($workspaces as $workspace) {
                $workspaces_usage[] = [
                    'workspace' => $workspace,
                    'total_usage' => $this->Model->get_daily_usage_by_workspace($filter_date, $workspace->id)
                ];
            }
        } elseif ($filter_type == 'week') {
            $start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($filter_date)));
            $end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($filter_date)));
    
            foreach ($workspaces as $workspace) {
                $workspaces_usage[] = [
                    'workspace' => $workspace,
                    'total_usage' => $this->Model->get_weekly_usage_by_workspace($start_of_week, $end_of_week, $workspace->id)
                ];
            }
        } elseif ($filter_type == 'month') {
            $year = date('Y', strtotime($filter_date));
            $month = date('m', strtotime($filter_date));
    
            foreach ($workspaces as $workspace) {
                $workspaces_usage[] = [
                    'workspace' => $workspace,
                    'total_usage' => $this->Model->get_monthly_usage_by_workspace($year, $month, $workspace->id)
                ];
            }
        }
        $data['workspaces_usage'] = $workspaces_usage;
        $data['filter_type'] = $filter_type; 
        $data['filter_date'] = $filter_date; 
    
        $this->load->view('pages/usage_summary', $data);
    }
    
    

    public function index() {
        $active_workspaces = $this->session->userdata('active_workspaces') ?? [];
        $data['active_workspaces'] = $this->Model->get_by_ids($active_workspaces);    
        $data['intervals'] = $this->Model->get_all_intervals(); 
        $data['content'] = 'pages/index';     
        $this->load->view('default/default', $data);
    }

    public function timer() {
        $data['workspaces'] = $this->Model->get_all();
        $data['active_workspaces'] = $this->session->userdata('active_workspaces') ?? [];
        $data['content'] = 'pages/timer';
        $this->load->view('default/default', $data);
    }

    public function create() {
        $data['content'] = 'pages/create';
        $this->load->view('default/default', $data);
    }

    public function edit($id) {
        $data['workspace'] = $this->Model->get_by_id($id);

        if ($data['workspace']) {
            $data['content'] = 'pages/edit';
            $this->load->view('default/default', $data);
        } else {
            show_404();
        }
    }

    public function update($id) {
        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'rate' => $this->input->post('rate'),
            'color' => $this->input->post('color') ? $this->input->post('color') : '#000000'
        ];

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = time() . '_' . $_FILES['image']['name'];
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $data['image'] = $uploadData['file_name'];

                $workspace = $this->Model->get_by_id($id);
                if (!empty($workspace->image) && $workspace->image !== 'default.png' && file_exists(FCPATH . 'uploads/' . $workspace->image)) {
                    unlink(FCPATH . 'uploads/' . $workspace->image);
                }
            } else {
                $data['image'] = 'default.png';
            }
        }

        $this->Model->update($id, $data);
        redirect('timersController/timer');
    }

    public function delete($id) {
        $this->Model->delete($id);
        redirect('timersController/timer');
    }

    public function db_create() {
        $name = $this->input->post('name');
        $description = $this->input->post('description');
        $color = $this->input->post('color') ? $this->input->post('color') : '#000000'; 
        $rate = $this->input->post('rate');
        $image = 'default.png';

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = time() . '_' . $_FILES['image']['name'];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $image = $uploadData['file_name'];
            } else {
                $image = 'default.png';
            }
        } else {
            $image = 'default.png';
        }

        $data = [
            'name' => $name,
            'description' => $description,
            'color' => $color,
            'rate' => $rate,
            'image' => $image
        ];

        $this->Model->insert($data);
        $workspace_id = $this->db->insert_id();
        redirect('timersController/timer');
    }

    public function toggle_timer() {
    $workspace_id = $this->input->post('workspace_id');
    $active_workspaces = $this->session->userdata('active_workspaces') ?? [];

    if (in_array($workspace_id, $active_workspaces)) {
        $active_workspaces = array_diff($active_workspaces, [$workspace_id]);
    } else {
        $active_workspaces[] = $workspace_id;
    }

    $this->session->set_userdata('active_workspaces', $active_workspaces);
    echo json_encode(['success' => true, 'active' => in_array($workspace_id, $active_workspaces)]);
}


    public function save_interval() {
        $name = $this->input->post('name');
        $duration = $this->input->post('duration');

        $data = [
            'name' => $name,
            'duration' => $duration
        ];

        $this->db->insert('intervals', $data);
        redirect('timersController/interval_settings'); 
    }

    public function interval_settings() {
        $data['intervals'] = $this->Model->get_all_intervals(); 
        $data['content'] = 'pages/interval_settings'; 
        $this->load->view('default/default', $data);
    }

    public function edit_interval($id) {
        $data['interval'] = $this->Model->get_interval_by_id($id);

        if ($this->input->post()) {
            $update_data = [
                'name' => $this->input->post('name'),
                'duration' => $this->input->post('duration'),
            ];
            $this->Model->update_interval($id, $update_data);
            redirect('timersController/manage_intervals');
        }

        $data['intervals'] = $this->Model->get_all_intervals();
        $data['content'] = 'pages/interval_settings';
        $this->load->view('default/default', $data);
    }

    public function delete_interval($id) {
        $this->Model->delete_interval($id);
        redirect('timersController/manage_intervals');
    }

    public function manage_intervals() {
        $data['intervals'] = $this->Model->get_all_intervals();
        $data['content'] = 'pages/manage_intervals';
        $this->load->view('default/default', $data);
    }


    public function get_daily_usage_by_workspace($date, $workspace_id) {
        $this->db->select('SUM(duration) as total_duration, SUM(cost) as total_cost');
        $this->db->where('strftime("%Y-%m-%d", start_time) =', $date);  
        $this->db->where('workspace_id', $workspace_id);
        $query = $this->db->get('workspace_usage_logs');
        return $query->row();
    }
    
    public function get_weekly_usage_by_workspace($start_date, $end_date, $workspace_id) {
        $this->db->select('SUM(duration) as total_duration, SUM(cost) as total_cost');
        $this->db->where('start_time >=', $start_date);  
        $this->db->where('start_time <=', $end_date);    
        $this->db->where('workspace_id', $workspace_id);
        $query = $this->db->get('workspace_usage_logs');
        return $query->row();
    }
    
    public function get_monthly_usage_by_workspace($year, $month, $workspace_id) {
        $this->db->select('SUM(duration) as total_duration, SUM(cost) as total_cost');
        $this->db->where('strftime("%Y", start_time) =', $year); 
        $this->db->where('strftime("%m", start_time) =', str_pad($month, 2, '0', STR_PAD_LEFT));  
        $this->db->where('workspace_id', $workspace_id);
        $query = $this->db->get('workspace_usage_logs');
        return $query->row();
    }
    
}
