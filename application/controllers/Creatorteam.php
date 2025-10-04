<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creatorteam extends CI_Controller {
	
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
    
	public function index()
	{
		$data = ['title' => 'Creator l Fly Studio'];
		$data['creators'] = $this->M_User->get_all_role_creators();
		$this->load->view('navbar', $data);
		$this->load->view('creator', $data);
		//$this->load->view('footer');
	}
}
?>
