<?php

class M_Recruitment extends CI_Model {

    public function apply($data) {
        return $this->db->insert('recruitment_approvals', $data);
    }

    public function get_all_applications() {
		$this->db->select('
			ra.*, 
			peserta.name AS user_name, 
			peserta.profile_picture, 
			e.event_name, 
			admin.name AS admin_name
		');
		$this->db->from('recruitment_approvals ra');
		$this->db->join('user peserta', 'peserta.id_user = ra.id_user');
		$this->db->join('events e', 'e.id_event = ra.id_event');
		$this->db->join('user admin', 'admin.id_user = ra.id_admin', 'left'); // Bisa null
		$this->db->order_by('ra.applied_at', 'DESC');

		return $this->db->get()->result_array();
	}


    public function get_by_id($id_recruit) {
        return $this->db->get_where('recruitment_approvals', ['id_recruit' => $id_recruit])->row_array();
    }

    public function update_status($id_recruit, $data_update) {
        $this->db->where('id_recruit', $id_recruit);
        return $this->db->update('recruitment_approvals', $data_update);
    }
	
	function id_rec() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_recruit');
		$this->db->order_by('id_recruit', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('recruitment_approvals');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_recruit, 3, 4));

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
		$kodetampil = "FLY" . $batas . "REC";

		return $kodetampil;
	}
	
	public function get_user_entries_for_event($id_user, $id_event) {
		return $this->db->get_where('recruitment_approvals', [
			'id_user' => $id_user,
			'id_event' => $id_event
		])->result();
	}
	
	public function update_by_id($id_recruit, $data) {
		return $this->db->where('id_recruit', $id_recruit)
						->update('recruitment_approvals', $data);
	}
	
	public function get_by_user_event($user_id, $event_id) {
    return $this->db
        ->where('id_user', $user_id)
        ->where('id_event', $event_id)
        ->get('recruitment_approvals')
        ->row(); // pakai row() karena hanya 1 data yang diambil
	}
	
	public function get_user_recruitment_by_event($id_event, $id_user) {
		$this->db->select('r.*, e.event_name, u.name as user_name, u.profile_picture, a.name as admin_name');
		$this->db->from('recruitment_approvals r');
		$this->db->join('events e', 'e.id_event = r.id_event');
		$this->db->join('user u', 'u.id_user = r.id_user'); // pengguna (user biasa)
		$this->db->join('user a', 'a.id_user = r.id_admin', 'left'); // admin yang menyetujui
		$this->db->where('r.id_event', $id_event);
		$this->db->where('r.id_user', $id_user);
		return $this->db->get()->row_array(); // ambil satu record
	}


}

?>