<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collaboration extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('user'); // Middleware untuk cek role
        $this->load->model('M_Multiproject');
        $this->load->model('M_Collaboration');
        $this->load->model('M_User');
		$this->load->library('session');
		
		 // Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }
	
    // View all content
    public function index() {
		$data['is_collab'] = true;
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->get_user_by_id($user_id);
		
		$data['contents'] = $this->M_Multiproject->get_all_collaborations_with_details();
		$data['liked_contents'] = $this->M_Multiproject->get_user_liked_collab($this->session->userdata('user_id'));
		$data['title'] = 'Collaboration | Fly Studio';
		//$data['contents'] = $this->M_Content->get_all_content_with_likes();
        $this->load->view('User/sidebar', $data);
        $this->load->view('User/listmultiproject', $data);
        $this->load->view('User/footer', $data);
    }

	public function view($encoded_id_content) {
		// Decode id_content dari Base64 URL
		$id_content = base64_decode(rawurldecode($encoded_id_content));
		
		// Cek apakah user sudah login
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		// Ambil data user (admin)
		$data['user'] = $this->M_User->get_user_by_id($user_id);
		
		$this->M_Multiproject->increment_view($id_content);
	
		// Fetch content details and comments
		$data['content'] = $this->M_Multiproject->get_content_detail($id_content);
		if (!$data['content']) {
			show_404(); // Jika konten tidak ditemukan
		}
		$data['is_liked'] = $this->M_Multiproject->is_liked($id_content, $this->session->userdata('user_id'));
		$data['comments'] = $this->M_Multiproject->get_comments_with_replies($id_content);
		$data['participants'] = $this->M_Collaboration->get_participants_by_file($id_content);
		$data['title'] = 'Collaboration ' . ($data['content']-> title . ' | Fly Studio');


		// Load the view
		$this->load->view('User/sidebar', $data);
		$this->load->view('User/multidtl', $data);
		$this->load->view('User/footer', $data);
	}

    // Menambahkan atau menghapus like berdasarkan kondisi
    public function toggle_like($id_content) {
        $id_user = $this->session->userdata('user_id'); // Ambil id_user dari session
        if (!$id_user) {
            redirect('signin'); // Redirect jika user belum login
        }

        if ($this->M_Multiproject->is_liked($id_content, $id_user)) {
            // Jika sudah like, hapus like
            $this->M_Multiproject->remove_like($id_content, $id_user);
            $response = ['status' => 'removed'];
        } else {
            // Jika belum like, tambahkan like
            $this->M_Multiproject->add_like($id_content, $id_user);
            $response = ['status' => 'added'];
        }

        echo json_encode($response); // Response untuk AJAX
    }
	
	public function add_comment($encoded_id_content, $id_parent = null) {
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		
		// Validasi input komentar
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		// Cek validasi form
		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('User/collaboration/view/' .  rawurlencode(base64_encode($id_file)));
			return;
		}

		// Validasi id_parent (jika diberikan)
		if ($id_parent) {
			$parent_comment = $this->M_Multiproject->get_comment_by_id($id_parent);
			if (!$parent_comment) {
				$this->session->set_flashdata('error', 'Invalid parent comment.');
				redirect('User/collaboration/view
				/' .  rawurlencode(base64_encode($id_file)));
				return;
			}
		}

		// Data komentar yang akan disimpan
		$comment_data = [
			'id_file' => $id_file,
			'id_user' => $this->session->userdata('user_id'),
			'id_parent' => $id_parent,
			'comment_text' => $this->input->post('comment'),
			'created_at' => date('Y-m-d H:i:s')
		];

		// Simpan ke database
		$result = $this->M_Multiproject->add_comment($comment_data);

		if ($result) {
			$this->session->set_flashdata('success', 'Comment added successfully!');
		} else {
			$this->session->set_flashdata('error', 'Failed to add comment.');
		}

		redirect('User/collaboration/view/' .  rawurlencode(base64_encode($id_file)));
	}


    // Mendapatkan komentar dalam format JSON (opsional, jika diperlukan)
    public function get_comments($id_content) {
        $comments = $this->M_Multiproject->get_comments_by_content($id_content);
        echo json_encode($comments);
    }

    public function delete_comment($id_comment) {
		// Ambil data komentar dari database
		$comment = $this->db->get_where('collaboration_comment', ['id_comment' => $id_comment])->row();
		
		// Cek apakah komentar ada
		if (!$comment) {
			$this->session->set_flashdata('error', 'Komentar tidak ditemukan.');
			redirect($this->input->server('HTTP_REFERER'));
			return;
		}

		// Ambil user yang sedang login
		$current_user_id = $this->session->userdata('user_id');
		$is_admin = $this->session->userdata('role') === 'admin'; // pastikan role admin sudah diset di session

		// Hanya admin atau pemilik komentar yang boleh hapus
		if ($comment->id_user != $current_user_id && !$is_admin) {
			$this->session->set_flashdata('error', 'Kamu tidak punya izin untuk menghapus komentar ini.');
			redirect($this->input->server('HTTP_REFERER'));
			return;
		}

		// Lanjutkan ke proses hapus
		$result = $this->M_Multiproject->delete_comment($id_comment);
		$message = $result ? 'Komentar berhasil dihapus.' : 'Gagal menghapus komentar.';
		$this->session->set_flashdata($result ? 'success' : 'error', $message);

		redirect($this->input->server('HTTP_REFERER'));
	}

}
