<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimersController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Model');
        $this->load->library('session');
    }
   
    public function index() {
        $active_workspaces = $this->session->userdata('active_workspaces') ?? [];
        $data['active_workspaces'] = $this->Model->get_by_ids($active_workspaces);    
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
            'color' => $this->input->post('color')
        ];
        $this->Model->update($id, $data);
        redirect('timersController/timer');
    }

    public function delete($id) {
        $this->Model->delete($id);
        redirect('timersController/timer');
    }

    public function db_create() {
        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'color' => $this->input->post('color')
        ];
        $this->Model->insert($data);
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
}