<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Creator extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('admin'); // Middleware untuk cek role
		$this->load->model('M_User');
		$this->load->model('M_Follow');
		$this->load->model('M_Content');
		$this->load->model('M_Collaboration');
		$this->load->model('M_Event');
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
		
		$data['title'] = 'Creator | Fly Studio';
		$data['creators'] = $this->M_User->get_all_role_creators();

		$this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listcreator', $data);
        $this->load->view('Admin/footer', $data);
	}
	
	// Search creator
	public function search() {
		$user_id = $this->session->userdata('user_id'); // Ambil ID user dari session
		
		if (!$user_id) {
			echo "User ID tidak ditemukan di session"; die();
		}

		$keyword = $this->input->get('keyword'); // Ambil input pencarian dari form GET
		// Jika tidak ada keyword, tampilkan semua data creator
		if (empty($keyword)) {
			$data['creators'] = $this->M_User->get_all_role_creators();
		} else {
			$data['creators'] = $this->M_User->search_role_creator($keyword);
		}
		
		$data['user'] = $this->M_User->get_admin_by_id($user_id); // Ambil data terbaru dari database
		$data['title'] = 'Creator | Fly Studio';
		
		$this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listcreator', $data);
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
		$data['creator'] = $this->M_User->getCreatorById($decoded_id);


		if (!$data['creator']) {
			show_404();
		}
		
		$data['title'] = 'Creator ' . ($data['creator']['name'] . ' | Fly Studio');

		// Cek apakah user sudah mengikuti creator ini
		$data['is_following'] = $this->M_Follow->isFollowing($user_id, $decoded_id);

		// Hitung jumlah postingan yang dibuat oleh creator
		$data['total_posts'] = $this->M_Content->countCreatorPosts($decoded_id);
		$data['posts'] = $this->M_Content->getCreatorPosts($decoded_id);

		// Hitung jumlah kolaborasi yang diikuti
		$data['total_collaborations'] = $this->M_Collaboration->countUserCollaborations($decoded_id);
		$data['collaborations'] = $this->M_Collaboration->getUserCollaborations($decoded_id);

		// Hitung jumlah event yang diikuti
		$data['total_events'] = $this->M_Event->countUserEvents($decoded_id);
		$data['events'] = $this->M_Event->getUserEvents($decoded_id);

		// Load view
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/creatorinfo', $data);
		$this->load->view('Admin/footer', $data);
	}



    public function follow($encoded_id) {
        $id_user = base64_decode(rawurldecode($encoded_id));
        $follower_id = $this->session->userdata('user_id');

        if ($follower_id) {
            $this->M_Follow->followUser($follower_id, $id_user);
            $this->session->set_flashdata('success', 'Berhasil mengikuti creator.');
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }
	
	public function unfollow($encoded_id) {
        $id_user = base64_decode(rawurldecode($encoded_id));
        $follower_id = $this->session->userdata('user_id');

        if (!$follower_id || !$id_user) {
            show_error("Invalid User ID", 400);
        }

        $this->M_Follow->unfollowUser($follower_id, $id_user);
        redirect($_SERVER['HTTP_REFERER']);
    }
}
?>