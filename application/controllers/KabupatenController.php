<?php

class KabupatenController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Provinsi');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Kabupaten->count_kabupaten();  // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('KabupatenController/index'),
            'total_rows' => $this->M_Kabupaten->count_kabupaten(),
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
        $data['kabupaten'] = ($limit != 'all') ? $this->M_Kabupaten->tampil_data($limit, $page) : $this->M_Kabupaten->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit; 
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kabupaten/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        // Halaman untuk menambah kabupaten baru

        // Mengambil data provinsi untuk dropdown
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Menampilkan semua provinsi

        // Menampilkan form tambah kabupaten dengan data provinsi
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kabupaten/tambah', $data);  // Mengirim data provinsi ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil data dari form input
        $kabupaten_data = [
            'nama' => $this->input->post('nama'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active')
        ];

        // Ambil ID provinsi yang dipilih dari dropdown
        $provinsi_id = $this->input->post('provinsi_id');

        // Pastikan ID provinsi valid (misalnya sudah ada dalam database)
        $provinsi = $this->db->get_where('master_provinsi', ['id' => $provinsi_id])->row();

        if ($provinsi) {
            // Jika provinsi ada, tambahkan ID provinsi ke dalam data kabupaten
            $kabupaten_data['id_prov'] = $provinsi->id;
        
            // Ambil data koordinat latitude dan longitude
            $coords_data = [
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude')
            ];

            // Simpan data kabupaten
            $result = $this->M_Kabupaten->input_data($kabupaten_data, $coords_data);

            if ($result) {
                $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
                redirect('kabupaten/index');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data');
                redirect('kabupaten/tambah');
            }
        } else {
            // Jika provinsi tidak valid
            $this->session->set_flashdata('error', 'Provinsi tidak ditemukan');
            redirect('kabupaten/tambah');
        }
    }

    public function detail($id_kabupaten)
    {
        // Mengambil data detail kabupaten berdasarkan ID
        $data['detail'] = $this->M_Kabupaten->detail_data($id_kabupaten);
    
        // Ambil data provinsi berdasarkan id_prov yang ada pada data kabupaten
        $id_provinsi = $data['detail']->id_prov; // Mengambil id_prov dari data kabupaten
        $data['provinsi'] = $this->M_Provinsi->get_by_id($id_provinsi); // Menampilkan data provinsi

        // Load view dengan data yang diterima
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kabupaten/detail', $data);
        $this->load->view('app/footer');
    }

    public function edit($id)
    {
        // Ambil data kabupaten berdasarkan ID
        $data['kabupaten'] = $this->M_Kabupaten->get_by_id($id);
        $data['coords'] = $this->M_Kabupaten->get_coords_by_id($id);
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();

        // Jika data kabupaten tidak ditemukan, tampilkan error
        if (!$data['kabupaten']) {
            show_404();
        }

        // Tampilkan form edit
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kabupaten/edit', $data);
        $this->load->view('app/footer');
    }

    public function update($id)
    {
        // Validasi input
        $this->form_validation->set_rules('nama', 'Nama Kabupaten', 'trim|required');
        $this->form_validation->set_rules('kode_dagri', 'Kode Wilayah Dagri', 'trim|required');
        $this->form_validation->set_rules('kode_bps', 'Kode Wilayah BPS', 'trim|required');
        $this->form_validation->set_rules('id_prov', 'Provinsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke form edit
            $this->edit($id);
        } else {
            // Ambil data dari form input
            $kabupaten_data = [
                'nama' => $this->input->post('nama'),
                'kode_dagri' => $this->input->post('kode_dagri'),
                'kode_bps' => $this->input->post('kode_bps'),
                'active' => $this->input->post('active'),
                'id_prov' => $this->input->post('id_prov'), // Ambil ID provinsi yang dipilih
                'id' => $id // Pastikan ID kabupaten terupdate
            ];

            $coords_data = [
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude')
            ];

            // Panggil fungsi update_data dari model untuk update kedua tabel
            $result = $this->M_Kabupaten->update_data($kabupaten_data, $coords_data);

            // Redirect ke halaman daftar kabupaten setelah update
            redirect('kabupaten/index');
        }
    }

    public function hapus($id)
    {
        // Hapus kabupaten berdasarkan ID
        $result = $this->M_Kabupaten->hapus_data(['id' => $id], 'master_kabupaten');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('kabupaten/index');
    }

    public function toggle_activation()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // Update status aktif/tidak aktif
        $update_success = $this->M_Kabupaten->update_status($id, $status);

        $message = $update_success
            ? ($status == 0 ? 'Kabupaten berhasil dinonaktifkan ❌' : 'Kabupaten berhasil diaktivasi ✔️')
            : 'Gagal memperbarui status.';

        $this->session->set_flashdata('message', $message);
        redirect('kabupaten/index');
    }

    public function search()
    {
        // Pencarian kabupaten berdasarkan kata kunci
        $keyword = $this->input->post('keyword');
        $data = [
            'kabupaten' => $this->M_Kabupaten->get_keyword($keyword),
            'pagination' => '', // Kosongkan pagination saat pencarian
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kabupaten/index', $data);
        $this->load->view('app/footer');
    }
}