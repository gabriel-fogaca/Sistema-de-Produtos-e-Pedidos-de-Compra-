<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		permission();
		$this->load->model('ProductsModel');
		$this->load->model('UsersModel');
		$this->user_id = $this->session->userdata('user_id');
	}
	public function index()
	{
		$data["products"] = $this->ProductsModel->index();
		$data["userToken"] = $this->UsersModel->getToken($this->user_id);
		$data["title"] = "Dashboard - CodeIgniter";

		$this->load->view('templates/header', $data);
		$this->load->view('templates/nav-top', $data);
		$this->load->view('pages/dashboard', $data);
		$this->load->view('templates/footer', $data);
	}

	public function search()
	{
		$this->load->model('SearchModel');
		$data['title'] = "Resultado da pesquisa por *" . $_POST["search"] . "*";
		$data["result"] = $this->SearchModel->search($_POST);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/nav-top', $data);
		$this->load->view('pages/result', $data);
		$this->load->view('templates/footer', $data);
	}

}