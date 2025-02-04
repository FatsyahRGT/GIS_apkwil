<?php

class M_Kabupaten extends CI_Model
{
    public function tampil_data($limit, $start = 0)
    {
        $this->db->select('id, nama, kode_dagri, kode_bps, active');
        $this->db->from('master_kabupaten');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Get all data with province name
    public function tampil_data_all()
    {
        $this->db->select('
            master_kabupaten.id,
            master_kabupaten.nama AS nama_kabupaten,
            master_kabupaten.kode_dagri,
            master_kabupaten.kode_bps,
            master_kabupaten.active,
            master_provinsi.nama AS nama_provinsi
        ');
        $this->db->from('master_kabupaten');
        $this->db->join('master_provinsi', 'master_kabupaten.id_prov = master_provinsi.id', 'left');
        return $this->db->get()->result();
    }


    // Get coordinates by ID
    public function get_coords_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('koord_kab')->row();
    }

    // Get data by ID
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('master_kabupaten')->row();
    }

    // Insert new data into kabupaten and coordinates tables
    public function input_data($kabupaten_data, $coords_data)
    {
        // Insert kabupaten data
        $this->db->insert('master_kabupaten', $kabupaten_data);

        // Get the last inserted ID
        $kabupaten_id = $this->db->insert_id();

        // Add Kode Dagri sebagai foreign key to coordinates data
        $coords_data['id'] = $kabupaten_id;

        // Insert coordinates data
        return $this->db->insert('koord_kab', $coords_data);
    }

    // Delete data
    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Get detail data for a specific kabupaten
    public function detail_data($id_kabupaten)
    {
        $this->db->select('master_kabupaten.id, master_kabupaten.nama AS nama_kabupaten, koord_kab.latitude, koord_kab.longitude, master_provinsi.nama AS nama_provinsi, master_kabupaten.id_prov');
        $this->db->from('master_kabupaten');
        $this->db->join('koord_kab', 'master_kabupaten.id = koord_kab.id');
        $this->db->join('master_provinsi', 'master_kabupaten.id_prov = master_provinsi.id'); // JOIN provinsi
        $this->db->where('master_kabupaten.id', $id_kabupaten);
        return $this->db->get()->row();
    }


    // Update data in both kabupaten and coordinates tables
    public function update_data($kabupaten_data, $coords_data)
    {
        // Update kabupaten data
        $this->db->where('id', $kabupaten_data['id']);
        $this->db->update('master_kabupaten', $kabupaten_data);

        // Update coordinates data
        $this->db->where('id', $kabupaten_data['id']);
        $this->db->update('koord_kab', $coords_data);


        return $this->db->affected_rows() > 0;
    }


    public function update_status($id, $status)
    {
        $data = ['active' => $status];
        $this->db->where('id', $id);
        $this->db->update('master_kabupaten', $data);

        // Return true if the status is updated successfully
        return $this->db->affected_rows() > 0;
    }

    public function count_kabupaten()
    {
        return $this->db->count_all('master_kabupaten');
    }

    public function get_keyword($keyword)
    { 
        $this->db->select('*');
        $this->db->from('master_kabupaten');
        $this->db->like('nama', $keyword);
        $this->db->or_like('kode_dagri', $keyword);
        $this->db->or_like('kode_bps', $keyword);
        $this->db->or_like('active', $keyword);
        return $this->db->get()->result();
    }

    public function get_all_kabupaten()
    {
        $this->db->select('id, nama');
        $this->db->from('master_kabupaten');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result(); // Kembalikan sebagai array objek
    }

    // Get a limited number of kabupaten data
    public function get_data($limit)
    {
        $this->db->limit($limit);
        return $this->db->get('master_kabupaten')->result();
    }

    public function get_kabupaten_by_provinsi($id_provinsi)
    {
        $this->db->select('id, nama');
        $this->db->from('master_kabupaten');
        $this->db->where('id_prov', $id_provinsi);
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result();
    }

     // Ambil data kabupaten berdasarkan ID
     public function get_kabupaten_by_id($id_kabupaten)
    {
        $this->db->where('id', $id_kabupaten); // Kolom 'id' sesuai dengan struktur tabel
        $query = $this->db->get('master_kabupaten'); // Ganti 'kabupaten' dengan nama tabel Anda
        return $query->row(); // Ambil satu baris data
    }
    
   
    // Update data kabupaten berdasarkan ID
    public function update_kabupaten($id_kabupaten, $data)
    {
        $this->db->where('id', $id_kabupaten); // Ganti 'id' dengan nama kolom ID kabupaten Anda
        return $this->db->update('master_kabupaten', $data); // Ganti 'kabupaten' dengan nama tabel Anda
    }




}