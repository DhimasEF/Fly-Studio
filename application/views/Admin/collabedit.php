<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/collaboration') ?>"><i class='bx bxs-videos'></i>Edit Collaboration</a> /
			<span class="text2"> <?= $content->id_file; ?> 
			</span>
		</h1>
    </a>
	</div>
	
	<div class="mt-2">
		<div class="add-content">
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert success"><?= $this->session->flashdata('success') ?></div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert error"><?= $this->session->flashdata('error') ?></div>
		<?php endif; ?>

			<form method="POST" enctype="multipart/form-data" action="<?= base_url('Admin/collaboration/update/' . rawurlencode(base64_encode($content->id_file))); ?>">
			<div class="form-group">
				<label for="title"><i class='bx bx-edit'></i></label>
				<input type="text" name="title" id="title" value="<?= htmlspecialchars($content->title) ?>" required>
			</div>

			<div class="form-group">
				<label for="description"><i class='bx bx-detail'></i></label>
				<textarea class="f5" name="description" id="description" required><?= htmlspecialchars($content->description) ?></textarea>
			</div>

			<div class="form-group">
				<label for="thumbnail"><i class='bx bx-image'></i></label>
				<input type="file" name="thumbnail" id="thumbnail" accept="image/*">
			</div>

			<div class="form-group" id="preview-container">
				<img id="thumbnail-preview" class="width-add-content" style="display:none;">
			</div>

			<?php if (!empty($content->thumbnail)): ?>
				<div class="form-group" id="thumbnail-old-wrapper">
					<img id="current-thumbnail" src="<?= base_url('assets/uploads/MultiProject/Thumbnail/' . $content->thumbnail) ?>" class="width-add-content" >
				</div>
				<div class="form-group">
					<button type="button" onclick="removeOldThumbnail()"><i class="bx bx-trash"></i> Hapus Thumbnail</button>
					<input type="hidden" name="delete_thumbnail" id="delete-thumbnail" value="0">
				</div>
			<?php endif; ?>

			<button type="submit"><i class="bx bx-save"></i> Save Changes</button>
		</form>
	</div>
</div>



<script>
document.getElementById('thumbnail').addEventListener('change', function(event) {
	const preview = document.getElementById('thumbnail-preview');
	const current = document.getElementById('current-thumbnail');
	const file = event.target.files[0];

	if (file) {
		const reader = new FileReader();
		reader.onload = function(e) {
			preview.src = e.target.result;
			preview.style.display = 'block';
			if (current) current.style.display = 'none';
		};
		reader.readAsDataURL(file);
	} else {
		// Jika batal memilih file
		preview.src = '';
		preview.style.display = 'none';
		if (current) current.style.display = 'block';
	}
});

function removeOldThumbnail() {
	const wrapper = document.getElementById('thumbnail-old-wrapper');
	const input = document.getElementById('delete-thumbnail');
	const preview = document.getElementById('thumbnail-preview');

	if (wrapper) wrapper.style.display = 'none';
	if (input) input.value = '1';
	if (preview) preview.style.display = 'none';
}
</script>
