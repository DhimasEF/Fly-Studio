<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-transfer'></i>List Transaction</h1>
		<a href="<?= site_url('Creator/transaction/add'); ?>" class="create-room">
        <i class='bx bx-plus'></i> Create Order
    </a>
	</div>
	
	<div class="col-12 mt-2">
		<!-- Sidebar Kiri: Navigasi dan Statistik -->
		<div class="trans-filter-buttons">
			<a href="<?= site_url('Creator/transaction?filter=orderer'); ?>" class="nav-btn <?= ($current_filter === 'orderer') ? 'active' : ''; ?>">
				<i class='bx bx-user'></i>Orderer
			</a>
			<a href="<?= site_url('Creator/transaction?filter=worker'); ?>" class="nav-btn <?= ($current_filter === 'worker') ? 'active' : ''; ?>">
				<i class='bx bx-briefcase'></i>Worker
			</a>
        </div>
    </div>

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
								<a class="trans-detail-btn" href="<?= site_url('Creator/transaction/detail/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
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
								<a class="trans-detail-btn" href="<?= site_url('Creator/transaction/detail/' . rawurlencode(base64_encode($transaction->id_transaction))); ?>">
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
							<span><?= $project_summary['All'] ?? 0; ?></span>
						</div>
						<span class="f5">All</span>
					</div>

					<!-- Completed -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-check-circle'></i>
							<span><?= $project_summary['completed'] ?? 0; ?></span>
						</div>
						<span class="f5">Completed</span>
					</div>

					<!-- In Progress -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-loader'></i>
							<span><?= $project_summary['in_progress'] ?? 0; ?></span>
						</div>
						<span class="f5">In Progress</span>
					</div>

					<!-- Pending -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-time'></i>
							<span><?= $project_summary['pending'] ?? 0; ?></span>
						</div>
						<span class="f5">Pending</span>
					</div>

					<!-- Canceled -->
					<div class="text-center">
						<div class="rg1 f4">
							<i class='bx bx-x-circle'></i>
							<span><?= $project_summary['canceled'] ?? 0; ?></span>
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
		</div>
	</div>
</div>