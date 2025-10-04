<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('admin'); // Middleware untuk cek role
		$this->load->model('M_User');
		$this->load->model('M_Follow');
		$this->load->model('M_Content');
		$this->load->model('M_Collaboration');
		$this->load->model('M_Event');
		$this->load->model('M_Medsos');
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
		$data['medsos'] = $this->M_Medsos->get_medsos_list($user_id);
		
		$data['title'] = 'Profil ' . ($data['user']->name . ' | Fly Studio');

		// Hitung jumlah postingan yang dibuat oleh creator
		$data['total_posts'] = $this->M_Content->countCreatorPosts($user_id);
		$data['posts'] = $this->M_Content->getCreatorPosts($user_id);

		// Hitung jumlah kolaborasi yang diikuti
		$data['total_collaborations'] = $this->M_Collaboration->countUserCollaborations($user_id);
		$data['collaborations'] = $this->M_Collaboration->getUserCollaborations($user_id);

		// Hitung jumlah event yang diikuti
		$data['total_events'] = $this->M_Event->countUserEvents($user_id);
		$data['events'] = $this->M_Event->getUserEvents($user_id);

		$this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/profil', $data);
        $this->load->view('Admin/footer', $data);
	}


    public function edit($encoded_id) {
		$user_id = $this->session->userdata('user_id');

		// Validasi session
		if (!$user_id || !ctype_alnum($user_id)) {
			show_error("User ID dalam session tidak valid", 403);
			return;
		}

		// Decode ID yang dikirim
		$decoded_id = rawurldecode($encoded_id);
		if (!preg_match('/^[a-zA-Z0-9\/+=]+$/', $decoded_id)) {
			show_error("Encoded ID tidak valid", 400);
			return;
		}

		$id_user = base64_decode($decoded_id, true);
		if ($id_user === false || !ctype_alnum($id_user)) {
			show_error("Decoding ID gagal atau format ID tidak valid", 400);
			return;
		}

		// Validasi kepemilikan akun
		if ($user_id !== $id_user) {
			show_error("Anda tidak memiliki izin untuk mengedit profil ini", 403);
			return;
		}

        $data['user'] = $this->M_User->get_admin_by_id($id_user);
		$data['title'] = 'Edit Profil ' . ($data['user']->name . ' | Fly Studio');
		if (!$data['user']) {
			show_404();
		}

        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/editprof', $data);
        $this->load->view('Admin/footer', $data);
    }

    public function update($encoded_id) {
		$id = base64_decode(rawurldecode($encoded_id));

		if (!$id) {
			show_error('Invalid Admin ID', 400);
			return;
		}

		$user = $this->M_User->get_admin_by_id($id);
		if (!$user) {
			show_error('User Not Found', 404);
			return;
		}

		// Cek hak akses
		if ($this->session->userdata('user_id') != $id) {
			show_error("Anda tidak memiliki izin untuk mengupdate profil ini", 403);
			return;
		}

		// Validasi form
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('name', 'Nama', 'trim|required');
		$this->form_validation->set_rules('description', 'Deskripsi', 'trim');

		if ($this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
		}

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('Admin/profil/edit/' . rawurlencode(base64_encode($id)));
			return;
		}

		// Data user (tabel user)
		$user_data = [
			'email' => htmlspecialchars($this->input->post('email', TRUE), ENT_QUOTES, 'UTF-8'),
			'name'  => htmlspecialchars($this->input->post('name', TRUE), ENT_QUOTES, 'UTF-8'),
		];

		if (!empty($this->input->post('password'))) {
			$user_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
		}

		// Upload foto profil
		if (!empty($_FILES['profile_picture']['name'])) {
			$config['upload_path']   = './assets/uploads/Profil/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size']      = 2048;
			$config['encrypt_name']  = TRUE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('profile_picture')) {
				$uploadData = $this->upload->data();
				$ext = pathinfo($uploadData['file_name'], PATHINFO_EXTENSION);

				if (!in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
					$this->session->set_flashdata('error', 'Format file tidak didukung.');
					unlink($uploadData['full_path']);
					redirect('Admin/profil/edit/' . rawurlencode(base64_encode($id)));
					return;
				}

				$user_data['profile_picture'] = $uploadData['file_name'];

				// Hapus file lama
				if (!empty($user->profile_picture) && file_exists('./assets/uploads/Profil/' . $user->profile_picture)) {
					unlink('./assets/uploads/Profil/' . $user->profile_picture);
				}
			} else {
				$this->session->set_flashdata('error', $this->upload->display_errors());
				redirect('Admin/profil/edit/' . rawurlencode(base64_encode($id)));
				return;
			}
		} else {
			$user_data['profile_picture'] = $user->profile_picture;
		}

		// Data admin (tabel admin)
		$admin_data = [
			'description' => htmlspecialchars($this->input->post('description', TRUE), ENT_QUOTES, 'UTF-8'),
		];

		// Update ke database via model
		$this->M_User->update_admin($id, $user_data, $admin_data);

		// Update session jika yang login adalah user ini
		if ($this->session->userdata('user_id') == $id) {
			$this->session->set_userdata([
				'email'           => $user_data['email'],
				'name'            => $user_data['name'],
				'description'     => $admin_data['description'],
				'profile_picture' => $user_data['profile_picture'],
			]);
		}

		$this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
		redirect('Admin/profil');
	}


	
	public function add_medsos() {
		$id_medsos = $this->M_Medsos->id_mds();
		$id_user = $this->session->userdata('user_id');
		$platform = $this->input->post('platform');
		$url = $this->input->post('url');

		$result = $this->M_Medsos->add_medsos([
			'id_medsos' => $id_medsos,
			'id_user' => $id_user,
			'platform' => $platform,
			'url' => $url
		]);

		echo json_encode($result);
	}

	public function update_medsos($id) {
		$url = $this->input->post('url');
		$this->M_Medsos->update_medsos($id, $url);
		echo 'ok';
	}

	public function delete_medsos($id) {
		$deleted = $this->M_Medsos->delete_medsos($id);
		if ($deleted) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false, 'message' => 'Gagal menghapus data']);
		}
	}
	
	public function logout() {
        $this->session->sess_destroy(); // Hapus semua session
        redirect('signin'); // Arahkan kembali ke halaman login
    }
}
?>