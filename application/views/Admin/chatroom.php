<div id="chat-container">

    <!-- Sidebar: Daftar Chat Private -->
    <div id="member-list">
        <div class="mmb-list-top">
            <span class="f4 gap-1"><i class='bx bx-message-rounded'></i> Private Chats</span>
        </div>
        <ul>
            <?php if (!empty($chats)): ?>
                <?php foreach ($chats as $chat): ?>
                    <?php
                        $chat_id = $chat['id_chat_room'];
                        $chat_name = $chat['partner_name'];
                        $profile = !empty($chat['profile_picture']) ? $chat['profile_picture'] : 'default.jpg';
                        $is_active = ($chat_id == $room_id);
                    ?>
                    <li>
                        <div class="member-group-info chat-item <?= $is_active ? 'active' : '' ?>" onclick="openPrivateChat('<?= $chat_id ?>')">
                            <img src="<?= base_url('assets/uploads/Profil/' . $profile); ?>" alt="Profile" class="chat-avatar">
                            <div>
                                <strong><?= htmlspecialchars($chat_name) ?></strong>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Belum ada percakapan.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Main Chat -->
    <div id="message-section">

        <!-- Chat Header -->
        <div class="chat-header">
            <?php
                $partner_profile = !empty($partner['profile_picture']) ? $partner['profile_picture'] : 'default.jpg';
            ?>
            <span class="gap-1">
                <img src="<?= base_url('assets/uploads/Profil/' . $partner_profile); ?>"
                     alt="User Icon"
                     class="chat-avatar"
                     id="partner-avatar">
                <span id="partner-name"><?= htmlspecialchars($partner['name'] ?? 'Private Chat') ?></span>
            </span>
        </div>

        <!-- Chat Messages (AJAX refreshable) -->
        <ul id="message-list">
            <!-- Akan diisi ulang dengan fetchMessages() -->
        </ul>

        <!-- Send Form -->
        <form id="send-message-form" method="POST" action="<?= site_url('Admin/chat/send_message_private/' . $room_id); ?>">
            <input type="text" name="message_content" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>


<script>
function openPrivateChat(chatRoomId) {
    if (!chatRoomId) return;
    window.location.href = '<?= site_url('Admin/chat/proom/') ?>' + chatRoomId;
}
</script>

<script>
function loadMessages() {
    $.ajax({
        url: "<?= site_url('Admin/chat/fetch_messages/' . $room_id) ?>", // private
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

// Pertama kali load
loadMessages();

// Refresh otomatis setiap 3 detik
setInterval(loadMessages, 3000);

// Submit form kirim pesan via AJAX
$('#send-message-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'), // ke send_message_private
        method: "POST",
        data: $(this).serialize(),
        success: function () {
            $('input[name="message_content"]').val('');
            loadMessages(); // langsung reload pesan
            scrollToBottom();
        }
    });
});
</script>

