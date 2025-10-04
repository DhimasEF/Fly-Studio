<div class="content">
	<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-user'></i>List User</h1>
	</div>
	
	<div class="col-12 mt-2">
		<form action="<?= base_url('Admin/user/search'); ?>" method="get" class="search-form">
			<input type="text" name="keyword" placeholder="Cari user..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
			<button type="submit">Search</button>
			<a href="<?= base_url('Admin/user/search'); ?>">Reset</a> <!-- Tombol reset -->
		</form>

		<div class="mt-2 creator-container">
		<?php if (!empty($users)) : ?>
			<?php foreach ($users as $user): ?>
				<div class="creator-card">
					<a href="<?= base_url('Admin/user/detail/' . rawurlencode(base64_encode($user->id_user))); ?>">
						<img src="<?= !empty($user->profile_picture) 
							? base_url('assets/uploads/Profil/' . $user->profile_picture) 
							: base_url('assets/uploads/Profil/default.jpg'); ?>" 
							alt="<?= $user->name; ?>" class="profile-pic">
						<h3><?= $user->name; ?></h3>
					</a>
					<p class="f5"><?= date('d M Y', strtotime($user->created_at)); ?></p>

					<div class="social-icons">
						<a href="mailto:<?= $user->email; ?>">
							<i class="bx bx-envelope"></i>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<p>Tidak ada data ditemukan.</p>
		<?php endif; ?>
	</div>
</div>