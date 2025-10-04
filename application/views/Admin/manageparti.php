<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/collaboration') ?>"><i class='bx bxs-videos'></i>Manajement Participant</a> /
			<span class="text2"> <?= $content->id_file; ?> 
			</span>
		</h1>
    </a>
	</div>
	
	<div class="manage-grid">
		<!-- Notifikasi -->
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert success"><?= $this->session->flashdata('success') ?></div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert error"><?= $this->session->flashdata('error') ?></div>
		<?php endif; ?>

		<!-- Form Tambah Participant -->
		<h3 class="f4">Add Participant</h3>
		<form method="POST" class="add-mp-form" action="<?= base_url('Admin/collaboration/add_participant/' . rawurlencode(base64_encode($content->id_file))); ?>">
			<div class="add-mp-inp">
				<div class="form-group">
					<label for="id_user"><i class="bx bx-user"></i></label>
					<select name="id_user" required>
						<option value="">-- Pilih --</option>
						<?php foreach ($users as $user): ?>
							<option value="<?= $user['id_user'] ?>"><?= $user['name'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="part_label"><i class="bx bxs-videos"></i></label>
					<input type="text" placeholder="Part Label" name="part_label">
				</div>
			</div>

			<button type="submit" class="btn-add-mp"><i class="bx bx-plus"></i></button>
		</form>

		<!-- Daftar Participant -->
		<h3 class="f4 mt-2">List Participant</h3>
		<div class="participants-container mt-1">
			<?php foreach ($participants as $p): ?>
				<div class="participant-card2">
					<!-- Tooltip -->
					<span class="tooltip-text"><?= htmlspecialchars($p['part_label'] ?? '-') ?></span>
					<div class="participant-info">
						<?php if (!empty($p['profile_picture']) && file_exists('assets/uploads/Profil/' . $p['profile_picture'])): ?>
							<img src="<?= base_url('assets/uploads/Profil/' . $p['profile_picture']) ?>" alt="Foto Profil" class="participant-avatar">
						<?php else: ?>
							<img src="<?= base_url('assets/uploads/Profil/default.jpg') ?>" alt="Foto Default" class="participant-avatar">
						<?php endif; ?>

						<div class="participant-name">
							<?= $p['name'] ?? 'User #' . $p['id_user'] ?>
						</div>
					</div>

					<div class="participant-actions">
						<a href="<?= base_url('Admin/collaboration/delete_participant/' . rawurlencode(base64_encode($content->id_file)) . '/' . $p['id_user']) ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus participant ini?')"><i class="bx bx-trash"></i></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
