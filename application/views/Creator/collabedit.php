<h2>Edit Konten Kolaborasi</h2>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert error"><?= $this->session->flashdata('error') ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" action="<?= base_url('Creator/collaboration/update/' . $content->id_file) ?>">
    <label for="title">Judul</label><br>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($content->title) ?>" required><br><br>

    <label for="description">Deskripsi</label><br>
    <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($content->description) ?></textarea><br><br>

    <label for="thumbnail">Thumbnail (opsional)</label><br>
    <input type="file" name="thumbnail" accept="image/*"><br>
    <?php if ($content->thumbnail): ?>
        <p>Thumbnail saat ini:</p>
        <img src="<?= base_url('assets/uploads/MultiProject/Thumbnail/' . $content->thumbnail) ?>" width="200">
    <?php endif; ?>
    <br><br>

    <button type="submit">Simpan Perubahan</button>
</form>
