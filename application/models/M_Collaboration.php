<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Collaboration extends CI_Model {

    public function insert_collaboration($collab_data) {
		if ($this->db->insert_batch('collaboration_participant', $collab_data)) {
			return true;
		} else {
			return $this->db->error(); // Mengembalikan detail error
		}
	}
	
	public function get_collaborations($id_content) {
		$this->db->select('cp.*, u.username, u.email');
		$this->db->from('collaboration_participant cp');
		$this->db->join('users u', 'cp.id_user = u.id', 'left');
		$this->db->where('cp.id_content', $id_content);
		return $this->db->get()->result_array();
	}	
	
	function id_mpp($offset = 0) {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_collaboration');
		$this->db->order_by('id_collaboration', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('collaboration_participant');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPP"
			$last_number = intval(substr($data->id_collaboration, 3, 4));

			// Tambahkan angka untuk ID berikutnya, termasuk offset
			$kode = $last_number + 1 + $offset;

			// Reset jika melebihi 9999
			if ($kode > 9999) {
				$kode = $kode % 9999;
			}
		} else {
			// Jika tidak ada data, mulai dari 1
			$kode = 1 + $offset;
		}

		// Tambahkan padding agar 4 digit
		$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);

		// Gabungkan prefix dan suffix
		$kodetampil = "FLY" . $batas . "MPP";

		return $kodetampil;
	}
	
	public function countUserCollaborations($id_user) {
		$this->db->where('id_user', $id_user);
		return $this->db->count_all_results('collaboration_participant');
	}

	public function getUserCollaborations($id_user) {
		$this->db->select('
			cf.*,
			COUNT(DISTINCT cl.id_like) as like_count, 
			COUNT(DISTINCT cc.id_comment) as comment_count, 
			COUNT(DISTINCT cp2.id_user) as participant_count
		');
		$this->db->from('collaboration_files cf');
		$this->db->join('collaboration_participant cp', 'cp.id_content = cf.id_file', 'left'); // untuk filter user
		$this->db->join('collaboration_like cl', 'cl.id_file = cf.id_file', 'left');
		$this->db->join('collaboration_comment cc', 'cc.id_file = cf.id_file', 'left');
		$this->db->join('collaboration_participant cp2', 'cp2.id_content = cf.id_file', 'left'); // untuk hitung total participant

		$this->db->where('cp.id_user', $id_user);
		$this->db->group_by('cf.id_file');
		$this->db->order_by('cf.upload_at', 'DESC');
		$this->db->limit(2);

		return $this->db->get()->result_array();
	}

	
	public function get_all_collaborations_with_participants() {
        $this->db->select('cf.*, COUNT(cp.id_user) as participant_count');
        $this->db->from('collaboration_files cf');
        $this->db->join('collaboration_participant cp', 'cf.id_file = cp.id_file', 'left');
        $this->db->group_by('cf.id_file');
        return $this->db->get()->result_array();
    }

    public function get_collaboration_by_id($id_file) {
        return $this->db->get_where('collaboration_files', ['id_file' => $id_file])->row_array();
    }

    public function get_participants_by_file($id_file) {
        $this->db->select('cp.*, u.*');
        $this->db->from('collaboration_participant cp');
        $this->db->join('user u', 'u.id_user = cp.id_user');
        $this->db->where('cp.id_content', $id_file);
        $this->db->order_by('cp.part_label', 'ASC');
        return $this->db->get()->result_array();
    }
	
    public function get_content_detail($id_file) {
        return $this->db->get_where('collaboration_files', ['id_file' => $id_file])->row();
    }

    public function update_content($id_file, $data) {
        return $this->db->where('id_file', $id_file)->update('collaboration_files', $data);
    }

    public function get_participants($id_file) {
        $this->db->select('cp.*, u.name');
        $this->db->from('collaboration_participant cp');
        $this->db->join('user u', 'cp.id_user = u.id_user', 'left');
        $this->db->where('cp.id_content', $id_file);
        return $this->db->get()->result_array();
    }

    public function delete_participant($id_file, $id_user) {
        return $this->db->delete('collaboration_participant', [
            'id_content' => $id_file,
            'id_user' => $id_user
        ]);
    }

}
