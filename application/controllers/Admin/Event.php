<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('admin'); // Middleware untuk cek role
        $this->load->model('M_User');
        $this->load->model('M_Event');
		$this->load->model('M_EventParticipant');
		$this->load->model('M_EventFile');
        $this->load->library('form_validation');
        $this->load->library('session');
		
		 // Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }

    // Menampilkan form untuk membuat event baru
    public function create() {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}
		
		$data['title'] = 'Create Event | Fly Studio';

		$data['user'] = $this->M_User->get_admin_by_id($user_id);
		$data['user'] = $this->M_User->get_admin_by_id($user_id);
        // Mengambil kategori dan scope dari database
        $data['categories'] = $this->M_Event->get_all_categories();
        $data['scopes'] = $this->M_Event->get_all_scopes();
        
        // Memuat view untuk form pembuatan event
        $this->load->view('Admin/sidebar', $data);
        $this->load->view('Admin/cevent', $data);
        $this->load->view('Admin/footer', $data);
    }

    // Proses untuk menyimpan event baru
	public function store() {
		$this->form_validation->set_rules('event_name', 'Event Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('id_category', 'Category', 'required');
		$this->form_validation->set_rules('id_scope', 'Scope', 'required');

		// Ambil nama kategori berdasarkan id
		$id_category = $this->input->post('id_category');
		$category = $this->db->get_where('event_categories', ['id_category' => $id_category])->row();

		if ($category && in_array(strtolower($category->category_name), ['mep', 'battle', 'collab'])) {
			// Tambahkan validasi jika kategori MEP atau Battle
			$this->form_validation->set_rules('max_participant', 'Max Participant', 'required|integer|greater_than[0]');
		}

		if ($this->form_validation->run() === FALSE) {
			$this->create();
		} else {
			$data = [
				'id_event' => uniqid('EV'),
				'event_name' => $this->input->post('event_name'),
				'description' => $this->input->post('description'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date'),
				'id_category' => $id_category,
				'id_scope' => $this->input->post('id_scope'),
				'created_at' => date('Y-m-d H:i:s')
			];

			// Tambahkan max_participant hanya jika MEP/Battle
			if ($category && in_array(strtolower($category->category_name), ['mep', 'battle', 'collab'])) {
				$data['max_participants'] = $this->input->post('max_participant');
			}

			if ($this->M_Event->create_event($data)) {
				$this->session->set_flashdata('success', 'Event created successfully.');
				redirect('Admin/event');
			} else {
				$this->session->set_flashdata('error', 'Failed to create event.');
				redirect('Admin/event/create');
			}
		}
	}


    public function index() {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}
		
		$data['title'] = 'Event | Fly Studio';

		$data['user'] = $this->M_User->get_admin_by_id($user_id);

		$scope = strtolower($this->input->get('scope'));
		$data['scope'] = $scope;
		$data['events'] = $this->M_Event->getAllEvents($scope, 'admin');

		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/levent', $data);
		$this->load->view('Admin/footer', $data);
	}

	
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

		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/eventdtl', $data);
		$this->load->view('Admin/footer', $data);
	}

	// Menampilkan Form Edit Event
	public function edit($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->get_user_by_id($user_id);
		$data['event'] = $this->M_Event->get_event_by_id($id_event);
		
		$data['title'] = 'Event ' . ($data['event']->event_name . ' | Fly Studio');

		if (!$data['event']) {
			show_404();
		}

		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/editevent', $data);
		$this->load->view('Admin/footer');
	}
	
	public function update_event($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));
		$event = $this->M_Event->get_event_by_id($id_event);
		if (!$event) {
			show_404();
		}

		// Cek data lama, update hanya jika ada perubahan
		$data_update = [
			'event_name'  => $this->input->post('event_name') ?: $event->event_name,
			'description' => $this->input->post('description') ?: $event->description,
			'start_date'  => $this->input->post('start_date') ?: $event->start_date,
			'end_date'    => $this->input->post('end_date') ?: $event->end_date,
		];

		// Update max_participants hanya untuk MEP/Battle/Collab
		if ($event->category_name !== 'recruitment') {
			$data_update['max_participants'] = $this->input->post('max_participants') ?: $event->max_participants;
		}

		// Proses upload banner jika ada
		if (!empty($_FILES['banner']['name'])) {
			$config['upload_path'] = 'assets/uploads/FileEvent/Banner/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size'] = 2048; // 2MB
			$config['file_name'] = 'banner_' . time();
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('banner')) {
				$this->session->set_flashdata('error', $this->upload->display_errors());
				redirect('Admin/event/edit/' . rawurlencode(base64_encode($id_event)));
				return;
			}

			$upload_data = $this->upload->data();
			$data_update['banner'] = $upload_data['file_name'];
		}

		// Jalankan update
		if ($this->M_Event->update_event($id_event, $data_update)) {
			$this->session->set_flashdata('success', 'Event updated successfully.');
		} else {
			$this->session->set_flashdata('error', 'Failed to update event.');
		}

		redirect('Admin/event/detail/' . rawurlencode(base64_encode($id_event)));
	}


	
    // Join an event
    public function join($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
        $id_user = $this->session->userdata('user_id');

        if (!$id_user) {
            $this->session->set_flashdata('error', 'You must log in to join an event.');
            redirect('Admin/event/detail/' . $id_event);
        }

        $current_participants = $this->M_EventParticipant->count_participants($id_event);
        $event = $this->M_Event->get_event_by_id($id_event);

        if ($current_participants < $event->max_participants) {
            $this->M_EventParticipant->add_participant($id_event, $id_user);
            $this->session->set_flashdata('success', 'You have successfully joined the event.');
        } else {
            $this->session->set_flashdata('error', 'Event is already full.');
        }

        redirect('Admin/event/detail/' . rawurlencode(base64_encode($id_event)));
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
		redirect('Admin/event/detail/' . rawurlencode(base64_encode($id_event)));
	}
	
	// Controller: Recruitment.php
	public function form($encoded_id)	{
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		$event = $this->M_Event->get_event_by_id($id_event); // Pastikan ada method ini
		if (!$event || strtolower($event->category_name) !== 'recruitment') {
			show_404();
		}

		$data['event'] = $event;
		$this->load->view('Admin/sidebar', $data);
		$this->load->view('Admin/apply', $data);
		$this->load->view('Admin/footer', $data);
	}


	
	public function add_files($encoded_id) {
		$id_event = base64_decode(urldecode($encoded_id));  // <-- DECODE dulu
		// Cek jika form file diupload
		if ($_FILES['event_file']['name']) {
			// Pengaturan untuk upload file
			$config['upload_path'] = './assets/uploads/FileEvent/';
			$config['allowed_types'] = 'pdf|mp4|mp3|jpg|jpeg|png'; // Tambahkan tipe file yang diizinkan
			$config['max_size'] = 8192; // Maksimal ukuran file dalam KB
			$this->load->library('upload', $config);

			if ($this->upload->do_upload('event_file')) {
				// Ambil data file yang diupload
				$file_data = $this->upload->data();
				$file_info = [
					'id_event' => $id_event,
					'file_name' => $file_data['file_name'],
					'file_url' => base_url('./assets/uploads/FileEvent/' . $file_data['file_name']),
					'file_type' => $file_data['file_type'],
					'file_size' => $file_data['file_size'],
				];

				// Simpan data file ke dalam database
				$this->M_EventFile->add_file($file_info);

				// Berikan notifikasi sukses
				$this->session->set_flashdata('success', 'File has been uploaded successfully!');
			} else {
				// Berikan pesan error jika upload gagal
				$this->session->set_flashdata('error', $this->upload->display_errors());
			}
		}

		// Redirect ke halaman detail event
		redirect('Admin/event/detail/' . rawurlencode(base64_encode($id_event)));
	}

}

?>
