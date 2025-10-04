<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Creator/content') ?>"><i class='bx bxs-videos'></i>Edit Content</a> /
			<span class="text2"> <?= $content->id_content; ?> 
			</span>
		</h1>
    </a>
	</div>
	
	<div class="mt-2">
		<div class="add-content">
			<form action="<?= base_url('Creator/content/update/' . rawurlencode(base64_encode($content->id_content))) ?>" method="POST">
				<div class="form-group">
					<label for="title"><i class='bx bx-edit'></i></label>
					<input type="text" name="title" id="title" value="<?= htmlspecialchars($content->title) ?>" required>
				</div>
			
				<div class="form-group">
					<label for="description"><i class='bx bx-detail'></i></label>
					<textarea class="f5" name="description" id="description"><?= htmlspecialchars($content->description) ?></textarea>
				</div>

				<button type="submit"><i class="bx bx-save"></i>Save Changes</button>
			</form>
		</div>
	</div>
</div>
