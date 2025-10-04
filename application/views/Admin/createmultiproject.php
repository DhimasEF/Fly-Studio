<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-videos'></i> Create Collaboration</h1>
	</div>

	<div class="mt-2">
		<div class="add-content">
		<?php if ($this->session->flashdata('success')): ?>
			<div style="color: green;"><?= $this->session->flashdata('success'); ?></div>
		<?php elseif ($this->session->flashdata('error')): ?>
			<div style="color: red;"><?= $this->session->flashdata('error'); ?></div>
		<?php endif; ?>

		<form action="<?= base_url('Admin/collaboration/store') ?>" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="title"><i class='bx bx-edit'></i></label>
				<input type="text" name="title" id="title" maxlength="100" required>
			</div>
			
			<div class="form-group">
				<label for="description"><i class='bx bx-detail'></i></label>
				<textarea class="f5" name="description" id="description" maxlength="500"></textarea>
			</div>
			
			<div class="form-group">
				<label for="file_path"><i class='bx bx-video'></i></label>
				<input id="file_path" type="file" name="file_path" accept=".jpg,.jpeg,.png,.mp4,.avi,.mkv" required>
			</div>
			
			<!--<div class="form-group">
				<i class='bx bx-user'></i>
				<div class="participant-grid">
					<?php foreach ($users as $user): ?>
						<label class="participant-card">
							<input type="checkbox" name="participants[]" value="<?= htmlspecialchars($user['id_user']) ?>">
							<div class="profile-img" style="background-image: url('<?= base_url('assets/uploads/Profil/' . ($user['profile_picture'] ?? 'default.jpg')) ?>');"></div>
							<div class="caption"><?= htmlspecialchars($user['name']) ?></div>
						</label>
					<?php endforeach; ?>
				</div>
			</div>-->

			<button type="submit"><i class='bx bx-upload'></i> Save Collaboration</button>
		</form>
	</div>
</div>
