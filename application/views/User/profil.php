<div class="content">
	<div class="bg justify-between align-center">
		<h1 class="f3">Dashboard / <span class="text2"><?= htmlspecialchars($user->name ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></span></h1>
		 <!-- Tombol Edit Profil -->
        <a href="<?= site_url('User/profil/edit/' . rawurlencode(base64_encode($user->id_user))); ?>" class="edit-btn">
            <i class='bx bx-edit'></i> Edit Profil
        </a>
	</div>
	
    <div class="prof-grid mt-2">
		<div class="prof-container">
			<?php if (!empty($user)): ?>
			<div class="prof-bg1">
				<!-- Tampilkan Gambar Profil Jika Ada -->
				<div class="relative pd-1 col-3 col align-center">
					<?php if (!empty($user->profile_picture) && file_exists('assets/uploads/Profil/' . $user->profile_picture)): ?>
						<img src="<?= base_url('assets/uploads/Profil/' . $user->profile_picture); ?>" alt="Foto Profil" class="prof-img">
					<?php else: ?>
						<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="prof-img">
					<?php endif; ?>
					<div class="row badge-name">
						<?php
							$role = strtolower($user->role ?? 'user');
							$roleIcon = '';

							switch ($role) {
								case 'admin':
									$roleIcon = "<i class='bx bxs-crown' style='color: black;'></i>";
									break;
								case 'creator':
									$roleIcon = "<i class='bx bxs-videos' style='color: black;'></i>";
									break;
								case 'user':
								default:
									$roleIcon = "<i class='bx bxs-user' style='color: black;'></i>";
									break;
							}
						?>

						<h1 class="f5 badge-content">
							<span class="icon-area"><?= $roleIcon ?></span>
							<span class="name-area"><?= htmlspecialchars($user->name ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></span>
						</h1>
					</div>
				</div>
			
				<div class="pd-1 col-9">
					<div class="stat-row">
						<div class="middle">
							<div class="stat-box">
								<span class="f5">Transaction</span>
								<p><?= $total_transactions ?></p>
							</div>
						</div>
						<div class="middle">
							<div class="stat-box">
								<span class="f5">Collab</span>
								<p><?= $total_collaborations ?></p>
							</div>
						</div>
						<div class="middle">
							<div class="stat-box">
								<span class="f5">Event</span>
								<p><?= $total_events ?></p>
							</div>
						</div>
					</div>	
					<div class="ml-25">
						<?php if ($user->id_user == $this->session->userdata('user_id')): ?>
							<!-- Jika sedang melihat profil sendiri -->
							<div class="gap-1">
								<i class="bx bx-envelope"></i>
								<small><em><?= htmlspecialchars($user->email) ?></em></small>
							</div>
						<?php else: ?>
							<div class="gap-1">
								<!-- Profil orang lain -->
								<!-- Tombol Follow / Unfollow -->
								<a href="<?= base_url('User/creator/' . ($is_following ? 'unfollow' : 'follow') . '/' . $encoded_id); ?>" 
								   class="follow-btn" 
								   title="<?= $is_following ? 'Unfollow' : 'Follow'; ?>">
									<i class='bx <?= $is_following ? 'bxs-heart' : 'bx-heart'; ?>'></i>
								</a>

								<!-- Tombol Email -->
								<a href="mailto:<?= htmlspecialchars($user->email) ?>" title="Email">
									<i class="bx bx-envelope"></i>
								</a>

								<!-- Tombol Chat -->
								<a href="<?= base_url('User/chat/request/' . $user->id_user); ?>" title="Chat">
									<i class="bx bx-chat"></i>
								</a>
							</div>
						<?php endif; ?>

					</div>
				</div>	
			</div>

			
			<div class="row mt-2">
				<div class="col-3">
					<div class="relative f5">
						<span class="list-title bgc2">Joined</span>
						<div class="f4 bg-post bgc1"><?= date('d M Y', strtotime($user->created_at)); ?>
						</div>
					</div>
					<?php else: ?>
						<p>Data pengguna tidak ditemukan.</p>
					<?php endif; ?>
				</div>
			</div>
			
			<h3 class="mt-2">Followed Event</h3>
			<?php if (!empty($events)): ?>
				<div class="event-carousel-wrapper">

					<?php if (count($events) > 1): ?>
						<button class="carousel-btn left" onclick="scrollCarousel(-1)">‹</button>
					<?php endif; ?>

					<div class="event-carousel" id="eventCarousel">
						<?php foreach ($events as $event): ?>
							<?php
								$banner_file = !empty($event['banner']) && file_exists(FCPATH . 'assets/uploads/FileEvent/Banner/' . $event['banner'])
									? $event['banner']
									: 'def.jpg';

								$banner_url = base_url('assets/uploads/FileEvent/Banner/' . $banner_file);
							?>
						<div class="event-item" style="background-image: url('<?= $banner_url ?>');">
							<div class="event-overlay">
								<strong><?= htmlspecialchars($event['event_name']) ?></strong><br>
								<?php
									$start = strtotime($event['start_date']);
									$end = strtotime($event['end_date']);

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

							</div>
						</div>
						<?php endforeach; ?>
					</div>

					<?php if (count($events) > 1): ?>
						<button class="carousel-btn right" onclick="scrollCarousel(1)">›</button>
					<?php endif; ?>

				</div>
			<?php else: ?>
				<p>Belum mengikuti event.</p>
			<?php endif; ?>
		</div>

		<div class="prof-container">
		<!-- Daftar Kolaborasi -->
				<div class="justify-between">
					<h3>Joined Collaborations</h3>
					<div class="see-more-wrap">
						<a href="<?= site_url('User/collaboration'); ?>" class="see-more-btn">See more <i class="bx bx-chevron-right"></i></a>
					</div>
				</div>
				<?php if (!empty($collaborations)): ?>
					<div class="card-grid">
						<?php foreach ($collaborations as $collab): ?>
						<div class="content-card">
							<?php if ($collab['file_type'] == 'Image'): ?>
								<img src="<?= base_url('assets/uploads/MultiProject/' . $collab['file_name']) ?>" width="200">
							<?php else: ?>
								<video width="200" controls poster="<?= base_url('assets/uploads/MultiProject/Thumbnail/' . $collab['thumbnail']) ?>">
									<source src="<?= base_url('assets/uploads/MultiProject/' . $collab['file_name']) ?>" type="video/mp4">
								</video>
							<?php endif; ?>
							
							<div class="prof-content-meta">
								<img src="<?= base_url('assets/uploads/Profil/Grup/defaultgrup.png'); ?>" alt="Foto Default" class="ctn-prof-img">
								<div class="meta-box">
									<h2 class="f4 m-0"><?= htmlspecialchars($collab['title']) ?></h2>
									<div class="meta-data f5">
										<span><i class='bx bxs-heart'></i><?= $collab['like_count'] ?></span>
										<span><i class='bx bx-group'></i><?= $collab['participant_count'] ?></span>
										<span><i class='bx bx-play'></i><?= $collab['view_count'] ?></span>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				<?php else: ?>
					<p>Belum ada kolaborasi yang diikuti.</p>
				<?php endif; ?>
		</div>
	</div>
</div>


<script>
$(document).ready(function() {
	// Buka Modal
	$('#openModal').on('click', function() {
		$('#medsosModal').fadeIn().css('display', 'flex');
	});

	// Tutup Modal
	$('#closeModal').on('click', function() {
		$('#medsosModal').fadeOut();
	});

	// Klik luar modal
	$('#medsosModal').on('click', function(e) {
		if ($(e.target).is('#medsosModal')) {
			$(this).fadeOut();
		}
	});

	// Tambah medsos
	$('#formAdd').on('submit', function(e) {
		e.preventDefault();
		$.ajax({
			url: '<?= site_url("User/profil/add_medsos") ?>',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				let res = JSON.parse(response);
				if (res.status) {
					location.reload(); // Refresh halaman
				} else {
					alert(res.message);
				}
			},
			error: function(xhr) {
				alert('Gagal menambahkan: ' + xhr.responseText);
			}
		});
	});

	// Update medsos
	$(document).on('click', '.update', function() {
		let li = $(this).closest('li');
		let id = li.data('id');
		let url = li.find('.url-input').val();

		console.log("Update clicked", id, url); // Debug

		$.post('<?= site_url("User/profil/update_medsos/") ?>' + id, { url: url }, function(res) {
			alert('Berhasil diperbarui!');
		}).fail(function(xhr) {
			console.error("Update error:", xhr.responseText);
			alert("Gagal update");
		});
	});

	// Delete medsos
	$(document).on('click', '.delete', function() {
		console.log("Tombol delete diklik");

		let li = $(this).closest('li');
		let id = li.data('id');
		console.log("ID medsos:", id);

		let fullUrl = '<?= site_url("User/profil/delete_medsos/") ?>' + id;
		console.log("URL yang dikirim:", fullUrl);

		if (!confirm('Yakin ingin menghapus?')) return;

		$.ajax({
			url: fullUrl,
			type: 'POST',
			success: function(res) {
				console.log("Response:", res);
				let response = JSON.parse(res);
				if (response.status) {
					alert("Berhasil dihapus");
					location.reload(); // atau li.remove();
				} else {
					alert("Gagal menghapus: " + response.message);
				}
			},
			error: function(xhr, status, error) {
				console.error("AJAX Error:", xhr.responseText);
				alert("Terjadi kesalahan saat menghapus.");
			}
		});
	});

});
</script>

<script>
function scrollCarousel(direction) {
  const carousel = document.getElementById("eventCarousel");
  const scrollAmount = 180; // Adjust per event box width
  carousel.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}
</script>
