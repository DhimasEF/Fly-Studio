<?php 

class Listrecruitment extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('admin'); // Middleware untuk cek role
        $this->load->model('M_Recruitment');
        $this->load->model('M_User');
        $this->load->library('session');

        // Pastikan hanya admin yang bisa mengakses controller ini
        if ($this->session->userdata('role') != 'admin') {
            redirect('signin');
        }
    }

    public function list() {
		$user_id = $this->session->userdata('user_id'); // Pastikan session user_id ada
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
        $data['recruitments'] = $this->M_Recruitment->get_all_applications();
        $data['title'] = 'List Recruitment | Fly Studio';
        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/listrec', $data);
        $this->load->view('Admin/footer', $data);
    }

    public function update_status($id_recruit, $status) {
		$id_admin = $this->session->userdata('user_id');
		
		// Ambil data rekrutmen berdasarkan id_recruit
		$recruitment = $this->M_Recruitment->get_by_id($id_recruit);

		if (!$recruitment) {
			$this->session->set_flashdata('error', 'Recruitment not found!');
			redirect('Admin/listrecruitment/list');
		}

		// Update status rekrutmen
		$data_update = [
			'status' => $status,
			'id_admin' => $id_admin,
			'decision_at' => date('Y-m-d H:i:s')
		];

		if ($this->M_Recruitment->update_status($id_recruit, $data_update)) {
			if ($status === 'approved') {
				// Jika status approved, ubah role user menjadi creator
				$this->M_User->update_role($recruitment['id_user'], 'creator');
				
				// Tambahkan profil creator hanya jika belum ada
				$create_profile = $this->M_User->create_creator_profile($recruitment['id_user']);
				
				// Periksa apakah profil creator berhasil dibuat
				if ($create_profile) {
					$this->session->set_flashdata('success', 'Recruitment approved, user role updated, and creator profile created!');
				} else {
					$this->session->set_flashdata('error', 'Failed to create creator profile.');
				}

			} elseif ($status === 'rejected') {
				$this->session->set_flashdata('success', 'Recruitment rejected!');
			}
		} else {
			$this->session->set_flashdata('error', 'Failed to update recruitment status.');
		}

		redirect('Admin/listrecruitment/list');
	}

	
}

?>