<h2>Manajemen Participant: <?= $content->title ?></h2>

<!-- Notifikasi -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert error"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<!-- Form Tambah Participant -->
<h3>Tambah Participant</h3>
<form method="POST" action="<?= base_url('Creator/collaboration/add_participant/' . $content->id_file) ?>">
    <label for="id_user">Pilih User:</label>
    <select name="id_user" required>
        <option value="">-- Pilih --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id_user'] ?>"><?= $user['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label for="part_label">Part Label (misal: Part A)</label>
    <input type="text" name="part_label">
    <br><br>

    <button type="submit">Tambah Participant</button>
</form>

<!-- Daftar Participant -->
<h3>Daftar Participant</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Part Label</th>
        <th>Aksi</th>
    </tr>
    <?php $no = 1; foreach ($participants as $p): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $p['username'] ?? 'User #' . $p['id_user'] ?></td>
        <td><?= $p['part_label'] ?? '-' ?></td>
        <td>
            <a href="<?= base_url('Creator/collaboration/edit_participant/' . $content->id_file . '/' . $p['id_user']) ?>">Edit</a> |
            <a href="<?= base_url('Creator/collaboration/delete_participant/' . $content->id_file . '/' . $p['id_user']) ?>" onclick="return confirm('Yakin ingin menghapus participant ini?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
