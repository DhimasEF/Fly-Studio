<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-chat'></i> Create GrupRoom</h1>
	</div>
	
   <div class="mt-2">
    <form method="POST" action="<?= site_url('Admin/chat/storeGroup'); ?>" class="trans-edit add-content">
        
        <div class="form-group">
            <label for="group_name" title="Group Name"><i class='bx bx-group'></i></label>
            <input type="text" id="group_name" name="group_name" placeholder="Enter group name..." required>
        </div>

        <div class="form-group" style="flex-direction: column; align-items: start;">
            <label title="Select Members"><i class='bx bx-user-check'></i> <span class="f4">Select Members </span></label>
            <div class="member-list">
                <?php foreach ($users as $index => $user): ?>
                    <label class="member-card" for="user_<?= $index; ?>">
						<input type="checkbox" id="user_<?= $index; ?>" name="user_id[]" value="<?= $user['id_user']; ?>" class="member-checkbox">

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

        <div class="form-group" style="justify-content: space-between;">
            <button type="submit"><i class='bx bx-send'></i> Create Group</button>
        </div>
    </form>
</div>


    <script>
        // Toggle selected style when checkbox changes
        document.querySelectorAll('.member-card input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                this.closest('.member-card').classList.toggle('selected', this.checked);
            });
        });

        // Apply selected style on load
        window.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.member-card input[type="checkbox"]').forEach(function (checkbox) {
                if (checkbox.checked) {
                    checkbox.closest('.member-card').classList.add('selected');
                }
            });
        });
    </script>