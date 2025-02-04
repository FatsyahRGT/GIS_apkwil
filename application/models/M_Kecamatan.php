<?php

class M_Kecamatan extends CI_Model
{
    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('id, nama_kec, kode_dagri, kode_bps, active');
        $this->db->from('master_kecamatan');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('master_kecamatan.id, master_kecamatan.nama_kec, master_kecamatan.kode_dagri, master_kecamatan.kode_bps, master_kecamatan.active, master_provinsi.nama AS nama_provinsi');
        $this->db->from('master_kecamatan');
        $this->db->join('master_provinsi', 'master_kecamatan.id_prov = master_provinsi.id', 'left'); // LEFT JOIN dengan master_provinsi
        return $this->db->get()->result();
    }
    

    // Get data by ID
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('master_kecamatan')->row();
    }

    // Get coordinates by ID
    public function get_coords_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('koord_kec')->row();
    }

    // Update data in both kecamatan and coordinates tables
  
public function update_data($kecamatan_data, $coords_data)
{
    // Update kecamatan data
    $this->db->where('id', $kecamatan_data['id']);
    $this->db->update('master_kecamatan', $kecamatan_data);

    // Update coordinates data
    $this->db->where('id', $kecamatan_data['id']);
    $this->db->update('koord_kec', $coords_data);

    // Cek apakah ada baris yang diperbarui di kedua tabel
    return $this->db->affected_rows() > 0;
}


    // Update activation status
    public function update_status($id, $status)
    {
        $data = ['active' => $status];
        $this->db->where('id', $id);
        $this->db->update('master_kecamatan', $data);

        // Return true if the status is updated successfully
        return $this->db->affected_rows() > 0;
    }

    // Insert new data into kecamatan and coordinates tables
    public function input_data($kecamatan_data, $coords_data)
    {
        // Insert kecamatan data
        $this->db->insert('master_kecamatan', $kecamatan_data);

        // Get the last inserted ID
        $kecamatan_id = $this->db->insert_id();

        // Add kecamatan ID to coordinates data
        $coords_data['id'] = $kecamatan_id;

        // Insert coordinates data
        return $this->db->insert('koord_kec', $coords_data);
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Get detail data for a specific kecamatan
    public function detail_data($id_kecamatan)
{
    $this->db->select('master_kecamatan.nama_kec AS nama_kecamatan, koord_kec.latitude, koord_kec.longitude, master_kecamatan.id_prov, master_kecamatan.id_kab');
    $this->db->from('master_kecamatan');
    $this->db->join('koord_kec', 'master_kecamatan.id = koord_kec.id');
    $this->db->where('master_kecamatan.id', $id_kecamatan);
    $query = $this->db->get();
    $result = $query->row();

    if ($result) {
        // Ambil nama provinsi dan kabupaten berdasarkan ID
        $result->provinsi_name = $this->get_provinsi_name($result->id_prov);
        $result->kabupaten_name = $this->get_kabupaten_name($result->id_kab);
    }

    return $result;
}

    // Count the total number of kecamatan
    public function count_kecamatan()
    {
        return $this->db->count_all('master_kecamatan');
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('*');
        $this->db->from('master_kecamatan');
        $this->db->like('nama_kec', $keyword);
        $this->db->or_like('kode_dagri', $keyword);
        $this->db->or_like('kode_bps', $keyword);
        $this->db->or_like('active', $keyword);
        return $this->db->get()->result();
    }

    // Get a limited number of kecamatan data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('master_kecamatan')->result();
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

    public function get_all_kecamatan()
    {
        $this->db->select('id, nama_kec');
        $this->db->from('master_kecamatan');
        $this->db->order_by('nama_kec', 'ASC');
        return $this->db->get()->result(); // Kembalikan sebagai array objek
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

    public function get_kecamatan_by_id($id_kecamatan)
    {
        $this->db->where('id', $id_kecamatan); // Kolom 'id' sesuai dengan struktur tabel
        $query = $this->db->get('master_kecamatan'); // Ganti 'kabupaten' dengan nama tabel Anda
        return $query->row(); // Ambil satu baris data
    }
    
   
    // Update data kecamatan berdasarkan ID
    public function update_kecamatan($id_kecamatan, $data)
    {
        $this->db->where('id', $id_kecamatan); // Ganti 'id' dengan nama kolom ID kecamatan Anda
        return $this->db->update('master_kecamatan', $data); // Ganti 'kecamatan' dengan nama tabel Anda
    }
}
?>
