<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-videos'></i> List Collaboration</h1>
	</div>
	
	<div class="col-12 mt-2">
		<?php
			$current_page = $this->uri->segment(2); // ambil nama controller
			$is_collab = ($current_page === 'collaboration');
		?>
		<div class="content-nav">
			<a href="<?= base_url('Creator/content') ?>" class="<?= !$is_collab ? 'active' : '' ?>">
				<i class='bx bx-play'></i> Biasa
			</a>
			<a href="<?= base_url('Creator/collaboration') ?>" class="<?= $is_collab ? 'active' : '' ?>">
				<i class='bx bx-group'></i> Kolaborasi
			</a>
		</div>
	</div>
	
	<div class=" mt-2">
		<?php if (!empty($contents)): ?>
			<div class="content-grid">
				<?php foreach ($contents as $content): ?>
					<div class="content-cardc">
						<?php if ($content['file_type'] == 'Image'): ?>
							<img src="<?= base_url('assets/uploads/MultiProject/' . $content['file_name']) ?>">
						<?php else: ?>
							<video controls poster="<?= base_url('assets/uploads/MultiProject/Thumbnail/' . $content['thumbnail']) ?>">
								<source src="<?= base_url('assets/uploads/MultiProject/' . $content['file_name']) ?>" type="video/mp4">
							</video>
						<?php endif; ?>

						<div class="content-meta">
							<img src="<?= base_url('assets/uploads/Profil/Grup/defaultgrup.png'); ?>" alt="Foto Default" class="ctn-img">
							<div class="meta-box">
								<h2 class="f3 m-0"><?= htmlspecialchars($content['title']) ?></h2>
								<div class="meta-data f5">
									<span><i class='bx bxs-heart'></i><?= $content['like_count'] ?></span>
									<span><i class='bx bx-group'></i><?= $content['participant_count'] ?></span>
									<span><i class='bx bx-play'></i><?= $content['view_count'] ?></span>
								</div>
							</div>
							<a href="<?= base_url('Creator/collaboration/view/' . rawurlencode(base64_encode($content['id_file']))); ?>">
								<button class="content-btn-list">View Details</button>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p>No collaboration content found.</p>
		<?php endif; ?>
	</div>
</div>