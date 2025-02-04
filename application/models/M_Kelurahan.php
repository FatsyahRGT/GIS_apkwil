<?php

class M_Kelurahan extends CI_Model
{
    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('id, nama, kode_dagri, kode_bps, active');
        $this->db->from('master_kelurahan');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('id, nama, kode_dagri, kode_bps, active');
        $this->db->from('master_kelurahan');
        return $this->db->get()->result();
    }

    // Get data by ID
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('master_kelurahan')->row();
    }

    // Get coordinates by ID
    public function get_coords_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('koord_kel')->row();
    }

    // Update data in both kecamatan and coordinates tables
  
    public function update_data($kelurahan_data, $coords_data)
    {
        // Update kelurahan data
        $this->db->where('id', $kelurahan_data['id']);
        $this->db->update('master_kelurahan', $kelurahan_data);

        // Update coordinates data
        $this->db->where('id', $kelurahan_data['id']);
        $this->db->update('koord_kel', $coords_data);

        // Cek apakah ada baris yang diperbarui di kedua tabel
        return $this->db->affected_rows() > 0;
    }


    // Update activation status
    public function update_status($id, $status)
    {
        $data = ['active' => $status];
        $this->db->where('id', $id);
        $this->db->update('master_kelurahan', $data);

        // Return true if the status is updated successfully
        return $this->db->affected_rows() > 0;
    }

    // Insert new data into kelurahan and coordinates tables
    public function input_data($kelurahan_data, $coords_data)
    {
        // Insert kelurahan data
        $this->db->insert('master_kelurahan', $kelurahan_data);

        // Get the last inserted ID
        $kelurahan_id = $this->db->insert_id();

        // Add kelurahan ID to coordinates data
        $coords_data['id'] = $kelurahan_id;

        // Insert coordinates data
        return $this->db->insert('koord_kel', $coords_data);
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function detail_data($id_kelurahan)
    {
        $this->db->select('master_kelurahan.nama AS nama_kelurahan, koord_kel.latitude, koord_kel.longitude, master_kelurahan.id_prov, master_kelurahan.id_kab, master_kelurahan.id_kec');
        $this->db->from('master_kelurahan');
        $this->db->join('koord_kel', 'master_kelurahan.id = koord_kel.id', 'left'); // Pastikan menggunakan LEFT JOIN
        $this->db->where('master_kelurahan.id', $id_kelurahan);
        $query = $this->db->get();
        $result = $query->row(); // Ambil baris pertama dari query

        // Pastikan hasil query tidak kosong
        if ($result) {
            // Tambahkan data nama provinsi, kabupaten, dan kecamatan jika ID tersedia
            $result->provinsi_name = $result->id_prov ? $this->get_provinsi_name($result->id_prov) : '-';
            $result->kabupaten_name = $result->id_kab ? $this->get_kabupaten_name($result->id_kab) : '-';
            $result->kecamatan_name = $result->id_kec ? $this->get_kecamatan_name($result->id_kec) : '-';
        } else {
            // Jika tidak ada data, kembalikan objek kosong dengan properti default
            $result = (object) [
                'nama_kelurahan' => '-',
                'latitude' => '-',
                'longitude' => '-',
                'provinsi_name' => '-',
                'kabupaten_name' => '-',
                'kecamatan_name' => '-'
            ];
        }

        return $result;
    }

    // Count the total number of kelurahan
    public function count_kelurahan()
    {
        return $this->db->count_all('master_kelurahan');
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('*');
        $this->db->from('master_kelurahan');
        $this->db->like('nama', $keyword);
        $this->db->or_like('kode_dagri', $keyword);
        $this->db->or_like('kode_bps', $keyword);
        $this->db->or_like('active', $keyword);
        return $this->db->get()->result();
    }

    // Get a limited number of kecamatan data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('master_kelurahan')->result();
    }

    public function get_all_kelurahan()
    {
        $this->db->select('id, nama');
        $this->db->from('master_kelurahan');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result(); // Kembalikan sebagai array objek
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

    public function get_kelurahan_by_id($id_kelurahan)
    {
        $this->db->where('id', $id_kelurahan); // Kolom 'id' sesuai dengan struktur tabel
        $query = $this->db->get('master_kelurahan'); // Ganti 'kabupaten' dengan nama tabel Anda
        return $query->row(); // Ambil satu baris data
    }
    
   
    // Update data kelurahan berdasarkan ID
    public function update_kelurahan($id_kelurahan, $data)
    {
        $this->db->where('id', $id_kelurahan); // Ganti 'id' dengan nama kolom ID kelurahan Anda
        return $this->db->update('master_kelurahan', $data); // Ganti 'kelurahan' dengan nama tabel Anda
    }
}
?>
