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

    // Métodos auxiliares para verificar roles y autenticación
    private function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']->role === 'admin';
    }

    private function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function usage_summary() {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        // Obtener los cronómetros activos de la sesión
        $active_workspaces = $this->session->userdata('active_workspaces') ?? [];
        $data['active_workspaces'] = $this->Model->get_by_ids($active_workspaces);    
        $data['intervals'] = $this->Model->get_all_intervals(); 

        // Si no hay cronómetros activos, mostramos un mensaje de bienvenida
        if (empty($data['active_workspaces'])) {
            $data['welcome_message'] = true; // Bandera para mostrar la bienvenida
        } else {
            $data['welcome_message'] = false; // Mostrar cronómetros activos
        }

        // Cargar la vista correspondiente
        $data['content'] = 'pages/index';     
        $this->load->view('default/default', $data);
    }

    public function timer() {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        $data['workspaces'] = $this->Model->get_all();
        $data['active_workspaces'] = $this->session->userdata('active_workspaces') ?? [];
        $data['content'] = 'pages/timer';
        $this->load->view('default/default', $data);
    }

    public function create() {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

        $data['content'] = 'pages/create';
        $this->load->view('default/default', $data);
    }

    public function edit($id) {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

        $data['workspace'] = $this->Model->get_by_id($id);

        if ($data['workspace']) {
            $data['content'] = 'pages/edit';
            $this->load->view('default/default', $data);
        } else {
            show_404();
        }
    }

    public function update($id) {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

        $this->Model->delete($id);
        redirect('timersController/timer');
    }

    public function db_create() {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        $data['intervals'] = $this->Model->get_all_intervals(); 
        $data['content'] = 'pages/interval_settings'; 
        $this->load->view('default/default', $data);
    }

    public function edit_interval($id) {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

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
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        if (!$this->isAdmin()) {
            show_error('Acceso denegado', 403);
        }

        $this->Model->delete_interval($id);
        redirect('timersController/manage_intervals');
    }

    public function manage_intervals() {
        if (!$this->isLoggedIn()) {
            redirect('usercontroller/login');
        }

        $data['intervals'] = $this->Model->get_all_intervals();
        $data['content'] = 'pages/manage_intervals';
        $this->load->view('default/default', $data);
    }
}
