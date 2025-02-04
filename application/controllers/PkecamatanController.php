<?php

class PkecamatanController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Pkecamatan');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Pkecamatan->count_pkecamatan(); // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('PkecamatanController/index'),
            'total_rows' => $this->M_Pkecamatan->count_pkecamatan(),
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
    
        // Pastikan pemekaran_kecamatan ada dan bukan NULL
        if (empty($data['pemekaran_kecamatan'])) {
            $data['pemekaran_kecamatan'] = []; // Agar tidak ada error saat looping di view
        }
    
        // Jika pagination aktif, buat link pagination
    
        // Tambahkan variabel limit dan page untuk dikirim ke view
        $data['pemekaran_kecamatan'] = ($limit != 'all') ? $this->M_Pkecamatan->tampil_data($limit, $page) : $this->M_Pkecamatan->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit;
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkecamatan/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all(); // Ambil data kabupaten
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all(); // Ambil data kabupaten

        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkecamatan/tambah', $data);  // Kirim data ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
{
    // Ambil input dari form
    $id_kec_asal = $this->input->post('kecamatan_asal'); // ID Kecamatan asal
    $id_prov_baru = $this->input->post('provinsi_baru'); // ID Provinsi baru
    $id_kab_baru = $this->input->post('kabupaten_baru'); // ID Kabupaten baru

    // Validasi input
    if (empty($id_kec_asal) || empty($id_prov_baru) || empty($id_kab_baru)) {
        $this->session->set_flashdata('message', 'Semua data harus diisi!');
        redirect('pkecamatan/tambah');
    }

    // Ambil kecamatan asal untuk mendapatkan kabupaten dan provinsi asal
    $kecamatan = $this->M_Kecamatan->get_kecamatan_by_id($id_kec_asal);

    if (!$kecamatan) {
        $this->session->set_flashdata('message', 'Kecamatan tidak ditemukan!');
        redirect('pkecamatan/tambah');
    }

    $id_kab_asal = $kecamatan->id_kab; // ID Kabupaten asal dari kecamatan
    $id_prov_asal = $kecamatan->id_prov; // ID Provinsi asal dari kecamatan

    // Data yang akan disimpan ke tabel kec_pemekaran
    $data_pemekaran = [
        'id_prov' => $id_prov_asal,         // ID Provinsi asal
        'id_kab' => $id_kab_asal,           // ID Kabupaten asal
        'id_kec' => $id_kec_asal,           // ID Kecamatan asal
        'id_prov_new' => $id_prov_baru,     // ID Provinsi baru
        'id_kab_new' => $id_kab_baru,       // ID Kabupaten baru
        'created_at' => date('Y-m-d H:i:s') // Timestamp
    ];

    // Simpan data pemekaran ke tabel kec_pemekaran
    $insert_id = $this->M_Pkecamatan->input_data($data_pemekaran);

    if ($insert_id) {
        // Update data kecamatan untuk provinsi dan kabupaten baru
        $kecamatan_update_data = [
            'id_prov' => $id_prov_baru,          // Update ke provinsi baru
            'id_kab' => $id_kab_baru,            // Update ke kabupaten baru
            'updated_at' => date('Y-m-d H:i:s')  // Timestamp
        ];

        $update_kecamatan = $this->M_Kecamatan->update_kecamatan($id_kec_asal, $kecamatan_update_data);

        if ($update_kecamatan) {
            $this->session->set_flashdata('message', 'Data pemekaran berhasil disimpan dan kecamatan berhasil dipindahkan!');
            redirect('pkecamatan/index');
        } else {
            $this->session->set_flashdata('message', 'Gagal memindahkan kecamatan ke kabupaten dan provinsi baru!');
            redirect('pkecamatan/tambah');
        }
    } else {
        $this->session->set_flashdata('message', 'Gagal menyimpan data pemekaran!');
        redirect('pkecamatan/tambah');
    }
}


public function get_provinsi_kabupaten_by_kecamatan($id_kecamatan)
{
    // Validasi ID Kecamatan
    if (!$id_kecamatan || !is_numeric($id_kecamatan)) {
        echo json_encode(['error' => 'ID Kecamatan tidak valid']);
        return;
    }

    // Ambil data kecamatan
    $kecamatan = $this->M_Kecamatan->get_kecamatan_by_id($id_kecamatan);

    if ($kecamatan) {
        // Ambil kabupaten dan provinsi berdasarkan kecamatan
        $kabupaten = $this->M_Kabupaten->get_kabupaten_by_id($kecamatan->id_kab);
        $provinsi = $this->M_Provinsi->get_provinsi_by_id($kecamatan->id_prov);

        if ($kabupaten && $provinsi) {
            echo json_encode([
                'nama_kabupaten' => $kabupaten->nama ?? '',
                'nama_provinsi' => $provinsi->nama ?? ''
            ]);
        } else {
            echo json_encode(['error' => 'Data kabupaten atau provinsi tidak ditemukan']);
        }
    } else {
        echo json_encode(['error' => 'Data kecamatan tidak ditemukan']);
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

//     public function edit($id)
// {
//     // Ambil semua data yang dibutuhkan
//     $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil semua data provinsi
//     $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all(); // Ambil semua data kabupaten
//     $data['pemekaran_kabupaten'] = $this->M_Pkabupaten->get_data_by_id($id); // Ambil data detail berdasarkan ID

//     // Load view dengan data
//     $this->load->view('app/header');
//     $this->load->view('app/sidebar');
//     $this->load->view('pkabupaten/edit', $data);  // Kirim data ke view
//     $this->load->view('app/footer');
// }

// public function update($id)
// {
//     // Validasi input
//     $this->form_validation->set_rules('id_prov_new', 'Provinsi Baru', 'required');

//     if ($this->form_validation->run() === FALSE) {
//         // Jika validasi gagal, kembali ke halaman edit dengan pesan error
//         $this->session->set_flashdata('error', validation_errors());
//         redirect('pkabupaten/edit/' . $id);
//     } else {
//         // Ambil input dari form
//         $data = [
//             'id_prov' => $this->input->post('id_prov'),
//             'id_kab' => $this->input->post('id_kab'),
//             'id_prov_new' => $this->input->post('id_prov_new'),
//         ];

//         // Panggil fungsi update_data di model
//         $update = $this->M_Pkabupaten->update_data($id, $data);

//         if ($update) {
//             $this->session->set_flashdata('message', 'Data berhasil diperbarui!');
//             redirect('pkabupaten/index');
//         } else {
//             $this->session->set_flashdata('error', 'Gagal memperbarui data.');
//             redirect('pkabupaten/edit/' . $id);
//         }
//     }
// }


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
        'pemekaran_kecamatan' => $this->M_Pkecamatan->get_keyword($keyword, $limit, $start),
        'pagination' => '', // Buat pagination jika diperlukan
        'keyword' => $keyword // Untuk menampilkan kembali kata kunci di form
    ];

    // Load view
    $this->load->view('app/header');
    $this->load->view('app/sidebar');
    $this->load->view('pkecamatan/index', $data);
    $this->load->view('app/footer');
}


}
