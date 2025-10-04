<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-bell'></i>List Event</h1>
	</div>
	
	<div class="col-12 mt-2">
		<div class="scope-filter">
			<a href="<?= base_url('Creator/event'); ?>" class="filter-btn <?= ($scope == '') ? 'active' : ''; ?>">Intern</a>
			<a href="<?= base_url('Creator/event'); ?>?scope=all" class="filter-btn <?= ($scope == 'all') ? 'active' : ''; ?>">All</a>
		</div>
	</div>

	
	<div class="event-container">
		<?php foreach ($events as $event): ?>
			<?php

				$category_name = strtolower($event->category_name); // pastikan kamu sudah JOIN kategori di query
			?>
			<div class="event-card">
				<?php
					$banner_file = !empty($event->banner) && file_exists(FCPATH . 'assets/uploads/FileEvent/Banner/' . $event->banner)
						? $event->banner
						: 'def.jpg';

					$banner_url = base_url('assets/uploads/FileEvent/Banner/' . $banner_file);
				?>
				<div class="banner" style="background-image: url('<?= $banner_url ?>');">
					<div class="overlay">
						<h2><?= htmlspecialchars($event->event_name); ?></h2>

						<?php if (in_array($category_name, ['mep', 'battle', 'collab'])): ?>
							<p class="event-participants">Participants: <?= htmlspecialchars($event->participant_status); ?></p>
						<?php endif; ?>
						
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

						<small><em><?= $tanggal ?></em></small>

						<p><strong>Status:</strong> <?= htmlspecialchars($event->dynamic_status); ?></p>
						<a href="<?= site_url('Creator/event/detail/' . rawurlencode(base64_encode($event->id_event))); ?>" class="detail-button">View Details</a>
					</div>
				</div>
			</div>

		<?php endforeach; ?>
	</div>
</div>
</div>