<div class="dash-hero" data-aos="fade-up">
	<h2>Welcome to Fly Studio</h2>
	<span class="f4 dash-hero-txt">Fly Studio is a vibrant AMV community where creativity meets collaboration. Join our team-based projects, participate in exciting events, and showcase your editing skills. Whether you're a seasoned editor or just starting out, there's a place for you here!</span>
	<div class="see-more" data-aos="fade-up">
		<a href="<?= base_url('signin') ?>" class="see-more-btn">Join us</a>
	</div>
</div>

<div class="dash-container">
	<div class="dash-section" data-aos="fade-up">
		<h2><i class="bx bxs-bell"></i> Top Events</h2>
		<p class="section-desc">Discover the latest and hottest events happening in our AMV community. Join competitions, MEPs, and more!</p>
		<div class="dash-grid">
			<?php foreach ($events as $event): ?>
				<?php
					$category_name = strtolower($event->category_name);
					$banner_url = !empty($event->banner) 
						? base_url('assets/uploads/FileEvent/Banner/' . $event->banner) 
						: base_url('assets/uploads/FileEvent/Banner/def.jpg');

					// Format tanggal
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
							if ($startDay === $endDay) {
								$tanggal = "$startDay $startMonth $startYear";
							} else {
								$tanggal = "$startDay – $endDay $startMonth $startYear";
							}
						} else {
							$tanggal = "$startDay $startMonth – $endDay $endMonth $startYear";
						}
					} else {
						$tanggal = "$startDay $startMonth $startYear – $endDay $endMonth $endYear";
					}
				?>
				<div class="dash-card event-glow" data-aos="fade-up">
					<div class="dash-event" style="background-image: url('<?= $banner_url ?>');">
						<div class="dash-overlay">
							<h3><?= htmlspecialchars($event->event_name); ?></h3>
							<p>
								<?= htmlspecialchars($event->dynamic_status); ?>
								<?php if (in_array($category_name, ['mep', 'battle', 'collab'])): ?>
									• <i class="bx bxs-user"></i> <?= htmlspecialchars($event->participant_status); ?>
								<?php endif; ?>
							</p>
							<small><em><?= $tanggal ?></em></small>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="see-more" data-aos="fade-up">
			<a href="<?= base_url('event') ?>" class="see-more-btn">See All Events</a>
		</div>
	</div>
</div>

<div class="dash-hero2">
	<div class="dash-section" data-aos="fade-up">
		<h2><i class="bx bxs-palette"></i> Creators</h2>
		<p class="section-desc">Meet our talented editors and artists who contribute their creativity to every project. Follow and support them!</p>
		<div class="dash-grid">
			<?php foreach ($creators as $c): ?>
			<div class="dash-creator" data-aos="fade-up">
				<img src="<?= base_url('assets/uploads/Profil/' . $c->profile_picture); ?>" alt="<?= $c->name ?>">
				<div class="dash-creator-txt">
					<span class="f3 glow-txt"><?= $c->name ?></span>
					<span class="f5"><em><?= $c->team_name ?></em> <?= $c->total_followers ?> <i class="bx bxs-heart"></i></span>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="see-more" data-aos="fade-up">
			<a href="<?= base_url('creatorteam') ?>" class="see-more-btn">See More Creators</a>
		</div>
	</div>
</div>

<div class="dash-container">
	<div class="dash-section" data-aos="fade-up">
		<h2><i class="bx bxs-videos"></i> Popular Contents</h2>
		<p class="section-desc">Explore stunning AMVs and creative media from our community. Trending works that you don't want to miss!</p>
		<div class="dash-grid">
			<?php foreach ($contents as $ct): ?>
				<div class="dash-card media-hover-container" data-aos="fade-up">
					<?php if ($ct['file_type'] == 'Image'): ?>
						<img src="<?= base_url('assets/uploads/Content/' . $ct['file_name']) ?>" alt="<?= htmlspecialchars($ct['title']) ?>" class="media-thumb">
					<?php else: ?>
						<video muted loop preload="metadata" class="media-thumb" poster="<?= base_url('assets/uploads/Content/Thumbnail/' . $ct['thumbnail']) ?>">
							<source src="<?= base_url('assets/uploads/Content/' . $ct['file_name']) ?>" type="video/mp4">
						</video>
					<?php endif; ?>

					<div class="content-info">
						<?php if (!empty($ct['profile_picture']) && file_exists('assets/uploads/Profil/' . $ct['profile_picture'])): ?>
							<img src="<?= base_url('assets/uploads/Profil/' . $ct['profile_picture']); ?>" alt="Foto Profil" class="dash-ctn-img">
						<?php else: ?>
							<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="dash-ctn-img">
						<?php endif; ?>
							
						<div class="dash-meta-box">
							<span class="f3 glow-txt"><?= htmlspecialchars($ct['title']) ?></span>
							<div class="dash-meta-data f5">
								<span><?= htmlspecialchars($ct['name']) ?></span>
								<span id="like-count-<?= $ct['id_content'] ?>"><i class='bx bxs-heart'></i><?= $ct['like_count'] ?></span>
								<span><i class='bx bx-play'></i><?= $ct['view_count'] ?></span>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="see-more" data-aos="fade-up">
			<a href="<?= base_url('content') ?>" class="see-more-btn">See More Content</a>
		</div>
	</div>
</div>

<script src="<?= base_url('assets/js/aos.js'); ?>"></script>
<script>
	AOS.init({ duration: 1000, once: false, mirror: true });
</script>

<script>
  document.querySelectorAll('.media-hover-container video').forEach(video => {
    video.pause();
    video.closest('.media-hover-container').addEventListener('mouseenter', () => {
      video.play();
    });
    video.closest('.media-hover-container').addEventListener('mouseleave', () => {
      video.pause();
      video.currentTime = 0;
    });
  });
</script>


</body>
</html>

