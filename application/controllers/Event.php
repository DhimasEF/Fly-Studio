<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('M_User');
        $this->load->model('M_Event');
		$this->load->model('M_EventParticipant');
		$this->load->model('M_EventFile');
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
	
	public function index() {
		$data = ['title' => 'Event l Fly Studio'];
		$data['events'] = $this->M_Event->getAllEvents($scope, 'user');
		$this->load->view('navbar', $data);
		$this->load->view('event', $data);
		//$this->load->view('footer');
	}
	
	public function detail($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		$data['event'] = $this->M_Event->get_event_by_id($id_event);
		
		$data['title'] = 'Event ' . ($data['event']->event_name . ' | Fly Studio');
		$data['current_participants'] = $this->M_EventParticipant->count_participants($id_event);
		$data['event_files'] = $this->M_EventFile->get_files_by_event($id_event);
		$data['participants'] = $this->M_EventParticipant->get_event_participants($id_event);

		// Cek apakah user sudah join event ini
		$data['has_joined'] = $this->M_EventParticipant->has_user_joined($id_event, $user_id);

		$this->load->view('navbar', $data);
		$this->load->view('eventdtl', $data);
		//$this->load->view('footer', $data);
	}
}
?>
