<h2>Edit Part Label: <?= $content->title ?></h2>

<form method="POST">
    <label>Part Label untuk User ID <?= $participant->id_user ?></label>
    <input type="text" name="part_label" value="<?= $participant->part_label ?>" required><br><br>

    <button type="submit">Simpan</button>
</form>
