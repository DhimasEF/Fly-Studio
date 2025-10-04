<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('user'); // Middleware untuk cek role
        $this->load->model('M_User');
        $this->load->model('M_Event');
        $this->load->model('M_EventFile');
        $this->load->model('M_EventParticipant');
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

    // Display list of events
    public function index() {
		$user_id = $this->session->userdata('user_id'); // Ambil ID user dari session
		
		$data['user'] = $this->M_User->get_user_by_id($user_id);
		
		if (!$user_id) {
			echo "User ID tidak ditemukan di session"; die();
		}
		$data['title'] = 'Event | Fly Studio';
		
		$scope = strtolower($this->input->get('scope'));
		$data['scope'] = $scope;
		$data['events'] = $this->M_Event->getAllEvents($scope, 'user');
	
        $this->load->view('User/sidebar', $data);
        $this->load->view('User/event', $data);
        $this->load->view('User/footer', $data);
    }

    // Display event details
    public function detail($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}
		
		
		// Ambil event, user, dan data lain
		$data['user'] = $this->M_User->get_user_by_id($user_id);
		$data['event'] = $this->M_Event->get_event_by_id($id_event);
		$data['title'] = 'Event ' . ($data['event']->event_name . ' | Fly Studio');
		
		$data['current_participants'] = $this->M_EventParticipant->count_participants($id_event);
		$data['event_files'] = $this->M_EventFile->get_files_by_event($id_event);
		$data['participants'] = $this->M_EventParticipant->get_event_participants($id_event);

		// Cek apakah user sudah join event ini
		$data['has_joined'] = $this->M_EventParticipant->has_user_joined($id_event, $user_id);
		
		$data['user_recruitment'] = $this->M_Recruitment->get_user_recruitment_by_event($id_event, $user_id);

		$this->load->view('User/sidebar', $data);
		$this->load->view('User/eventdtl', $data);
		$this->load->view('User/footer', $data);
	}


    // Join an event
    public function join($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
        $id_user = $this->session->userdata('user_id');

        if (!$id_user) {
            $this->session->set_flashdata('error', 'You must log in to join an event.');
            redirect('creator/event/detail/' . $id_event);
        }

        $current_participants = $this->M_EventParticipant->count_participants($id_event);
        $event = $this->M_Event->get_event_by_id($id_event);

        if ($current_participants < $event->max_participants) {
            $this->M_EventParticipant->add_participant($id_event, $id_user);
            $this->session->set_flashdata('success', 'You have successfully joined the event.');
        } else {
            $this->session->set_flashdata('error', 'Event is already full.');
        }

        redirect('User/event/detail/' . rawurlencode(base64_encode($id_event)));
    }
	
	public function leave($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		// Panggil model untuk hapus keikutsertaan
		$this->M_EventParticipant->leave_event($id_event, $user_id);

		// Set flashdata & redirect
		$this->session->set_flashdata('success', 'You have left the event.');
		redirect('User/event/detail/' . rawurlencode(base64_encode($id_event)));
	}
}

?>