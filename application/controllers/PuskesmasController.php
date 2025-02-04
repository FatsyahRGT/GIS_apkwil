<?php

class PuskesmasController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Kelurahan');
        $this->load->model('M_Puskesmas');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Puskesmas->count_puskesmas();  // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('PuskesmasController/index'),
            'total_rows' => $this->M_Puskesmas->count_puskesmas(),
            'per_page' => $limit,
            'uri_segment' => 3,
            'reuse_query_string' => true,
            'full_tag_open' => '<div class="pagination-container"><ul class="pagination justify-content-center">',
            'full_tag_close' => '</ul></div>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a class="page-link">',
            'cur_tag_close' => '</a></li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'first_link' => 'First',
            'last_link' => 'Last',
            'next_link' => 'Next',
            'prev_link' => 'Prev',
        ];

        // Jika limit bukan 'all', lakukan pengecekan total halaman untuk pagination
        if ($limit != 'all') {
            $this->pagination->initialize($config);
            $total_pages = ceil($config['total_rows'] / $limit);
            $page = ($page < 0 || $page >= $total_pages * $limit) ? 0 : $page; // Reset ke halaman pertama jika offset tidak valid
        }

        // Ambil data dari model sesuai limit dan offset
        $data['puskesmas'] = ($limit != 'all') ? $this->M_Puskesmas->tampil_data($limit, $page) : $this->M_Puskesmas->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit; 
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('puskesmas/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        // Load data untuk dropdown
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all();
        $data['kelurahan'] = $this->M_Kelurahan->tampil_data_all();

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('puskesmas/tambah', $data);
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil data dari form input
        $puskesmas_data = [
            'nama_puskesmas' => $this->input->post('nama_puskesmas'),
            'alamat' => $this->input->post('alamat'),
            'kode_2' => $this->input->post('kode_2'),
            'kode_3' => $this->input->post('kode_3'),
            
            'id_prov' => $this->input->post('provinsi_id'),
            'id_kab' => $this->input->post('kabupaten_id'),
            'id_kec' => $this->input->post('kecamatan_id')
        ];

        // Validasi input
        if (empty($puskesmas_data['id_prov']) || empty($puskesmas_data['id_kab']) || empty($puskesmas_data['id_kec'])) {
            $this->session->set_flashdata('error', 'Provinsi, Kabupaten, atau Kecamatan tidak valid.');
            redirect('puskesmas/tambah');
        }

        // Simpan data menggunakan model
        $result = $this->M_Puskesmas->input_data($puskesmas_data);

        // Periksa hasil input dan beri feedback
        if ($result) {
            $this->session->set_flashdata('success', 'Data Puskesmas berhasil ditambahkan.');
            redirect('puskesmas/index');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Puskesmas.');
            redirect('puskesmas/tambah');
        }
    }

    public function detail($id_puskesmas)
    {
        // Mengambil data detail puskesmas berdasarkan ID
        $data['detail'] = $this->M_Puskesmas->detail_data($id_puskesmas);

        // Periksa jika data puskesmas ditemukan
        if (!$data['detail']) {
            show_404(); // Menampilkan halaman 404 jika data tidak ada
        }

        // Menampilkan nilai dari data detail provinsi
        $id_provinsi = $data['detail']->id_prov;
        $data['provinsi'] = $this->M_Provinsi->get_by_id($id_provinsi);

        // Menampilkan nilai dari data detail kabupaten
        $id_kabupaten = $data['detail']->id_kab;
        $data['kabupaten'] = $this->M_Kabupaten->get_by_id($id_kabupaten);

        // Menampilkan nilai dari data detail kecamatan
        $id_kecamatan = $data['detail']->id_kec;
        $data['kecamatan'] = $this->M_Kecamatan->get_by_id($id_kecamatan);

        // Load view dengan data yang diterima
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('puskesmas/detail', $data);
        $this->load->view('app/footer');

    }

    public function edit($id)
    {
        $data['puskesmas'] = $this->M_Puskesmas->get_by_id($id);
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all();
    
        if (!$data['puskesmas']) {
            show_404();
        }
    
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('puskesmas/edit', $data);
        $this->load->view('app/footer');
    }
    
    public function update($id)
    {
        $this->form_validation->set_rules('nama_puskesmas', 'Nama Puskesmas', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('kode_2', 'Kode 2', 'trim|required');
        $this->form_validation->set_rules('kode_3', 'Kode 3', 'trim|required');
        $this->form_validation->set_rules('id_prov', 'Provinsi', 'required');
        $this->form_validation->set_rules('id_kab', 'Kabupaten', 'required');
        $this->form_validation->set_rules('id_kec', 'Kecamatan', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $puskesmas_data = [
                'nama_puskesmas' => $this->input->post('nama_puskesmas'),
                'alamat' => $this->input->post('alamat'),
                'kode_2' => $this->input->post('kode_2'),
                'kode_3' => $this->input->post('kode_3'),
                'id_prov' => $this->input->post('id_prov'),
                'id_kab' => $this->input->post('id_kab'),
                'id_kec' => $this->input->post('id_kec')
            ];

            $result = $this->M_Puskesmas->update_data($puskesmas_data);
            
            // Cek apakah update berhasil dan berikan feedback
            if ($result) {
                $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data');
            }
    
            redirect('puskesmas/index');
        }
    }
    
    public function toggle_activation()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // Update status aktif/tidak aktif
        $update_success = $this->M_Puskesmas->update_status($id, $status);

        $message = $update_success
            ? ($status == 0 ? 'Puskesmas berhasil dinonaktifkan ❌' : 'Puskesmas berhasil diaktivasi ✔️')
            : 'Gagal memperbarui status.';

        $this->session->set_flashdata('message', $message);
        redirect('puskesmas/index');
    }

    public function hapus($id)
    {
        // Hapus puskesmas berdasarkan ID
        $result = $this->M_Puskesmas->hapus_data($id);
        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('puskesmas/index');
    }

    public function search()
    {
        // Pencarian puskesmas berdasarkan kata kunci
        $keyword = $this->input->post('keyword');
        $data = [
            'puskesmas' => $this->M_Puskesmas->get_keyword($keyword),
            'pagination' => '', // Kosongkan pagination saat pencarian
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('puskesmas/index', $data);
        $this->load->view('app/footer');
    }

}
