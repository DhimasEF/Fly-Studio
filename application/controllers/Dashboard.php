<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('M_User');
        $this->load->model('M_Event');
		$this->load->model('M_User');
		$this->load->model('M_Content');
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
		$data = ['title' => 'Dashboard l Fly Studio'];
		$data['events'] = $this->M_Event->getAllEvents5('public and all', 'user', 5);
		$data['creators'] = $this->M_User->get_creators_with_details6(); // 6 top creator
		$data['contents'] = $this->M_Content->get_all_content_with_likes6(); // 6 content
		$this->load->view('navbar', $data);
		$this->load->view('dashboard', $data);
		$this->load->view('footer');
	}
}
?>
