<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        permission();
        $this->load->model('SuppliersModel');
    }

    public function index()
    {
        $data['suppliers'] = $this->SuppliersModel->get_all();
        $this->load->view('pages/suppliers', $data);
    }

    public function create()
    {
        $this->load->view('pages/form-suppliers');
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|is_unique[suppliers.cnpj]');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'cnpj' => $this->input->post('cnpj'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->SuppliersModel->insert($data);
            redirect('suppliers');
        }
    }

    public function edit($id)
    {
        $data['supplier'] = $this->SuppliersModel->get_by_id($id);
        $this->load->view('pages/form-suppliers', $data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('name', 'Nome', 'required');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'cnpj' => $this->input->post('cnpj'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->SuppliersModel->update($id, $data);
            redirect('suppliers');
        }
    }

    public function delete($id)
    {
        $this->SuppliersModel->delete($id);
        redirect('suppliers');
    }
}