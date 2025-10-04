<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bxs-videos'></i> Upload Content</h1>
	</div>

	<div class="mt-2">
		<div class="add-content">
			<h1 class="f3">Step 1: Uploads Your Content</h1>
			<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
			<?php echo form_open_multipart('Admin/content/upload'); ?>

			<div class="form-group">
				<i class='bx bx-edit'></i>
				<input type="text" name="title" id="title" placeholder="Title" required>
			</div>

			<div class="form-group">
				<i class='bx bx-upload'></i>
				<input type="file" name="file_url" id="file_url" required>
			</div>

			<button type="submit">
				<i class='bx bx-cloud-upload'></i> Upload
			</button>

			<?php echo form_close(); ?>
		</div>
	</div>
</div>