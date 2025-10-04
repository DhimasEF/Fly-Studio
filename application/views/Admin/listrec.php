<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<i class="bx bx-bell"></i> List Recruitment
		</h1>
	</div>
	
	<?php if ($this->session->flashdata('success')): ?>
		<div class="text-green"><?= $this->session->flashdata('success') ?></div>
	<?php endif; ?>
	<?php if ($this->session->flashdata('error')): ?>
		<div class="text-red"><?= $this->session->flashdata('error') ?></div>
	<?php endif; ?>

	<div class="participants-container mt-2">
		<?php foreach ($recruitments as $recruitment): ?>
			<div class="participant-card2">
				<!-- Tooltip -->
				<?php
					$tooltip = "<strong>Event:</strong> " . htmlspecialchars($recruitment['event_name'] ?? '-') . "<br>" .
							   "<strong>Reason:</strong> " . htmlspecialchars($recruitment['reason_text']) . "<br>" .
							   "<strong>Decision:</strong> " . (!empty($recruitment['decision_at']) ? date('d M Y', strtotime($recruitment['decision_at'])) : '-') . "<br>" .
							   "<strong>By:</strong> " . ($recruitment['admin_name'] ?? '-');
				?>
				<span class="tooltip-text2"><?= $tooltip ?></span>

				<!-- Profil dan Nama -->
				<div class="participant-info">
					<?php if (!empty($recruitment['profile_picture']) && file_exists('assets/uploads/Profil/' . $recruitment['profile_picture'])): ?>
						<img src="<?= base_url('assets/uploads/Profil/' . $recruitment['profile_picture']) ?>" class="participant-avatar" alt="Profile">
					<?php else: ?>
						<img src="<?= base_url('assets/uploads/Profil/default.jpg') ?>" class="participant-avatar" alt="Default">
					<?php endif; ?>

					<div class="participant-name"><?= htmlspecialchars($recruitment['user_name'] ?? 'User #' . $recruitment['id_user']) ?></div>
				</div>

				<!-- Link Work -->
				<div class="participant-action">
					<a href="<?= $recruitment['work_url'] ?>" target="_blank" class="edit-btn2">
						<i class="bx bx-link"></i>
					</a>
				</div>

				<!-- Decision Button / Status -->
				<div class="participant-decision">
					<?php if ($recruitment['status'] == 'pending'): ?>
						<a href="<?= site_url('Admin/listrecruitment/update_status/' . $recruitment['id_recruit'] . '/approved') ?>"
							class="text-green">
							<i class="bx bx-check-circle"></i>
						</a>
						<a href="<?= site_url('Admin/listrecruitment/update_status/' . $recruitment['id_recruit'] . '/rejected') ?>"
							class="text-red">
							<i class="bx bx-x-circle"></i>
						</a>
					<?php else: ?>
						<span class="f5 <?= $recruitment['status'] == 'approved' ? 'text-green' : 'text-red' ?>">
							<i class="bx <?= $recruitment['status'] == 'approved' ? 'bx-check' : 'bx-x' ?>"></i>
							<?= ucfirst($recruitment['status']) ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
