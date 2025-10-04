<div class="creator-container pd-container">
	<?php 
	$last_user = null;
	foreach ($creators as $index => $creator): 
		if ($last_user != $creator['id_user']): 
	?>
		<div class="creator-card">
			<!-- HAPUS TAG <a> AGAR TIDAK LINK KE DETAIL -->
			<img src="<?= base_url('assets/uploads/Profil/'.$creator['profile_picture']); ?>" alt="<?= $creator['name']; ?>" class="profile-pic">
			<h3><?= $creator['name']; ?></h3>
			<p class="f4"><em><?= $creator['team_name']; ?></em></p>
			<p class="f5"><?= date('d M Y', strtotime($creator['created_at'])); ?></p>
			<div class="social-icons">
				<?php 
					endif;

					// Tampilkan ikon medsos jika tersedia
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
