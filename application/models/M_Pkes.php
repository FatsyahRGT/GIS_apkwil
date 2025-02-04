<?php

class M_Pkes extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('M_Provinsi');
        $this->load->model('M_Kabupaten');
        $this->load->model('M_Kecamatan');
        $this->load->model('M_Kelurahan');
        $this->load->model('M_Puskesmas');
        $this->load->library('form_validation');
    }

    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('
            pp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mpus_asal.nama_puskesmas AS puskesmas_asal,
            mpus_asal.alamat AS alamat_asal,
            pp.created_at
        '); 
        $this->db->from('pus_pemekaran pp');

        // Join ke tabel Provinsi, Kabupaten, Kecamatan, dan Puskesmas Asal
        $this->db->join('master_provinsi mp_asal', 'pp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'pp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'pp.id_kec = mc_asal.id', 'left');
        $this->db->join('master_puskesmas mpus_asal', 'pp.id_pus = mpus_asal.id', 'left');

        // Menambahkan limit untuk hasil query
        $this->db->limit($limit, $start);

        $result = $this->db->get()->result();
    
        // Debugging: Cek apakah data ditemukan
        if (empty($result)) {
            log_message('error', 'Data tidak ditemukan untuk puskesmas');
        }

        return $result;
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('
            pp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mpus_asal.nama_puskesmas AS puskesmas_asal,
            mpus_asal.alamat AS alamat_asal,
            pp.created_at
        '); 
        $this->db->from('pus_pemekaran pp');
    
        // Join ke tabel Provinsi, Kabupaten, Kecamatan, dan Puskesmas Asal
        $this->db->join('master_provinsi mp_asal', 'pp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'pp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'pp.id_kec = mc_asal.id', 'left');
        $this->db->join('master_puskesmas mpus_asal', 'pp.id_pus = mpus_asal.id', 'left');
    
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
        $this->db->where('id', $kecamatan_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;
    }

    public function get_puskesmas_name($puskesmas_id)
    {
        $this->db->select('nama_puskesmas');
        $this->db->from('master_puskesmas');
        $this->db->where('id', $puskesmas_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;
    }


    // Get kabupaten by provinsi ID
    public function get_kabupaten_by_provinsi($id_provinsi)
    {
        // Call method from M_Kabupaten
        return $this->M_Kabupaten->get_kabupaten_by_provinsi($id_provinsi);
    }

    // Insert new data into pus_pemekaran and update master_puskesmas
    public function input_data($data)
    {
        // Insert ke tabel kec_pemekaran
        $this->db->insert('pus_pemekaran', $data);
        $insert_id = $this->db->insert_id(); // Ambil ID yang baru dimasukkan

        if ($insert_id) {
            // Update master_puskesmas
            $update_puskesmas = [
                'id_prov' => $data['id_prov_new'],
                'id_kab' => $data['id_kab_new'],
                'id_kec' => $data['id_kec_new'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->where('id', $data['id_pus']);
            $this->db->update('pus_pemekaran', $update_puskesmas);

            // Update master_provinsi
            $update_provinsi = ['updated_at' => date('Y-m-d H:i:s')];
            $this->db->where('id', $data['id_prov_new']);
            $this->db->update('master_provinsi', $update_provinsi);

            // Update master_kabupaten
            $update_kabupaten = ['updated_at' => date('Y-m-d H:i:s')];
            $this->db->where('id', $data['id_kab_new']);
            $this->db->update('master_kabupaten', $update_kabupaten);

            // Update master_kecamatan
            $update_kecamatan = ['updated_at' => date('Y-m-d H:i:s')];
            $this->db->where('id', $data['id_kec_new']);
            $this->db->update('master_kecamatan', $update_kecamatan);
        }

        return $insert_id; // Kembalikan ID untuk memastikan keberhasilan
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function detail_data($id)
    {
        $this->db->select('
            pp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mpus_asal.nama_puskesmas AS puskesmas_asal,
            mpus_asal.alamat AS alamat_asal,
            mp_baru.nama AS provinsi_baru,
            mk_baru.nama AS kabupaten_baru,
            mc_baru.nama_kec AS kecamatan_baru,
            pp.created_at
        '); 
        $this->db->from('pus_pemekaran pp');

        // Join ke tabel Provinsi, Kabupaten, Kecamatan, dan Puskesmas Asal
        $this->db->join('master_provinsi mp_asal', 'pp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'pp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'pp.id_kec = mc_asal.id', 'left');
        $this->db->join('master_puskesmas mpus_asal', 'pp.id_pus = mpus_asal.id', 'left');

        // Join ke tabel Provinsi, Kabupaten, Kecamatan, dan Puskesmas Baru
        $this->db->join('master_provinsi mp_baru', 'pp.id_prov_new = mp_baru.id', 'left');
        $this->db->join('master_kabupaten mk_baru', 'pp.id_kab_new = mk_baru.id', 'left');
        $this->db->join('master_kecamatan mc_baru', 'pp.id_kec_new = mc_baru.id', 'left');

        $this->db->where('pp.id', $id);

        return $this->db->get()->row(); // Mengembalikan satu baris data
    }

    // Count the total number of puskesmas pemekaran
    public function count_ppuskesmas()
    {
        return $this->db->count_all('pus_pemekaran');
    }
    
    // Search data with a keyword
    public function get_keyword($keyword, $limit, $start = 0)
    {
        $this->db->select('
            pp.id,
            mp_asal.nama AS provinsi_asal,
            mk_asal.nama AS kabupaten_asal,
            mc_asal.nama_kec AS kecamatan_asal,
            mp_baru.nama AS provinsi_baru,
            mk_baru.nama AS kabupaten_baru,
            mcb.nama_kec AS kecamatan_baru, 
            mpus_asal.nama_puskesmas AS puskesmas_asal,
            mpus_asal.alamat AS alamat_asal,
            pp.created_at
        '); 
        $this->db->from('pus_pemekaran pp');

        // Join ke tabel Provinsi, Kabupaten, Kecamatan, dan Puskesmas Asal
        $this->db->join('master_provinsi mp_asal', 'pp.id_prov = mp_asal.id', 'left');
        $this->db->join('master_kabupaten mk_asal', 'pp.id_kab = mk_asal.id', 'left');
        $this->db->join('master_kecamatan mc_asal', 'pp.id_kec = mc_asal.id', 'left');
        $this->db->join('master_puskesmas mpus_asal', 'pp.id_pus = mpus_asal.id', 'left');

        // Join ke data setelah pemekaran
        $this->db->join('master_provinsi mp_baru', 'pp.id_prov_new = mp_baru.id', 'left');
        $this->db->join('master_kabupaten mk_baru', 'pp.id_kab_new = mk_baru.id', 'left');
        $this->db->join('master_kecamatan mcb', 'pp.id_kec_new = mcb.id', 'left');

        // Filter pencarian
        $this->db->group_start();
        $this->db->like('mp_asal.nama', $keyword);
        $this->db->or_like('mk_asal.nama', $keyword);
        $this->db->or_like('mc_asal.nama_kec', $keyword);
        $this->db->or_like('mp_baru.nama', $keyword);
        $this->db->or_like('mk_baru.nama', $keyword);
        $this->db->or_like('mcb.nama_kec', $keyword);
        $this->db->or_like('mpus_asal.nama_puskesmas', $keyword);
        $this->db->or_like('mpus_asal.alamat', $keyword);
        $this->db->or_like('pp.created_at', $keyword);
        $this->db->group_end();

        // Tambahkan limit untuk pagination
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }

    // Get a limited number of kabupaten data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('pus_pemekaran')->result();
    }
    
    public function get_data_by_id($id)
    {
        return $this->db->get_where('pus_pemekaran', ['id' => $id])->row();
    }
    
    public function get_puskesmas_by_kecamatan($kecamatan_id) 
    {
        $this->db->where('kecamatan_id', $kecamatan_id);
        $query = $this->db->get('master_puskesmas'); // Sesuaikan dengan nama tabel kelurahan
        return $query->result();
    }
}

