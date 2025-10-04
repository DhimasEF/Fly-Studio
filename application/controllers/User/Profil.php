<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('user'); // Middleware untuk cek role
		$this->load->model('M_User');
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
			show_error("User ID tidak ditemukan di session", 403);
		}
		
		$data['user'] = $this->M_User->get_user_by_id($user_id); // Ambil data terbaru dari database
		$data['title'] = 'Profil ' . ($data['user']->name . ' | Fly Studio');
		
		// Hitung jumlah kolaborasi yang diikuti
		$data['total_collaborations'] = $this->M_Collaboration->countUserCollaborations($user_id);
		$data['collaborations'] = $this->M_Collaboration->getUserCollaborations($user_id);

		// Hitung jumlah event yang diikuti
		$data['total_events'] = $this->M_Event->countUserEvents($user_id);
		$data['events'] = $this->M_Event->getUserEvents($user_id);
		
		$data['total_transactions'] = $this->M_Transaction->countUserTransactions($user_id);
		$data['transactions'] = $this->M_Transaction->getUserTransactions($user_id);

		$this->load->view('User/sidebar', $data);
		$this->load->view('User/profil', $data);
		$this->load->view('User/footer', $data);
	}

    public function edit($encoded_id) {
        $id = base64_decode(rawurldecode($encoded_id)); // Decode dengan rawurldecode untuk URL
        $data['user'] = $this->M_User->get_user_by_id($id);
		$data['title'] = 'Edit Profil ' . ($data['user']->name . ' | Fly Studio');
		
        // Pastikan user ditemukan sebelum lanjut
        if (!$data['user']) {
            show_404();
        }

        $this->load->view('User/sidebar', $data);
        $this->load->view('User/editprof', $data);
        $this->load->view('User/footer', $data);
    }

    public function update($encoded_id) {
		$id = base64_decode(rawurldecode($encoded_id));

		if (!$id) {
			show_error('Invalid User ID', 400);
			return;
		}

		$user = $this->M_User->get_user_by_id($id);
		if (!$user) {
			show_error('User Not Found', 404);
			return;
		}

		$email = $this->input->post('email', TRUE);
		$name = $this->input->post('name', TRUE);
		$password = $this->input->post('password');

		// Validasi email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_flashdata('error', 'Format email tidak valid.');
			redirect('User/profil/edit/' . rawurlencode(base64_encode($id)));
			return;
		}

		// Validasi name tidak boleh kosong
		if (empty($name)) {
			$this->session->set_flashdata('error', 'Nama tidak boleh kosong.');
			redirect('User/profil/edit/' . rawurlencode(base64_encode($id)));
			return;
		}

		// Data yang akan diperbarui
		$data = [
			'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
			'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
			'role' => $user->role // Tetap gunakan role yang lama
		];

		// Update password jika diisi
		if (!empty($password)) {
			if (strlen($password) < 6) {
				$this->session->set_flashdata('error', 'Password harus minimal 6 karakter.');
				redirect('User/profil/edit/' . rawurlencode(base64_encode($id)));
				return;
			}
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);
		}

		// Handle upload gambar
		if (!empty($_FILES['profile_picture']['name'])) {
			$config['upload_path'] = './assets/uploads/Profil/';
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = 2048; // Maksimal 2MB
			$config['encrypt_name'] = TRUE; // Hindari nama file duplikat

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('profile_picture')) {
				$uploadData = $this->upload->data();
				$fileExt = pathinfo($uploadData['file_name'], PATHINFO_EXTENSION);

				// Validasi ekstensi file
				$allowedExt = ['jpg', 'jpeg', 'png'];
				if (!in_array(strtolower($fileExt), $allowedExt)) {
					$this->session->set_flashdata('error', 'Format file tidak didukung.');
					unlink($uploadData['full_path']); // Hapus file yang tidak valid
					redirect('User/profil/edit/' . rawurlencode(base64_encode($id)));
					return;
				}

				$data['profile_picture'] = $uploadData['file_name'];

				// Hapus foto lama jika ada
				if (!empty($user->profile_picture) && file_exists('./assets/uploads/Profil/' . $user->profile_picture)) {
					unlink('./assets/uploads/Profil/' . $user->profile_picture);
				}
			} else {
				// Jika upload gagal, tampilkan pesan error
				$this->session->set_flashdata('error', $this->upload->display_errors());
				redirect('User/profil/edit/' . rawurlencode(base64_encode($id)));
				return;
			}
		} else {
			// Jika tidak ada upload gambar baru, gunakan gambar lama
			$data['profile_picture'] = $user->profile_picture;
		}

		// Update data user di database
		$this->M_User->update_user($id, $data);

		// **Perbarui session jika user yang sedang login diupdate**
		if ($this->session->userdata('id') == $id) {
			$this->session->set_userdata([
				'email' => $data['email'],
				'name'  => $data['name'],
				'role'  => $data['role'],
				'profile_picture' => $data['profile_picture']
			]);
		}

		// Redirect ke profil/index setelah update berhasil
		$this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
		redirect('User/profil');
	}


	
	public function logout() {
        $this->session->sess_destroy(); // Hapus semua session
        redirect('signin'); // Arahkan kembali ke halaman login
    }
}
?>