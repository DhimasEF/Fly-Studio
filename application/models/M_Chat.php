<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Chat extends CI_Model {
	
	public function get_private_chats($user_id) {
		$this->db->select('crm.id_chat_room, u.name as partner_name, u.profile_picture, cr.type, cr.name as group_name, cr.id_room');
		$this->db->from('chat_room_members crm');
		$this->db->join('chat_rooms cr', 'crm.id_chat_room = cr.id_room');
		$this->db->join('chat_room_members crm_partner', 'crm.id_chat_room = crm_partner.id_chat_room');
		$this->db->join('user u', 'crm_partner.id_user = u.id_user');
		$this->db->where('crm.id_user', $user_id);
		$this->db->where('crm_partner.id_user !=', $user_id);
		$this->db->where('cr.type', 'private');
		$query = $this->db->get();

		log_message('debug', 'Last Query: ' . $this->db->last_query());
		log_message('debug', 'Query Result: ' . print_r($query->result_array(), true));

		return $query->result_array();
	}

	
	public function get_group_chats($user_id) {
		$this->db->select('cr.*, cr.name as group_name, cr.type, COUNT(crm.id_user) as member_count');
		$this->db->from('chat_room_members crm');
		$this->db->join('chat_rooms cr', 'crm.id_chat_room = cr.id_room');
		$this->db->where('crm.id_user', $user_id);
		$this->db->where('cr.type', 'group');
		$this->db->group_by('cr.id_room');
		$query = $this->db->get();

		return $query->result_array();
	}
	
	public function get_group_members($room_id) {
		$this->db->select('u.*, crm.status'); // Pilih kolom yang ingin ditampilkan
		$this->db->from('chat_room_members crm');
		$this->db->join('user u', 'crm.id_user = u.id_user');
		$this->db->where('crm.id_chat_room', $room_id);
		return $this->db->get()->result_array();
	}
	
	public function is_admin($room_id, $user_id) {
		$query = $this->db->get_where('chat_room_members', [
			'id_chat_room' => $room_id,
			'id_user' => $user_id,
			'status' => 'admin' // Ubah ke 'status' jika sebelumnya pakai 'role'
		]);
		return $query->num_rows() > 0;
	}
	
	public function update_member_status($room_id, $user_id, $status) {
		$this->db->where([
			'id_chat_room' => $room_id,
			'id_user' => $user_id
		])->update('chat_room_members', ['status' => $status]);
	}

	public function remove_member($room_id, $user_id) {
		$this->db->where([
			'id_chat_room' => $room_id,
			'id_user' => $user_id
		])->delete('chat_room_members');
	}


	public function get_messages_by_room($room_id) {
		$this->db->select('m.id_messages, m.message, m.created_at, m.id_user, u.*, u.name as sender_name');
		$this->db->from('messages m');
		$this->db->join('user u', 'm.id_user = u.id_user');
		$this->db->where('m.id_chat_room', $room_id);
		$this->db->order_by('m.created_at', 'ASC'); // Urutkan pesan dari yang paling lama
		return $this->db->get()->result_array();
	}
	
	public function insert_message($data) {
		$this->db->insert('messages', $data);
	}

    public function get_messages($room_id) {
        $this->db->select('messages.message, cm.created_at, u.name as sender_name');
        $this->db->from('messages as m');
        $this->db->join('user as u', 'cm.id_user = u.id_user');
        $this->db->where('m.id_room', $room_id);
        $this->db->order_by('m.created_at', 'ASC');
        return $this->db->get()->result();
    }

    public function send_message($data) {
        return $this->db->insert('messages', $data);
    }

	/////////////////
   public function get_rooms($user_id) {
		$this->db->select('cr.*');
		$this->db->from('chat_rooms cr');
		$this->db->join('chat_room_members crm', 'crm.id_chat_room = cr.id_room');
		$this->db->where('crm.id_user', $user_id);
		$query = $this->db->get();
		
		// Debugging output
		//echo $this->db->last_query();
		//print_r($query->result());
		//exit;

		return $query->result();
	}

	public function get_chat_requests($user_id) {
		$this->db->select('
			chat_requests.*,
			user.name,
			user.profile_picture
		');
		$this->db->from('chat_requests');
		$this->db->join('user', 'user.id_user = chat_requests.id_requester');
		$this->db->where('chat_requests.id_receiver', $user_id);
		$this->db->where('chat_requests.status', 'pending');

		$query = $this->db->get();
		return $query->result();
	}
	
	public function get_private_room_between($user1, $user2) {
		$this->db->select('chat_rooms.id_room');
		$this->db->from('chat_rooms');
		$this->db->join('chat_room_members rm1', 'rm1.id_chat_room = chat_rooms.id_room');
		$this->db->join('chat_room_members rm2', 'rm2.id_chat_room = chat_rooms.id_room');
		$this->db->where('chat_rooms.type', 'private');
		$this->db->where('rm1.id_user', $user1);
		$this->db->where('rm2.id_user', $user2);
		$this->db->group_by('chat_rooms.id_room');

		$query = $this->db->get();
		return $query->row(); // null jika tidak ada
	}

	
	public function get_chat_partner($room_id, $current_user_id) {
		return $this->db
			->select('user.*')
			->from('chat_room_members')
			->join('user', 'user.id_user = chat_room_members.id_user')
			->where('chat_room_members.id_chat_room', $room_id)
			->where('chat_room_members.id_user !=', $current_user_id)
			->get()
			->row_array();
	}
	
	public function is_user_in_room($user_id, $room_id) {
    return $this->db
			->where('id_user', $user_id)
			->where('id_chat_room', $room_id)
			->get('chat_room_members')
			->num_rows() > 0;
	}



    // Send a chat request
    public function send_chat_request($data) {
        return $this->db->insert('chat_requests', $data);
    }

    // Update chat request status
    public function update_chat_request_status($request_id, $status) {
        $this->db->where('id_chat_request', $request_id);
        $this->db->update('chat_requests', ['status' => $status]);
    }

    // Create a new chat room
    public function create_room($data) {
        $this->db->insert('chat_rooms', $data);
        return $data['id_room'];	
    }
	
	public function create_group_room($group_data) {
		$this->db->insert('chat_rooms', $group_data);
		return $group_data['id_room']; // Mengembalikan ID grup yang baru dibuat
	}
	
	public function add_members_to_group($members_data) {
		$this->db->insert_batch('chat_room_members', $members_data);
	}

    // Add members to a chat room
    public function add_members($room_id, $members) {
        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'id_chat_room' => $room_id,
                'id_user' => $member
            ];
        }
        $this->db->insert_batch('chat_room_members', $data);
    }
	
	public function update_group_info($room_id, $data) {
		$this->db->where('id_room', $room_id);
		return $this->db->update('chat_rooms', $data);
	}
	
	
	public function get_group_by_id($room_id) {
		$this->db->select('*');
		$this->db->from('chat_rooms');
		$this->db->where('id_room', $room_id);
		$query = $this->db->get();

		return $query->row_array();
	}
	
	function id_crr() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_room');
		$this->db->order_by('id_room', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('chat_rooms');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_room, 3, 4));

			// Tambahkan angka untuk ID berikutnya
			$kode = $last_number + 1;

			// Reset jika melebihi 9999
			if ($kode > 9999) {
				$kode = 1;
			}
		} else {
			// Jika tidak ada data, mulai dari 1
			$kode = 1;
		}

		// Tambahkan padding agar 4 digit
		$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);

		// Gabungkan prefix dan suffix
		$kodetampil = "FLY" . $batas . "CRR";

		return $kodetampil;
	}
	
	function id_crs() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_chat_request');
		$this->db->order_by('id_chat_request', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('chat_requests');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_chat_request, 3, 4));

			// Tambahkan angka untuk ID berikutnya
			$kode = $last_number + 1;

			// Reset jika melebihi 9999
			if ($kode > 9999) {
				$kode = 1;
			}
		} else {
			// Jika tidak ada data, mulai dari 1
			$kode = 1;
		}

		// Tambahkan padding agar 4 digit
		$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);

		// Gabungkan prefix dan suffix
		$kodetampil = "FLY" . $batas . "CRS";

		return $kodetampil;
	}
	
	public function check_existing_request($requester_id, $receiver_id) {
		$this->db->where('status !=', 'rejected'); // hanya cek yang masih aktif
		$this->db->group_start()
			->group_start()
				->where('id_requester', $requester_id)
				->where('id_receiver', $receiver_id)
			->group_end()
			->or_group_start()
				->where('id_requester', $receiver_id)
				->where('id_receiver', $requester_id)
			->group_end()
		->group_end();

		$query = $this->db->get('chat_requests');
		return $query->row(); // return null kalau tidak ada
	}

}
