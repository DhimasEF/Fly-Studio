<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Middle_Controller {
    public function __construct() {
        parent::__construct();
		$this->check_role('user'); // Middleware untuk cek role
        $this->load->model('M_User');
        $this->load->model('M_Content');
        $this->load->model('M_Comment');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
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
		$data['is_collab'] = false;
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->get_user_by_id($user_id);
		
		$data['contents'] = $this->M_Content->get_all_content_with_likes();
		$data['liked_contents'] = $this->M_Content->get_user_liked_content($this->session->userdata('user_id'));
		$data['title'] = 'Content | Fly Studio';

		//$data['contents'] = $this->M_Content->get_all_content_with_likes();
        $this->load->view('User/sidebar', $data);
        $this->load->view('User/listcontent', $data);
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
        $this->load->view('User/sidebar', $data);
		$this->load->view('User/contentdtl', $data);
        $this->load->view('User/footer', $data);
	}

    // Menambahkan atau menghapus like berdasarkan kondisi
    public function toggle_like($id_content) {
        $id_user = $this->session->userdata('user_id'); // Ambil id_user dari session
        if (!$id_user) {
            redirect('signin'); // Redirect jika user belum login
        }

        if ($this->M_Content->is_liked($id_content, $id_user)) {
            // Jika sudah like, hapus like
            $this->M_Content->remove_like($id_content, $id_user);
            $response = ['status' => 'removed'];
        } else {
            // Jika belum like, tambahkan like
            $this->M_Content->add_like($id_content, $id_user);
            $response = ['status' => 'added'];
        }

        echo json_encode($response); // Response untuk AJAX
    }

	public function add_comment($encoded_id_content, $id_parent = null) {
		// Decode id_content dari base64
		$id_content = base64_decode(rawurldecode($encoded_id_content));

		// Validasi input komentar
		$this->form_validation->set_rules('comment', 'Comment', 'required');

		// Jika ada parent ID (berarti ini adalah reply)
		if ($id_parent !== null) {
			$parent_comment = $this->M_Comment->get_comment_by_id($id_parent);
			if (!$parent_comment) {
				$this->session->set_flashdata('error', 'Invalid parent comment.');
				redirect('User/content/view/' . rawurlencode(base64_encode($id_content)));
				return;
			}
		}

		// Cek validasi
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
		} else {
			// Data yang disimpan, id_comment auto-increment
			$comment_data = [
				'id_content'   => $id_content,
				'id_user'      => $this->session->userdata('user_id'),
				'id_parent'    => $id_parent, // null jika komentar utama
				'comment_text' => $this->input->post('comment'),
				'created_at'   => date('Y-m-d H:i:s')
			];

			// Simpan ke database
			$result = $this->M_Comment->add_comment($comment_data);

			if ($result) {
				$this->session->set_flashdata('success', 'Comment added successfully!');
			} else {
				$this->session->set_flashdata('error', 'Failed to add comment.');
			}
		}

		// Redirect balik ke halaman detail konten
		redirect('User/content/view/' .  rawurlencode(base64_encode($id_content)));
	}


    // Mendapatkan komentar dalam format JSON (opsional, jika diperlukan)
    public function get_comments($id_content) {
        $comments = $this->M_Comment->get_comments_by_content($id_content);
        echo json_encode($comments);
    }

    // Menghapus komentar (dan opsional balasannya jika ada logika seperti itu)
    public function delete_comment($id_comment) {
        $result = $this->M_Comment->delete_comment($id_comment);
        $message = $result ? 'Comment deleted successfully.' : 'Failed to delete comment.';
        $this->session->set_flashdata($result ? 'success' : 'error', $message);

        // Redirect kembali ke halaman sebelumnya
        redirect($this->input->server('HTTP_REFERER'));
    }

}

?>

