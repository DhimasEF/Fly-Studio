<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/transaction') ?>"><i class='bx bx-transfer'></i>List Transaction</a> /
			<span class="text2"> <?= $transaction->id_transaction; ?> 
			</span>
		</h1>
    </a>
	</div>

<div class="trx-grid mt-2">
	<div class="trx-container">
		<div class="trx-info">
			<div class="trx-data">
				<p>Orderer: <?= $transaction->orderer_name; ?></p>
				<p>Worker: <?= $transaction->worker_name; ?></p>
				<p>Status: <?= ucwords(str_replace('_', ' ', $transaction->order_status)); ?></p>
				<?php if ((($is_orderer && $is_paid && $transaction->order_status === 'completed') || $is_worker) && !empty($transaction->password)): ?>
					<p>Password: <?= $transaction->password; ?></p>
				<?php endif; ?>

				<?php if ($is_worker): ?>
					<a class="trx-button" href="<?= site_url('Admin/transaction/edit/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
						<i class='bx bx-edit'></i> Edit Order
					</a>
				<?php endif; ?>
			</div>
			
			<div class="trx-down">
				<?php if ((($is_orderer && $is_paid && $transaction->order_status === 'completed') || $is_worker) && !empty($transaction->password)): ?>
					<?php if (!empty($qr_code_url)) : ?>
						<div class="trx-qr">
							<span>QR Code (Password)</span>
							<img src="<?= $qr_code_url ?>" alt="QR Code">
						</div>
						<?php if ($is_orderer && $is_paid && !empty($transaction->order_file_url) && $transaction->order_status === 'completed') : ?>
							<a class="trx-button" href="<?= $transaction->order_file_url; ?>" target="_blank">⬇️ Download Final</a>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

		<table class="trx-table">
			<tr>
				<th>Total Harga</th>
				<td>Rp <?= number_format($transaction->total_price, 0, ',', '.') ?></td>
			</tr>
			<tr>
				<th>Status</th>
				<td><?= $is_paid ? "<span class='trx-paid'>Lunas</span>" : "<span class='trx-unpaid'>Belum Lunas</span>" ?></td>
			</tr>
			<tr>
				<th>Total Pembayaran</th>
				<td>Rp <?= number_format($transaction->total_paid, 0, ',', '.') ?></td>
			</tr>
		</table>
		<?php if ($is_orderer && !$is_paid): ?>
			<h3 class="f4 mt-2">Form Pembayaran</h3>
			<div class="mt-2">
				<form class="trans-edit" action="<?= base_url('Admin/transaction/pay/' . $transaction->id_transaction) ?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="amount"><i class='bx bx-money'></i></label>
						<input type="number" name="amount" placeholder="Masukkan jumlah" required>
					</div>
					<div class="form-group">
						<label for="payment_proof"><i class='bx bx-upload'></i></label>
						<input type="file" name="payment_proof" accept="image/*" required>
					</div>
					<div class="form-group">
						<button type="submit"><i class='bx bx-wallet'></i> Bayar</button>
					</div>
				</form>
			</div>
		<?php endif; ?>
	</div>
	
	<div class="trx-container">
		<div class="payment-data">
			<h3>Riwayat Pembayaran</h3>
			<div class="note-btn">
				<?php if (($is_orderer && $is_paid && $transaction->order_status === 'completed') || $is_worker): ?>
					<a class="trx-button" href="<?= base_url('Admin/transaction/print_invoice_pdf/' . rawurlencode(base64_encode($transaction->id_transaction))) ?>" target="_blank">
						<i class='bx bx-search-alt'></i> Preview Nota
					</a>
					<a class="trx-button" href="<?= base_url('Admin/transaction/download_invoice_pdf/' . rawurlencode(base64_encode($transaction->id_transaction))) ?>" target="_blank">
						<i class='bx bx-download'></i> Download Nota
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php if (empty($payments)) : ?>
			<p>Belum ada pembayaran.</p>
		<?php else: ?>
		<?php 
			$has_pending = false;
			foreach ($payments as $payment) {
				if ($payment->payment_status === 'pending') {
					$has_pending = true;
					break;
				}
			}
		?>
			<table class="trx-table f4">
				<tr>
					<th>ID Pembayaran</th>
					<th>Jumlah</th>
					<th>Status</th>
					<th>Bukti</th>
					<th>Tanggal</th>
					<?php if ($is_worker && $has_pending): ?>
						<th>Aksi</th>
					<?php endif; ?>
				</tr>
				<?php foreach ($payments as $payment) : ?>
					<tr>
						<td><?= htmlspecialchars($payment->id_payment) ?></td>
						<td>Rp <?= number_format($payment->amount, 0, ',', '.') ?></td>
						<td><?= htmlspecialchars($payment->payment_status) ?></td>
						<td class="text-center">
							<?php if (!empty($payment->payment_proof)): ?>
								<a href="<?= base_url($payment->payment_proof) ?>" target="_blank" title="Lihat Bukti Transfer">
									<i class='bx bx-image'></i>
								</a>
							<?php else: ?>
								<span>-</span>
							<?php endif; ?>
						</td>
						<td><?= date('d M Y, H:i', strtotime($payment->payment_date)) ?></td>
						<?php if ($is_worker && $has_pending): ?>
							<td class="row-btn">
								<?php if ($payment->payment_status === 'pending') : ?>
									<?php $encoded_id = rawurlencode(base64_encode($payment->id_payment)); ?>
									<a href="<?= base_url('Admin/transaction/accept/' . $encoded_id) ?>">
										<i class='bx bx-check-circle' style="color: green;"></i>
									</a>
									<a href="<?= base_url('Admin/transaction/reject/' . $encoded_id) ?>">
										<i class='bx bx-x-circle' style="color: red;"></i>
									</a>
								<?php else: ?>
									<?= ucfirst($payment->payment_status) ?>
								<?php endif; ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
</div>