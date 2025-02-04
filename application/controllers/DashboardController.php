<?php

class DashboardController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Kecamatan');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->session->userdata('username')) {
            redirect('dashboard');
        }

        // Ambil data username dari session supaya bisa dilempar ke view ketika login sebagai siapa
        $data['username'] = $this->session->userdata('username');
        $data['profile_photo'] = $this->session->userdata('profile_photo');

        // Load model
        $this->load->model('M_User');
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Kabupaten');

        // Ambil jumlah user
        $data['jumlah_user'] = $this->M_User->count_user();
        $data['jumlah_provinsi'] = $this->M_Provinsi->count_provinsi();
        $data['jumlah_kecamatan'] = $this->M_Kecamatan->count_kecamatan();
        $data['jumlah_kabupaten'] = $this->M_Kabupaten->count_kabupaten();

        // Load views
        $this->load->view('app/header', $data);
        $this->load->view('app/sidebar', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('app/footer', $data);
    }

}
