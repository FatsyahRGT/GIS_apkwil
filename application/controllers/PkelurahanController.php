<?php

class PkelurahanController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Kelurahan');
        $this->load->model('M_Pkelurahan');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
{
    // Ambil limit dari query string, default ke 10
    $limit = $this->input->get('limit', TRUE) ?: 10;

    // Cek jika limit adalah 'all'
    if ($limit == 'all') {
        $limit = $this->M_Pkelurahan->count_pkelurahan(); // Ambil total data untuk menampilkan semua
    }

    // Konfigurasi pagination
    $config = [
        'base_url' => site_url('PkelurahanController/index'),
        'total_rows' => $this->M_Pkelurahan->count_pkelurahan(),
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
    if ($limit != 'all') {
        $data['pemekaran_kelurahan'] = $this->M_Pkelurahan->tampil_data($limit, $page);
    } else {
        $data['pemekaran_kelurahan'] = $this->M_Pkelurahan->tampil_data_all();
    }

    // Pastikan pemekaran_kelurahan ada dan bukan NULL
    if (empty($data['pemekaran_kelurahan'])) {
        $data['pemekaran_kelurahan'] = []; // Agar tidak ada error saat looping di view
    }

    // Tambahkan variabel limit dan page untuk dikirim ke view
    $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
    $data['limit'] = $limit;
    $data['page'] = $page;

    // Load view
    $this->load->view('app/header');
    $this->load->view('app/sidebar');
    $this->load->view('pkelurahan/index', $data);
    $this->load->view('app/footer');
}


    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all(); // Ambil data kabupaten
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all(); // Ambil data kabupaten
        $data['kelurahan'] = $this->M_Kelurahan->tampil_data_all(); // Ambil data kabupaten

        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkelurahan/tambah', $data);  // Kirim data ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
{
    // Ambil input dari form
    $id_kel_asal = $this->input->post('kelurahan_asal'); // ID Kelurahan asal
    $id_kec_asal = $this->input->post('kecamatan_asal'); // ID Kecamatan asal
    $id_prov_baru = $this->input->post('provinsi_baru'); // ID Provinsi baru
    $id_kab_baru = $this->input->post('kabupaten_baru'); // ID Kabupaten baru
    $id_kec_baru = $this->input->post('kecamatan_baru'); // ID Kecamatan baru

    // Validasi input
    if (empty($id_kel_asal) || empty($id_kec_asal) || empty($id_prov_baru) || empty($id_kab_baru) || empty($id_kec_baru)) {
        $this->session->set_flashdata('message', 'Semua data harus diisi!');
        redirect('pkelurahan/tambah');
    }

    // Ambil data kelurahan untuk mendapatkan provinsi dan kabupaten asal
    $kelurahan = $this->M_Kelurahan->get_kelurahan_by_id($id_kel_asal);

    if (!$kelurahan) {
        $this->session->set_flashdata('message', 'Kelurahan tidak ditemukan!');
        redirect('pkelurahan/tambah');
    }

    $id_kab_asal = $kelurahan->id_kab;
    $id_prov_asal = $kelurahan->id_prov;

    // Data yang akan disimpan ke tabel kel_pemekaran
    $data_pemekaran = [
        'id_prov' => $id_prov_asal,         // ID Provinsi asal
        'id_kab' => $id_kab_asal,           // ID Kabupaten asal
        'id_kec' => $id_kec_asal,           // ID Kecamatan asal
        'id_kel' => $id_kel_asal,           // ID Kelurahan asal
        'id_prov_new' => $id_prov_baru,     // ID Provinsi baru
        'id_kab_new' => $id_kab_baru,       // ID Kabupaten baru
        'id_kec_new' => $id_kec_baru,       // ID Kecamatan baru
        'created_at' => date('Y-m-d H:i:s') // Timestamp
    ];

    // Simpan ke tabel kel_pemekaran
    if ($this->M_Pkelurahan->input_data($data_pemekaran)) {
        // Update data kelurahan di master_kelurahan
        $kelurahan_update_data = [
            'id_prov' => $id_prov_baru,       
            'id_kab' => $id_kab_baru,         
            'id_kec' => $id_kec_baru,         
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->M_Kelurahan->update_kelurahan($id_kel_asal, $kelurahan_update_data)) {
            $this->session->set_flashdata('message', 'Data pemekaran berhasil disimpan!');
            redirect('pkelurahan/index');
        } else {
            $this->session->set_flashdata('message', 'Gagal memperbarui data kelurahan!');
            redirect('pkelurahan/tambah');
        }
    } else {
        $this->session->set_flashdata('message', 'Gagal menyimpan data pemekaran!');
        redirect('pkelurahan/tambah');
    }
}


public function get_provinsi_kabupaten_kecamatan_by_kelurahan()
{
    $id_kelurahan = $this->input->post('id_kelurahan');

    if (!$id_kelurahan || !is_numeric($id_kelurahan)) {
        echo json_encode(['error' => 'ID Kelurahan tidak valid']);
        return;
    }

    // Ambil data kelurahan
    $kelurahan = $this->M_Kelurahan->get_kelurahan_by_id($id_kelurahan);

    if ($kelurahan) {
        // Ambil kabupaten, provinsi, dan kecamatan berdasarkan kelurahan
        $kecamatan = $this->M_Kecamatan->get_kecamatan_by_id($kelurahan->id_kec);
        $kabupaten = $this->M_Kabupaten->get_kabupaten_by_id($kelurahan->id_kab);
        $provinsi = $this->M_Provinsi->get_provinsi_by_id($kelurahan->id_prov);

        echo json_encode([
            'id_provinsi' => $provinsi->id ?? '',
            'nama_provinsi' => $provinsi->nama_provinsi ?? '',
            'id_kabupaten' => $kabupaten->id ?? '',
            'nama_kabupaten' => $kabupaten->nama_kabupaten ?? '',
            'id_kecamatan' => $kecamatan->id ?? '',
            'nama_kecamatan' => $kecamatan->nama_kecamatan ?? ''
        ]);
    } else {
        echo json_encode(['error' => 'Data Kelurahan tidak ditemukan']);
    }
}





    public function detail($id)
    {
        // Ambil data detail pemekaran
        $this->load->model('M_Pkecamatan');
        $data['pemekaran_kecamatan'] = $this->M_Pkecamatan->detail_data($id);

        if (!$data['pemekaran_kecamatan']) {
            $this->session->set_flashdata('message', 'Data tidak ditemukan!');
            redirect('pkecamatan/index');
        }

        // Load view dengan data detail
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkecamatan/detail', $data);
        $this->load->view('app/footer');
    }

    public function get_kabupaten_by_provinsi()
    {
        $provinsi_id = $this->input->post('provinsi_id');
        $kabupaten = $this->db->get_where('master_kabupaten', ['id' => $provinsi_id])->result();
        echo json_encode($kabupaten);
    }
    
    public function get_kecamatan_by_kabupaten()
    {
        $kabupaten_id = $this->input->post('kabupaten_id');
        $kecamatan = $this->db->get_where('master_kecamatan', ['id' => $kabupaten_id])->result();
        echo json_encode($kecamatan);
    }
    
    public function get_kelurahan_by_kecamatan()
    {
        $kecamatan_id = $this->input->post('kecamatan_id');
        $kelurahan = $this->db->get_where('master_kelurahan', ['id' => $kecamatan_id])->result();
        echo json_encode($kelurahan);
    }
    


    public function hapus($id)
    {
        // Hapus kelurahan berdasarkan ID
        $result = $this->M_Pkecamatan->hapus_data(['id' => $id], 'kec_pemekaran');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('pkecamatan/index');
    }

    public function search()
{
    $keyword = $this->input->post('keyword'); // Ambil kata kunci dari form
    $limit = 10; // Jumlah data per halaman
    $start = $this->uri->segment(3, 0); // Halaman saat ini (jika ada)

    $data = [
        'pemekaran_kelurahan' => $this->M_Pkelurahan->get_keyword($keyword, $limit, $start),
        'pagination' => '', // Buat pagination jika diperlukan
        'keyword' => $keyword // Untuk menampilkan kembali kata kunci di form
    ];

    // Load view
    $this->load->view('app/header');
    $this->load->view('app/sidebar');
    $this->load->view('pkelurahan/index', $data);
    $this->load->view('app/footer');
}



}
