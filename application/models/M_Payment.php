<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Payment extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Mendapatkan semua pembayaran berdasarkan ID transaksi
     */
    public function get_payments_by_transaction($transaction_id) {
        return $this->db->where('id_transaction', $transaction_id)
                        ->order_by('payment_date', 'DESC')
                        ->get('payment')
                        ->result();
    }

    /**
     * Mendapatkan total pembayaran berdasarkan transaksi dan pengguna tertentu
     */
    public function get_total_paid($transaction_id, $user_id) {
		$this->db->select_sum('amount');
		$this->db->where('id_transaction', $transaction_id);
		$this->db->where('id_user', $user_id);
		$query = $this->db->get('payment');

		return $query->row()->amount ?? 0;
	}


    /**
     * Menambahkan pembayaran baru
     */
    public function add_payment($data) {
        return $this->db->insert('payment', $data);
    }
	
	public function get_payment_by_id($payment_id) {
        return $this->db->get_where('payment', ['id_payment' => $payment_id])->row();
    }

    public function update_payment_status($payment_id, $status) {
        $this->db->where('id_payment', $payment_id);
        return $this->db->update('payment', ['payment_status' => $status]);
    }
	
	function id_py() {
		// Ambil ID file terakhir berdasarkan urutan descending
		$this->db->select('id_payment');
		$this->db->order_by('id_payment', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('payment');

		if ($query->num_rows() > 0) {
			$data = $query->row();
			// Ekstrak angka dari format ID "FLYxxxxMPF"
			$last_number = intval(substr($data->id_payment, 3, 4));

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
		$kodetampil = "FLY" . $batas . "PYM";

		return $kodetampil;
	}

}

