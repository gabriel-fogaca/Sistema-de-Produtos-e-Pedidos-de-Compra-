<?php

class LogModel extends CI_Model
{
    public function create_log($user_id, $action, $description)
    {
        $data = [
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description
        ];

        if ($this->db->insert('logs', $data)) {
            return true;
        } else {
            log_message('error', 'Falha ao inserir log: ' . $this->db->error()['message']);
            return false;
        }
    }

    public function get_logs()
    {
        return $this->db->order_by('id', 'DESC')->get('logs')->result_array();
    }

    public function get_logs_by_user($user_id)
    {
        return $this->db->where('user_id', $user_id)->order_by('id', 'DESC')->get('logs')->result_array();
    }
}