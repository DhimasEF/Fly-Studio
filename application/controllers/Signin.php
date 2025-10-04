<?php

class Signin extends CI_Controller  {	

    public function __construct() {
        parent::__construct();
        $this->load->model('M_User');
        $this->load->library('form_validation');
        $this->load->helper('url');
		
		// Kalau sudah login, arahkan ke dashboard sesuai role
		if ($this->session->userdata('logged_in')) {
			$role = $this->session->userdata('role');
			if ($role === 'admin') {
				redirect('Admin/profil');
				exit;
			} elseif ($role === 'creator') {
				redirect('Creator/profil');
				exit;
			} elseif ($role === 'user') {
				redirect('User/profil');
				exit;
			}
		}
	}
    
    public function index() {
		$data = ['title' => 'Sign in l Fly Studio'];
		$this->load->view('navbar', $data);
        $this->load->view('auth');
    }
    
    public function login() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('signin');
        }

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->session->set_userdata([
                'user_id' => $user['id_user'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
                'profile_picture' => $user['profile_picture'],
				'logged_in' => TRUE,  // Anda juga bisa menyet nilai TRUE untuk 'logged_in' pada saat login berhasil
            ]);

            switch ($user['role']) {
                case 'admin':
                    redirect(base_url('Admin/profil/'));
					break;
                case 'creator':
					redirect(base_url('Creator/profil'));
					break;
                case 'user':
                    redirect(base_url('User/profil'));
                    break;
                default:
                    redirect('signin');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid login credentials');
            redirect('signin');
        }
    }
    
    public function no_access() {
        $this->load->view('auth/no_access');
    }

    public function register() {
        $data = [
            'id_user' => $this->M_User->id_usr(),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'name' => $this->input->post('name'),
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->M_User->register_user($data)) {
            $this->session->set_flashdata('success', 'Registration successful. Please login.');
            redirect('signin');
        } else {
            $this->session->set_flashdata('error', 'Registration failed. Please try again.');
            redirect('signin');
        }
    }
}
?>
