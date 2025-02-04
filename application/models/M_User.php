<?php

class M_User extends CI_Model {
    
    public function tampil_data($limit, $start)
    {
        $this->db->select('users.*, role.role_name');
        $this->db->from('users');
        $this->db->join('role', 'users.id_role = role.id_role', 'left');   
        $this->db->limit($limit, $start); 
        $query = $this->db->get(); 
        return $query;
    }


    public function input_data($data, $table)
    {
        $this->db->insert($table, $data); 
    }

    public function hapus_data($where, $table)
    {
        $this->db->where($where);
        $this->db->delete($table); 
    }

    public function edit_data($where, $table)
    {
        return $this->db->get_where($table, $where); 
    }

    public function update_data($where, $table, $data)
    {
        $this->db->where($where);
        return $this->db->update($table, $data); 
    }


    public function detail_data($id)
    {
        $this->db->select('users.*, role.role_name');
        $this->db->from('users');
        $this->db->join('role', 'users.id_role = role.id_role', 'left'); // Join dengan tabel role   
        $this->db->where('users.id', $id); // Filter berdasarkan id user
        $query = $this->db->get();
    
        return $query->row(); // Mengembalikan satu baris data sebagai objek
    }

    public function count_user()
    {
        return $this->db->count_all('users');
    }


    //fungsi search dengan kata kunci pencarian menggunakkan tabel username, first dan lastname
    public function get_keyword($keyword, $limit = null, $start = null)
    {
        $this->db->select('users.*, role.role_name');
        $this->db->from('users');
        $this->db->join('role', 'users.id_role = role.id_role');
        $this->db->like('username', $keyword);
        $this->db->or_like('first_name', $keyword);
        $this->db->or_like('last_name', $keyword);
        $this->db->or_like('role.role_name', $keyword);
        if ($limit !== null && $start !== null) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }
    
    public function count_keyword($keyword)
    {
        $this->db->from('users');
        $this->db->join('role', 'users.id_role = role.id_role');
        $this->db->like('username', $keyword);
        $this->db->or_like('first_name', $keyword);
        $this->db->or_like('last_name', $keyword);
        $this->db->or_like('role.role_name', $keyword);
        return $this->db->count_all_results();
    }  
}
