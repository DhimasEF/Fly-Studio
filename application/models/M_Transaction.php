<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Transaction extends CI_Model {

    public function get_all_transactions() {
        $this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
        $this->db->from('transactions');
        $this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
        $this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
        return $this->db->get()->result();
    }

    public function get_transaction($id) {
        $this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
        $this->db->from('transactions');
        $this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
        $this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
        $this->db->where('transactions.id_transaction', $id);
        return $this->db->get()->row();
    }

    public function insert_transaction($data) {
        return $this->db->insert('transactions', $data);
    }

    public function update_transaction($id, $data) {
        $this->db->where('id_transaction', $id);
        return $this->db->update('transactions', $data);
    }
	
	public function update_transaction_status($id, $status) {
        $this->db->where('id_transaction', $id);
        return $this->db->update('transactions', ['order_status' => $status]);
    }

    public function delete_transaction($id) {
        $this->db->where('id_transaction', $id);
        return $this->db->delete('transactions');
    }
	
	public function countUserTransactions($user_id) {
		$this->db->where('id_orderer', $user_id);
		return $this->db->count_all_results('transactions');
	}

	public function getUserTransactions($user_id) {
		$this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
		$this->db->from('transactions');
		$this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
		$this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
		$this->db->where('transactions.id_orderer', $user_id);
		return $this->db->get()->result();
	}
	
	public function get_transaction_by_worker($user_id) {
		$this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
		$this->db->from('transactions');
		$this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
		$this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
		$this->db->where('transactions.id_worker', $user_id);
		return $this->db->get()->result();
	}
	
	// Pesanan yang dibuat oleh user
    public function get_orderer_transactions($user_id) {
        $this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
        $this->db->from('transactions');
        $this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
        $this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
        $this->db->where('transactions.id_orderer', $user_id);
        return $this->db->get()->result();
    }

    // Transaksi yang dikerjakan oleh user
    public function get_worker_transactions($user_id) {
        $this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
        $this->db->from('transactions');
        $this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
        $this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
        $this->db->where('transactions.id_worker', $user_id);
        return $this->db->get()->result();
    }

    // Semua transaksi, kecuali yang dibuat oleh admin sendiri
    public function get_other_transactions($user_id) {
		$this->db->select('transactions.*, u1.name AS orderer_name, u2.name AS worker_name');
		$this->db->from('transactions');
		$this->db->join('user AS u1', 'transactions.id_orderer = u1.id_user', 'left');
		$this->db->join('user AS u2', 'transactions.id_worker = u2.id_user', 'left');
		
		// Tambahkan dua kondisi untuk mengecualikan user_id sebagai orderer dan worker
		$this->db->where('transactions.id_orderer !=', $user_id);
		$this->db->where('transactions.id_worker !=', $user_id);

		return $this->db->get()->result();
	}

	
	public function update_total_paid($transaction_id, $amount) {
		$this->db->set('total_paid', 'total_paid + ' . (int)$amount, FALSE);
		$this->db->where('id_transaction', $transaction_id);
		return $this->db->update('transactions');
	}
	
	function id_trs() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_transaction');
		$this->db->order_by('id_transaction', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('transactions');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_transaction, 3, 4));

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
		$kodetampil = "FLY" . $batas . "TRS";

		return $kodetampil;
	}
	
	// Di dalam M_Transaction.php

// 1. Statistik global untuk Admin (semua transaksi)
	public function get_admin_transaction_summary() {
		$this->db->select("order_status, COUNT(*) as total");
		$this->db->from('transactions');
		$this->db->group_by('order_status');
		$query = $this->db->get()->result();

		$summary = ['All' => 0, 'completed' => 0, 'in_progress' => 0, 'Pending' => 0, 'Canceled' => 0];
		foreach ($query as $row) {
			$summary['All'] += $row->total;
			$summary[$row->order_status] = $row->total;
		}
		return $summary;
	}

	// 2. Statistik completed untuk admin/creator/user (sebagai orderer atau worker)
	public function get_user_completed_summary($user_id) {
		$summary = [
			'Complete as Orderer' => 0,
			'Complete as Worker' => 0,
		];

		// Count completed orders as orderer
		$this->db->from('transactions');
		$this->db->where('id_orderer', $user_id);
		$this->db->where('order_status', 'completed');
		$summary['Complete as Orderer'] = $this->db->count_all_results();

		// Count completed orders as worker
		$this->db->from('transactions');
		$this->db->where('id_worker', $user_id);
		$this->db->where('order_status', 'completed');
		$summary['Complete as Worker'] = $this->db->count_all_results();

		return $summary;
	}


	// 3. Statistik pekerjaan untuk creator (jika dibutuhkan)
	public function get_creator_project_summary($user_id) {
		$this->db->select("order_status, COUNT(*) as total");
		$this->db->from('transactions');
		$this->db->where('id_worker', $user_id);
		$this->db->group_by('order_status');
		$query = $this->db->get()->result();

		$summary = ['All' => 0, 'Completed' => 0, 'In Progress' => 0, 'Pending' => 0, 'Canceled' => 0];
		foreach ($query as $row) {
			$summary['All'] += $row->total;
			$summary[$row->order_status] = $row->total;
		}
		return $summary;
	}

	// 3. Statistik pekerjaan untuk creator (jika dibutuhkan)
	public function get_user_project_summary($user_id) {
		$this->db->select("order_status, COUNT(*) as total");
		$this->db->from('transactions');
		$this->db->where('id_orderer', $user_id);
		$this->db->group_by('order_status');
		$query = $this->db->get()->result();

		$summary = ['All' => 0, 'Completed' => 0, 'In Progress' => 0, 'Pending' => 0, 'Canceled' => 0];
		foreach ($query as $row) {
			$summary['All'] += $row->total;
			$summary[$row->order_status] = $row->total;
		}
		return $summary;
	}



}
?>
