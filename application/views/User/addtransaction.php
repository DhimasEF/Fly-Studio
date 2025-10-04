<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-transfer'></i>Create Transaction</h1>
    </a>
	</div>
	
	<form class="mt-2 worker-grid" action="<?= site_url('User/transaction/store') ?>" method="post">
		<div class="worker-select-container">
			<?php foreach ($workers as $worker): ?>
				<label class="worker-option-card">
					<input type="radio" name="id_worker" value="<?= $worker->id_user ?>" required>
					<img class="worker-profile-img" src="<?= base_url('assets/uploads/Profil/' . (!empty($worker->profile_picture) ? $worker->profile_picture : 'default.jpg')) ?>" alt="Profil">
					<div class="worker-details">
						<span class="worker-name"><?= htmlspecialchars($worker->name) ?></span>
						<span class="worker-projects">
							<i class='bx bx-folder'></i> <?= $worker->project_count ?>
						</span>
					</div>
				</label>
			<?php endforeach; ?>
		</div>
		<button type="submit" class="mt-2 btn-submit-trans">Create Order</button>
	</form>
</div>
