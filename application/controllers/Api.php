<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function authenticate()
    {
        $token = $this->input->get_request_header('API-Token');
        $user = $this->db->where('api_token', $token)->get('users')->row();

        if (!$user) {
            $this->output
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Unauthorized']));
            return false;
        }
        return true;
    }

    protected function return_json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}