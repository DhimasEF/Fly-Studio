<h2>Edit Transaksi</h2>

<form method="post">
    <label for="file_url">File URL:</label>
    <input type="text" name="file_url" id="file_url" value="<?= htmlspecialchars($transaction->order_file_url); ?>" required>

    <label for="password">Password:</label>
    <div style="position: relative;">
        <input type="password" name="password" id="password" value="<?= htmlspecialchars($transaction->password); ?>" required>
        <span id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
            👁️
        </span>
    </div>

    <button type="submit">Update</button>
</form>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.textContent = "🙈"; // Ubah ikon menjadi mata tertutup
        } else {
            passwordField.type = "password";
            this.textContent = "👁️"; // Kembalikan ikon mata terbuka
        }
    });
</script>