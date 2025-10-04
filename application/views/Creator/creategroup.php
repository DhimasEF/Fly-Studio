public <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
</head>
<body>
    <h3>Create Group Chat</h3>
    <form method="POST" action="<?= site_url('Creator/chat/storeGroup'); ?>">
        <label for="group_name">Group Name:</label>
        <input type="text" id="group_name" name="group_name" required>

        <label for="user_ids">Select Members:</label>
        <select id="user_ids" name="user_ids[]" multiple required>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id_user']; ?>">
                    <?php echo htmlspecialchars($user['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Create Group</button>
    </form>
</body>
</html>