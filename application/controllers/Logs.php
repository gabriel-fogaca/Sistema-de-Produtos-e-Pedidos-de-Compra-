<?php
class Logs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        permission();
        $this->load->model('LogModel');
        $this->user_id = $this->session->userdata('user_id');
    }

    public function index()
    {
        $data['logs'] = $this->LogModel->get_logs();
        $data['title'] = 'Lista de Logs';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/nav-top', $data);
        $this->load->view('pages/logs', $data);
        $this->load->view('templates/footer', $data);
    }
}