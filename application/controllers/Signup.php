<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Signup extends CI_Controller
{
	public function index()
	{
		$data["title"] = "Sign-up - CodeIgniter";
		$this->load->view('pages/signup', $data);
	}

	public function store()
	{
		$this->load->model("UsersModel");

		$user = [
			'name' => $this->input->post('name'),
			'cpf_cnpj' => $this->input->post('cpf_cnpj'),
			'email' => $this->input->post('email'),
			'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
		];

		$this->UsersModel->store($user);
		redirect("login");
	}
}