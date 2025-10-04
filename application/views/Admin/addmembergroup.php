<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= site_url('Admin/chat'); ?>">
				<i class='bx bx-chat'></i> Add Members to Group
			</a> / 
			<a class="text2" href="<?= site_url('Admin/chat/groom/' . $room_id); ?>">
				<?= htmlspecialchars($room_id); ?>
			</a>
		</h1>
	</div>

	<div class="mt-2">
		<form method="POST" action="<?= site_url('Admin/chat/storeMembers/' . $room_id); ?>" class="trans-edit add-content">

			<div class="form-group" style="flex-direction: column; align-items: start;">
				<label title="Select Members"><i class='bx bx-user-check'></i> <span class="f4">Select Members</span></label>
				
				<div class="member-list">
					<?php foreach ($users as $index => $user): ?>
						<label class="member-card" for="user_<?= $index; ?>">
							<input type="checkbox" id="user_<?= $index; ?>" name="user_ids[]" value="<?= $user['id_user']; ?>" class="member-checkbox">
							
							<div class="member-avatar">
								<img src="<?= base_url('assets/uploads/Profil/' . ($user['profile_picture'] ?? 'default.jpg')); ?>" alt="Profile">
							</div>
							
							<div class="member-info">
								<span class="member-label"><?= htmlspecialchars($user['name']); ?></span>
								<span class="member-email"><?= htmlspecialchars($user['email']); ?></span>
							</div>
						</label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="form-group">
				<button type="submit"><i class="bx bx-plus"></i> Add Selected Members</button>
			</div>
		</form>
	</div>
</div>

<script>
    // Toggle style saat checkbox dicentang
    document.querySelectorAll('.member-card input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            this.closest('.member-card').classList.toggle('selected', this.checked);
        });
    });

    // Terapkan class selected saat load jika sudah dicentang
    window.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.member-card input[type="checkbox"]').forEach(function (checkbox) {
            if (checkbox.checked) {
                checkbox.closest('.member-card').classList.add('selected');
            }
        });
    });
</script>
