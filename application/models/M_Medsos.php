<?php

	class M_Medsos extends CI_Model {
		
		public function get_medsos_list($id_user) {
			return $this->db->get_where('medsos', ['id_user' => $id_user])->result();
		}

		public function add_medsos($data) {
			// Cek apakah platform sudah ada untuk user ini
			$existing = $this->db->get_where('medsos', [
				'id_user' => $data['id_user'],
				'platform' => $data['platform']
			])->row();

			if ($existing) {
				return ['status' => false, 'message' => 'Platform sudah ada.'];
			}

			// Hitung total medsos user
			$count = $this->db->where('id_user', $data['id_user'])->count_all_results('medsos');
			if ($count >= 5) {
				return ['status' => false, 'message' => 'Maksimal 5 media sosial.'];
			}

			// Generate ID baru
			$this->db->select('id_medsos');
			$this->db->order_by('id_medsos', 'DESC');
			$this->db->limit(1);
			$last = $this->db->get('medsos')->row();
			$num = $last ? intval(substr($last->id_medsos, 3, 4)) + 1 : 1;
			$id_medsos = 'FLY' . str_pad($num, 4, '0', STR_PAD_LEFT) . 'MDS';

			$data['id_medsos'] = $id_medsos;
			$this->db->insert('medsos', $data);

			return ['status' => true];
		}
		
		public function id_mds() {
			// Ambil ID file terakhir berdasarkan urutan descending
			$this->db->select('id_medsos');
			$this->db->order_by('id_medsos', 'DESC');
			$this->db->limit(1);

			$query = $this->db->get('medsos');

			if ($query->num_rows() > 0) {
				$data = $query->row();
				// Ekstrak angka dari format ID "FLYxxxxCTR"
				$last_number = intval(substr($data->id_medsos, 3, 4));

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
			$id_medsos = "FLY" . $batas . "MDS";

			// Cek apakah ID yang sudah digenerate sudah ada di tabel 'creator'
			while ($this->db->get_where('medsos', ['id_medsos' => $id_medsos])->num_rows() > 0) {
				// Jika ID sudah ada, tambahkan angka dan coba lagi
				$kode++;
				if ($kode > 9999) {
					$kode = 1;
				}

				$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
				$id_medsos = "FLY" . $batas . "MDS";
			}

			return $id_medsos;
		}

		public function update_medsos($id, $url) {
			return $this->db->update('medsos', ['url' => $url], ['id_medsos' => $id]);
		}

		public function delete_medsos($id) {
			return $this->db->delete('medsos', ['id_medsos' => $id]);
		}
	
	}

?>
