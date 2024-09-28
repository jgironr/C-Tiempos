<?php

class UserController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User');  // Cargar el modelo User
        $this->load->library('session');  // Asegurarse de que la sesión esté cargada
    }

    // Métodos auxiliares para verificar roles y autenticación
    private function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']->role === 'admin';
    }

    private function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    // Método para listar todos los usuarios (solo si el usuario está logueado)
    public function index() {
        if (!$this->isLoggedIn()) {  // Verifica si el usuario está logueado
            redirect('usercontroller/login');  // Redirigir al login si no está autenticado
        }

        $data['users'] = $this->User->getAll();
        $this->load->view('users/list', $data);  // Cargar la vista de usuarios
    }

    // Método para crear un nuevo usuario (Solo admin)
    public function create() {
        if (!$this->isLoggedIn()) {  // Verifica si está logueado
            redirect('usercontroller/login');  // Redirigir al login
        }

        if (!$this->isAdmin()) {  // Verifica si el usuario es admin
            show_error('Acceso denegado', 403);  // Mostrar error si no es admin
        }

        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role' => $this->input->post('role')
            );
            $this->User->create($data);
            redirect('usercontroller/index');  // Redireccionar al listado de usuarios
        } else {
            $this->load->view('users/create');  // Cargar la vista de creación de usuario
        }
    }

    // Método para editar un usuario existente (Solo admin)
    public function edit($id) {
        if (!$this->isLoggedIn()) {  // Verifica si está logueado
            redirect('usercontroller/login');  // Redirigir al login
        }

        if (!$this->isAdmin()) {  // Verifica si el usuario es admin
            show_error('Acceso denegado', 403);  // Mostrar error si no es admin
        }

        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            );
            $this->User->update($id, $data);
            redirect('usercontroller/index');  // Redireccionar al listado de usuarios
        } else {
            $data['user'] = $this->User->get($id);
            $this->load->view('users/edit', $data);  // Cargar la vista de edición de usuario
        }
    }

    // Método para eliminar un usuario (Solo admin)
    public function delete($id) {
        if (!$this->isLoggedIn()) {  // Verifica si está logueado
            redirect('usercontroller/login');  // Redirigir al login
        }

        if (!$this->isAdmin()) {  // Verifica si el usuario es admin
            show_error('Acceso denegado', 403);  // Mostrar error si no es admin
        }

        $this->User->delete($id);
        redirect('usercontroller/index');  // Redireccionar al listado de usuarios
    }

    // Método para iniciar sesión (login)
    public function login() {
        if ($this->isLoggedIn()) {  // Si ya está logueado, redirigir al index
            redirect('usercontroller/index');
        }

        if ($this->input->post()) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user = $this->User->authenticate($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                redirect('usercontroller/index');  // Redireccionar al listado de usuarios
            } else {
                $data['error'] = 'Credenciales inválidas';
                $this->load->view('users/login', $data);  // Cargar la vista de login con error
            }
        } else {
            $this->load->view('users/login');  // Cargar la vista de login
        }
    }

    // Método para cerrar sesión (logout)
    public function logout() {
        session_destroy();
        redirect('usercontroller/login');  // Redireccionar a la página de login
    }
}
