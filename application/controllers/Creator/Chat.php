<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('creator'); // Middleware untuk cek role
        $this->load->model('M_User');
        $this->load->model('M_Chat');
		$this->load->library('session');
		
		// Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }

    // Display list of chat rooms
    public function index() {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->getCreatorById($user_id);

		// Cek apakah ada filter
		$filter = $this->input->get('filter'); // nilai: 'private' atau 'group'

		if ($filter === 'group') {
			$data['chats'] = $this->M_Chat->get_group_chats($user_id);
		} else {
			// Default atau 'private'
			$data['chats'] = $this->M_Chat->get_private_chats($user_id);
		}

		// Tetap ambil request untuk ditampilkan di kolom kanan
		$data['requests'] = $this->M_Chat->get_chat_requests($user_id);
		$data['filter'] = $filter;
		
		$data['title'] = 'Chat | Fly Studio';

        $this->load->view('Creator/sidebar', $data);
        $this->load->view('Creator/listchatroom', $data);
        $this->load->view('Creator/footer', $data);
    }

    // Send a chat request
    public function send_request($receiver_id = null) {
		$requester_id = $this->session->userdata('user_id');

		if (!$receiver_id || !$requester_id) {
			show_error("Invalid request");
			return;
		}

		// 1. Cek apakah sudah ada room private aktif
		$existing_room = $this->M_Chat->get_private_room_between($requester_id, $receiver_id);
		if ($existing_room) {
			$this->session->set_flashdata('info', 'Chat sudah tersedia.');
			redirect('Creator/chat/proom/' . $existing_room->id_room); // ganti sesuai role jika perlu
			return;
		}

		// 2. Cek apakah sudah ada request yang masih pending
		$existing_request = $this->M_Chat->check_existing_request($requester_id, $receiver_id);
		if ($existing_request) {
			$this->session->set_flashdata('error', 'Request sudah pernah dikirim.');
			redirect('Creator/Creator/detail/' . rawurlencode(base64_encode($receiver_id)));
			return;
		}

		// 3. Buat request baru
		$data = [
			'id_chat_request' => $this->M_Chat->id_crs(),
			'id_requester' => $requester_id,
			'id_receiver' => $receiver_id,
			'status' => 'pending',
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->M_Chat->send_chat_request($data);
		$this->session->set_flashdata('success', 'Request berhasil dikirim.');
		redirect('Creator/chat');
	}

    public function respond_request($request_id, $action) {
		$status = ($action === 'approve') ? 'approved' : 'rejected';
		$this->M_Chat->update_chat_request_status($request_id, $status);

		if ($status === 'approved') {
			$request = $this->db->get_where('chat_requests', ['id_chat_request' => $request_id])->row();

			// Debug request data
			if (!$request) {
				show_error('Chat request data not found.', 500);
			}

			$current_user_id = $this->session->userdata('user_id');
			$id_requester = $request->id_requester;
			$id_receiver = $request->id_receiver;

			// Buat ID room unik
			$room_id = uniqid();

			// Buat room baru dengan name null
			$this->M_Chat->create_room([
				'id_room' => $room_id,
				'name' => null, // Nama room diatur null
				'type' => 'private'
			]);

			// Tambahkan anggota ke room
			$this->M_Chat->add_members($room_id, [$id_requester, $id_receiver]);
		}

		redirect('Creator/chat');
	}

    // Open a chat room
    public function proom($room_id) {
		$user_id = $this->session->userdata('user_id'); // Pastikan session user_id ada
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->getCreatorById($user_id); // Ambil data terbaru dari database

		$is_member = $this->M_Chat->is_user_in_room($user_id, $room_id);
		
		$data['room_id'] = $room_id;
		$data['current_user_id'] = $user_id;

		// Ambil semua daftar chat privat yang dimiliki user ini
		// Ambil partner chat (lawan bicara) berdasarkan room dan user yang login
		$data['partner'] = $this->M_Chat->get_chat_partner($room_id, $user_id);
		$data['chats'] = $this->M_Chat->get_private_chats($user_id); // <== Tambahan penting
		
		
		if (!$is_member) {
			show_error('Access Denied: You are not part of this room', 403);
		}
		$data['title'] = 'Chat ' . ($data['partner']['name'] . ' | Fly Studio');

		// Ambil isi pesan dari room ini
		$data['messages'] = $this->M_Chat->get_messages_by_room($room_id);
		
        // Load view dengan data pesan
        $this->load->view('Creator/sidebar', $data);
        $this->load->view('Creator/chatroom', $data);
        $this->load->view('Creator/footer', $data);
    }
	
	public function groom($room_id) {
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			show_error('User not logged in', 403);
		}

		$data['user'] = $this->M_User->getCreatorById($user_id);
		$data['room_id'] = $room_id;
		
		$is_member = $this->M_Chat->is_user_in_room($user_id, $room_id);
		
		if (!$is_member) {
			show_error('Access Denied: You are not part of this room', 403);
		}

		// Pesan dan anggota grup
		$data['messages'] = $this->M_Chat->get_messages_by_room($room_id);
		$data['group_members'] = $this->M_Chat->get_group_members($room_id);
		$data['current_user_id'] = $user_id;

		// Cek apakah dia admin grup
		$data['is_admin'] = $this->M_Chat->is_admin($room_id, $user_id);

		// Ambil data grup (nama dan icon)
		$group = $this->M_Chat->get_group_by_id($room_id);
		if ($group) {
			$data['group_name'] = $group['name'];
			$data['group_icon'] = $group['icon'];
			$data['title'] = 'Chat ' . $data['group_name'] . ' | Fly Studio';
		} else {
			show_error("Group tidak ditemukan", 404);
		}
		
		// Load view dengan data pesan dan anggota grup
        $this->load->view('Creator/sidebar', $data);
		$this->load->view('Creator/grouproom', $data);
        $this->load->view('Creator/footer', $data);
	}
	
    public function fetch_messages_group($room_id) {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            show_error('Unauthorized', 403);
        }

        $messages = $this->M_Chat->get_messages_by_room($room_id);

        foreach ($messages as $message):
            $is_sender = ($message['id_user'] == $user_id);
            $profile = !empty($message['profile_picture']) ? $message['profile_picture'] : 'default.jpg';
            ?>
            <li class="<?= $is_sender ? 'sent' : 'received' ?>">
                <img src="<?= base_url('assets/uploads/Profil/' . $profile); ?>" alt="Profile" class="chatgrup-avatar">
                <div class="chat-bubble-grup">
                    <strong class="strong"><?= htmlspecialchars($message['sender_name']) ?></strong><br>
                    <div class="message-text"><?= nl2br(htmlspecialchars($message['message'])) ?></div>
                    <small class="timestamp"><?= date('d M Y, H:i:s', strtotime($message['created_at'])) ?></small>
                </div>
            </li>
        <?php
        endforeach;
    }

    public function fetch_messages($room_id) {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            show_error('User not logged in', 403);
        }

        $messages = $this->M_Chat->get_messages_by_room($room_id);
        $current_user_id = $user_id;

        ob_start(); // Buat output HTML dari view
        foreach ($messages as $message):
            $is_sender = ($message['id_user'] == $current_user_id);
            $profile = !empty($message['profile_picture']) ? $message['profile_picture'] : 'default.jpg';
            ?>
            <li class="<?= $is_sender ? 'sent' : 'received' ?>">
                <img src="<?= base_url('assets/uploads/Profil/' . $profile); ?>" alt="Profile" class="chatgrup-avatar">
                <div class="chat-bubble-grup">
                    <strong class="strong"><?= htmlspecialchars($message['sender_name']) ?></strong><br>
                    <div class="message-text"><?= nl2br(htmlspecialchars($message['message'])) ?></div>
                    <small class="timestamp"><?= date('d M Y, H:i:s', strtotime($message['created_at'])) ?></small>
                </div>
            </li>
        <?php endforeach;
        echo ob_get_clean();
    }


	public function ajax_update_group_icon() {
		$user_id = $this->session->userdata('user_id');
		$room_id = $this->input->post('room_id');
		$group_name = $this->input->post('group_name');

		if (!$this->M_Chat->is_admin($room_id, $user_id)) {
			echo json_encode(['success' => false, 'error' => 'Unauthorized']);
			return;
		}

		$update_data = [];

		// Update nama grup jika diubah
		if (!empty($group_name)) {
			$update_data['name'] = $group_name;
		}

		// Proses upload icon jika ada file
		if (!empty($_FILES['icon']['name'])) {
			$config['upload_path'] = './assets/uploads/Profil/Grup/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size'] = 2048;
			$config['file_name'] = 'group_' . time();

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('icon')) {
				$data = $this->upload->data();
				$update_data['icon'] = $data['file_name'];
			} else {
				echo json_encode(['success' => false, 'error' => $this->upload->display_errors()]);
				return;
			}
		}

		// Update ke DB
		$this->M_Chat->update_group_info($room_id, $update_data);

		echo json_encode([
			'success' => true,
			'new_group_name' => $group_name ?? null,
			'new_icon_url' => isset($update_data['icon']) ? base_url('assets/uploads/Profil/Grup/' . $update_data['icon']) : null
		]);
	}

    // Send a message
	public function send_message_private($room_id = null) {
		// Debugging: Log or display room_id to ensure it's being passed
		log_message('error', 'Room ID: ' . $room_id);
		
		if (empty($room_id)) {
			show_error('Room ID is required.', 400);
			return;
		}

		$message_content = $this->input->post('message_content');
		if (empty($message_content)) {
			show_error('Message content is required.', 400);
			return;
		}

		// Validasi
		if (empty($room_id) || empty($message_content)) {
			show_error('Room ID and message content are required.', 400);
		}

		// Simpan pesan
		$this->M_Chat->insert_message([
			'id_chat_room' => $room_id,
			'message' => $message_content,
			'id_user' => $this->session->userdata('user_id'),
			'created_at' => date('Y-m-d H:i:s')
		]);

		// Redirect kembali ke halaman chat room
		redirect('Creator/chat/proom/' . $room_id);
	}
	
    // Send a message
	public function send_message_group($room_id = null) {
		// Debugging: Log or display room_id to ensure it's being passed
		log_message('error', 'Room ID: ' . $room_id);
		
		if (empty($room_id)) {
			show_error('Room ID is required.', 400);
			return;
		}

		$message_content = $this->input->post('message_content');
		if (empty($message_content)) {
			show_error('Message content is required.', 400);
			return;
		}

		// Validasi
		if (empty($room_id) || empty($message_content)) {
			show_error('Room ID and message content are required.', 400);
		}

		// Simpan pesan
		$this->M_Chat->insert_message([
			'id_chat_room' => $room_id,
			'message' => $message_content,
			'id_user' => $this->session->userdata('user_id'),
			'created_at' => date('Y-m-d H:i:s')
		]);

		// Redirect kembali ke halaman chat room
		redirect('Creator/chat/groom/' . $room_id);
	}
	
	public function addMember($room_id) {
		$user_id = $this->session->userdata('user_id');
		if (!$this->M_Chat->is_admin($room_id, $user_id)) {
			show_error('Unauthorized action', 403);
		}

		$data['room_id'] = $room_id;
		$data['user'] = $this->M_User->getCreatorById($user_id);
		$data['users'] = $this->M_User->get_users_not_in_group($room_id); // Ambil user yang belum masuk grup
		$data['title'] = 'Add Member | Fly Studio';

		$this->load->view('Creator/sidebar', $data);
		$this->load->view('Creator/addmembergroup', $data);
		$this->load->view('Creator/footer', $data);
	}
	
	public function storeMembers($room_id) {
		$user_id = $this->session->userdata('user_id');
		if (!$this->M_Chat->is_admin($room_id, $user_id)) {
			show_error('Unauthorized action', 403);
		}

		$selected_users = $this->input->post('user_ids');
		if (empty($selected_users)) {
			redirect('Creator/chat/add_member/' . $room_id);
		}

		$members_data = [];
		foreach ($selected_users as $selected_user) {
			$members_data[] = [
				'id_chat_room' => $room_id,
				'id_user' => $selected_user,
				'status' => 'member'
			];
		}

		$this->M_Chat->add_members_to_group($members_data);
		redirect('Creator/chat/groom/' . $room_id);
	}

	
	public function promote_to_admin($room_id, $user_id) {
		$current_user = $this->session->userdata('user_id');
		
		// Cek apakah user adalah admin
		if (!$this->M_Chat->is_admin($room_id, $current_user)) {
			show_error('Access denied.', 403);
		}

		// Ubah status user menjadi admin
		$this->M_Chat->update_member_status($room_id, $user_id, 'admin');
		redirect('Creator/chat/groom/' . $room_id);
	}

	public function remove_member($room_id, $user_id) {
		$current_user = $this->session->userdata('user_id');

		// Cek apakah user adalah admin
		if (!$this->M_Chat->is_admin($room_id, $current_user)) {
			show_error('Access denied.', 403);
		}

		// Hapus user dari grup
		$this->M_Chat->remove_member($room_id, $user_id);
		redirect('Creator/chat/groom/' . $room_id);
	}


}
