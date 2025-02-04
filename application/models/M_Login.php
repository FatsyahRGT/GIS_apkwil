<?php

class M_Login extends CI_Model {

    public function cek_login($username)
    {
        $this->db->select('id, username, password, first_name, last_name, profile_photo, id_role'); // Tambahkan id_role
        $this->db->from('users'); // Tabel user
        $this->db->where('username', $username); // Kondisi untuk username
        $query = $this->db->get(); // Eksekusi query
    
        // Periksa apakah user ditemukan
        if ($query->num_rows() > 0) {
            return $query->row(); // Kembalikan data user
        }
    
        return null; // Jika user tidak ditemukan
    }
    

    // Fungsi untuk menyimpan data pengguna baru ke dalam database
    public function insert_user($data) {
        return $this->db->insert('users', $data); // Simpan data ke tabel 'users'
    }
}
