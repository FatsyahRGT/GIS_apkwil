<?php

class M_Pkecamatan extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->library('form_validation');
    }

    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('
            kp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mp_baru.nama AS provinsi_baru,
            mk_baru.nama AS kabupaten_baru,
            kp.created_at
        ');
        $this->db->from('kec_pemekaran kp');

        // Join untuk provinsi asal
        $this->db->join('master_provinsi mp_asal', 'kp.id_prov = mp_asal.id', 'left');

        // Join untuk kabupaten asal
        $this->db->join('master_kabupaten mk_asal', 'kp.id_kab = mk_asal.id', 'left');

        // Join untuk kecamatan asal
        $this->db->join('master_kecamatan mc_asal', 'kp.id_kec = mc_asal.id', 'left');

        // Join untuk provinsi baru
        $this->db->join('master_provinsi mp_baru', 'kp.id_prov_new = mp_baru.id', 'left');

        // Join untuk kabupaten baru
        $this->db->join('master_kabupaten mk_baru', 'kp.id_kab_new = mk_baru.id', 'left');

        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }


    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('id, id_prov, id_kab, id_kec, id_prov_new, id_kab_new, created_at');
        $this->db->from('kec_pemekaran');
        return $this->db->get()->result();
    }

    // Get the name of the province based on the province ID
    public function get_provinsi_name($provinsi_id)
    {
        $this->db->select('nama');
        $this->db->from('master_provinsi');
        $this->db->where('id', $provinsi_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;
    }

    // Get the name of the kabupaten based on the kabupaten ID
    public function get_kabupaten_name($kabupaten_id)
    {
        $this->db->select('nama');
        $this->db->from('master_kabupaten');
        $this->db->where('id', $kabupaten_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;
    }

    public function get_kecamatan_name($kecamatan_id)
    {
        $this->db->select('nama_kec');
        $this->db->from('master_kecamatan');
        $this->db->where('id', $kabupaten_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;
    }

    // Get kabupaten by provinsi ID
    public function get_kabupaten_by_provinsi($id_provinsi)
    {
        // Call method from M_Kabupaten
        return $this->M_Kabupaten->get_kabupaten_by_provinsi($id_provinsi);
    }

    // Insert new data into kec_pemekaran and update master_kabupaten
    public function input_data($data)
    {
        // Insert into kec_pemekaran
    $pkecamatan = [
        'id_prov' => $data['id_prov'],
        'id_kab' => $data['id_kab'],
        'id_kec' => $data['id_kec'],
        'id_prov_new' => $data['id_prov_new'],
        'id_kab_new' => $data['id_kab_new'],
        'created_at' => date('Y-m-d H:i:s'),
    ];
        $this->db->insert('kec_pemekaran', $pkecamatan);

        // Update master_kecamatan
    $kecamatan_update = [  // Perbaiki nama variabel ini
        'id_prov' => $data['id_prov_new'],
        'id_kab' => $data['id_kab_new'],
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    
        // Update berdasarkan id_kab, ini bisa disesuaikan dengan id_kec jika Anda perlu
        $this->db->where('id', $data['id_kec']);
        return $this->db->update('master_kecamatan', $kecamatan_update);  // Gunakan $kabupaten_update di sini
    }

    public function detail_data($id)
    {
        $this->db->select('
            kp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mp_baru.nama AS provinsi_baru,
            mk_baru.nama AS kabupaten_baru,
            kp.created_at
        ');
        $this->db->from('kec_pemekaran kp');
        $this->db->join('master_provinsi mp_asal', 'kp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'kp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'kp.id_kec = mc_asal.id', 'left');
        $this->db->join('master_provinsi mp_baru', 'kp.id_prov_new = mp_baru.id', 'left');
        $this->db->join('master_kabupaten mk_baru', 'kp.id_kab_new = mk_baru.id', 'left');
        $this->db->where('kp.id', $id);

        return $this->db->get()->row(); // Mengembalikan satu baris data
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Count the total number of kecamatan pemekaran
    public function count_pkecamatan()
    {
        return $this->db->count_all('kec_pemekaran');
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('
            kp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mp_baru.nama AS provinsi_baru,
            mk_baru.nama AS kabupaten_baru,
            kp.created_at
        ');
        $this->db->from('kec_pemekaran kp');

        // Join untuk provinsi, kabupaten, dan kecamatan asal
        $this->db->join('master_provinsi mp_asal', 'kp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'kp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'kp.id_kec = mc_asal.id', 'left');

        // Join untuk provinsi dan kabupaten baru
        $this->db->join('master_provinsi mp_baru', 'kp.id_prov_new = mp_baru.id', 'left');
        $this->db->join('master_kabupaten mk_baru', 'kp.id_kab_new = mk_baru.id', 'left');

        // Kondisi pencarian
        $this->db->group_start();
        $this->db->like('mp_asal.nama', $keyword);
        $this->db->or_like('mk_asal.nama', $keyword);
        $this->db->or_like('mc_asal.nama_kec', $keyword);
        $this->db->or_like('mp_baru.nama', $keyword);
        $this->db->or_like('mk_baru.nama', $keyword);
        $this->db->or_like('kp.created_at', $keyword);
        $this->db->group_end();

        // Kembalikan hasil
        return $this->db->get()->result();
    }

    // Get a limited number of kabupaten data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('kab_pemekaran')->result();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('kab_pemekaran', ['id' => $id])->row();
    }

}

