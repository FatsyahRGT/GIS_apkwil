<?php

class M_Pkabupaten extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->library('form_validation');
    }

    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('
            kp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mp_baru.nama AS provinsi_baru,
            kp.created_at
        ');
        $this->db->from('kab_pemekaran kp');
        $this->db->join('master_provinsi mp_asal', 'kp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'kp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_provinsi mp_baru', 'kp.id_prov_new = mp_baru.id', 'left');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('id, id_prov, id_kab, id_prov_new, created_at');
        $this->db->from('kab_pemekaran');
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

    // Get kabupaten by provinsi ID
    public function get_kabupaten_by_provinsi($id_provinsi)
    {
        // Call method from M_Kabupaten
        return $this->M_Kabupaten->get_kabupaten_by_provinsi($id_provinsi);
    }

    // Insert new data into kab_pemekaran and update master_kabupaten
    public function input_data($data)
    {
        // Insert into kab_pemekaran
        $pkabupaten = [
            'id_prov' => $data['id_prov'],
            'id_kab' => $data['id_kab'],
            'id_prov_new' => $data['id_prov_new'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('kab_pemekaran', $pkabupaten);

        // Update master_kabupaten
        $kabupaten_update = [
            'id_prov' => $data['id_prov_new'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->where('id', $data['id_kab']);
        return $this->db->update('master_kabupaten', $kabupaten_update);
    }

    public function detail_data($id)
    {
        $this->db->select('kp.id, kp.id_prov, kp.id_prov_new, kp.id_kab, 
                           p1.nama AS provinsi_asal, 
                           p2.nama AS provinsi_baru, 
                           k.nama AS kabupaten,
                           kp.created_at');
        $this->db->from('kab_pemekaran kp');
        $this->db->join('master_provinsi p1', 'kp.id_prov = p1.id');
        $this->db->join('master_provinsi p2', 'kp.id_prov_new = p2.id');
        $this->db->join('master_kabupaten k', 'kp.id_kab = k.id');
        $this->db->where('kp.id', $id);

        $query = $this->db->get();
        return $query->row(); // Mengembalikan satu baris data
    }

    // public function update_data($id, $data)
    // {
    //     // Update tabel kab_pemekaran
    //     $pkabupaten = [
    //         'id_prov' => $data['id_prov'],
    //         'id_kab' => $data['id_kab'],
    //         'id_prov_new' => $data['id_prov_new'],
    //         'updated_at' => date('Y-m-d H:i:s'),
    //     ];
    //     $this->db->where('id', $id);
    //     $this->db->update('kab_pemekaran', $pkabupaten);
    
    //     // Update tabel master_kabupaten
    //     $kabupaten_update = [
    //         'id_prov' => $data['id_prov_new'], // Update provinsi baru
    //         'updated_at' => date('Y-m-d H:i:s'),
    //     ];
    //     $this->db->where('id', $data['id_kab']);
    //     return $this->db->update('master_kabupaten', $kabupaten_update);
    // }
    

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Count the total number of kabupaten pemekaran
    public function count_pkabupaten()
    {
        return $this->db->count_all('kab_pemekaran');
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('
            kp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mp_baru.nama AS provinsi_baru,
            kp.created_at
        ');
        $this->db->from('kab_pemekaran kp');
        $this->db->join('master_provinsi mp_asal', 'kp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'kp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_provinsi mp_baru', 'kp.id_prov_new = mp_baru.id', 'left');
        $this->db->group_start();
        $this->db->like('mk_asal.nama', $keyword);
        $this->db->or_like('mp_asal.nama', $keyword);
        $this->db->or_like('mp_baru.nama', $keyword);
        $this->db->or_like('kp.created_at', $keyword);
        $this->db->group_end();
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

