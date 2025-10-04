<?php

	class M_Follow extends CI_Model {

		public function isFollowing($follower_id, $followed_id) {
			$this->db->where('follower_id', $follower_id);
			$this->db->where('followed_id', $followed_id);
			return $this->db->get('follow')->num_rows() > 0;
		}

		public function followUser($follower_id, $followed_id) {
			if (!$this->isFollowing($follower_id, $followed_id)) {
				$data = [
					'follower_id' => $follower_id,
					'followed_id' => $followed_id,
					'follow_date' => date('Y-m-d H:i:s')
				];
				return $this->db->insert('follow', $data);
			}
			return false;
		}
		
		public function unfollowUser($follower_id, $followed_id) {
			$this->db->where('follower_id', $follower_id);
			$this->db->where('followed_id', $followed_id);
			$this->db->delete('follow');
		}
	}

?>