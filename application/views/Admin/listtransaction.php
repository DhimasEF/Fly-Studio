<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-transfer'></i>List Transaction</h1>
		<a href="<?= site_url('Admin/transaction/add'); ?>" class="create-room">
        <i class='bx bx-plus'></i> Create Order
    </a>
	</div>
	
	<div class="col-12 mt-2">
		<!-- Sidebar Kiri: Navigasi dan Statistik -->
		<div class="trans-filter-buttons">
			<a href="<?= site_url('Admin/transaction?filter=orderer'); ?>" class="nav-btn <?= ($current_filter === 'orderer') ? 'active' : ''; ?>">
				<i class='bx bx-user'></i>Orderer
			</a>
			<a href="<?= site_url('Admin/transaction?filter=worker'); ?>" class="nav-btn <?= ($current_filter === 'worker') ? 'active' : ''; ?>">
				<i class='bx bx-briefcase'></i>Worker
			</a>
            <!-- Tombol Buka Modal -->
            <button class="neon-btn" id="openPriceModal">
                <i class='bx bx-purchase-tag-alt'></i> Lihat Info Harga
            </button>
            <button class="neon-btn" id="openPaymentModal">
                <i class='bx bx-credit-card'></i> Lihat Metode Pembayaran
            </button>
        </div>
    </div>



<!-- Modal 1: Info Harga -->
<div class="modal-wrapper" id="priceModal">
  <div class="modal-box">
    <span class="close-modal" id="closePriceModal">&times;</span>
    <h3 style="color:#00aabb;"><i class='bx bx-money'></i> Info Harga Commission – Fly Studio</h3>
    <div style="margin-top: 15px; font-size: 0.95rem;">
      🔰 <strong>Logo Animation:</strong> Simple Glow Rp30.000+, Neon Intro Rp45.000+ <br>
      🎨 <strong>GFX / Graphic Design:</strong> Banner Rp25.000+, Poster Rp40.000+ <br>
      🎬 <strong>AMV:</strong> Fast Sync Rp75.000+, Emotional Slow Rp50.000+ <br>
      🖼️ <strong>PMV:</strong> Slideshow Rp40.000+, Lyrics Sync Rp55.000+ <br>
      🌀 <strong>Motion Graphic:</strong> Promo Rp60.000+, Flat Explainer Rp100.000+ <br>
      🛠️ <strong>Custom:</strong> Fleksibel, tergantung brief dan kompleksitas <br><br>
      <small><i class='bx bx-info-circle'></i> Harga estimasi. Diskusikan detail via chat sebelum order.</small>
    </div>
  </div>
</div>

<!-- Modal 2: Metode Pembayaran -->
<div class="modal-wrapper" id="paymentModal">
  <div class="modal-box">
    <span class="close-modal" id="closePaymentModal">&times;</span>
    <h3 style="color:#00aabb;"><i class='bx bx-wallet-alt'></i> Metode Pembayaran</h3>
    <div style="text-align:center; font-size: 0.95rem;">
      <img src="<?= base_url('assets/img/qrcode_dana.png') ?>" alt="QR DANA" width="180"
           style="border: 2px solid #00ffee; box-shadow: 0 0 15px #00ffee; border-radius: 8px;"><br><br>
      <strong>DANA</strong><br>
      Nama: <strong>Dega Fly Studio</strong><br>
      Nomor: <strong>0812-XXXX-XXXX</strong><br><br>
      <small><i class='bx bx-info-circle'></i> Setelah transfer, isi nominal & upload bukti di form.</small>
    </div>
  </div>
</div>

    <!-- Modal Metode Pembayaran -->
    <div class="modal-wrapper" id="paymentModal">
    <div class="modal-box">
        <span class="close-modal" id="closePaymentModal">&times;</span>
        <h3 style="color:#00aabb;"><i class='bx bx-wallet-alt'></i> Metode Pembayaran</h3>
        <div style="margin-top: 15px; font-size: 0.95rem; line-height: 1.8; text-align: center;">
        <img src="<?= base_url('assets/img/qrcode_dana.png') ?>" alt="QR DANA" width="180" style="border: 2px solid #00ffee; box-shadow: 0 0 15px #00ffee; border-radius: 8px;"><br><br>
        <strong>DANA</strong><br>
        Nama: <strong>Dega Fly Studio</strong><br>
        Nomor: <strong>0812-XXXX-XXXX</strong><br><br>
        <small style="color: #555;">
            <i class='bx bx-info-circle'></i> Setelah transfer, silakan isi nominal dan upload bukti di bawah form.
        </small>
        </div>
    </div>
    </div>

    <!-- CSS Modal Khusus Pembayaran -->
    <style>
    .neon-btn {
    background-color: #00ffee;
    color: #000;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    box-shadow: 0 0 10px #00ffee, 0 0 20px #00ffee inset;
    transition: 0.3s;
    }
    .neon-btn:hover {
    background-color: #00ccdd;
    box-shadow: 0 0 15px #00ffee, 0 0 25px #00ffee inset;
    }

    .modal-wrapper {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    z-index: 999;
    }

    .modal-wrapper.show {
    opacity: 1;
    visibility: visible;
    }

    .modal-wrapper.hide .modal-box {
    animation: slideUp 0.4s ease-in forwards;
    }

    .modal-box {
    background: #fff;
    color: #111;
    padding: 20px 30px;
    border-radius: 12px;
    border: 2px solid #00ffee;
    box-shadow: 0 0 25px #00ffee;
    max-width: 500px;
    width: 90%;
    position: relative;
    animation: slideDown 0.4s ease-out;
    }

    .close-modal {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 24px;
    color: #111;
    cursor: pointer;
    }

    @keyframes slideDown {
    from { transform: translateY(-100px); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideUp {
    from { transform: translateY(0); opacity: 1; }
    to   { transform: translateY(100px); opacity: 0; }
    }

    </style>
    
    <script>
  function setupModal(openBtnId, closeBtnId, modalId) {
    const openBtn = document.getElementById(openBtnId);
    const closeBtn = document.getElementById(closeBtnId);
    const modal = document.getElementById(modalId);
    const modalBox = modal.querySelector('.modal-box');

    // Buka Modal
    openBtn.onclick = () => {
      modalBox.style.animation = "slideDown 0.4s ease-out";
      modal.classList.add("show");
      modal.style.opacity = 1;
      modal.style.visibility = "visible";
    };

    // Tutup Modal
    closeBtn.onclick = () => {
      modalBox.style.animation = "slideUp 0.4s ease-in";
      setTimeout(() => {
        modal.classList.remove("show");
        modal.style.opacity = 0;
        modal.style.visibility = "hidden";
      }, 400); // sesuai durasi slideUp
    };

    // Klik luar modal untuk menutup
    window.addEventListener("click", function (e) {
      if (e.target === modal && modal.classList.contains("show")) {
        closeBtn.click();
      }
    });
  }

  // Inisialisasi untuk dua modal
  setupModal("openPriceModal", "closePriceModal", "priceModal");
  setupModal("openPaymentModal", "closePaymentModal", "paymentModal");
</script>

    <div class="trans-grid mt-2">
        <div class="trans-container">
		   <!-- Kolom Kanan: Daftar Transaksi -->
			<?php if (isset($filtered_transactions)): ?>
				<span class="f4 pd-1">
					<?php
						$filter = $this->input->get('filter');
						if ($filter === 'orderer') {
							echo "Orderer Transaction";
						} elseif ($filter === 'worker') {
							echo "Worker Transaction";
						} else {
							echo "Orderer Transaction";
						}
					?>
				</span>
				<?php if (!empty($filtered_transactions)) : ?>
					<?php foreach ($filtered_transactions as $transaction): ?>
						<div class="transaction-card">
							<div class="trans-card-header">
								<span class="card-id">#<?= $transaction->id_transaction; ?></span>
								<?php
								$status_map = [
									'in_progress' => 'In Progress',
									'completed' => 'Completed',
									'cancelled' => 'Cancelled',
									'pending' => 'Pending'
								];

								$display_status = $status_map[$transaction->order_status] ?? 'Unknown';
								?>
								<span class="trans-card-status"><?= $display_status; ?></span>

							</div>
							<div class="trans-card-body">
								<div class="trans-card-dt">
									<span class="trans-card-bg1"><i class='bx bx-user'></i><?= $transaction->orderer_name; ?></span>
									<span class="trans-card-bg2"><i class='bx bx-briefcase'></i><?= $transaction->worker_name; ?></span>
								</div>
								<a class="trans-detail-btn" href="<?= site_url('Admin/transaction/detail/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
									<i class='bx bx-search'></i> Detail
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p>Tidak ada transaksi dalam kategori ini.</p>
				<?php endif; ?>
			</div>

		<?php else: ?>
			<div class="card-container mb-4">
				<?php if (!empty($my_orders)) : ?>
					<?php foreach ($my_orders as $transaction): ?>
						<div class="transaction-card">
							<div class="trans-card-header">
								<span class="card-id">#<?= $transaction->id_transaction; ?></span>
								<?php
								$status_map = [
									'in_progress' => 'In Progress',
									'completed' => 'Completed',
									'cancelled' => 'Cancelled',
									'pending' => 'Pending'
								];

								$display_status = $status_map[$transaction->order_status] ?? 'Unknown';
								?>
								<span class="trans-card-status"><?= $display_status; ?></span>

							</div>
							<div class="trans-card-body">
								<div class="trans-card-dt">
									<span class="trans-card-bg1"><i class='bx bx-user'></i><?= $transaction->orderer_name; ?></span>
									<span class="trans-card-bg2"><i class='bx bx-briefcase'></i><?= $transaction->worker_name; ?></span>
								</div>
								<a class="trans-detail-btn" href="<?= site_url('Admin/transaction/detail/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
									<i class='bx bx-search'></i> Detail
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p>Tidak ada transaksi yang Anda buat.</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<!-- Statistik Transaksi -->
		<div class="trans-container">
			<div class="row justify-between">
				<div class="row justify-around trans-stat">
					<!-- Semua Transaksi -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-list-ul'></i>
							<span><?= $transaction_summary['All'] ?? 0; ?></span>
						</div>
						<span class="f5">All</span>
					</div>

					<!-- Completed -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-check-circle'></i>
							<span><?= $transaction_summary['completed'] ?? 0; ?></span>
						</div>
						<span class="f5">Completed</span>
					</div>

					<!-- In Progress -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-loader'></i>
							<span><?= $transaction_summary['in_progress'] ?? 0; ?></span>
						</div>
						<span class="f5">In Progress</span>
					</div>

					<!-- Pending -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-time'></i>
							<span><?= $transaction_summary['pending'] ?? 0; ?></span>
						</div>
						<span class="f5">Pending</span>
					</div>

					<!-- Canceled -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-x-circle'></i>
							<span><?= $transaction_summary['canceled'] ?? 0; ?></span>
						</div>
						<span class="f5">Canceled</span>
					</div>
				</div>

			
				<div class="row justify-around trans-stat2">
					<!-- Completed as Orderer -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-user-check'></i>
							<span><?= $personal_summary['Complete as Orderer'] ?? 0; ?></span>
						</div>
						<span class="f5">Order</span>
					</div>

					<!-- Completed as Worker -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-briefcase-alt'></i>
							<span><?= $personal_summary['Complete as Worker'] ?? 0; ?></span>
						</div>
						<span class="f5">Worker</span>
					</div>
				</div>
			</div>
			
			<div class="mt-2">
				<span class="f4 pd-1">Other User Transactions</span>
				<?php if (!empty($other_orders)) : ?>
					<?php foreach ($other_orders as $transaction): ?>
						<div class="transaction-card">
							<div class="trans-card-header">
								<span class="card-id">#<?= $transaction->id_transaction; ?></span>
								<?php
								$status_map = [
									'in_progress' => 'In Progress',
									'completed' => 'Completed',
									'cancelled' => 'Cancelled',
									'pending' => 'Pending'
								];

								$display_status = $status_map[$transaction->order_status] ?? 'Unknown';
								?>
								<span class="trans-card-status"><?= $display_status; ?></span>

							</div>
							<div class="trans-card-body">
								<div class="trans-card-dt">
									<span class="trans-card-bg1"><i class='bx bx-user'></i><?= $transaction->orderer_name; ?></span>
									<span class="trans-card-bg2"><i class='bx bx-briefcase'></i><?= $transaction->worker_name; ?></span>
								</div>
								<a class="trans-detail-btn" href="<?= site_url('Admin/transaction/detail/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
									<i class='bx bx-search'></i> Detail
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p>Tidak ada transaksi dari pengguna lain.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>