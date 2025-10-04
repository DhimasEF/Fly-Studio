<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->model('M_User');
        $this->load->model('M_Content');
        $this->load->model('M_Comment');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
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
		$data['contents'] = $this->M_Content->get_all_content_with_likes();

		$data['title'] = 'Content | Fly Studio';

		$this->load->view('navbar', $data);
		$this->load->view('content', $data);
		//$this->load->view('footer');
	}
	
	public function view($encoded_id_content) {
		// Decode id_content dari Base64 URL
		$id_content = base64_decode(rawurldecode($encoded_id_content));
		// Tambah jumlah view
		
		$this->M_Content->increment_view($id_content);

		// Ambil detail konten
		$data['content'] = $this->M_Content->get_content_detail($id_content);
		if (!$data['content']) {
			show_404(); // Tampilkan error jika konten tidak ditemukan
		}
		
		$data['title'] = 'Content ' . ($data['content']->title . ' | Fly Studio');

		// Cek apakah user sudah like konten ini
		$data['is_liked'] = $this->M_Content->is_liked($id_content, $user_id);

		// Ambil komentar beserta reply-nya
		$data['comments'] = $this->M_Comment->get_comments_with_replies($id_content);

		// Simpan ID encoded untuk keperluan form di view
		$data['encoded_id_content'] = rawurlencode(base64_encode($id_content));

		// Load the view
        $this->load->view('navbar', $data);
		$this->load->view('contentdetail', $data);
        //$this->load->view('footer', $data);
	}
}
?>
