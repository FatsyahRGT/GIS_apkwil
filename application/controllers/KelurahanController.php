<?php

class KelurahanController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Kelurahan');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Kelurahan->count_kelurahan();  // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('KelurahanController/index'),
            'total_rows' => $this->M_Kelurahan->count_kelurahan(),
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
        $data['kelurahan'] = ($limit != 'all') ? $this->M_Kelurahan->tampil_data($limit, $page) : $this->M_Kelurahan->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit; 
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kelurahan/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all();
    
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kelurahan/tambah', $data);  // Pastikan data dikirim ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil data dari form input
        $kelurahan_data = [
            'nama' => $this->input->post('nama'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active'),
            'id_prov' => $this->input->post('provinsi_id'),  // Menggunakan ID provinsi
            'id_kab' => $this->input->post('kabupaten_id'),  // Menggunakan ID kabupaten
            'id_kec' => $this->input->post('kecamatan_id')   // Menggunakan ID kabupaten
        ];

        // Mendapatkan nama provinsi dan kabupaten untuk disimpan
        $provinsi_id = $this->input->post('provinsi_id');
        $kabupaten_id = $this->input->post('kabupaten_id');
        $kecamatan_id = $this->input->post('kecamatan_id');

        // Pastikan ID provinsi dan kabupaten valid
        if (empty($provinsi_id) || empty($kabupaten_id) || empty($kecamatan_id)) {
            $this->session->set_flashdata('error', 'Provinsi atau Kabupaten tidak valid');
            redirect('kelurahan/tambah');
        }

        // Ambil data koordinat dari inputan
        $coords_data = [
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        // Panggil fungsi input_data dari model untuk memasukkan data ke dalam tabel
        $result = $this->M_Kelurahan->input_data($kelurahan_data, $coords_data);

        // Periksa hasil input dan beri feedback
        if ($result) {
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            redirect('kelurahan/index');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data');
            redirect('kelurahan/tambah');
        }
    }



public function detail($id_kelurahan)
{
    // Mengambil data detail kelurahan berdasarkan ID
    $data['detail'] = $this->M_Kelurahan->detail_data($id_kelurahan);

    // Periksa jika data kelurahan ditemukan
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
    $this->load->view('kelurahan/detail', $data);
    $this->load->view('app/footer');
}

    public function edit($id)
    {
        // Ambil data kelurahan berdasarkan ID
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil semua data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();  // Ambil semua data kabupaten
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all();  // Ambil semua data kecamatan
        $data['kelurahan'] = $this->M_Kelurahan->get_by_id($id);
        $data['coords'] = $this->M_Kelurahan->get_coords_by_id($id);
    
        // Jika data kelurahan tidak ditemukan, tampilkan error
        if (!$data['kelurahan']) {
            show_404();
        }
    
        // Tampilkan form edit
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kelurahan/edit', $data);
        $this->load->view('app/footer');
    }
    

public function update($id)
{
    // Validasi input
    $this->form_validation->set_rules('nama', 'Nama Kelurahan', 'trim|required');
    $this->form_validation->set_rules('kode_dagri', 'Kode Wilayah Dagri', 'trim|required');
    $this->form_validation->set_rules('kode_bps', 'Kode Wilayah BPS', 'trim|required');
    $this->form_validation->set_rules('id_prov', 'Provinsi', 'required');
    $this->form_validation->set_rules('id_kab', 'Kabupaten', 'required');
    $this->form_validation->set_rules('id_kec', 'Kecamatan', 'required');

    if ($this->form_validation->run() == FALSE) {
        // Jika validasi gagal, kembalikan ke form edit
        $this->edit($id);
    } else {
        // Ambil data dari form input
        $kelurahan_data = [
            'nama' => $this->input->post('nama'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active'),
            'id_prov' => $this->input->post('id_prov'), // Ambil ID provinsi yang dipilih
            'id_kab' => $this->input->post('id_kab'), // Ambil ID kabupaten yang dipilih
            'id_kec' => $this->input->post('id_kec'), // Ambil ID kecamatan yang dipilih
            'id' => $id // Pastikan ID kecamatan terupdate
        ];

        $coords_data = [
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        // Panggil fungsi update_data dari model untuk update kedua tabel
        $result = $this->M_Kelurahan->update_data($kelurahan_data, $coords_data);

        // Cek apakah update berhasil dan berikan feedback
        if ($result) {
            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data');
        }

        // Redirect setelah update
        redirect('kelurahan/index');
    }
}


    public function toggle_activation()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // Update status aktif/tidak aktif
        $update_success = $this->M_Kelurahan->update_status($id, $status);

        $message = $update_success
            ? ($status == 0 ? 'kelurahan berhasil dinonaktifkan ❌' : 'kelurahan berhasil diaktivasi ✔️')
            : 'Gagal memperbarui status.';

        $this->session->set_flashdata('message', $message);
        redirect('kelurahan/index');
    }

    public function hapus($id)
    {
        // Hapus kelurahan berdasarkan ID
        $result = $this->M_Kelurahan->hapus_data(['id' => $id], 'master_kelurahan');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('kelurahan/index');
    }

    public function search()
    {
        // Pencarian kelurahan berdasarkan kata kunci
        $keyword = $this->input->post('keyword');
        $data = [
            'kelurahan' => $this->M_Kelurahan->get_keyword($keyword),
            'pagination' => '', // Kosongkan pagination saat pencarian
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kelurahan/index', $data);
        $this->load->view('app/footer');
    }

}
