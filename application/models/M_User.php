<?php

	class M_User extends CI_Model {
		
		public function check_user($email) {
			return $this->db->get_where('user', ['email' => $email])->row_array();
		}

		public function register_user($data) {
			return $this->db->insert('user', $data);
		}
		
		public function update_role($id_user, $new_role) {
			$this->db->where('id_user', $id_user);
			return $this->db->update('user', ['role' => $new_role]);
		}
		
		public function get_user_by_id($user_id) {
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where('id_user', $user_id);
			return $this->db->get()->row(); // Mengambil data pengguna dalam bentuk satu objek
		}
		
		public function get_admin_by_id($user_id) {
			$this->db->select('user.*, admin.position, admin.description, admin.created_at AS admin_created_at');
			$this->db->from('user');
			$this->db->join('admin', 'admin.id_user = user.id_user', 'left'); // Tetap ambil meskipun tidak ada di admin
			$this->db->where('user.id_user', $user_id);
			
			$query = $this->db->get();

			// Debugging (jika masih error, bisa dicetak)
			// echo $this->db->last_query(); die(); 

			return $query->row(); // Ambil satu data admin berdasarkan ID user
		}
		
		public function update_user($id, $data) {
			$this->db->where('id_user', $id);
			return $this->db->update('user', $data);
		}

		
		public function getAllUsersExcept($id_user) {
			$this->db->select('id_user, name');
			$this->db->from('user');
			$this->db->where('id_user !=', $id_user); // Kecualikan pengirim
			return $this->db->get()->result();
		}

		
		public function get_all_users_except_me($user_id) {
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where('id_user !=', $user_id); // Kecualikan pengirim
			return $this->db->get()->result_array();
		}
		
		public function get_workers_only($user_id) {
			$this->db->select('u.id_user, u.name, u.profile_picture, COUNT(t.id_transaction) as project_count');
			$this->db->from('user u');
			$this->db->where_in('u.role', ['creator', 'admin']);
			$this->db->where('u.id_user !=', $user_id);
			$this->db->join('transactions t', 't.id_worker = u.id_user', 'left');
			$this->db->group_by('u.id_user');
			return $this->db->get()->result();
		}

		
		public function get_users_not_in_group($room_id) {
			$query = $this->db->query("
				SELECT * FROM user
				WHERE id_user NOT IN (
					SELECT id_user FROM chat_room_members WHERE id_chat_room = ?
				)
			", [$room_id]);

			return $query->result_array();
		}

		
		public function getAdminCreator() {
			$this->db->select('id_user, name');
			$this->db->from('user');
			$this->db->where_in('role', ['admin', 'creator']); // Ambil admin dan creator
			return $this->db->get()->result();
		}

		
		public function get_all_users() {
			$query = $this->db->get('user');
			return $query->result_array(); // Mengembalikan semua user sebagai array
		}
		
		public function get_creators_with_details() {
			$this->db->select('
				user.*,
				creator.*,
				COUNT(follow.id_follow) as total_followers,
				medsos.platform,
				medsos.url
			');
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('user.role', 'creator');
			$this->db->group_by('user.id_user, medsos.id_medsos');
			$this->db->order_by('total_followers', 'DESC');
			$query = $this->db->get();
			return $query->result();
		}
		
		public function get_creators_with_details6($limit = 6) {
			$this->db->select('
				user.*, 
				creator.*, 
				COUNT(DISTINCT follow.id_follow) as total_followers, 
				GROUP_CONCAT(DISTINCT CONCAT(medsos.platform, ":", medsos.url) SEPARATOR "; ") as social_links
			');
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('user.role', 'creator');
			$this->db->group_by('user.id_user');
			$this->db->order_by('total_followers', 'DESC');
			$this->db->limit($limit);
			return $this->db->get()->result();
		}


			
		// Get all creators
		public function get_all_role_creators() {
			$this->db->select('
				user.*,
				creator.*,
				COUNT(follow.id_follow) as total_followers,
				medsos.platform,
				medsos.url
			');
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('user.role', 'creator');
			$this->db->group_by('user.id_user, medsos.id_medsos');
			$this->db->order_by('total_followers', 'DESC');
			return $this->db->get()->result_array();
		}
		
		public function get_all_role_creators_except_userlogin($user_id) {
			$this->db->select('
				user.*,
				creator.*,
				creator.created_at AS team_created_at,
				COUNT(follow.id_follow) AS total_followers,
				medsos.platform,
				medsos.url
			');
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('user.role', 'creator');
			$this->db->where('user.id_user !=', $user_id); // Filter: tidak tampilkan user yang sedang login
			$this->db->group_by('user.id_user, medsos.id_medsos');
			$this->db->order_by('total_followers', 'DESC');
			return $this->db->get()->result_array();
		}


		// Get creator by search (filter by name or team name)
		public function search_role_creator($keyword) {
			$this->db->select('user.id_user, user.email, user.name, user.profile_picture, user.role, user.created_at, creator.team_name, creator.description, creator.created_at AS team_created_at');
			$this->db->from('user');
			$this->db->join('creator', 'user.id_user = creator.id_user');
			$this->db->where('user.role', 'creator');
			$this->db->group_start(); // Start grouping conditions
			$this->db->like('user.name', $keyword);
			$this->db->or_like('creator.team_name', $keyword);
			$this->db->group_end(); // End grouping conditions
			return $this->db->get()->result_array();
		}
		
		public function search_role_creator_except_userlogin($keyword, $user_id) {
			$this->db->select('user.id_user, user.email, user.name, user.profile_picture, user.role, user.created_at, creator.team_name, creator.description, creator.created_at AS team_created_at');
			$this->db->from('user');
			$this->db->join('creator', 'user.id_user = creator.id_user');
			$this->db->where('user.role', 'creator');
			$this->db->where('user.id_user !=', $user_id); // Filter agar user yang login tidak muncul
			$this->db->group_start();
			$this->db->like('user.name', $keyword);
			$this->db->or_like('creator.team_name', $keyword);
			$this->db->group_end();
			return $this->db->get()->result_array();
		}

		
		// Ambil semua akun dengan role "user"
		public function get_all_role_users() {
			return $this->db->get_where('user', ['role' => 'user'])->result();
		}

		// Cari user berdasarkan keyword (nama atau email)
		public function search_role_user($keyword) {
			$this->db->from('user');
			$this->db->where('role', 'user');
			$this->db->group_start();
			$this->db->like('name', $keyword);
			$this->db->or_like('email', $keyword);
			$this->db->group_end();
			return $this->db->get()->result();
		}
	
		public function getCreatorById($id_user, $with_password = false) {
			$fields = [
				'user.id_user',
				'user.name',
				'user.profile_picture',
				'user.role',
				'user.email',
				'user.created_at',
				'creator.id_user AS creator_user_id',
				'creator.id_team',
				'creator.team_name',
				'creator.description',
				'creator.created_at AS creator_joined_at',
				'COUNT(DISTINCT follow.id_follow) as total_followers',
				'GROUP_CONCAT(DISTINCT medsos.platform ORDER BY medsos.id_medsos SEPARATOR ", ") as platforms',
				'GROUP_CONCAT(DISTINCT medsos.url ORDER BY medsos.id_medsos SEPARATOR ", ") as urls'
			];

			if ($with_password) {
				$fields[] = 'user.password';
				$fields[] = 'user.password_hash';
			}

			$this->db->select(implode(",\n", $fields));
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('CONVERT(user.id_user USING utf8mb4) =', $id_user);
			$this->db->group_by('user.id_user');

			$query = $this->db->get();
			return $query->row_array();
		}


		public function getUserById($id_user) {
			$this->db->select('
				user.*,
				COUNT(DISTINCT follow.id_follow) as total_followers,  -- Perbaikan menghitung followers unik
				GROUP_CONCAT(DISTINCT medsos.platform ORDER BY medsos.id_medsos SEPARATOR ", ") as platforms,
				GROUP_CONCAT(DISTINCT medsos.url ORDER BY medsos.id_medsos SEPARATOR ", ") as urls
			');
			$this->db->from('user');
			$this->db->join('medsos', 'medsos.id_user = user.id_user', 'left');
			$this->db->join('follow', 'follow.followed_id = user.id_user', 'left');
			$this->db->where('CONVERT(user.id_user USING utf8mb4) =', $id_user); // Paksa utf8mb4
			$this->db->group_by('user.id_user');
			
			$query = $this->db->get();
			return $query->row_array();
		}

		public function get_creator_by_id($id_user) {
			$this->db->select('user.*, creator.team_name, creator.description');
			$this->db->from('user');
			$this->db->join('creator', 'creator.id_user = user.id_user', 'left'); // Join dengan tabel creator
			$this->db->where('user.id_user', $id_user);
			return $this->db->get()->row_array(); // Ambil satu baris data
		}
		
		public function create_creator_profile($id_user) {
			// Cek apakah user sudah punya creator profile
			$exists = $this->db->get_where('creator', ['id_user' => $id_user])->row();
			if ($exists) {
				return false; // Sudah ada, tidak perlu insert ulang
			}

			// Load helper untuk generate ID unik
			$id_team = $this->id_cre();
			
			// Insert ke tabel creator
			$data = [
				'id_team' => $id_team,
				'id_user' => $id_user,
				'team_name' => 'Fly Studio',
				'description' => 'New creator profile',
				'created_at' => date('Y-m-d H:i:s')
			];

			return $this->db->insert('creator', $data);
		}


		public function update_creator($id_user, $user_data, $creator_data) {
			$this->db->trans_start(); // Mulai transaksi

			// Ambil data user lama sebagai object
			$this->db->select('role'); 
			$this->db->where('id_user', $id_user);
			$old_user = $this->db->get('user')->row(); // <-- Pastikan ini row() agar berbentuk object

			if ($old_user) {
				// Pastikan role tetap sama
				$user_data['role'] = $old_user->role;
			}

			// Update data user (tanpa mengubah role)
			$this->db->where('id_user', $id_user);
			$this->db->update('user', $user_data);

			// Cek apakah data creator sudah ada
			$this->db->where('id_user', $id_user);
			$existing_creator = $this->db->get('creator')->row();

			if ($existing_creator) {
				// Jika data creator sudah ada, update
				$this->db->where('id_user', $id_user);
				$this->db->update('creator', $creator_data);
			} else {
				// Jika belum ada, insert data baru
				$creator_data['id_user'] = $id_user;
				$this->db->insert('creator', $creator_data);
			}

			$this->db->trans_complete(); // Selesaikan transaksi

			return $this->db->trans_status(); // Cek apakah transaksi berhasil
		}
		
		public function update_admin($id_user, $user_data, $admin_data) {
			$this->db->trans_start(); // Mulai transaksi

			// Ambil role user lama agar tidak berubah
			$this->db->select('role');
			$this->db->where('id_user', $id_user);
			$old_user = $this->db->get('user')->row();

			if ($old_user) {
				$user_data['role'] = $old_user->role;
			}

			// Update data user
			$this->db->where('id_user', $id_user);
			$this->db->update('user', $user_data);

			// Cek apakah data admin sudah ada
			$this->db->where('id_user', $id_user);
			$existing_admin = $this->db->get('admin')->row();

			if ($existing_admin) {
				// Jika ada, update
				$this->db->where('id_user', $id_user);
				$this->db->update('admin', $admin_data);
			} else {
				// Jika tidak ada, insert baru
				$admin_data['id_user'] = $id_user;
				$this->db->insert('admin', $admin_data);
			}

			$this->db->trans_complete(); // Selesai transaksi

			return $this->db->trans_status(); // TRUE jika berhasil, FALSE jika gagal
		}

		
		function id_usr() {
			// Ambil ID file terakhir berdasarkan urutan descending
			$this->db->select('id_user');
			$this->db->order_by('id_user', 'DESC');
			$this->db->limit(1);

			$query = $this->db->get('user');

			if ($query->num_rows() > 0) {
				$data = $query->row();
				// Ekstrak angka dari format ID "FLYxxxxMPF"
				$last_number = intval(substr($data->id_user, 3, 4));

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
			$kodetampil = "FLY" . $batas . "USR";

			return $kodetampil;
		}
		
		public function id_cre() {
			// Ambil ID file terakhir berdasarkan urutan descending
			$this->db->select('id_team');
			$this->db->order_by('id_team', 'DESC');
			$this->db->limit(1);

			$query = $this->db->get('creator');

			if ($query->num_rows() > 0) {
				$data = $query->row();
				// Ekstrak angka dari format ID "FLYxxxxCTR"
				$last_number = intval(substr($data->id_team, 3, 4));

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
			$id_team = "FLY" . $batas . "CTR";

			// Cek apakah ID yang sudah digenerate sudah ada di tabel 'creator'
			while ($this->db->get_where('creator', ['id_team' => $id_team])->num_rows() > 0) {
				// Jika ID sudah ada, tambahkan angka dan coba lagi
				$kode++;
				if ($kode > 9999) {
					$kode = 1;
				}

				$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
				$id_team = "FLY" . $batas . "CTR";
			}

			return $id_team;
		}


	}
?>