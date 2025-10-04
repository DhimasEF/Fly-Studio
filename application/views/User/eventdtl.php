<?php
    $today = date('Y-m-d');
    $is_event_ended = strtotime($event->end_date) < strtotime($today);
    $is_event_started = strtotime($event->start_date) <= strtotime($today);
    $is_event_active = $is_event_started && !$is_event_ended;
    $category_name = strtolower($event->category_name);
    $is_special_event = in_array($category_name, ['mep', 'battle', 'collab']);
    $is_recruitment = ($category_name === 'recruitment');
?>
			
<div class="content">
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-bell'></i>Event / <span class="text2"><?= htmlspecialchars($event->event_name ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></span></h1>
		<!-- Tombol edit untuk admin -->
		<?php if (!$is_event_ended && $user->role === 'admin'): ?>
			<a href="<?= site_url('admin/event/edit/' . rawurlencode(base64_encode($event->id_event))); ?>" class="edit-btn">
				<i class='bx bx-edit'></i>Edit Event
			</a>
		<?php endif; ?>
	</div>


<div class="event-grid">
    <div class="event-dtl-container1 mt-2">
        <?php
			$banner_file = !empty($event->banner) && file_exists(FCPATH . 'assets/uploads/FileEvent/Banner/' . $event->banner)
				? $event->banner
				: 'def.jpg';

			$banner_url = base_url('assets/uploads/FileEvent/Banner/' . $banner_file);
		?>

		<div class="banner-eventdtl" style="background-image: url('<?= $banner_url ?>');">
			<div class="event-paragraph">
				<div class="eventdtl-header">
					<div class="">
						<span><?= $event->event_name; ?></span>
						<?php
							$start = strtotime($event->start_date);
							$end = strtotime($event->end_date);

							// Pecah bagian tanggal
							$startDay   = date('d', $start);
							$startMonth = date('M', $start);
							$startYear  = date('Y', $start);
							$endDay     = date('d', $end);
							$endMonth   = date('M', $end);
							$endYear    = date('Y', $end);

							if ($startYear === $endYear) {
								if ($startMonth === $endMonth) {
									if ($startDay === $endDay) {
										// Tanggal sama persis
										$tanggal = "$startDay $startMonth $startYear";
									} else {
										// Hanya hari beda
										$tanggal = "$startDay – $endDay $startMonth $startYear";
									}
								} else {
									// Bulan beda
									$tanggal = "$startDay $startMonth – $endDay $endMonth $startYear";
								}
							} else {
								// Tahun juga beda
								$tanggal = "$startDay $startMonth $startYear – $endDay $endMonth $endYear";
							}
						?>

						<small class="text2 f6"><em>(<?= $tanggal ?>)</em></small>
					</div>
					<span class="stat-event"><?= htmlspecialchars($event->dynamic_status); ?></span>
				</div>
				<span class="f5"><?= $event->description; ?></span>
			</div>
		</div>
			
			<?php if ($this->session->flashdata('success')): ?>
				<p class="text-green"><?= $this->session->flashdata('success'); ?></p>
			<?php endif; ?>
			<?php if ($this->session->flashdata('error')): ?>
				<p class="text-red"><?= $this->session->flashdata('error'); ?></p>
			<?php endif; ?>
			
			<?php if ($is_special_event): ?>
				<div class="justify-between mt-2">
					<div class="start-middle">
						<span><i class="bx bxs-user"></i> 
						<?= $current_participants; ?> / <?= $event->max_participants; ?> Participants</span>
					</div>
					
					<?php if (!$is_event_ended): ?>
						<?php if ($has_joined): ?>
							<a href="<?= site_url('User/event/leave/' . rawurlencode(base64_encode($event->id_event))); ?>" onclick="return confirm('Are you sure you want to leave this event?')">
								<button class="btn-lj gap-1">
									<i class='bx bx-log-out'></i> Leave
								</button>
							</a>
						<?php elseif ($current_participants < $event->max_participants): ?>
							<a href="<?= site_url('User/event/join/' . rawurlencode(base64_encode($event->id_event))); ?>" onclick="return confirm('Are you sure you want to join this event?')">
								<button class="btn-lj gap-1">
									<i class='bx bx-log-in'></i> Join
								</button>
							</a>
						<?php else: ?>
							<span class="text-red f5"><em>This event is full</em></span>
						<?php endif; ?>
					<?php else: ?>
						<span class="text-gray f5"><em>This event has ended</em></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

            <?php if (!empty($participants)): ?>
				<div class="participants-container mt-1">
					<?php foreach ($participants as $p): ?>
						<div class="participant-card2">
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
						</div>
					<?php endforeach; ?>
				</div>
			<?php elseif (!$is_recruitment): ?>
				<p class="f5 text-gray"><em>No participants yet.</em></p>
			<?php endif; ?>
            <?php if ($is_recruitment && $is_event_active): ?>
				<div class="justify-between mt-2">
					<?php if ($this->session->userdata('role') === 'admin'): ?>
					<span>Go to List Recruitment</span>
						<a href="<?= site_url('admin/listrecruitment/list'); ?>">
							<button class="btn-lj gap-1">
								<i class='bx bx-list-ul'></i> Go to List
							</button>
						</a>
					<?php else: ?>
					<span>Go to Apply</span>
						<a href="<?= site_url('User/recruitment/form/' . rawurlencode(base64_encode($event->id_event))); ?>">
							<button class="btn-lj gap-1">
								<i class='bx bx-send'></i> Apply
							</button>
						</a>
					<?php endif; ?>
				</div>
			<?php elseif ($is_recruitment && !$is_event_started): ?>
				<p class="mt-2 f5 text-gray"><em>This recruitment event has not started yet.</em></p>
			<?php elseif ($is_recruitment && $is_event_ended): ?>
				<p class="mt-2 f5 text-gray"><em>This recruitment event has ended.</em></p>
			<?php endif; ?>
			
			<?php if ($is_recruitment): ?>
				<?php if (!empty($user_recruitment)): ?>
					<div class="participants-container mt-2">
						<div class="participant-card2">
							<?php
								$tooltip = "<strong>Event:</strong> " . htmlspecialchars($user_recruitment['event_name'] ?? '-') . "<br>" .
										   "<strong>Reason:</strong> " . htmlspecialchars($user_recruitment['reason_text']) . "<br>" .
										   "<strong>Decision:</strong> " . (!empty($user_recruitment['decision_at']) ? date('d M Y', strtotime($user_recruitment['decision_at'])) : '-') . "<br>" .
										   "<strong>By:</strong> " . ($user_recruitment['admin_name'] ?? '-');
							?>
							<span class="tooltip-text2"><?= $tooltip ?></span>

							<!-- Profil dan Nama -->
							<div class="participant-info">
								<?php if (!empty($user_recruitment['profile_picture']) && file_exists('assets/uploads/Profil/' . $user_recruitment['profile_picture'])): ?>
									<img src="<?= base_url('assets/uploads/Profil/' . $user_recruitment['profile_picture']) ?>" class="participant-avatar" alt="Profile">
								<?php else: ?>
									<img src="<?= base_url('assets/uploads/Profil/default.jpg') ?>" class="participant-avatar" alt="Default">
								<?php endif; ?>

								<div class="participant-name"><?= htmlspecialchars($user_recruitment['user_name']) ?></div>
							</div>

							<!-- Work Link -->
							<div class="participant-action">
								<a href="<?= $user_recruitment['work_url'] ?>" target="_blank" class="edit-btn2">
									<i class="bx bx-link"></i>
								</a>
							</div>

							<!-- Status -->
							<div class="participant-decision">
								<span class="f5 <?= $user_recruitment['status'] == 'approved' ? 'text-green' : ($user_recruitment['status'] == 'rejected' ? 'text-red' : 'text-gray') ?>">
									<i class="bx <?= $user_recruitment['status'] == 'approved' ? 'bx-check' : ($user_recruitment['status'] == 'rejected' ? 'bx-x' : 'bx-time') ?>"></i>
									<?= ucfirst($user_recruitment['status']) ?>
								</span>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>



    </div>

    <!-- Container Kanan -->
    <div class="event-dtl-container2 mt-2">
		<?php if ($user->role === 'admin'): ?>
			<span class="event-title">Upload Files</span>
			<!-- Form Upload -->
			<form class="event-upload-form" action="<?= site_url('admin/event/add_files/' . rawurlencode(base64_encode($event->id_event))); ?>" method="post" enctype="multipart/form-data">
				<input type="file" name="event_file" class="upload-input" required>
				<button type="submit" class="upload-button">
					<i class='bx bx-upload'></i> Upload
				</button>
			</form>
		<?php endif; ?>

		<!-- File List -->
		<span class="event-title">Files Event</span>

		<?php if (!empty($event_files)): ?>
			<ul class="event-file-list">
				<?php foreach ($event_files as $file): ?>
					<li class="event-file-item">
						<div class="file-info f5">
							<a href="<?= $file->file_url; ?>" target="_blank" class="file-name"><?= htmlspecialchars($file->file_name); ?></a>
							<span class="file-size"><?= $file->file_size; ?> KB</span>
						</div>
						<a href="<?= $file->file_url; ?>" download class="download-button">
							<i class='bx bx-download'></i>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p class="f5 text-gray"><em>No files uploaded for this event yet.</em></p>
		<?php endif; ?>

	</div>

</div>
