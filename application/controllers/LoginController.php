<?php

class LoginController extends CI_Controller {

    // Konstruktor untuk memuat model, helper, dan library yang dibutuhkan.
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Login'); // Memuat model M_Login untuk memproses login
        $this->load->helper(['url', 'form']); // Memuat helper untuk URL dan form
        $this->load->library('form_validation'); // Memuat library untuk validasi form
    }

   
    public function index()
    {
       
        if ($this->session->userdata('username')) {
            redirect('AppController/dashboard'); 
        }

        // Menampilkan halaman login
        $this->load->view('auth/login');
    }

    // Fungsi login_aksi adalah method untuk memproses login ketika user mengirimkan data login
    public function login_aksi()
{
    $this->form_validation->set_rules('username', 'Username', 'required|trim');
    $this->form_validation->set_rules('password', 'Password', 'required|trim');

    if ($this->form_validation->run() == FALSE) {
        // Jika validasi gagal
        $this->session->set_flashdata('error', validation_errors());
        redirect('login');
    } else {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Proses cek login melalui model
        $user = $this->M_Login->cek_login($username);

        if ($user && password_verify($password, $user->password)) {
            // Set session data jika login berhasil
            $this->session->set_userdata([
                'id'            => $user->id,
                'username'      => $user->username,
                'first_name'    => $user->first_name,
                'last_name'     => $user->last_name,
                'profile_photo' => $user->profile_photo, // Menyimpan path foto
                'logged_in'     => true
            ]);
            redirect('dashboard');
        } else {
            // Jika username atau password salah
            $this->session->set_flashdata('error', 'Username atau Password salah.');
            redirect('login');
        }
    }
}

    

    // Fungsi logout untuk menghapus session dan mengarahkan kembali ke halaman login
    public function logout()
    {
        // Menghapus semua data session
        $this->session->sess_destroy();

        // Redirect ke halaman login
        redirect('login');
    }
}
