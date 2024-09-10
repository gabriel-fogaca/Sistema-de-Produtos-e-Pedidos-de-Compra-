<?php

class LoginModel extends CI_Model
{
    public function get_user($email)
    {
        $email = $this->security->xss_clean($email);

        $this->db->where('email', $email);
        $user = $this->db->get('users')->row();

        return $user;
    }

    public function update_token($user_id, $token)
    {
        $this->db->where('id', $user_id);
        $this->db->update('users', ['api_token' => $token]);
    }
}