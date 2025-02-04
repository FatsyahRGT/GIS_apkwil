<?php

class RegisterController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Login'); // Memuat model M_Login
        $this->load->helper(['url', 'form']); // Memuat helper URL dan form
        $this->load->library('form_validation'); // Memuat library validasi form
    }

    // Menampilkan halaman registrasi
    public function index() {
        $this->load->view('auth/register'); // Menampilkan view form registrasi
    }

    // Proses registrasi
    public function register_aksi() {
        // Validasi input form
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('id_role', 'Role', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');

        // Jika validasi gagal
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/register'); // Tampilkan form register lagi
        } else {
            // Jika validasi berhasil, proses penyimpanan data
            $data = [
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'username'   => $this->input->post('username'),
                'id_role'    => $this->input->post('id_role'),          
                'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // Hash password
            ];

            // Simpan data ke database melalui model
            $this->M_Login->insert_user($data);

            // Set flash message
            $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');

            // Redirect ke halaman login
            redirect('login');
        }
    }
}
