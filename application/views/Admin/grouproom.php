    <div id="chat-container">

        <!-- Member List -->
        <div id="member-list">
			<div class="mmb-list-top">
				<span class="f4 gap-1"><i class="bx bx-group"></i>Group Members</span>
				<?php if ($is_admin): ?>
					<a href="<?= site_url('Admin/chat/addMember/' . $room_id); ?>" class="no-deco">
						<i class='bx bx-plus'></i>
					</a>
				<?php endif; ?>
			</div>
            <ul>
                <?php if (!empty($group_members)): ?>
                    <?php foreach ($group_members as $member): ?>
                        <li>
                            <div class="member-group-info">
								<?php
									$profile = !empty($member['profile_picture']) ? $member['profile_picture'] : 'default.jpg';
								?>
								<img src="<?= base_url('assets/uploads/Profil/' . $profile); ?>" alt="Profile" class="chat-avatar">
								<div>
									<strong><?= htmlspecialchars($member['name']) ?></strong><br>
									<small>(<?= $member['status'] ?>)</small>
								</div>
							</div>
                            <?php if ($is_admin): ?>
                                <div class="member-actions">
                                    <?php if ($member['status'] == 'member'): ?>
                                        <a href="<?= site_url('Admin/chat/promote_to_Admin/' . $room_id . '/' . $member['id_user']) ?>" title="Make Admin">
                                            <i class='bx bxs-up-arrow-alt'></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($member['id_user'] != $current_user_id): ?>
                                        <a href="<?= site_url('Admin/chat/remove_member/' . $room_id . '/' . $member['id_user']) ?>" title="Remove Member">
                                            <i class='bx bxs-user-minus'></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No members in this group.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Message Section -->
        <div id="message-section">

            <!-- Chat Header -->
            <div class="chat-header">
                <?php $icon = !empty($group_icon) ? $group_icon : 'defaultgrup.png'; ?>
                <span class="gap-1">
                    <img src="<?= base_url('assets/uploads/Profil/Grup/' . $icon); ?>" alt="Group Icon"
                        class="chat-avatar" id="group-avatar-preview">
                    <span id="group-name-text"><?= htmlspecialchars($group_name) ?></span>
                </span>
                <?php if ($is_admin): ?>
                    <button id="change-icon-btn" title="Change Icon">
                        <i class='bx bx-edit'></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Pesan (diisi lewat AJAX) -->
            <ul id="message-list">
                <!-- Pesan akan dimuat via AJAX -->
            </ul>

            <!-- Send Form -->
            <form id="send-message-form" method="POST" action="<?= site_url('Admin/chat/send_message_group/' . $room_id); ?>">
                <input type="text" name="message_content" placeholder="Type your message..." required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
	
<!-- Modal -->
<div id="icon-upload-modal" style="display:none; background: rgba(0,0,0,0.6); position: fixed; top:0; left:0; width:100%; height:100%; z-index:1000;">
    <div style="background:#fff; padding:20px; width:300px; margin:100px auto; border-radius:10px; position:relative;">
        <h4>Edit Group Info</h4>
        <form id="icon-upload-form" enctype="multipart/form-data">
            <input type="hidden" name="room_id" value="<?= $room_id ?>">

            <div style="margin-bottom:10px;">
                <label for="group_name">Group Name</label><br>
                <input type="text" name="group_name" id="group_name" value="<?= htmlspecialchars($group_name) ?>" required style="width:100%; padding:5px;">
            </div>

            <div style="margin-bottom:10px;">
                <label for="icon">Group Icon</label><br>
                <input type="file" name="icon" id="icon" accept="image/*">
            </div>

            <div style="text-align:right;">
                <button type="submit">Save</button>
                <button type="button" onclick="document.getElementById('icon-upload-modal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

</body>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const changeBtn = document.getElementById('change-icon-btn');
    const modal = document.getElementById('icon-upload-modal');
    const form = document.getElementById('icon-upload-form');

    // Tampilkan modal
    if (changeBtn) {
        changeBtn.addEventListener('click', function () {
            modal.style.display = 'block';
        });
    }

    // Tangani submit form
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            fetch("<?= site_url('Admin/chat/ajax_update_group_icon') ?>", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    const timestamp = new Date().getTime(); // Hindari cache

                    if (res.new_icon_url) {
                        const avatar = document.getElementById('group-avatar-preview');
                        avatar.src = res.new_icon_url + '?t=' + timestamp;
                    }

                    if (res.new_group_name) {
                        const nameText = document.getElementById('group-name-text');
                        nameText.textContent = res.new_group_name;
                    }

                    alert('Group info updated!');
                    modal.style.display = 'none';
                } else {
                    alert('Update failed: ' + res.error);
                }
            })
            .catch(err => {
                alert('Upload error: ' + err);
            });
        });
    }
});
</script>

<script>
function loadMessages() {
    $.ajax({
        url: "<?= site_url('Admin/chat/fetch_messages_group/' . $room_id) ?>",
        method: "GET",
        success: function(data) {
            $('#message-list').html(data);
        }
    });
}

function scrollToBottom() {
    const msgList = document.getElementById("message-list");
    msgList.scrollTop = msgList.scrollHeight;
}

// Jalankan pertama kali
loadMessages();

// Polling tiap 3 detik
setInterval(loadMessages, 3000);

// Submit form via AJAX (tanpa reload)
$('#send-message-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: "POST",
        data: $(this).serialize(),
        success: function () {
            $('input[name="message_content"]').val('');
            loadMessages();
            scrollToBottom();
        }
    });
});
</script>

