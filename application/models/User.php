<?php

class User extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Crear un nuevo usuario
    public function create($data) {
        return $this->db->insert('users', $data);
    }

    // Obtener un usuario por su ID
    public function get($id) {
        return $this->db->get_where('users', array('id' => $id))->row();
    }

     // Obtener un usuario por su correo
     public function getByEmail($email) {
        return $this->db->get_where('users', array('email' => $email))->row();
    }

    // Listar todos los usuarios
    public function getAll() {
        return $this->db->get('users')->result();
    }

    // Actualizar un usuario
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    // Eliminar un usuario
    public function delete($id) {
        return $this->db->delete('users', array('id' => $id));
    }

    // Autenticar usuario (login)
    public function authenticate($email, $password) {
        $user = $this->db->get_where('users', array('email' => $email))->row();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}

