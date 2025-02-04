<?php

class M_Provinsi extends CI_Model
{
    // Get data with pagination
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('id, nama, kode_dagri, kode_bps, active');
        $this->db->from('master_provinsi');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data without pagination
    public function tampil_data_all()
    {
        $this->db->select('id, nama, kode_dagri, kode_bps, active');
        $this->db->from('master_provinsi');
        return $this->db->get()->result();
    }

    // Get data by ID
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('master_provinsi')->row();
    }

    // Get coordinates by ID
    public function get_coords_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('koord_prov')->row();
    }

    // Update data in both provinsi and coordinates tables
    public function update_data($provinsi_data, $coords_data)
    {
        // Update provinsi data
        $this->db->where('id', $provinsi_data['id']);
        $this->db->update('master_provinsi', $provinsi_data);

        // Update coordinates data
        $this->db->where('id', $provinsi_data['id']);
        $this->db->update('koord_prov', $coords_data);

        // Check if any row is updated
        return $this->db->affected_rows() > 0;
    }

    // Update activation status
    public function update_status($id, $status)
    {
        $data = ['active' => $status];
        $this->db->where('id', $id);
        $this->db->update('master_provinsi', $data);

        // Return true if the status is updated successfully
        return $this->db->affected_rows() > 0;
    }

    // Insert new data into provinsi and coordinates tables
    public function input_data($provinsi_data, $coords_data)
    {
        // Insert provinsi data
        $this->db->insert('master_provinsi', $provinsi_data);

        // Get the last inserted ID
        $provinsi_id = $this->db->insert_id();

        // Add provinsi ID to coordinates data
        $coords_data['id'] = $provinsi_id;

        // Insert coordinates data
        return $this->db->insert('koord_prov', $coords_data);
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Get detail data for a specific provinsi
    public function detail_data($id_provinsi)
    {
        $this->db->select('master_provinsi.nama AS nama_provinsi, koord_prov.latitude, koord_prov.longitude');
        $this->db->from('master_provinsi');
        $this->db->join('koord_prov', 'master_provinsi.id = koord_prov.id');
        $this->db->where('master_provinsi.id', $id_provinsi);
        return $this->db->get()->row();
    }

    // Count the total number of provinsi
    public function count_provinsi()
    {
        return $this->db->count_all('master_provinsi');
    }

    // Search data with a keyword
    public function get_keyword($keyword)
    {
        $this->db->select('*');
        $this->db->from('master_provinsi');
        $this->db->like('nama', $keyword);
        $this->db->or_like('kode_dagri', $keyword);
        $this->db->or_like('kode_bps', $keyword);
        $this->db->or_like('active', $keyword);
        return $this->db->get()->result();
    }

    public function get_all_provinsi()
    {
        $this->db->select('id, nama');
        $this->db->from('master_provinsi');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result(); // Kembalikan sebagai array objek
    }

    // Get a limited number of provinsi data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('master_provinsi')->result();
    }

    public function get_provinsi_by_id($id_provinsi)
    {
        $this->db->where('id', $id_provinsi); // Kolom 'id' sesuai dengan struktur tabel
        $query = $this->db->get('master_provinsi'); // Ganti 'kabupaten' dengan nama tabel Anda
        return $query->row(); // Ambil satu baris data
    }
}
?>
