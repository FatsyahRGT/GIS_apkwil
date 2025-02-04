<?php

class M_Puskesmas extends CI_Model
{
    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('id, nama_puskesmas, alamat, kode_2, kode_3, active');
        $this->db->from('master_puskesmas');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('id, nama_puskesmas, alamat, kode_2, kode_3, active');
        $this->db->from('master_puskesmas');
        return $this->db->get()->result();
    }

    // Get data by ID
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('master_puskesmas')->row();
    }

    //Update data by ID
    public function update_data($puskesmas_data)
    {
        $this->db->where('id', $puskesmas_data['id']);
        $this->db->update('master_puskesmas', $puskesmas_data);
        return $this->db->affected_rows() > 0;
    }


    // Update activation status
    public function update_status($id, $status)
    {
        $data = ['active' => $status];
        $this->db->where('id', $id);
        $this->db->update('master_puskesmas', $data);
        return $this->db->affected_rows() > 0; // Return true if rows were updated
    }

    // Insert new data
    public function input_data($puskesmas_data)
    {
        $this->db->insert('master_puskesmas', $puskesmas_data);
        return $this->db->insert_id(); // Return the last inserted ID
    }

    // Delete data by ID
    public function hapus_data($id)
    { 
        $this->db->where('id', $id);
        $this->db->delete('master_puskesmas');
        return $this->db->affected_rows() > 0; // Return true if rows were deleted
    }

    public function detail_data($id_puskesmas)
    {
        $this->db->select('nama_puskesmas, alamat, kode_2, kode_3, id_prov, id_kab, id_kec');
        $this->db->from('master_puskesmas');
        $this->db->where('id', $id_puskesmas);
        $query = $this->db->get();
        return $query->row(); // Mengembalikan satu baris data sebagai objek
    }

    // Count the total number of Puskesmas
    public function count_puskesmas()
    {
        return $this->db->count_all('master_puskesmas');
    }

    public function get_all_puskesmas()
    {
        $this->db->select('id, nama_puskesmas');
        $this->db->from('master_puskesmas');
        $this->db->order_by('nama_puskesmas', 'ASC');
        return $this->db->get()->result(); // Kembalikan sebagai array objek
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('*');
        $this->db->from('master_puskesmas');
        $this->db->like('nama_puskesmas', $keyword);
        $this->db->or_like('alamat', $keyword);
        $this->db->or_like('kode_2', $keyword);
        $this->db->or_like('kode_3', $keyword);
        $this->db->or_like('active', $keyword);
        return $this->db->get()->result();
    }

    // Get the name of the province based on the province ID
    public function get_provinsi_name($provinsi_id)
    {
        $this->db->select('nama');
        $this->db->from('master_provinsi');
        $this->db->where('id', $provinsi_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;  // Return null if not found
    }

    // Get the name of the kabupaten based on the kabupaten ID
    public function get_kabupaten_name($kabupaten_id)
    {
        $this->db->select('nama');
        $this->db->from('master_kabupaten');
        $this->db->where('id', $kabupaten_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama : null;  // Return null if not found
    }

    public function get_kecamatan_name($kecamatan_id)
    {
        $this->db->select('nama_kec');
        $this->db->from('master_kecamatan');
        $this->db->where('id', $kecamatan_id);
        $result = $this->db->get()->row();
        return $result ? $result->nama_kec : null;  // Return null if not found
    }

    public function get_puskesmas_by_id($id_puskesmas)
    {
        $this->db->where('id', $id_puskesmas); // Kolom 'id' sesuai dengan struktur tabel
        $query = $this->db->get('master_puskesmas'); // Ganti 'kabupaten' dengan nama tabel Anda
        return $query->row(); // Ambil satu baris data
    }

    public function update_puskesmas($id_pus, $data)
    {
        // Update data puskesmas berdasarkan ID
        $this->db->where('id', $id_pus);
        return $this->db->update('master_puskesmas', $data);  // Update ke tabel master_puskesmas
    }

}
