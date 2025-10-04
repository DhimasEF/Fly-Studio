<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-videos'></i> List Content</h1>
		<a class="create-room" href="<?= base_url('Creator/content/upload_form'); ?>">
			<i class='bx bx-plus'></i> Upload Content
		</a>
	</div>
	
	<div class="col-12 mt-2">
		<?php
			$current_page = $this->uri->segment(2); // ambil nama controller
			$is_collab = ($current_page === 'collaboration');
		?>
		<div class="content-nav">
			<a href="<?= base_url('Creator/content') ?>" class="<?= !$is_collab ? 'active' : '' ?>">
				<i class='bx bx-play'></i> Content
			</a>
			<a href="<?= base_url('Creator/collaboration') ?>" class="<?= $is_collab ? 'active' : '' ?>">
				<i class='bx bx-group'></i> Collaboration
			</a>
		</div>
	</div>
	
	<div class="mt-2">
		<?php if (!empty($contents)): ?>
			<div class="content-grid">
				<?php foreach ($contents as $content): ?>
					<div class="content-cardc">
						<?php if ($content['file_type'] == 'Image'): ?>
							<img src="<?= base_url('assets/uploads/Content/' . $content['file_name']) ?>" alt="<?= htmlspecialchars($content['title']) ?>">
						<?php else: ?>
							<video controls poster="<?= base_url('assets/uploads/Content/Thumbnail/' . $content['thumbnail']) ?>">
								<source src="<?= base_url('assets/uploads/Content/' . $content['file_name']) ?>" type="video/mp4">
								Your browser does not support the video tag.
							</video>
						<?php endif; ?>

						<div class="content-meta">
							<?php if (!empty($content['profile_picture']) && file_exists('assets/uploads/Profil/' . $content['profile_picture'])): ?>
								<img src="<?= base_url('assets/uploads/Profil/' . $content['profile_picture']); ?>" alt="Foto Profil" class="ctn-img">
							<?php else: ?>
								<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctn-img">
							<?php endif; ?>
							
							<div class="meta-box">
								<h2 class="f3 m-0"><?= htmlspecialchars($content['title']) ?></h2>
								<div class="meta-data f5">
									<span><?= htmlspecialchars($content['name']) ?></span>
									<span id="like-count-<?= $content['id_content'] ?>"><i class='bx bxs-heart'></i><?= $content['like_count'] ?></span>
									<span><i class='bx bx-play'></i><?= $content['view_count'] ?></span>
								</div>
							</div>
							<a href="<?= base_url('Creator/content/view/' . rawurlencode(base64_encode($content['id_content']))); ?>">
								<button class="content-btn-list">View Details</button>
							</a>

						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p>No content available.</p>
		<?php endif; ?>
	</div>
</div>