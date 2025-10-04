<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Middle_Controller {
    public function __construct() {
        parent::__construct();
		$this->check_role('admin'); // Middleware untuk cek role
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

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		
		$data['contents'] = $this->M_Content->get_all_content_with_likes();
		$data['liked_contents'] = $this->M_Content->get_user_liked_content($this->session->userdata('user_id'));
		$data['title'] = 'Content | Fly Studio';
		
		//$data['contents'] = $this->M_Content->get_all_content_with_likes();
        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listcontent', $data);
        $this->load->view('Admin/footer', $data);
    }

    // Add new content
     // Load view form upload
    public function upload_form() {
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			show_error("User ID tidak ditemukan di session", 403);
		}
		
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		$data['title'] = 'Create Content | Fly Studio';

        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/createcontent');
        $this->load->view('Admin/footer');
    }

    // Process content upload
    public function upload() {
        $config['upload_path'] = './assets/uploads/Content/';
        $config['allowed_types'] = 'jpg|jpeg|png|mp4|mov|avi';
        $config['max_size'] = 102400; // Maks 100MB
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_url')) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('Admin/upload_initial', $data);
        } else {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $file_type = (strpos($upload_data['file_type'], 'image') !== false) ? 'Image' : 'Video';

            $id_content = $this->M_Content->id_ctn(); // Ambil ID dari konten yang baru dimasukkan
			
            // Data yang akan disimpan
            $data = [
                'id_content' => $id_content,
                'id_uploader' => $this->session->userdata('user_id'), // Ambil dari session user
                'title' => $this->input->post('title'),
                'file_name' => $file_name,
                'file_type' => $file_type,
                'file_url' => base_url('assets/uploads/Content/' . $file_name),
                'created_at' => date('Y-m-d H:i:s'),
                'view_count' => 0
            ];

            // Insert ke database
            $this->M_Content->insert_content($data);

            // Redirect ke tahap berikutnya
            redirect('Admin/content/uploaddtl/' . $id_content);
        }
    }
	
	public function uploaddtl($id_content) {
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			show_error("User ID tidak ditemukan di session", 403);
		}
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		
        $query = $this->db->get_where('konten', ['id_content' => $id_content]);
        if ($query->num_rows() == 0) {
            redirect('Admin/content/upload'); // Jika ID tidak ditemukan
        }
        $data['content'] = $query->row();
		$data['title'] = 'Create Content ' . ($data['content']->title . ' | Fly Studio');
		
        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/createcontentdetail', $data);
        $this->load->view('Admin/footer', $data);
    }
	
	public function save_content_details() {
        $id_content = $this->input->post('id_content');

        // Jika Video, maka upload thumbnail juga
        $thumbnail_url = null;
        if ($this->input->post('file_type') === 'Video' && !empty($_FILES['thumbnail']['name'])) {
            $config['upload_path'] = './assets/uploads/Content/Thumbnail/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 10240; // Maks 10MB
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('thumbnail')) {
                $thumbnail_data = $this->upload->data();
                $thumbnail_name = $thumbnail_data['file_name'];
            }
        }

        // Update konten dengan deskripsi dan thumbnail (jika ada)
        $data = [
            'description' => $this->input->post('description')
        ];
        if ($thumbnail_name) {
            $data['thumbnail'] = $thumbnail_name;
        }

        $this->db->where('id_content', $id_content);
        $this->db->update('konten', $data);

        redirect('Admin/content'); // Redirect ke daftar konten setelah selesai
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
		$data['user'] = $this->M_User->get_admin_by_id($user_id);

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

		// Tampilkan view
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/contentdtl', $data);
		$this->load->view('Admin/footer');
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
				redirect('Admin/content/view/' . rawurlencode(base64_encode($id_content)));
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
		redirect('Admin/content/view/' .  rawurlencode(base64_encode($id_content)));
	}


    // Mendapatkan komentar dalam format JSON (opsional, jika diperlukan)
    public function get_comments($id_content) {
        $comments = $this->M_Comment->get_comments_by_content($id_content);
        echo json_encode($comments);
    }

    // Menghapus komentar (dan opsional balasannya jika ada logika seperti itu)
    public function delete_comment($id_comment) {
		$comment = $this->M_Comment->get_comment_by_id($id_comment);
		if (!$comment) {
			$this->session->set_flashdata('error', 'Comment not found.');
			redirect($this->input->server('HTTP_REFERER'));
			return;
		}

		$id_user = $this->session->userdata('user_id');
		if ($comment['id_user'] != $id_user && !$this->session->userdata('is_admin')) {
			$this->session->set_flashdata('error', 'Unauthorized access.');
			redirect($this->input->server('HTTP_REFERER'));
			return;
		}

		$result = $this->M_Comment->delete_comment($id_comment);
		$message = $result ? 'Comment deleted successfully.' : 'Failed to delete comment.';
		$this->session->set_flashdata($result ? 'success' : 'error', $message);

		redirect($this->input->server('HTTP_REFERER'));
	}

	
	public function edit($encoded_id) {
		$user_id = $this->session->userdata('user_id');
		
		$id_content = base64_decode(rawurldecode($encoded_id));  // <-- DECODE dulu
		$content = $this->M_Content->get_content_detail($id_content);

		if (!$content) {
			show_404();
		}

		// Cek apakah yang mau edit itu uploadernya
		if ($content->id_uploader != $user_id) {
			show_error("You are not allowed to edit this content.", 403);
		}
		
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		$data['content'] = $content;
		$data['title'] = 'Content ' . ($data['content']->title . ' | Fly Studio');
		
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/editcontent', $data);
		$this->load->view('Admin/footer');
	}

	public function update($encoded_id) {
		$id_content = base64_decode(rawurldecode($encoded_id));  // <-- DECODE dulu
		$user_id = $this->session->userdata('user_id');
		$content = $this->M_Content->get_content_detail($id_content);

		if (!$content) {
			show_404();
		}

		if ($content->id_uploader != $user_id) {
			show_error("You are not allowed to edit this content.", 403);
		}

		$title = $this->input->post('title');
		$description = $this->input->post('description');

		$this->M_Content->update_content($id_content, [
			'title' => $title,
			'description' => $description
		]);

		$this->session->set_flashdata('success', 'Content updated successfully!');
		redirect('Admin/content/view/' . rawurlencode(base64_encode($id_content)));
	}
	
	public function delete($encoded_id)	{
		$id_content = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		
		// Pastikan user sudah login
		if (!$this->session->userdata('user_id')) {
			redirect('auth/login');
			return;
		}

		// Ambil data konten berdasarkan id_content
		$content = $this->M_Content->get_content_detail($id_content);

		if (!$content) {
			$this->session->set_flashdata('error', 'Content not found.');
			redirect('Admin/content');
			return;
		}

		// Pastikan hanya uploader yang boleh hapus
		if ($content->id_uploader != $this->session->userdata('user_id')) {
			$this->session->set_flashdata('error', 'You do not have permission to delete this content.');
			redirect('Admin/content');
			return;
		}

		// Hapus file konten di server (kalau perlu)
		if (!empty($content->file_name)) {
			$file_path = FCPATH . 'assets/uploads/Content/' . $content->file_name;
			if (file_exists($file_path)) {
				unlink($file_path);
			}
		}

		// (Optional) Hapus thumbnail kalau ada
		if (!empty($content->thumbnail)) {
			$thumb_path = FCPATH . 'assets/uploads/Content/Thumbnail/' . $content->thumbnail;
			if (file_exists($thumb_path)) {
				unlink($thumb_path);
			}
		}

		// Hapus dari database
		$this->M_Content->delete_content($id_content);

		$this->session->set_flashdata('success', 'Content successfully deleted.');
		redirect('Admin/content');
	}

}

?>

