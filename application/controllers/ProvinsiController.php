<?php

class ProvinsiController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Provinsi->count_provinsi();  // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('ProvinsiController/index'),
            'total_rows' => $this->M_Provinsi->count_provinsi(),
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
        $data['provinsi'] = ($limit != 'all') ? $this->M_Provinsi->tampil_data($limit, $page) : $this->M_Provinsi->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit; 
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('provinsi/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        // Halaman untuk menambah provinsi baru
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('provinsi/tambah');
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil data dari form input
        $provinsi_data = [
            'nama' => $this->input->post('nama'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active')
        ];

        $coords_data = [
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        // Panggil fungsi input_data dari model
        $result = $this->M_Provinsi->input_data($provinsi_data, $coords_data);

        if ($result) {
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            redirect('provinsi/index');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data');
            redirect('provinsi/tambah');
        }
    }

    public function detail($id_provinsi)
    {
        // Mengambil data detail provinsi berdasarkan ID
        $data['detail'] = $this->M_Provinsi->detail_data($id_provinsi);

        // Cek jika data provinsi ditemukan
        if (!$data['detail']) {
            show_404(); // Menampilkan halaman 404 jika data tidak ada
        }

        // Load view dengan data yang diterima
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('provinsi/detail', $data);
        $this->load->view('app/footer');
    }

    public function edit($id)
    {
        // Ambil data provinsi berdasarkan ID
        $data['provinsi'] = $this->M_Provinsi->get_by_id($id);
        $data['coords'] = $this->M_Provinsi->get_coords_by_id($id);

        // Jika data provinsi tidak ditemukan, tampilkan error
        if (!$data['provinsi']) {
            show_404();
        }

        // Tampilkan form edit
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('provinsi/edit', $data);
        $this->load->view('app/footer');
        
    }

    public function update($id)
    {
        // Ambil data dari form input
        $provinsi_data = [
            'nama' => $this->input->post('nama'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active'),
            'id' => $id // Pastikan ID provinsi terupdate
        ];

        $coords_data = [
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        // Panggil fungsi update_data dari model untuk update kedua tabel
        $result = $this->M_Provinsi->update_data($provinsi_data, $coords_data);

        redirect('provinsi/index');
    }

    public function toggle_activation()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // Update status aktif/tidak aktif
        $update_success = $this->M_Provinsi->update_status($id, $status);

        $message = $update_success
            ? ($status == 0 ? 'Provinsi berhasil dinonaktifkan ❌' : 'Provinsi berhasil diaktivasi ✔️')
            : 'Gagal memperbarui status.';

        $this->session->set_flashdata('message', $message);
        redirect('provinsi/index');
    }

    public function hapus($id)
    {
        // Hapus provinsi berdasarkan ID
        $result = $this->M_Provinsi->hapus_data(['id' => $id], 'master_provinsi');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('provinsi/index');
    }

    public function search()
    {
        // Pencarian provinsi berdasarkan kata kunci
        $keyword = $this->input->post('keyword');
        $data = [
            'provinsi' => $this->M_Provinsi->get_keyword($keyword),
            'pagination' => '', // Kosongkan pagination saat pencarian
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('provinsi/index', $data);
        $this->load->view('app/footer');
    }

}
