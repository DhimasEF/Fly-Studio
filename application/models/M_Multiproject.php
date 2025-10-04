<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Multiproject extends CI_Model {

    // Tabel media
    private $table = 'collaboration_files';

    /**
     * Insert data file media ke database
     * @param array $data Data yang akan dimasukkan
     * @return bool Status insert
     */
    public function insert($file_data) {
        return $this->db->insert($this->table, $file_data);
    }

    /**
     * Ambil data file berdasarkan ID
     * @param string $id ID file
     * @return array Data file
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_file' => $id])->row_array();
    }

    /**
     * Ambil semua data file
     * @return array Semua data file
     */
    public function get_all() {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Hapus file berdasarkan ID
     * @param string $id ID file
     * @return bool Status delete
     */
    public function delete($id) {
        return $this->db->delete($this->table, ['id_file' => $id]);
    }

    /**
     * Update data file
     * @param string $id ID file
     * @param array $data Data yang akan diperbarui
     * @return bool Status update
     */
    public function update($id, $data) {
        return $this->db->where('id_file', $id)->update($this->table, $data);
    }
	
	function id_mpf() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_file');
		$this->db->order_by('id_file', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('collaboration_files');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_file, 3, 4));

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
		$kodetampil = "FLY" . $batas . "MPF";

		return $kodetampil;
	}
	
	public function get_all_collaborations_with_details() {
		$this->db->select('
			cf.*, 
			COUNT(DISTINCT cl.id_like) as like_count, 
			COUNT(DISTINCT cc.id_comment) as comment_count, 
			COUNT(DISTINCT cp.id_user) as participant_count
		');
		$this->db->from('collaboration_files cf');
		$this->db->join('collaboration_like cl', 'cl.id_file = cf.id_file', 'left');
		$this->db->join('collaboration_comment cc', 'cc.id_file = cf.id_file', 'left');
		$this->db->join('collaboration_participant cp', 'cp.id_content = cf.id_file', 'left');
		$this->db->group_by('cf.id_file');
		
		return $this->db->get()->result_array();
	}

	
	public function get_user_liked_collab($id_user) {
		$this->db->select('id_file');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get('collaboration_like');
		return array_column($query->result_array(), 'id_file');
	}
	
	// Get single content by ID
	public function get_content_detail($id_content) {
		$this->db->select('collaboration_files.*, COUNT(collaboration_like.id_file) as like_count');
		$this->db->from('collaboration_files');
		$this->db->join('collaboration_like', 'collaboration_like.id_file = collaboration_files.id_file', 'left');
		$this->db->where('collaboration_files.id_file', $id_content);
		$this->db->group_by('collaboration_files.id_file');
		$query = $this->db->get();
		return $query->row(); // Mengembalikan satu baris data
	}
	
	
	// Increment view count
    public function increment_view($id_content) {
        $this->db->set('view_count', 'view_count + 1', FALSE);
        $this->db->where('id_file', $id_content);
        $this->db->update('collaboration_files');
    }
	
	// Add comment
    public function add_comment($comment_data) {
        return $this->db->insert('collaboration_comment', $comment_data);
    }

    // Get comments for a content
    public function get_comments($id_content) {
        return $this->db->get_where('collaboration_comment', ['id_content' => $id_content])->result_array();
    }
	
	// Check if user has already liked the content
	public function has_liked($id_content, $id_user) {
		$this->db->where('id_file', $id_content);
		$this->db->where('id_user', $id_user);
		return $this->db->get('collaboration_like')->num_rows() > 0;
	}

    // Menambahkan like
    public function add_like($id_content, $id_user) {
        $data = [
            'id_file' => $id_content,
            'id_user' => $id_user
        ];
        $this->db->insert('collaboration_like', $data);
    }

    // Menghapus like
    public function remove_like($id_content, $id_user) {
        $this->db->where('id_file', $id_content);
        $this->db->where('id_user', $id_user);
        $this->db->delete('collaboration_like');
    }

    // Mengecek apakah user sudah like konten tertentu
    public function is_liked($id_content, $id_user) {
        $this->db->where('id_file', $id_content);
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('collaboration_like');
        return $query->num_rows() > 0;
    }
	
	public function get_user_liked_content($id_user) {
		$this->db->select('id_content');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get('likes');
		return array_column($query->result_array(), 'id_content');
	}
	
	public function get_comments_with_replies($id_content) {
		$this->db->select('collaboration_comment.*, user.*');
		$this->db->from('collaboration_comment');
		$this->db->join('user', 'user.id_user = collaboration_comment.id_user');
		$this->db->where('collaboration_comment.id_file', $id_content);
		$this->db->where('collaboration_comment.id_parent IS NULL');
		$this->db->order_by('collaboration_comment.created_at', 'ASC');
		$main_comments = $this->db->get()->result_array();

		// Untuk setiap komentar utama, ambil balasannya
		foreach ($main_comments as &$comment) {
			$this->db->select('collaboration_comment.*, user.*');
			$this->db->from('collaboration_comment');
			$this->db->join('user', 'user.id_user = collaboration_comment.id_user');
			$this->db->where('collaboration_comment.id_parent', $comment['id_comment']);
			$this->db->order_by('collaboration_comment.created_at', 'ASC');
			$comment['replies'] = $this->db->get()->result_array();
		}

		return $main_comments;
	}
	
	public function get_available_users_for_collab($id_content) {
		// Ambil semua user yang BELUM menjadi participant untuk konten ini
		$this->db->select('u.id_user, u.name');
		$this->db->from('user u');
		$this->db->where("u.id_user NOT IN (
			SELECT id_user FROM collaboration_participant WHERE id_content = '$id_content'
		)", null, false); // false = supaya tidak di-escape
		return $this->db->get()->result_array();
	}

	
	
	//////////////////////////
	public function get_comment_by_id($id_comment) {
		return $this->db->get_where('collaboration_comment', ['id_comment' => $id_comment])->row_array();
	}

    public function get_comments_by_content($id_content) {
        $this->db->select('*');
        $this->db->from('collaboration_comment');
        $this->db->where('id_file', $id_content);
        $this->db->where('id_parent IS NULL'); // Hanya komentar utama
        $this->db->order_by('created_at', 'ASC'); // Urutkan berdasarkan waktu
        return $this->db->get()->result_array();
    }
	
	public function delete_comment($id_comment) {
        // Delete main comment and its replies in one query
        $this->db->where('id_comment', $id_comment);
        $this->db->or_where('id_parent', $id_comment);
        return $this->db->delete('collaboration_comment');
    }
	
	public function update_content($id_file, $data) {
		$this->db->where('id_file', $id_file);
		return $this->db->update('collaboration_files', $data);
	}

}
