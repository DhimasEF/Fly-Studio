<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collaboration extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('admin'); // Middleware untuk cek role
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

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		
		$data['contents'] = $this->M_Multiproject->get_all_collaborations_with_details();
		$data['liked_contents'] = $this->M_Multiproject->get_user_liked_collab($this->session->userdata('user_id'));
		$data['title'] = 'Collaboration | Fly Studio';
		//$data['contents'] = $this->M_Content->get_all_content_with_likes();
        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listmultiproject', $data);
        $this->load->view('Admin/footer', $data);
    }

    // Form untuk menambahkan file dan kolaborasi
    public function create() {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
        $data['users'] = $this->M_User->get_all_users(); // Ambil semua user
		$data['title'] = 'Create Collaboration | Fly Studio';
		
        $this->load->view('Admin/sidebar', $data); // Tampilkan form
        $this->load->view('Admin/createmultiproject', $data); // Tampilkan form
        $this->load->view('Admin/footer', $data); // Tampilkan form
    }

	public function store() {
		// 1. Konfigurasi upload file
		$config['upload_path'] = './assets/uploads/MultiProject/';
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, true);
		}
		$config['allowed_types'] = 'jpg|jpeg|png|mp4|avi|mkv';
		$config['max_size'] = 10240; // Max size in KB
		$config['file_name'] = uniqid('media_');

		$this->load->library('upload', $config);

		// 2. Proses unggah file
		if (!$this->upload->do_upload('file_path')) {
			$error = $this->upload->display_errors('', '');
			log_message('error', "File upload error: $error");
			$this->session->set_flashdata('error', "Error uploading file: $error");
			redirect('Admin/collaboration/add');
			exit;
		}

		// Ambil data file yang diunggah
		$upload_data = $this->upload->data();

		// Data file untuk disimpan ke database
		$file_data = [
			'id_file' => $this->M_Multiproject->id_mpf(),
			'title' => $this->input->post('title', true),
			'description' => $this->input->post('description', true),
			'file_name' => $upload_data['file_name'], // Ambil nama file
			'upload_at' => date('Y-m-d H:i:s')
		];

		// Simpan file ke database
		if (!$this->M_Multiproject->insert($file_data)) {
			$this->session->set_flashdata('error', 'Gagal menyimpan data file.');
			redirect('Admin/collaboration');
			exit;
		}

		/*
		// 3. Tambahkan partisipan ke tabel collaborations
		$participants = $this->input->post('participants');
		if (!empty($participants) && is_array($participants)) {
			$collab_data_batch = [];
			$offset = 0; // Awal offset

			foreach ($participants as $user_id) {
				$collab_data_batch[] = [
					'id_collaboration' => $this->M_Collaboration->id_mpp($offset),
					'id_content' => $file_data['id_file'],
					'id_user' => filter_var($user_id, FILTER_SANITIZE_STRING)
				];
				$offset++; // Tingkatkan offset untuk ID unik
			}

			if (!empty($collab_data_batch)) {
				if (!$this->M_Collaboration->insert_collaboration($collab_data_batch)) {
					$this->session->set_flashdata('error', 'Gagal menyimpan data kolaborasi.');
					redirect('Admin/collaboration');
					exit;
				}
			}
		}
		*/


		// 4. Redirect ke halaman sukses
		$this->session->set_flashdata('success', 'File dan kolaborasi berhasil disimpan!');
		redirect('Admin/collaboration');
		exit;
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
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/multidtl', $data);
		$this->load->view('Admin/footer', $data);
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
			redirect('Admin/collaboration/view/' .  rawurlencode(base64_encode($id_file)));
			return;
		}

		// Validasi id_parent (jika diberikan)
		if ($id_parent) {
			$parent_comment = $this->M_Multiproject->get_comment_by_id($id_parent);
			if (!$parent_comment) {
				$this->session->set_flashdata('error', 'Invalid parent comment.');
				redirect('Admin/collaboration/view
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

		redirect('Admin/collaboration/view/' .  rawurlencode(base64_encode($id_file)));
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
		
	
	public function edit($encoded_id_content) {
		// Decode id_content dari Base64 URL
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		// Ambil data user (admin)
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		
		$data['content'] = $this->M_Collaboration->get_content_detail($id_file);
		
		if (!$data['content']) {
			show_404();
		}
		
		$data['title'] = 'Edit Collaboration ' . ($data['content']-> title . ' | Fly Studio');


		if ($this->input->post()) {
			$update = [
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
			];

			if ($this->M_Collaboration->update_content($id_file, $update)) {
				$this->session->set_flashdata('success', 'Konten berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('error', 'Gagal memperbarui konten.');
			}

			redirect('Admin/collaboration/view/' . rawurlencode(base64_encode($id_file)));
		}

		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/collabedit', $data);
		$this->load->view('Admin/footer');
	}
	
	public function update($encoded_id_content) {
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		$title = $this->input->post('title', true);
		$description = $this->input->post('description', true);

		if (empty($title) || empty($description)) {
			$this->session->set_flashdata('error', 'Semua field harus diisi.');
			redirect('Admin/collaboration/edit/' . rawurlencode($encoded_id_content));
			return;
		}

		// Ambil data lama
		$content = $this->M_Multiproject->get_content_detail($id_file);

		// Persiapan array data
		$data = [
			'title' => $title,
			'description' => $description
		];

		// Cek jika user ingin menghapus thumbnail lama
		if ($this->input->post('delete_thumbnail') == '1' && !empty($content->thumbnail)) {
			$old_path = FCPATH . 'assets/uploads/MultiProject/Thumbnail/' . $content->thumbnail;
			if (file_exists($old_path)) {
				unlink($old_path);
			}
			$data['thumbnail'] = null;
		}

		// Upload thumbnail baru jika ada
		if (!empty($_FILES['thumbnail']['name'])) {
			$config['upload_path'] = './assets/uploads/MultiProject/Thumbnail/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size'] = 2048; // 2MB
			$config['file_name'] = 'thumb_' . time();

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('thumbnail')) {
				$this->session->set_flashdata('error', 'Gagal upload thumbnail: ' . $this->upload->display_errors('', ''));
				redirect('Admin/collaboration/edit/' . rawurlencode(base64_encode($id_file)));
				return;
			}

			$thumbnail_data = $this->upload->data();
			$data['thumbnail'] = $thumbnail_data['file_name'];

			// Hapus thumbnail lama jika ada
			if (!empty($content->thumbnail)) {
				$old_path = FCPATH . 'assets/uploads/MultiProject/Thumbnail/' . $content->thumbnail;
				if (file_exists($old_path)) {
					unlink($old_path);
				}
			}
		}

		// Simpan perubahan ke DB
		$update = $this->M_Multiproject->update_content($id_file, $data);

		if ($update) {
			$this->session->set_flashdata('success', 'Konten berhasil diperbarui.');
		} else {
			$this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan.');
		}

		redirect('Admin/collaboration/view/' . rawurlencode(base64_encode($id_file)));
	}


	public function manage_participant($encoded_id_content) {
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		// Ambil data user (admin)
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		$data['content'] = $this->M_Collaboration->get_content_detail($id_file);
		$data['title'] = 'Manage Participant Collaboration | Fly Studio';

		$data['participants'] = $this->M_Collaboration->get_participants_by_file($id_file);
		$data['users'] = $this->M_Multiproject->get_available_users_for_collab($id_file);

		if (!$data['content']) {
			show_404();
		}

		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/manageparti', $data);
		$this->load->view('Admin/footer');
	}


	public function delete_participant($encoded_id_content, $id_user) {
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		
		$this->M_Collaboration->delete_participant($id_file, $id_user);
		$this->session->set_flashdata('success', 'Peserta berhasil dihapus.');
		redirect('Admin/collaboration/manage_participant/' .  rawurlencode(base64_encode($id_file)));
	}

	public function add_participant($encoded_id_content) {
		$id_file = base64_decode(rawurldecode($encoded_id_content));
		if ($this->input->method() === 'post') {
			$id_user = $this->input->post('id_user');
			$part_label = trim($this->input->post('part_label'));

			// Cek apakah user sudah jadi participant
			$exists_user = $this->db->get_where('collaboration_participant', [
				'id_content' => $id_file,
				'id_user' => $id_user
			])->row();

			if ($exists_user) {
				$this->session->set_flashdata('error', 'User ini sudah terdaftar sebagai participant.');
				redirect('Admin/collaboration/manage_participant/' . rawurlencode(base64_encode($id_file)));
				return;
			}

			// Cek apakah part_label sudah digunakan (jika tidak kosong)
			if ($part_label !== '') {
				$this->db->where('id_content', $id_file);
				$this->db->where('part_label', $part_label);
				$exists_label = $this->db->get('collaboration_participant')->row();

				if ($exists_label) {
					$this->session->set_flashdata('error', 'Part label tersebut sudah digunakan oleh participant lain.');
					redirect('Admin/collaboration/manage_participant/' . rawurlencode(base64_encode($id_file)));
					return;
				}
			}

			// Tambahkan participant baru
			$this->db->insert('collaboration_participant', [
				'id_collaboration' => $this->M_Collaboration->id_mpp(),
				'id_content' => $id_file,
				'id_user' => $id_user,
				'part_label' => $part_label
			]);

			$this->session->set_flashdata('success', 'Participant berhasil ditambahkan.');

			redirect('Admin/collaboration/manage_participant/' . rawurlencode(base64_encode($id_file)));
		}
	}

}
