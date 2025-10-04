<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('admin'); // Middleware untuk cek role
		$this->load->model('M_User');
		$this->load->model('M_Follow');
		$this->load->model('M_Content');
		$this->load->model('M_Collaboration');
		$this->load->model('M_Event');
		$this->load->model('M_Transaction');
		$this->load->library('session');
		
		// Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }
	
	
	public function index() {
		$user_id = $this->session->userdata('user_id'); // Ambil ID user dari session
		
		if (!$user_id) {
			echo "User ID tidak ditemukan di session"; die();
		}

		$data['user'] = $this->M_User->get_admin_by_id($user_id); // Ambil data terbaru dari database
		$data['users'] = $this->M_User->get_all_role_users();
		$data['title'] = 'User | Fly Studio';
		
		$this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listuser', $data);
        $this->load->view('Admin/footer', $data);
	}
	
	// Search creator
	public function search() {
		
		$user_id = $this->session->userdata('user_id'); // Ambil ID user dari session
		
		if (!$user_id) {
			echo "User ID tidak ditemukan di session"; die();
		}

		$keyword = $this->input->get('keyword'); // Ambil input pencarian dari form GET
		// Jika keyword kosong, ambil semua user
		if (empty($keyword)) {
			$data['users'] = $this->M_User->get_all_role_users();
		} else {
			$data['users'] = $this->M_User->search_role_user($keyword);
		}
		
		$data['user'] = $this->M_User->get_admin_by_id($user_id); // Ambil data terbaru dari database

		$this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listuser', $data);
        $this->load->view('Admin/footer', $data);
	}
	
	public function detail($encoded_id) {
		$decoded_id = base64_decode(rawurldecode($encoded_id));

		// Pastikan ID hanya terdiri dari karakter yang valid (misalnya alfanumerik)
		if (!$decoded_id || !preg_match('/^[a-zA-Z0-9_]+$/', $decoded_id)) {
			show_error("Invalid User ID", 400);
		}

		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			show_error("User ID tidak ditemukan di session", 403);
		}

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		$data['users'] = $this->M_User->getUserById($decoded_id);
		
		$data['title'] = 'User ' . ($data['users']['name'] . ' | Fly Studio');

		if (!$data['users']) {
			show_404();
		}

		// Hitung jumlah kolaborasi yang diikuti
		$data['total_collaborations'] = $this->M_Collaboration->countUserCollaborations($decoded_id);
		$data['collaborations'] = $this->M_Collaboration->getUserCollaborations($decoded_id);

		// Hitung jumlah event yang diikuti
		$data['total_events'] = $this->M_Event->countUserEvents($decoded_id);
		$data['events'] = $this->M_Event->getUserEvents($decoded_id);
		
		$data['total_transactions'] = $this->M_Transaction->countUserTransactions($decoded_id);
		$data['transactions'] = $this->M_Transaction->getUserTransactions($decoded_id);


		// Load view
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/userinfo', $data);
		$this->load->view('Admin/footer', $data);
	}
	

}
?>