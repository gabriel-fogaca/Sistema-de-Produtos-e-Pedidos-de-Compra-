<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
    }

    public function index()
    {
        $data["title"] = "Login - CodeIgniter";

        if ($this->session->userdata('error')) {
            $data['error'] = $this->session->userdata('error');
            $this->session->unset_userdata('error');
        } else {
            $data['error'] = ''; 
        }

        $this->load->view('pages/login', $data);
    }

    public function store()
	{
		$this->load->model('LoginModel');
		$ip_address = $this->input->ip_address();

		$login_attempts = $this->db->get_where('login_attempts', ['ip_address' => $ip_address])->row();
		
		if ($login_attempts && strtotime($login_attempts->blocked_until) > time()) {
			$this->session->set_userdata('error', "Você excedeu o limite máximo de tentativas. Tente novamente mais tarde: " . $login_attempts->blocked_until);
			redirect("login");
			return;
		}

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->LoginModel->get_user($email);

		if (!$user) {
			$this->session->set_flashdata('error', "Usuário não encontrado.");
			redirect("login");
			return;
		}

		if (!password_verify($password, $user->password)) {
			$this->register_failed_attempt($ip_address, $login_attempts);
			$this->session->set_flashdata('error', "Credenciais inválidas. Tente novamente.");
			redirect("login");
			return;
		}
		
		$this->db->delete('login_attempts', ['ip_address' => $ip_address]);

		$token = bin2hex(random_bytes(32));
		$this->LoginModel->update_token($user->id, $token);

		$this->session->set_userdata([
			'user_id' => $user->id,
			'user_name' => $user->name,
			'api_token' => $token,
			'logged_in' => true
		]);

		redirect("/dashboard");
	}


    private function register_failed_attempt($ip_address, $login_attempts)
    {
        if ($login_attempts) {
            $attempts = $login_attempts->attempts + 1;
            $blocked_until = $attempts >= 3 ? date('Y-m-d H:i:s', strtotime('+15 minutes')) : null;
            $this->db->update('login_attempts', [
                'attempts' => $attempts,
                'blocked_until' => $blocked_until,
                'last_attempt' => date('Y-m-d H:i:s')
            ], ['ip_address' => $ip_address]);
        } else {
            $this->db->insert('login_attempts', [
                'ip_address' => $ip_address,
                'attempts' => 1,
                'last_attempt' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function logout()
	{
		$user_id = $this->session->userdata('user_id');

		$this->LoginModel->update_token($user_id, null);

		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('api_token');
		$this->session->unset_userdata('logged_in');

		redirect("login");
	}

}