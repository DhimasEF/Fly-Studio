<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<i class='bx bxs-videos'></i>Upload Content /
			<span class="text2"> <?= $content->id_content; ?> 
			</span>
		</h1>
    </a>
	</div>
	
	<div class="mt-2">
		<div class="add-content">
			<h1 class="f3">Step 2: Complete Content Details</h1>

			<div class="form-group">
				<i class='bx bx-edit'></i>
				<input type="text" name="title" id="title" value="<?= htmlspecialchars($content->title) ?>" readonly>
			</div>
			
			<?php if ($content->file_type === 'Image'): ?>
				<img class="width-add-content" src="<?= $content->file_url ?>" alt="<?= htmlspecialchars($content->title) ?>">
			<?php elseif ($content->file_type === 'Video'): ?>
				<video class="width-add-content" controls>
					<source src="<?= $content->file_url ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			<?php endif; ?>
			
			<?php echo form_open_multipart('Admin/content/save_content_details'); ?>
				<input type="hidden" name="id_content" value="<?= $content->id_content ?>">
				<input type="hidden" name="file_type" value="<?= $content->file_type ?>">

			<?php if ($content->file_type === 'Video'): ?>
				<div class="form-group">
					<label for="thumbnail"><i class='bx bx-image'></i></label>
					<input type="file" name="thumbnail" id="thumbnail">
				</div>
			<?php endif; ?>
				<div class="form-group">
					<label for="description"><i class='bx bx-detail'></i></label>
					<textarea class="f5" name="description" id="description" required></textarea>
				</div>	
				<button type="submit"><i class='bx bx-save'></i> Save Content</button>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>