<?php

class PkabupatenController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Pkabupaten');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Pkabupaten->count_pkabupaten(); // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('PkabupatenController/index'),
            'total_rows' => $this->M_Pkabupaten->count_pkabupaten(),
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
    
        // Pastikan pemekaran_kabupaten ada dan bukan NULL
        if (empty($data['pemekaran_kabupaten'])) {
            $data['pemekaran_kabupaten'] = []; // Agar tidak ada error saat looping di view
        }
    
        // Jika pagination aktif, buat link pagination
    
        // Tambahkan variabel limit dan page untuk dikirim ke view
        $data['pemekaran_kabupaten'] = ($limit != 'all') ? $this->M_Pkabupaten->tampil_data($limit, $page) : $this->M_Pkabupaten->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit;
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkabupaten/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all(); // Ambil data kabupaten

        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkabupaten/tambah', $data);  // Kirim data ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil input dari form
        $id_kab_asal = $this->input->post('kabupaten_asal'); // ID Kabupaten asal
        $id_prov_baru = $this->input->post('provinsi_baru'); // ID Provinsi baru

        // Validasi input
        if (empty($id_kab_asal) || empty($id_prov_baru)) {
            $this->session->set_flashdata('message', 'Semua data harus diisi!');
            redirect('pkabupaten/tambah');
        }

        // Ambil provinsi asal berdasarkan ID kabupaten
        $kabupaten = $this->M_Kabupaten->get_kabupaten_by_id($id_kab_asal);

        if (!$kabupaten) {
            $this->session->set_flashdata('message', 'Kabupaten tidak ditemukan!');
            redirect('pkabupaten/tambah');
        }

        $id_prov_asal = $kabupaten->id_prov; // ID Provinsi asal dari kabupaten

        // Data yang akan disimpan ke tabel kab_pemekaran
        $data_pemekaran = [
            'id_prov' => $id_prov_asal,     // ID Provinsi asal
            'id_prov_new' => $id_prov_baru, // ID Provinsi baru
            'id_kab' => $id_kab_asal,       // ID Kabupaten asal
            'created_at' => date('Y-m-d H:i:s'), // Timestamp
        ];

        // Simpan data pemekaran ke tabel kab_pemekaran
        $insert_id = $this->M_Pkabupaten->input_data($data_pemekaran);

        if ($insert_id) {
            // Update data kabupaten untuk provinsi baru
            $kabupaten_update_data = [
                'id_prov' => $id_prov_baru,           // Update ke provinsi baru
                'updated_at' => date('Y-m-d H:i:s'),  // Timestamp
            ];

            $update_kabupaten = $this->M_Kabupaten->update_kabupaten($id_kab_asal, $kabupaten_update_data);

            if ($update_kabupaten) {
                $this->session->set_flashdata('message', 'Data pemekaran berhasil disimpan!');
                redirect('pkabupaten/index');
            } else {
                $this->session->set_flashdata('message', 'Gagal memindahkan kabupaten ke provinsi baru!');
                redirect('pkabupaten/tambah');
            }
        } else {
            $this->session->set_flashdata('message', 'Gagal menyimpan data pemekaran!');
            redirect('pkabupaten/tambah');
        }
    }

    public function get_provinsi_by_kabupaten($id_kabupaten)
    {
        // Validasi ID Kabupaten
        if (!$id_kabupaten) {
            echo json_encode(['error' => 'ID Kabupaten tidak valid']);
            return;
        }

        // Ambil data kabupaten berdasarkan ID
        $kabupaten = $this->M_Kabupaten->get_kabupaten_by_id($id_kabupaten);

        if ($kabupaten) {
            // Ambil data provinsi berdasarkan ID Provinsi di kabupaten
            $provinsi = $this->M_Provinsi->get_provinsi_by_id($kabupaten->id_prov);

            if ($provinsi) {
                echo json_encode([
                    'id_provinsi' => $provinsi->id,
                    'nama_provinsi' => $provinsi->nama,
                ]);
            } else {
                echo json_encode(['error' => 'Provinsi tidak ditemukan']);
            }
        } else {
            echo json_encode(['error' => 'Kabupaten tidak ditemukan']);
        }
    }

    public function detail($id)
    {
        // Ambil data detail pemekaran
        $this->load->model('M_Pkabupaten');
        $data['pemekaran_kabupaten'] = $this->M_Pkabupaten->detail_data($id);

        if (!$data['pemekaran_kabupaten']) {
            $this->session->set_flashdata('message', 'Data tidak ditemukan!');
            redirect('pkabupaten/index');
        }

        // Load view dengan data detail
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkabupaten/detail', $data);
        $this->load->view('app/footer');
    }



    public function hapus($id)
    {
        // Hapus kelurahan berdasarkan ID
        $result = $this->M_Pkabupaten->hapus_data(['id' => $id], 'kab_pemekaran');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('pkabupaten/index');
    }

    public function search()
    {
        $keyword = $this->input->post('keyword'); // Ambil kata kunci dari form
        $data = [
            'pemekaran_kabupaten' => $this->M_Pkabupaten->get_keyword($keyword),
            'pagination' => '', // Tidak ada pagination untuk pencarian
            'keyword' => $keyword // Untuk menampilkan kembali kata kunci di form
        ];
    
        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('pkabupaten/index', $data);
        $this->load->view('app/footer');
    }

}
