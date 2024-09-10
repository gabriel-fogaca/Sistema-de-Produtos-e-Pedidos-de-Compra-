<?php

class UsersModel extends CI_Model
{
    private function generate_api_key($lenght = 64){
        return bin2hex(random_bytes($lenght / 2));
    }
    public function field_exists($field, $value)
    {
        $this->db->where($field, $value);
        $query = $this->db->get('users');

        return $query->num_rows() > 0;
    }
    public function store($user)
    {
        $user['api_token'] = $this->generate_api_key();

        $this->db->insert("users", $user);

        return $this->db->insert_id();
    }

    public function getToken($id){
        $this->db->select("api_token");
        $this->db->where('id',$id);
        return $this->db->get('users', '')->row_array()['api_token'];
    }
}