<?php

class PkesController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();  
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Puskesmas');
        $this->load->model('M_Pkes');
        $this->load->model('M_Pkecamatan');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
{
    // Ambil limit dari query string, default ke 10 jika tidak ada nilai
    $limit = $this->input->get('limit', TRUE) ?: 10;

    // Jika limit adalah 'all', ambil total data
    if ($limit == 'all') {
        $limit = $this->M_Pkes->count_ppuskesmas(); // Ambil total data untuk menampilkan semua
    }

    // Pastikan limit tidak nol untuk menghindari pembagian dengan nol
    $limit = ($limit > 0) ? $limit : 10;

    // Konfigurasi pagination
    $config = [
        'base_url' => site_url('PkesController/index'),
        'total_rows' => $this->M_Pkes->count_ppuskesmas(),
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

    // Jika limit bukan 'all', inisialisasi pagination
    if ($limit != 'all') {
        $this->pagination->initialize($config);

        // Pastikan total_rows tidak nol untuk menghindari error division by zero
        $total_pages = ($config['total_rows'] > 0) ? ceil($config['total_rows'] / $limit) : 1;
        $page = ($page < 0 || $page >= $total_pages * $limit) ? 0 : $page;
    }

    // Ambil data berdasarkan limit
    $data['pemekaran_puskesmas'] = ($limit != 'all') ? $this->M_Pkes->tampil_data($limit, $page) : $this->M_Pkes->tampil_data_all();
    $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
    $data['limit'] = $limit;
    $data['page'] = $page;

    // Load tampilan
    $this->load->view('app/header');
    $this->load->view('app/sidebar');
    $this->load->view('ppuskes/index', $data);
    $this->load->view('app/footer');
}





    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all(); // Ambil data kabupaten
        $data['kecamatan'] = $this->M_Kecamatan->tampil_data_all(); // Ambil data kabupaten
        $data['puskesmas'] = $this->M_Puskesmas->tampil_data_all(); // Ambil data puskesmas

        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('ppuskes/tambah', $data);  // Kirim data ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil input dari form
        $id_pus_asal = $this->input->post('puskesmas_asal');
        $id_prov_baru = $this->input->post('provinsi_baru');
        $id_kab_baru = $this->input->post('kabupaten_baru');
        $id_kec_baru = $this->input->post('kecamatan_baru');
    
        // Validasi input
        if (empty($id_pus_asal) || empty($id_prov_baru) || empty($id_kab_baru) || empty($id_kec_baru)) {
            $this->session->set_flashdata('message', 'Semua data harus diisi!');
            redirect('ppuskes/tambah');
        }
    
        // Ambil data Puskesmas Asal
        $puskesmas = $this->M_Puskesmas->get_puskesmas_by_id($id_pus_asal);
    
        if (!$puskesmas) {
            $this->session->set_flashdata('message', 'Puskesmas tidak ditemukan!');
            redirect('ppuskes/tambah');
        }
    
        // Ambil data asal
        $id_kec_asal = $puskesmas->id_kec;
        $id_kab_asal = $puskesmas->id_kab;
        $id_prov_asal = $puskesmas->id_prov;
    
        // Data untuk disimpan ke tabel pemekaran puskesmas
        $data_pemekaran = [
            'id_prov' => $id_prov_asal,
            'id_kab' => $id_kab_asal,
            'id_kec' => $id_kec_asal,
            'id_pus' => $id_pus_asal,
            'id_prov_new' => $id_prov_baru,
            'id_kab_new' => $id_kab_baru,
            'id_kec_new' => $id_kec_baru,
            'created_at' => date('Y-m-d H:i:s')
        ];
    
        // Simpan data pemekaran ke tabel pemekaran puskesmas
        $insert_id = $this->M_Pkes->input_data($data_pemekaran);
    
        if ($insert_id) {
            // Update data puskesmas, kabupaten, kecamatan ke provinsi baru
            $puskesmas_update_data = [
                'id_prov' => $id_prov_baru,
                'id_kab' => $id_kab_baru,
                'id_kec' => $id_kec_baru,
            ];
    
            // Update Puskesmas, Kabupaten dan Kecamatan untuk pemekaran
            $update_puskesmas = $this->M_Puskesmas->update_puskesmas($id_pus_asal, $puskesmas_update_data);
            $update_kabupaten = $this->M_Kabupaten->update_kabupaten($id_kab_asal, ['id_prov' => $id_prov_baru]);
            $update_kecamatan = $this->M_Kecamatan->update_kecamatan($id_kec_asal, ['id_kab' => $id_kab_baru]);
    
            // Verifikasi dan beri pesan berdasarkan hasil update
            if ($update_puskesmas && $update_kabupaten && $update_kecamatan) {
                $this->session->set_flashdata('message', 'Data pemekaran Puskesmas berhasil disimpan dan diperbarui!');
                redirect('ppuskes/index');
            } else {
                $this->session->set_flashdata('message', 'Gagal memindahkan data pemekaran!');
                redirect('ppuskes/tambah');
            }
        } else {
            $this->session->set_flashdata('message', 'Gagal menyimpan data pemekaran!');
            redirect('ppuskes/tambah');
        }
    }    


    public function get_provinsi_kabupaten_kecamatan_by_puskesmas($id_puskesmas)
    {
        if (!$id_puskesmas || !is_numeric($id_puskesmas)) {
            echo json_encode(['error' => 'ID Puskesmas tidak valid']);
            return;
        }
    
        // Ambil data Puskesmas
        $puskesmas = $this->M_Puskesmas->get_puskesmas_by_id($id_puskesmas);
    
        if ($puskesmas) {
            // Ambil data Kecamatan terkait
            $kecamatan = $this->M_Kecamatan->get_kecamatan_by_id($puskesmas->id_kec);
            $kabupaten = $this->M_Kabupaten->get_kabupaten_by_id($kecamatan->id_kab );
            $provinsi = $this->M_Provinsi->get_provinsi_by_id($kabupaten->id_prov );
    
            if ($kecamatan && $kabupaten && $provinsi) {
                echo json_encode([
                    'nama_provinsi' => $provinsi->nama ?? '',
                    'nama_kabupaten' => $kabupaten->nama ?? '',
                    'nama_kecamatan' => $kecamatan->nama_kec ?? ''
                ]);
            } else {
                echo json_encode(['error' => 'Data Kecamatan, Kabupaten, atau Provinsi tidak ditemukan']);
            }
        } else {
            echo json_encode(['error' => 'Data Puskesmas tidak ditemukan']);
        }
    }
    

    public function detail($id)
    {
        // Ambil data detail pemekaran
        $this->load->model('M_Pkes');
        $data['pemekaran_puskesmas'] = $this->M_Pkes->detail_data($id);

        if (!$data['pemekaran_puskesmas']) {
            $this->session->set_flashdata('message', 'Data tidak ditemukan!');
            redirect('ppuskes/index');
        }

        // Load view dengan data detail
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('ppuskes/detail', $data);
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
        // Hapus pemekaran puskesmas berdasarkan ID
        $result = $this->M_Pkes->hapus_data(['id' => $id], 'pus_pemekaran');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('ppuskes/index');
    }


    public function search()
    {
        $keyword = $this->input->post('keyword'); // Ambil kata kunci dari form
        $limit = 10; // Jumlah data per halaman
        $start = $this->uri->segment(3, 0); // Halaman saat ini (jika ada)

        $data = [
            'pemekaran_puskesmas' => $this->M_Pkes->get_keyword($keyword, $limit, $start),
            'pagination' => '', // Buat pagination jika diperlukan
            'keyword' => $keyword // Untuk menampilkan kembali kata kunci di form
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('ppuskes/index', $data); // Ubah view ke ppuskes
        $this->load->view('app/footer');
    }



}
