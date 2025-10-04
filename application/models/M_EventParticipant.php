<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_EventParticipant extends CI_Model {
	
    // Mendapatkan daftar partisipan pada suatu event
    public function get_event_participants($id_event) {
        $this->db->select('user.*');
        $this->db->from('event_participants');
        $this->db->join('user', 'user.id_user = event_participants.id_user');
        $this->db->where('event_participants.id_event', $id_event);
        return $this->db->get()->result_array();
    }
	
    public function add_participant($id_event, $id_user) {
        // Cek apakah user sudah terdaftar dalam event
        if ($this->is_participant_exist($id_event, $id_user)) {
            return false;
        }

        // Tambahkan partisipan
        $data = [
            'id_event' => $id_event,
            'id_user' => $id_user
        ];
        return $this->db->insert('event_participants', $data);
    }

    public function is_participant_exist($id_event, $id_user) {
        return $this->db->get_where('event_participants', [
            'id_event' => $id_event,
            'id_user' => $id_user
        ])->num_rows() > 0;
    }

    public function count_participants($id_event) {
        $this->db->where('id_event', $id_event);
        return $this->db->count_all_results('event_participants');
    }
	
	
	public function has_user_joined($event_id, $user_id) {
		$this->db->where('id_event', $event_id);
		$this->db->where('id_user', $user_id);
		$query = $this->db->get('event_participants'); // ganti nama tabel jika berbeda
		return $query->num_rows() > 0;
	}
	
	public function leave_event($id_event, $user_id) {
		$this->db->where('id_event', $id_event);
		$this->db->where('id_user', $user_id);
		return $this->db->delete('event_participants');
	}


}
?>
