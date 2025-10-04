<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;

class Transaction extends Middle_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_role('user'); // Middleware untuk cek role
		$this->load->model('M_User');
		$this->load->model('M_Transaction');
		$this->load->model('M_Payment');
		$this->load->library('session');
		
		 // Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }
	
	
	public function index() {
		$user_id = $this->session->userdata('user_id');
		$role = $this->session->userdata('role');
		$filter = $this->input->get('filter') ?? 'orderer'; // default ke 'orderer'
		$data['current_filter'] = $filter;

		if (!$user_id || !$role) {
			show_error("Unauthorized access", 403);
			return;
		}

		$data['user'] = $this->M_User->get_user_by_id($user_id);

		if ($role === 'admin') {
			$data['transaction_summary'] = $this->M_Transaction->get_admin_transaction_summary(); 
			$data['personal_summary'] = $this->M_Transaction->get_user_completed_summary($user_id);
			$data['project_summary'] = $this->M_Transaction->get_creator_project_summary($user_id);
			$data['other_orders'] = $this->M_Transaction->get_other_transactions($user_id);

			if ($filter === 'orderer') {
				$data['filtered_transactions'] = $this->M_Transaction->get_orderer_transactions($user_id);
			} elseif ($filter === 'worker') {
				$data['filtered_transactions'] = $this->M_Transaction->get_worker_transactions($user_id);
			} else {
				$data['my_orders'] = $this->M_Transaction->get_orderer_transactions($user_id);
				$data['other_orders'] = $this->M_Transaction->get_other_transactions($user_id);
			}
		} else if ($role === 'creator') {
			$data['project_summary'] = $this->M_Transaction->get_creator_project_summary($user_id);
			$data['personal_summary'] = $this->M_Transaction->get_user_completed_summary($user_id);
			$data['transaction_summary'] = $this->M_Transaction->get_user_completed_summary($user_id);

			if ($filter === 'orderer') {
				$data['filtered_transactions'] = $this->M_Transaction->get_orderer_transactions($user_id);
			} elseif ($filter === 'worker') {
				$data['filtered_transactions'] = $this->M_Transaction->get_worker_transactions($user_id);
			} else {
				$data['my_orders'] = $this->M_Transaction->get_orderer_transactions($user_id);
				$data['my_works'] = $this->M_Transaction->get_worker_transactions($user_id);
			}
		} else {
			$data['personal_summary'] = $this->M_Transaction->get_user_completed_summary($user_id);
			$data['transaction_summary'] = $this->M_Transaction->get_user_project_summary($user_id);

			// Untuk user biasa, abaikan filter — hanya tampilkan order mereka sendiri
			$data['filtered_transactions'] = $this->M_Transaction->get_orderer_transactions($user_id);
		};
		
		$data['title'] = 'Transaction | Fly Studio';

		$this->load->view('User/sidebar', $data);
        $this->load->view('User/listtransaction', $data);
        $this->load->view('User/footer', $data);
	}
	
	public function add() {
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			echo "User belum login"; die();
		}
		
		$data['user'] = $this->M_User->get_user_by_id($user_id);

		// Ambil semua worker yang bukan user biasa
		$data['workers'] = $this->M_User->get_workers_only($user_id); // method ini akan kita buat
		$data['user_id'] = $user_id;
		$data['title'] = 'Create Transaction | Fly Studio';
		
		$this->load->view('User/sidebar', $data);
		$this->load->view('User/addtransaction', $data);
		$this->load->view('User/footer');
	}
	
	public function store() {
		$data = [
			'id_transaction' => $this->M_Transaction->id_trs(), // misalnya FLY0009TRS
			'id_orderer' => $this->session->userdata('user_id'),
			'id_worker' => $this->input->post('id_worker'),
			'max_revision' => 4,
			'revision_count' => 0,
			'order_status' => 'pending',
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->M_Transaction->insert_transaction($data);
		redirect('User/transaction');
	}
	
	public function pay($transaction_id) {
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) {
			show_error("Anda harus login untuk melakukan pembayaran.", 403);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$amount = $this->input->post('amount');

			// **Proses Upload File**
			$config['upload_path']   = './assets/uploads/Payment/';
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
			$config['max_size']      = 2048; // 2MB
			$config['file_name']     = 'payment_' . time() . '_' . $user_id;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('payment_proof')) {
				$this->session->set_flashdata('error', 'Gagal mengupload bukti pembayaran: ' . $this->upload->display_errors());
				redirect('User/transaction/detail/' . rawurlencode(base64_encode($transaction_id)));
			}

			$upload_data = $this->upload->data();
			$payment_proof = 'assets/uploads/Payment/' . $upload_data['file_name'];

			// Simpan data pembayaran
			$payment_data = [
				'id_payment' 	 => $this->M_Payment->id_py(),
				'id_transaction' => $transaction_id,
				'id_user'        => $user_id,
				'amount'         => $amount,
				'payment_proof'  => $payment_proof,
				'payment_date'   => date('Y-m-d H:i:s'),
				'payment_status' => 'pending'
			];

			if ($this->M_Payment->add_payment($payment_data)) {
				$this->session->set_flashdata('success', 'Pembayaran berhasil dikirim.');
			} else {
				$this->session->set_flashdata('error', 'Gagal menyimpan pembayaran.');
			}

			redirect('User/transaction/detail/' . rawurlencode(base64_encode($transaction_id)));
		}
	}
	
	public function detail($encoded_id) {
		// Decode and validate transaction ID
		$decoded_id = base64_decode(rawurldecode($encoded_id));
		
		if (!$decoded_id || !preg_match('/^[a-zA-Z0-9_]+$/', $decoded_id)) {
			show_error("Invalid Transaction ID", 400);
		}

		// Get logged-in user ID from session
		$user_id = $this->session->userdata('user_id');
		
		if (!$user_id) {
			show_error("User ID tidak ditemukan di session", 403);
		}

		// Get user data (admin / orderer / worker)
		$data['user'] = $this->M_User->get_user_by_id($user_id);
		$data['logged_in_user_id'] = $user_id;

		// Get transaction data
		$transaction = $this->M_Transaction->get_transaction($decoded_id);
		if (!$transaction) {
			show_404();
		}
		$data['transaction'] = $transaction;

		// Get payment list & total paid
		$data['payments'] = $this->M_Payment->get_payments_by_transaction($decoded_id);
		$data['total_paid'] = $this->M_Payment->get_total_paid($decoded_id, $user_id);
		
		// Determine payment & role status
		$data['is_paid'] = ($transaction->total_paid >= $transaction->total_price);
		$data['is_orderer'] = ($transaction->id_orderer === $user_id);
		$data['is_worker'] = ($transaction->id_worker === $user_id);

		// ✅ Generate QR code (only for worker, if password exists)
		if ($data['is_paid'] && !empty($transaction->password)) {
			$qr_path = FCPATH . 'assets/qr/';
			$qr_file = $transaction->id_transaction . '.png';
			$qr_full_path = $qr_path . $qr_file;

			// Buat folder kalau belum ada
			if (!file_exists($qr_path)) {
				mkdir($qr_path, 0755, true);
			}

			// Generate QR hanya jika belum ada
			if (!file_exists($qr_full_path)) {
				$this->load->library('ciqrcode');
				$params['data'] = $transaction->password;
				$params['level'] = 'H';
				$params['size'] = 5;
				$params['savename'] = $qr_full_path;
				$this->ciqrcode->generate($params);
			}

			$data['qr_code_url'] = base_url('assets/qr/' . $qr_file);
		} else {
			$data['qr_code_url'] = null;
		}
		$data['title'] = 'Transaction ' . ($data['transaction']->id_transaction . ' | Fly Studio');

		// Load view
		$this->load->view('User/sidebar', $data);
		$this->load->view('User/detailtransaction', $data);
		$this->load->view('User/footer', $data);
	}

	public function print_invoice_pdf($encoded_id) {
		$this->load->library('ciqrcode');

		$id_transaction = base64_decode(rawurldecode($encoded_id));

		$data['transaction'] = $this->M_Transaction->get_transaction($id_transaction);
		$data['payments'] = $this->M_Payment->get_payments_by_transaction($id_transaction);
		$data['is_paid'] = $data['transaction']->total_price <= $data['transaction']->total_paid;
		$logo_path = FCPATH . 'assets/resource/home.png'; // ganti sesuai path logo
		$data['logo_base64'] = base64_encode(file_get_contents($logo_path));


		$qrText = "ID Transaksi: {$data['transaction']->id_transaction}\nURL: {$data['transaction']->order_file_url}\nPassword: {$data['transaction']->password}";
		
		// QR simpan ke file sementara
		$filename = 'nota_qr_' . $id_transaction . '.png';
		$filepath = FCPATH . 'assets/qr/' . $filename;

		$params['data'] = $qrText;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = $filepath;

		$this->ciqrcode->generate($params);

		// Konversi ke base64 untuk dimasukkan ke PDF
		$data['qr_base64'] = base64_encode(file_get_contents($filepath));

		$html = $this->load->view('User/invoice_pdf', $data, true);

		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		$dompdf->stream("invoice_{$id_transaction}.pdf", array("Attachment" => false));
	}

	public function download_invoice_pdf($encoded_id) {
		$this->load->library('ciqrcode');

		$id_transaction = base64_decode(rawurldecode($encoded_id));

		$data['transaction'] = $this->M_Transaction->get_transaction($id_transaction);
		$data['payments'] = $this->M_Payment->get_payments_by_transaction($id_transaction);
		$data['is_paid'] = $data['transaction']->total_price <= $data['transaction']->total_paid;
		$logo_path = FCPATH . 'assets/resource/home.png'; // ganti sesuai path logo
		$data['logo_base64'] = base64_encode(file_get_contents($logo_path));

		$qrText = "ID Transaksi: {$data['transaction']->id_transaction}\nURL: {$data['transaction']->order_file_url}\nPassword: {$data['transaction']->password}";
		
		$filename = 'nota_qr_' . $id_transaction . '.png';
		$filepath = FCPATH . 'assets/qr/' . $filename;

		$params['data'] = $qrText;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = $filepath;

		$this->ciqrcode->generate($params);

		$data['qr_base64'] = base64_encode(file_get_contents($filepath));

		$html = $this->load->view('User/invoice_pdf', $data, true);

		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		$dompdf->stream("invoice_{$id_transaction}.pdf", array("Attachment" => true));
	}
	
}
?>