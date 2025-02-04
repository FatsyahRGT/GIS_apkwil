<?php

class KecamatanController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->library('form_validation');
    }

    public function index($page = 0)
    {
        // Ambil limit dari query string, default ke 10
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Cek jika limit adalah 'all'
        if ($limit == 'all') {
            $limit = $this->M_Kecamatan->count_kecamatan();  // Ambil total data untuk menampilkan semua
        }

        // Konfigurasi pagination
        $config = [
            'base_url' => site_url('KecamatanController/index'),
            'total_rows' => $this->M_Kecamatan->count_kecamatan(),
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
        $data['kecamatan'] = ($limit != 'all') ? $this->M_Kecamatan->tampil_data($limit, $page) : $this->M_Kecamatan->tampil_data_all();
        $data['pagination'] = ($limit != 'all') ? $this->pagination->create_links() : '';
        $data['limit'] = $limit; 
        $data['page'] = $page;

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kecamatan/index', $data);
        $this->load->view('app/footer');
    }

    public function tambah()
    {
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();
    
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kecamatan/tambah', $data);  // Pastikan data dikirim ke view
        $this->load->view('app/footer');
    }

    public function tambah_aksi()
    {
        // Ambil data dari form input
        $kecamatan_data = [
            'nama_kec' => $this->input->post('nama_kec'),
            'kode_dagri' => $this->input->post('kode_dagri'),
            'kode_bps' => $this->input->post('kode_bps'),
            'active' => $this->input->post('active'),
            'id_prov' => $this->input->post('provinsi_id'),  // Menggunakan ID provinsi
            'id_kab' => $this->input->post('kabupaten_id')   // Menggunakan ID kabupaten
        ];

        // Mendapatkan nama provinsi dan kabupaten untuk disimpan
        $provinsi_id = $this->input->post('provinsi_id');
        $kabupaten_id = $this->input->post('kabupaten_id');

        // Pastikan ID provinsi dan kabupaten valid
        if (empty($provinsi_id) || empty($kabupaten_id)) {
            $this->session->set_flashdata('error', 'Provinsi atau Kabupaten tidak valid');
            redirect('kecamatan/tambah');
        }

        // Ambil data koordinat dari inputan
        $coords_data = [
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude')
        ];

        // Panggil fungsi input_data dari model untuk memasukkan data ke dalam tabel
        $result = $this->M_Kecamatan->input_data($kecamatan_data, $coords_data);

        // Periksa hasil input dan beri feedback
        if ($result) {
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
            redirect('kecamatan/index');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data');
            redirect('kecamatan/tambah');
        }
    }



    public function detail($id_kecamatan)
    {
        // Mengambil data detail kecamatan berdasarkan ID
        $data['detail'] = $this->M_Kecamatan->detail_data($id_kecamatan);

        // Cek jika data kecamatan ditemukan
        if (!$data['detail']) {
            show_404(); // Menampilkan halaman 404 jika data tidak ada
        }

        //menampilkan nilai dari data detail provinsi
        $id_provinsi = $data['detail']->id_prov; 
        $data['provinsi'] = $this->M_Provinsi->get_by_id($id_provinsi); 

        //menampilkan nilai dari data detail kabupaten
        $id_kabupaten = $data['detail']->id_kab; 
        $data['kabupaten'] = $this->M_Kabupaten->get_by_id($id_kabupaten); 

        // Load view dengan data yang diterima
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kecamatan/detail', $data);
        $this->load->view('app/footer');
    }

    // Controller: Kecmatan Controller
    public function edit($id)
    {
        // Ambil data kecamatan berdasarkan ID
        $data['kecamatan'] = $this->M_Kecamatan->get_by_id($id);
        $data['provinsi'] = $this->M_Provinsi->tampil_data_all();  // Ambil semua data provinsi
        $data['kabupaten'] = $this->M_Kabupaten->tampil_data_all();  // Ambil semua data kabupaten
        $data['coords'] = $this->M_Kecamatan->get_coords_by_id($id);
    
        // Jika data kecamatan tidak ditemukan, tampilkan error
        if (!$data['kecamatan']) {
            show_404();
        }
    
        // Tampilkan form edit
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kecamatan/edit', $data);
        $this->load->view('app/footer');
    }
    

    public function update($id)
    {
        // Validasi input
        $this->form_validation->set_rules('nama_kec', 'Nama Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kode_dagri', 'Kode Wilayah Dagri', 'trim|required');
        $this->form_validation->set_rules('kode_bps', 'Kode Wilayah BPS', 'trim|required');
        $this->form_validation->set_rules('id_prov', 'Provinsi', 'required');
        $this->form_validation->set_rules('id_kab', 'Kabupaten', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke form edit
            $this->edit($id);
        } else {
            // Ambil data dari form input
            $kecamatan_data = [
                'nama_kec' => $this->input->post('nama_kec'),
                'kode_dagri' => $this->input->post('kode_dagri'),
                'kode_bps' => $this->input->post('kode_bps'),
                'active' => $this->input->post('active'),
                'id_prov' => $this->input->post('id_prov'), // Ambil ID provinsi yang dipilih
                'id_kab' => $this->input->post('id_kab'), // Ambil ID kabupaten yang dipilih
                'id' => $id // Pastikan ID kecamatan terupdate
            ];

            $coords_data = [
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude')
            ];

            // Panggil fungsi update_data dari model untuk update kedua tabel
            $result = $this->M_Kecamatan->update_data($kecamatan_data, $coords_data);

            // Cek apakah update berhasil dan berikan feedback
            if ($result) {
                $this->session->set_flashdata('success', 'Data berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data');
            }

            // Redirect setelah update
            redirect('kecamatan/index');
        }
    }


    public function toggle_activation()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // Update status aktif/tidak aktif
        $update_success = $this->M_Kecamatan->update_status($id, $status);

        $message = $update_success
            ? ($status == 0 ? 'Kecamatan berhasil dinonaktifkan ❌' : 'Kecamatan berhasil diaktivasi ✔️')
            : 'Gagal memperbarui status.';

        $this->session->set_flashdata('message', $message);
        redirect('kecamatan/index');
    }

    public function hapus($id)
    {
        // Hapus kecamatan berdasarkan ID
        $result = $this->M_Kecamatan->hapus_data(['id' => $id], 'master_kecamatan');

        $this->session->set_flashdata('message', $result ? 'Data berhasil dihapus.' : 'Data berhasil dihapus.');
        redirect('kecamatan/index');
    }

    public function search()
    {
        // Pencarian kecamatan berdasarkan kata kunci
        $keyword = $this->input->post('keyword');
        $data = [
            'kecamatan' => $this->M_Kecamatan->get_keyword($keyword),
            'pagination' => '', // Kosongkan pagination saat pencarian
        ];

        // Load view
        $this->load->view('app/header');
        $this->load->view('app/sidebar');
        $this->load->view('kecamatan/index', $data);
        $this->load->view('app/footer');
    }

}
