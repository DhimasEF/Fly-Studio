<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Content extends CI_Model {
    // Function to insert content
    public function insert_content($data) {
        return $this->db->insert('konten', $data);
    }
	
	// Update data konten
    public function update_content($id_content, $data) {
        $this->db->where('id_content', $id_content);
        return $this->db->update('konten', $data);
    }

    // Hapus konten
    public function delete_content($id_content) {
        $this->db->where('id_content', $id_content);
        return $this->db->delete('konten');
    }

    // Function to get all content
    public function get_all_content() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('konten')->result_array();
    }

    // Get single content by ID
	public function get_content_detail($id_content) {
		$this->db->select('konten.*, user.*,  COUNT(likes.id_content) as like_count');
		$this->db->from('konten');
		$this->db->join('user', 'user.id_user = konten.id_uploader', 'left');
		$this->db->join('likes', 'likes.id_content = konten.id_content', 'left');
		$this->db->where('konten.id_content', $id_content);
		$this->db->group_by('konten.id_content');
		$query = $this->db->get();
		return $query->row(); // Mengembalikan satu baris data
	}

	
	// Increment view count
    public function increment_view($id_content) {
        $this->db->set('view_count', 'view_count + 1', FALSE);
        $this->db->where('id_content', $id_content);
        $this->db->update('konten');
    }
	
    // Add comment
    public function add_comment($data) {
        $this->db->insert('komentar', $data);
    }

    // Get comments for a content
    public function get_comments($id_content) {
        return $this->db->get_where('komentar', ['id_content' => $id_content])->result_array();
    }
	
	// Check if user has already liked the content
	public function has_liked($id_content, $id_user) {
		$this->db->where('id_content', $id_content);
		$this->db->where('id_user', $id_user);
		return $this->db->get('likes')->num_rows() > 0;
	}

    // Mendapatkan semua konten dengan jumlah like
    public function get_all_content_with_likes() {
		$this->db->select('konten.*, COUNT(likes.id_like) as like_count, user.*');
		$this->db->from('konten');
		$this->db->join('user', 'user.id_user = konten.id_uploader', 'left');
		$this->db->join('likes', 'likes.id_content = konten.id_content', 'left');
		$this->db->group_by('konten.id_content');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_all_content_with_likes6($limit = 8) {
		$this->db->select('konten.*, COUNT(likes.id_like) as like_count, user.*');
		$this->db->from('konten');
		$this->db->join('user', 'user.id_user = konten.id_uploader', 'left');
		$this->db->join('likes', 'likes.id_content = konten.id_content', 'left');
		$this->db->group_by('konten.id_content');
		$this->db->order_by('like_count', 'DESC');
		$this->db->limit($limit);
		return $this->db->get()->result_array();
	}


    // Menambahkan like
    public function add_like($id_content, $id_user) {
        $data = [
            'id_content' => $id_content,
            'id_user' => $id_user
        ];
        $this->db->insert('likes', $data);
    }

    // Menghapus like
    public function remove_like($id_content, $id_user) {
        $this->db->where('id_content', $id_content);
        $this->db->where('id_user', $id_user);
        $this->db->delete('likes');
    }

    // Mengecek apakah user sudah like konten tertentu
    public function is_liked($id_content, $id_user) {
        $this->db->where('id_content', $id_content);
        $this->db->where('id_user', $id_user);
        $query = $this->db->get('likes');
        return $query->num_rows() > 0;
    }
	
	public function get_user_liked_content($id_user) {
		$this->db->select('id_content');
		$this->db->where('id_user', $id_user);
		$query = $this->db->get('likes');
		return array_column($query->result_array(), 'id_content');
	}
	
	public function countCreatorPosts($id_user) {
		$this->db->where('id_uploader', $id_user);
		return $this->db->count_all_results('konten');
	}

	public function getCreatorPosts($id_user) {
		$this->db->select('
			konten.*,
			user.*,
			COUNT(likes.id_like) as like_count
		');
		$this->db->from('konten');
		$this->db->join('user', 'konten.id_uploader = user.id_user', 'left');
		$this->db->join('likes', 'konten.id_content = likes.id_content', 'left');
		$this->db->where('konten.id_uploader', $id_user);
		$this->db->group_by('konten.id_content');
		$this->db->order_by('konten.created_at', 'DESC');
		$this->db->limit(2); // Tambahkan limit di sini

		return $this->db->get()->result_array();
	}

	
	function id_ctn() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_content');
		$this->db->order_by('id_content', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('konten');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_content, 3, 4));

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
		$kodetampil = "FLY" . $batas . "CTN";

		return $kodetampil;
	}


}

?>