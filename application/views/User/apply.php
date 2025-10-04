<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<i class="bx bx-bell"></i> Apply Recruitment
		</h1>
	</div>
	
	<div class="mt-2">
		<div class="trans-edit add-content">
			<!-- Flashdata -->
			<?php if ($this->session->flashdata('success')): ?>
				<div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
			<?php elseif ($this->session->flashdata('error')): ?>
				<div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
			<?php endif; ?>

			<!-- Hitung tanggal -->
			<?php
				$start = strtotime($event->start_date);
				$end = strtotime($event->end_date);

				$startDay   = date('d', $start);
				$startMonth = date('M', $start);
				$startYear  = date('Y', $start);
				$endDay     = date('d', $end);
				$endMonth   = date('M', $end);
				$endYear    = date('Y', $end);

				if ($startYear === $endYear) {
					if ($startMonth === $endMonth) {
						$tanggal = ($startDay === $endDay) ? "$startDay $startMonth $startYear" : "$startDay – $endDay $startMonth $startYear";
					} else {
						$tanggal = "$startDay $startMonth – $endDay $endMonth $startYear";
					}
				} else {
					$tanggal = "$startDay $startMonth $startYear – $endDay $endMonth $endYear";
				}
			?>

			<!-- Form -->
			<form action="<?= site_url('User/recruitment/apply/' . rawurlencode(base64_encode($event->id_event))) ?>" method="post">
				<?php if (isset($application)): ?>
					<input type="hidden" name="id_recruit" value="<?= $application->id_recruit ?>">
				<?php endif; ?>

				<!-- Event Name -->
				<div class="form-group index-icon">
					<i class="bx bx-calendar-event"></i>
					<input type="text" value="<?= $event->event_name ?>" readonly>
				</div>

				<!-- Event Period -->
				<div class="form-group index-icon">
					<i class="bx bx-time-five"></i>
					<input type="text" value="<?= $tanggal ?>" readonly>
				</div>

				<!-- Work URL -->
				<div class="form-group index-icon">
					<i class="bx bx-link"></i>
					<input type="text" name="work_url" placeholder="Link hasil edit kamu"
						value="<?= set_value('work_url', isset($application) ? $application->work_url : '') ?>" required>
				</div>
				<?= form_error('work_url', '<div class="text-danger small">', '</div>') ?>

				<!-- Reason -->
				<div class="form-group index-icon">
					<i class="bx bx-comment-detail"></i>
					<textarea name="reason_text" rows="4" placeholder="Kenapa kamu ingin bergabung?" required><?= set_value('reason_text', isset($application) ? $application->reason_text : '') ?></textarea>
				</div>
				<?= form_error('reason_text', '<div class="text-danger small">', '</div>') ?>

				<!-- Submit -->
				<div class="form-group index-icon">
					<button type="submit" class="btn btn-primary">
						<i class="bx bx-send"></i> Apply
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
