<form action="<?= base_url('User/chat/sendRequest') ?>" method="post">
    <label for="id_receiver">Pilih Penerima:</label>
    <select id="id_receiver" name="id_receiver" required>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user->id_user ?>"><?= $user->name ?></option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit">Kirim Permintaan</button>
</form>
