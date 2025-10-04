<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/profil') ?>"><i class='bx bx-user'></i> Edit Profil</a> /
			<span class="text2"> <?= $user->name; ?> 
			</span>
		</h1>
    </a>
	</div>
	
	<div class="mt-2">
		<!-- Flash Message -->
		<?php if ($this->session->flashdata('error')): ?>
			<p class="text-red"><?= $this->session->flashdata('error'); ?></p>
		<?php endif; ?>
		<?php if ($this->session->flashdata('success')): ?>
			<p class="text-green"><?= $this->session->flashdata('success'); ?></p>
		<?php endif; ?>

		<form action="<?= site_url('Admin/profil/update/' . rawurlencode(base64_encode($user->id_user))) ?>" method="POST" enctype="multipart/form-data" class="trans-edit add-content">

			<div class="form-group">
				<label for="email"><i class='bx bx-envelope'></i></label>
				<input type="email" name="email" id="email" value="<?= htmlspecialchars($user->email) ?>" required>
			</div>

			<div class="form-group password-wrapper">
				<label for="password"><i class='bx bx-lock-alt'></i></label>
				<input type="password" name="password" id="password" placeholder="New password (optional)">
				<i class='bx bx-show toggle-eye' id="togglePassword"></i>
			</div>

			<div class="form-group">
				<label for="name"><i class='bx bx-user'></i></label>
				<input type="text" name="name" id="name" value="<?= htmlspecialchars($user->name) ?>" required>
			</div>

			<div class="form-group">
				<label for="profile_picture"><i class='bx bx-image'></i></label>
				<?php if (!empty($user->profile_picture)): ?>
					<div class="preview-image mb-1">
						<img src="<?= base_url('assets/uploads/Profil/' . $user->profile_picture) ?>" alt="Profile Picture" width="100">
					</div>
				<?php endif; ?>
				<input type="file" name="profile_picture" id="profile_picture">
			</div>
			
			<div class="form-group">
                <label for="description"><i class='bx bx-comment-detail'></i></label>
                <textarea name="description" id="description" required placeholder="Deskripsi Tim"><?= htmlspecialchars($user->description) ?></textarea>
            </div>

			<button type="submit"><i class='bx bx-save'></i> Update</button>
		</form>
	</div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const pwd = document.getElementById('password');
    const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
    pwd.setAttribute('type', type);
    this.classList.toggle('bx-show');
    this.classList.toggle('bx-hide');
});
</script>
