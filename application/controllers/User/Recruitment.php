<?php

class Recruitment extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('user'); // Middleware untuk cek role
        $this->load->model('M_Event');
        $this->load->model('M_User');
        $this->load->model('M_Recruitment');
        $this->load->library('form_validation');
		$this->load->library('session');
		
		 // Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }
	
	public function form($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			redirect('login');
		}
		$user = $this->M_User->get_user_by_id($user_id);

		$event = $this->M_Event->get_event_by_id($id_event); // Pastikan ada method ini
		if (!$event || strtolower($event->category_name) !== 'recruitment') {
			show_404();
		}

		$data['event'] = $event;
		$existing_application = $this->M_Recruitment->get_by_user_event($user_id, $id_event); // ⬅️ Buat method ini jika belum ada

		$data = [
			'user' => $user,
			'event' => $event,
			'application' => $existing_application
		];
		
		$data['title'] = 'Apply ' . ($data['event']->event_name . ' | Fly Studio');

		$this->load->view('User/sidebar', $data);
		$this->load->view('User/apply', $data); // ✅ di dalam ini form submit ke apply()
		$this->load->view('User/footer', $data);
	}


	public function apply($encoded_event_id) {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			redirect('login');
		}

		$id_event = base64_decode(rawurldecode($encoded_event_id));
		if (!$id_event) {
			show_error("Invalid event ID", 400);
		}

		$this->form_validation->set_rules('work_url', 'Work URL', 'required|valid_url');
		$this->form_validation->set_rules('reason_text', 'Reason', 'required');

		if ($this->form_validation->run() === FALSE) {
			return $this->form(rawurlencode(base64_encode($id_event))); // validasi gagal → balik ke form
		}

		$id_recruit = $this->input->post('id_recruit');

		$data = [
			'work_url'    => $this->input->post('work_url'),
			'reason_text' => $this->input->post('reason_text'),
			'applied_at'  => date('Y-m-d H:i:s')
		];

		if ($id_recruit) {
			$success = $this->M_Recruitment->update_by_id($id_recruit, $data);
			$message = $success ? 'Application updated!' : 'Failed to update application.';
		} else {
			$data['id_recruit'] = $this->M_Recruitment->id_rec();
			$data['id_event']   = $id_event;
			$data['id_user']    = $user_id;
			$data['status']     = 'pending';

			$success = $this->M_Recruitment->apply($data);
			$message = $success ? 'Application submitted!' : 'Failed to submit application.';
		}

		$this->session->set_flashdata($success ? 'success' : 'error', $message);
		redirect('User/event/detail/' . rawurlencode(base64_encode($id_event)));
	}




}

?>