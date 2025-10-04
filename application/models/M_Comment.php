<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_Comment extends CI_Model {

    /**
     * Add a new comment
     *
     * @param array $data Data for the new comment
     * @return bool True if inserted successfully, false otherwise
     */
    public function add_comment($data) {
        return $this->db->insert('komentar', $data);
    }

    /**
     * Get all komentar for a specific content, including replies
     *
     * @param string $id_content Content ID
     * @return array List of komentar
     */
    public function get_comments_by_content($id_content) {
        $this->db->select('*');
        $this->db->from('komentar');
        $this->db->where('id_content', $id_content);
        $this->db->where('id_parent IS NULL'); // Hanya komentar utama
        $this->db->order_by('created_at', 'ASC'); // Urutkan berdasarkan waktu
        return $this->db->get()->result_array();
    }
	
	public function get_comments_with_replies($id_content) {
		// Ambil semua komentar utama (id_parent = NULL) beserta data user
		$this->db->select('komentar.*, user.*');
		$this->db->from('komentar');
		$this->db->join('user', 'user.id_user = komentar.id_user');
		$this->db->where('komentar.id_content', $id_content);
		$this->db->where('komentar.id_parent IS NULL');
		$this->db->order_by('komentar.created_at', 'ASC');
		$main_comments = $this->db->get()->result_array();

		// Untuk setiap komentar utama, ambil balasannya (reply) beserta data user juga
		foreach ($main_comments as &$comment) {
			$this->db->select('komentar.*, user.*');
			$this->db->from('komentar');
			$this->db->join('user', 'user.id_user = komentar.id_user');
			$this->db->where('komentar.id_parent', $comment['id_comment']);
			$this->db->order_by('komentar.created_at', 'ASC');
			$comment['replies'] = $this->db->get()->result_array();
		}

		return $main_comments;
	}



    /**
     * Get replies for a specific comment
     *
     * @param string $id_parent Parent comment ID
     * @return array List of replies
     */
    public function get_replies_by_comment($id_parent) {
        $this->db->select('*');
        $this->db->from('komentar');
        $this->db->where('id_parent', $id_parent); // Komentar balasan
        $this->db->order_by('created_at', 'ASC'); // Urutkan berdasarkan waktu
        return $this->db->get()->result_array();
    }

    /**
     * Delete a comment and its replies
     *
     * @param string $id_comment Comment ID to delete
     * @return bool True if deleted successfully, false otherwise
     */
    public function delete_comment($id_comment) {
        // Delete main comment and its replies in one query
        $this->db->where('id_comment', $id_comment);
        $this->db->or_where('id_parent', $id_comment);
        return $this->db->delete('komentar');
    }

    /**
     * Count the total number of komentar for a specific content
     *
     * @param string $id_content Content ID
     * @return int Total number of komentar
     */
    public function count_comments($id_content) {
        $this->db->where('id_content', $id_content);
        return $this->db->count_all_results('komentar');
    }
	
	public function get_comment_by_id($id_comment) {
		return $this->db->get_where('komentar', ['id_comment' => $id_comment])->row_array();
	}

}
