<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-videos'></i>List Creator</h1>
	</div>
	
	<div class="col-12 mt-2">
		<form action="<?= base_url('User/creator/search'); ?>" method="get" class="search-form">
			<input type="text" name="keyword" placeholder="Cari creator..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
			<button type="submit">Search</button>
			<a href="<?= base_url('User/creator/search'); ?>">Reset</a>
		</form>

		<div class="mt-2 creator-container">
			<?php 
			$last_user = null;
			foreach ($creators as $index => $creator): 
				if ($last_user != $creator['id_user']): 
			?>
				<div class="creator-card">
					<a href="<?= base_url('User/creator/detail/' . rawurlencode(base64_encode($creator['id_user']))); ?>">
						<img src="<?= base_url('assets/uploads/Profil/'. ($creator['profile_picture'] ?? 'default.jpg')); ?>" alt="<?= $creator['name']; ?>" class="profile-pic">
						<h3>
							<?= $creator['name']; ?>
						</h3>
					</a>
						<p class="f4"><?= $creator['team_name']; ?></p>
						<p class="f5"><?= date('d M Y', strtotime($creator['created_at'])); ?></p>
					<div class="social-icons">
						<?php 
							endif;

							// Deteksi medsos jika ada dan tampilkan ikon Boxicon
							if (!empty($creator['platform']) && !empty($creator['url'])):
								$icon_class = '';
								switch (strtolower($creator['platform'])) {
									case 'youtube': $icon_class = 'bx bxl-youtube'; break;
									case 'facebook': $icon_class = 'bx bxl-facebook-square'; break;
									case 'instagram': $icon_class = 'bx bxl-instagram'; break;
									case 'twitter': $icon_class = 'bx bxl-twitter'; break;
									case 'linkedin': $icon_class = 'bx bxl-linkedin-square'; break;
									default: $icon_class = 'bx bx-world'; break;
								}
						?>
								<a href="<?= $creator['url']; ?>" target="_blank">
									<i class="<?= $icon_class ?>"></i>
								</a>
						<?php 
							endif;

							$next_creator = $creators[$index + 1] ?? null;
							if (!$next_creator || $next_creator['id_user'] != $creator['id_user']):
						?>
					</div>
				</div>
			<?php 
				endif;
				$last_user = $creator['id_user'];
			endforeach;
			?>
		</div>
	</div>
</div>