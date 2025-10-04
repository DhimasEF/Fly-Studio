<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Event extends CI_Model {

    // Mendapatkan semua event
    public function get_all_events() {
        $this->db->select('*');
        $this->db->from('events');
        $this->db->join('event_categories', 'event_categories.id_category = events.id_category');
        $this->db->join('event_scope', 'event_scope.id_scope = events.id_scope');
        return $this->db->get()->result();
    }
	
	public function getAllEvents($scope = null, $role = null) {
		$this->db->select('
			e.*,
			c.category_name,
			s.scope_name,
			CASE 
				WHEN CURDATE() < e.start_date THEN "Coming"
				WHEN CURDATE() BETWEEN e.start_date AND e.end_date THEN "Ongoing"
				WHEN CURDATE() > e.end_date THEN "Ended"
				ELSE "Unknown"
			END AS dynamic_status,
			COUNT(DISTINCT ep.id_user) AS total_participants,
			CONCAT(COUNT(DISTINCT ep.id_user), " / ", e.max_participants) AS participant_status,
			COUNT(DISTINCT ef.id_event_file) AS total_files
		');
		$this->db->from('events e');
		$this->db->join('event_categories c', 'e.id_category = c.id_category', 'left');
		$this->db->join('event_scope s', 'e.id_scope = s.id_scope', 'left');
		$this->db->join('event_files ef', 'e.id_event = ef.id_event', 'left');
		$this->db->join('event_participants ep', 'e.id_event = ep.id_event', 'left');

		// Validasi dan filter berdasarkan role
		if ($role === 'admin') {
			// Jika ada scope tertentu
			if (!empty($scope)) {
				if ($scope == 'public') $this->db->where('s.id_scope', 2);
				elseif ($scope == 'intern') $this->db->where('s.id_scope', 1);
				elseif ($scope == 'all') $this->db->where('s.id_scope', 3);
				// kalau kosong, tampil semua (All Event)
			}
		} elseif ($role === 'creator') {
			if ($scope == 'all') {
				$this->db->where('s.id_scope', 3);
			} else {
				// default ke intern
				$this->db->where('s.id_scope', 1);
			}
		} elseif ($role === 'user') {
			if ($scope == 'all') {
				$this->db->where('s.id_scope', 3);
			} else {
				// default ke public
				$this->db->where('s.id_scope', 2);
			}
		}

		$this->db->group_by('e.id_event');
		$this->db->order_by('e.start_date', 'DESC');
		return $this->db->get()->result();
	}

	public function getAllEvents5($scope = null, $role = null, $limit = null) {
		$this->db->select('
			e.*,
			c.category_name,
			s.scope_name,
			CASE 
				WHEN CURDATE() < e.start_date THEN "Coming"
				WHEN CURDATE() BETWEEN e.start_date AND e.end_date THEN "Ongoing"
				WHEN CURDATE() > e.end_date THEN "Ended"
				ELSE "Unknown"
			END AS dynamic_status,
			COUNT(DISTINCT ep.id_user) AS total_participants,
			CONCAT(COUNT(DISTINCT ep.id_user), " / ", e.max_participants) AS participant_status,
			COUNT(DISTINCT ef.id_event_file) AS total_files
		');
		$this->db->from('events e');
		$this->db->join('event_categories c', 'e.id_category = c.id_category', 'left');
		$this->db->join('event_scope s', 'e.id_scope = s.id_scope', 'left');
		$this->db->join('event_files ef', 'e.id_event = ef.id_event', 'left');
		$this->db->join('event_participants ep', 'e.id_event = ep.id_event', 'left');

		// Filter berdasarkan scope dan role
		if ($role === 'admin') {
			if (!empty($scope)) {
				if ($scope == 'public') $this->db->where('s.id_scope', 2);
				elseif ($scope == 'intern') $this->db->where('s.id_scope', 1);
				elseif ($scope == 'all') $this->db->where('s.id_scope', 3);
			}
		} elseif ($role === 'creator') {
			if ($scope == 'all') {
				$this->db->where('s.id_scope', 3);
			} else {
				$this->db->where('s.id_scope', 1); // default ke intern
			}
		} elseif ($role === 'user') {
			if ($scope == 'public_and_all') {
				$this->db->where_in('s.id_scope', [2, 3]); // PUBLIC + ALL
			} elseif ($scope == 'all') {
				$this->db->where('s.id_scope', 3);
			} else {
				$this->db->where('s.id_scope', 2); // default ke public
			}
		}

		$this->db->group_by('e.id_event');
		$this->db->order_by('e.start_date', 'DESC');

		// Jika ada limit
		if ($limit) {
			$this->db->limit($limit);
		}

		return $this->db->get()->result();
	}




    // Menyimpan event baru
    public function create_event($data) {
        return $this->db->insert('events', $data);
    }

    // Mendapatkan kategori
    public function get_all_categories() {
        return $this->db->get('event_categories')->result();
    }

    // Mendapatkan scope
    public function get_all_scopes() {
        return $this->db->get('event_scope')->result();
    }
	
	// Mendapatkan event berdasarkan id_event
	public function get_event_by_id($id_event) {
		$this->db->select('e.*, 
			event_categories.category_name, 
			event_scope.scope_name,
			CASE 
				WHEN CURDATE() < e.start_date THEN "Coming"
				WHEN CURDATE() BETWEEN e.start_date AND e.end_date THEN "Ongoing"
				WHEN CURDATE() > e.end_date THEN "Ended"
				ELSE "Unknown"
			END AS dynamic_status');
		$this->db->from('events e');
		$this->db->join('event_categories', 'event_categories.id_category = e.id_category');
		$this->db->join('event_scope', 'event_scope.id_scope = e.id_scope');
		$this->db->where('e.id_event', $id_event);
		$this->db->limit(1);
		return $this->db->get()->row(); // Atau ->row_array() jika butuh array
	}


	
	public function countUserEvents($id_user) {
		$this->db->where('id_user', $id_user);
		return $this->db->count_all_results('event_participants');
	}

	public function getUserEvents($id_user) {
		$this->db->select('events.*');
		$this->db->from('event_participants');
		$this->db->join('events', 'events.id_event = event_participants.id_event');
		$this->db->where('event_participants.id_user', $id_user);
		$this->db->order_by('events.created_at', 'DESC');
		return $this->db->get()->result_array();
	}
	
	function id_eve() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_event');
		$this->db->order_by('id_event', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('events');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_event, 3, 4));

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
		$kodetampil = "FLY" . $batas . "EVE";

		return $kodetampil;
	}
	
	public function update_event($id_event, $data) {
		$this->db->where('id_event', $id_event);
		return $this->db->update('events', $data); // ganti 'events' dengan nama tabel yang sesuai
	}

	public function getEventsByScope($scope)
	{
		$this->db->select('*');
		$this->db->from('events');
		$this->db->join('categories', 'categories.id = events.category_id'); // jika ada
		$this->db->join('scopes', 'scopes.id = events.scope_id'); // jika ada
		$this->db->where('LOWER(scope_name)', strtolower($scope));
		return $this->db->get()->result();
	}

}
?>
