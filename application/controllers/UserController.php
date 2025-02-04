<?php

class UserController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_User'); 
        $this->load->library('form_validation'); 
    }

    public function index($page = 0)
    {
        $config['base_url']     = site_url('UserController/index');
        $config['total_rows']   = $this->db->count_all('users');
        $config['per_page']     = 5;
        $config['uri_segment']  = 3;

        // Konfigurasi tambahan untuk tampilan pagination
        $config['first_link'] = 'First';
        $config['last_link']  = 'Last';
        $config['next_link']  = 'Next';
        $config['prev_link']  = 'Prev';
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $data['user'] = $this->M_User->tampil_data($config['per_page'], $page)->result();
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = $page; // Kirim offset ke view
        $data['jumlah_user'] = $this->M_User->count_user(); // Kirim jumlah user ke view

        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('user/index', $data);
        $this->load->view('app/footer');
    }

    public function tampilkan_foto($filename)
    {
        $path = './assets/foto/' . $filename;

        if (file_exists($path)) {
            header('Content-Type: ' . mime_content_type($path));
            header('Content-Length: ' . filesize($path));
            readfile($path);
            exit;
        } else {
            echo "File tidak ditemukan.";
        }
    }

    public function tambah()
    {
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('user/tambah');
        $this->load->view('app/footer');
    }

    // Menambah data user baru
    public function tambah_aksi()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('id_role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('app/header');
            $this->load->view('app/sidebar');
            $this->load->view('user/tambah'); // Halaman form tambah user
            $this->load->view('app/footer');
        } else {
            $config['upload_path'] = './assets/foto/'; // Lokasi penyimpanan file
            $config['allowed_types'] = 'jpg|jpeg|png'; // Jenis file yang diizinkan
            $config['max_size'] = 2048; // Ukuran maksimum file dalam KB
            $config['encrypt_name'] = TRUE; // Mengenkripsi nama file

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile_photo')) {
                $error = $this->upload->display_errors(); // Menampilkan error upload
                $this->session->set_flashdata('error', $error);
                $this->load->view('app/header');
                $this->load->view('app/sidebar');
                $this->load->view('user/tambah');
                $this->load->view('app/footer');
            } else {
                $fileData = $this->upload->data();
                $data = [
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'username'   => $this->input->post('username'),
                    'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'id_role'    => $this->input->post('id_role'),
                    'profile_photo' => $fileData['file_name'], 
                    'join_date'  => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $this->M_User->input_data($data, 'users');
                $this->session->set_flashdata('success', 'Data user berhasil ditambahkan!');
                redirect('user/index');
            }
        }
    }


    // Menampilkan detail user
    public function detail($id)
    {
        $data['detail'] = $this->M_User->detail_data($id);
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('user/detail', $data);
        $this->load->view('app/footer');
    }
    


    // Menghapus user
    public function hapus($id)
    {
        $where = ['id' => $id];
        $this->M_User->hapus_data($where, 'users');
        redirect('user/index');
    }
    

    // Halaman untuk edit data user
    public function edit($id)
    {
        $data['user'] = $this->M_User->detail_data($id);
        if (!$data['user']) {
            show_404();
        }
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('user/edit', $data); // Halaman edit user
        $this->load->view('app/footer');
    }

    // Proses update data user
    public function update()
    {
        $id = $this->input->post('id');
    
    // Ambil data user lama
        $user_data = $this->M_User->detail_data($id);
        if (!$user_data) {
            $this->session->set_flashdata('error', 'Data user tidak ditemukan.');
            redirect('user/index');
        }
    
    // Cek apakah foto baru di-upload
        $profile_photo = $user_data->profile_photo; // Default: gunakan foto lama
        if (!empty($_FILES['profile_photo']['name'])) {
        // Konfigurasi upload foto baru
            $config['upload_path'] = './assets/foto/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
        
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile_photo')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect('user/edit/' . $id);
            } else {
                // Hapus foto lama jika ada dan berhasil upload foto baru
                if (!empty($profile_photo) && file_exists('./assets/foto/' . $profile_photo)) {
                    unlink('./assets/foto/' . $profile_photo);
                }
                $fileData = $this->upload->data();
                $profile_photo = $fileData['file_name'];
            }
        }

        // Menyusun data yang akan di-update
        $data = [
            'first_name'    => $this->input->post('first_name'),
            'last_name'     => $this->input->post('last_name'),
            'username'      => $this->input->post('username'),
            'id_role'       => $this->input->post('id_role'),
            'profile_photo' => $profile_photo,
            // 'update_at'     => date('Y-m-d H:i:s'), // Waktu update
        ];

        // Update password jika diinputkan
        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $where = ['id' => $id];
        if ($this->M_User->update_data($where, 'users', $data)) {
            $this->session->set_flashdata('success', 'Data user berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data user.');
        }
    
        redirect('user/index');
    }

    public function search()
    {
        $keyword = $this->input->post('keyword'); // Ambil keyword dari input
        if ($keyword) {
            $this->session->set_userdata('keyword', $keyword); // Simpan keyword ke session
        } else {
            $keyword = $this->session->userdata('keyword'); // Ambil keyword dari session
        }

        // Load library pagination
        $this->load->library('pagination');

        // Konfigurasi pagination
        $config['base_url'] = site_url('UserController/search');
        $config['total_rows'] = $this->M_User->count_keyword($keyword); // Total data sesuai keyword
        $config['per_page'] = 10; // Data per halaman
        $config['uri_segment'] = 3; // Posisi segment pagination

        // Styling pagination
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // Ambil data berdasarkan keyword dan pagination
        $start = $this->uri->segment(3, 0); // Posisi awal data
        $data['user'] = $this->M_User->get_keyword($keyword, $config['per_page'], $start);
        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('user/index', $data);
        $this->load->view('app/footer');
    }

}
