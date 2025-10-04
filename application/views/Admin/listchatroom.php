<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-chat'></i> List Chat</h1>
		<a class="create-room" href="<?= site_url('Admin/chat/createGroupRoom'); ?>">
			<i class='bx bx-group'></i> Create Room
		</a>
	</div>
	
	<div class="col-12 mt-2">
		<!-- Tombol Filter -->
		<div class="chat-filter-buttons">
			<a href="?filter=private" class="<?= ($filter !== 'group') ? 'active' : '' ?>">Private Chat</a>
			<a href="?filter=group" class="<?= ($filter === 'group') ? 'active' : '' ?>">Group Chat</a>
		</div>
	</div>
	

	<div class="chat-grid col-12 mt-2">
		<!-- Daftar Chat -->
		<div class="chat-content">
			<?php if (!empty($chats)): ?>
				<?php foreach ($chats as $chat): ?>
					<?php
						$is_private = $chat['type'] === 'private';
						$chat_name = $is_private ? $chat['partner_name'] : $chat['group_name'];
						$chat_id = $is_private ? $chat['id_chat_room'] : $chat['id_room'];
						$open_chat = $is_private ? "openPrivateChat('$chat_id')" : "openGroupChat('$chat_id')";
					?>
					<div class="chat-card" onclick="<?= $open_chat ?>">
						<?php if ($is_private): ?>
							<?php
								$profile = !empty($chat['profile_picture']) ? $chat['profile_picture'] : 'default.jpg';
							?>
							<img src="<?= base_url('assets/uploads/Profil/' . $profile); ?>" alt="Profile" class="chat-avatar">
						<?php else: ?>
							<?php
								$icon = !empty($chat['icon']) ? $chat['icon'] : 'defaultgrup.png';
							?>
							<img src="<?= base_url('assets/uploads/Profil/Grup/' . $icon); ?>" alt="Group Icon" class="chat-avatar">
						<?php endif; ?>
						
						<div class="chat-info">
							<h3 class="f4"><?= htmlspecialchars($chat_name); ?></h3>
							<p class="f5"><?= $is_private ? 'Private Chat' : 'Group Chat'; ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<p>No chat rooms available</p>
			<?php endif; ?>
		</div>


		<!-- Chat Requests -->
		<div class="chat-request">
			<h4>Chat Requests</h4>
			<ul class="request-list">
				<?php if (!empty($requests)): ?>
					<?php foreach ($requests as $request): ?>
						<li class="request-card">
							<img src="<?= base_url('assets/uploads/Profil/' . $request->profile_picture); ?>" 
								 alt="<?= $request->name; ?>" 
								 class="request-avatar">

							<div class="request-info">
								<div class="request-name"><?= $request->name; ?></div>
								<div class="request-message">Mengirimkan permintaan chat</div>
							</div>

							<div class="request-actions">
								<a href="<?= site_url('Admin/chat/respond_request/' . $request->id_chat_request . '/approve'); ?>"
								   title="Approve"
								   class="action-icon approve">
									<i class='bx bx-check-circle'></i>
								</a>
								<a href="<?= site_url('Admin/chat/respond_request/' . $request->id_chat_request . '/reject'); ?>"
								   title="Reject"
								   class="action-icon reject">
									<i class='bx bx-x-circle'></i>
								</a>
							</div>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<li class="no-request">No chat requests available</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	
</div>


<script>
function openPrivateChat(chatRoomId) {
    if (!chatRoomId) {
        console.error('Chat room ID is invalid.');
        return;
    }

    // Contoh aksi untuk membuka chat room
    console.log('Opening chat room with ID:', chatRoomId);

    // Redirect atau panggil API untuk membuka obrolan
    window.location.href = 'chat/proom/' + chatRoomId;
}


function openGroupChat(chatRoomId) {
    if (!chatRoomId) {
        console.error('Chat room ID is invalid.');
        return;
    }

    // Contoh aksi untuk membuka chat room
    console.log('Opening chat room with ID:', chatRoomId);

    // Redirect atau panggil API untuk membuka obrolan
    window.location.href = 'chat/groom/' + chatRoomId;
}
</script>