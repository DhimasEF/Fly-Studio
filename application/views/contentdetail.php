<div class="contentdtl-grid pd-container">
    <div class="ctndtl-container">
        <div class="contentdtl-title">
			<div class="ctndtl-acc">
				<?php if (!empty($content->profile_picture) && file_exists('assets/uploads/Profil/' . $content->profile_picture)): ?>
					<img src="<?= base_url('assets/uploads/Profil/' . $content->profile_picture); ?>" alt="Foto Profil" class="ctndtl-avt">
				<?php else: ?>
					<img src="<?= base_url('.assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctndtl-avt">
				<?php endif; ?>
				<div class="ctndtl-acc-txt">
					<span class="f4"><?= $content->name ?></span>
					<span class="f5"><?= $content->title ?></span>
				</div>
			</div>
			<div class="ctndtl-act">
				<?php if ($content->id_uploader == $this->session->userdata('user_id')): ?>
					<p>
						<a href="<?= site_url('admin/content/edit/' . rawurlencode(base64_encode($content->id_content))) ?>"><i class="bx bx-edit"></i></a>
						<a href="<?= site_url('admin/content/delete/' . rawurlencode(base64_encode($content->id_content))) ?>" onclick="return confirm('Are you sure you want to delete this content?');"><i class="bx bx-trash"></i></a>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php if ($content->file_type == 'Image'): ?>
			<img class="ctndtl-resource" src="<?= base_url('assets/uploads/Content/' . $content->file_name) ?>" alt="<?= htmlspecialchars($content->title) ?>">
		<?php else: ?>
			<video class="ctndtl-resource" controls poster="<?= base_url('assets/uploads/Content/Thumbnail/' . $content->thumbnail) ?>">
				<source src="<?= base_url('assets/uploads/Content/' . $content->file_name) ?>" type="video/mp4">
				Your browser does not support the video tag.
			</video>
		<?php endif; ?>
		<div class="ctndtl-data">
			<div class="ctndtl-desc">
				<strong><?= $content->name ?></strong> <?= $content->description ?>
			</div>
			<!-- Like & View -->
			<div class="ctndtl-lv">
				<button id="like-button" class="like-ctn" <?= empty($this->session->userdata('user_id')) ? 'disabled title="Login untuk menyukai"' : '' ?>>
					<i class='bx <?= $is_liked ? "bxs-heart" : "bx-heart" ?>'></i>
				</button>
				<span id="like-count"><?= $content->like_count ?></span>
				<div class="like-ctn">
					<i class='bx bx-play-circle'></i>
				</div>
				<span><?= $content->view_count ?></span>
			</div>
		</div>
    </div>

    <div class="ctndtl-container">
		<!-- Flash Messages -->
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert success"><?= $this->session->flashdata('success') ?></div>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert error"><?= $this->session->flashdata('error') ?></div>
		<?php endif; ?>

		<!-- Komentar -->
		<div class="comments-section">
		<?php if (empty($comments)): ?>
			<p class="no-comments">Belum ada komentar pada postingan ini.</p>
		<?php else: ?>
			<?php foreach ($comments as $comment): ?>
				<!-- Komentar Utama -->
				<div class="comment">
					<div class="cmt-ctn">
						<!-- Foto profil -->
						<?php if (!empty($comment['profile_picture']) && file_exists('assets/uploads/Profil/' . $comment['profile_picture'])): ?>
							<img src="<?= base_url('assets/uploads/Profil/' . $comment['profile_picture']) ?>" alt="Foto Profil" class="ctndtl-avt">
						<?php else: ?>
							<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctndtl-avt">
						<?php endif; ?>

						<!-- Nama & komentar -->
						<div class="cmt-ctn-txt">
							<span class="f5"><strong><?= $comment['name'] ?>
							<br></strong> <?= $comment['comment_text'] ?></span>
							<!-- Tombol hapus (jika pemilik komentar) -->
							<div class="del-cmt">
								<small class="f6 "><?= date('d M Y, H:i', strtotime($comment['created_at'])) ?></small>
								<?php if ($comment['id_user'] == $this->session->userdata('user_id')): ?>
									<form action="<?= base_url('admin/content/delete_comment/' . $comment['id_comment']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
										<button type="submit" style="color:red; background:none; border:none; cursor:pointer;"><i class="bx bx-trash"></i></button>
									</form>
								<?php endif; ?>
							</div>
						</div>

					</div>

					<!-- Form reply -->
					<div class="replies-cmt">
						<?php if (!empty($this->session->userdata('user_id'))): ?>
							<form action="<?= base_url('admin/content/add_comment/' . rawurlencode(base64_encode($content->id_content)) . '/' . $comment['id_comment']) ?>" method="POST">
								<input type="text" name="comment" placeholder="Reply to @<?= htmlspecialchars($comment['name']) ?>" required class="replies-inp"/>
								<button class="send-comment" type="submit"><i class="bx bx-send"></i></button>
							</form>
						<?php else: ?>
							<input type="text" placeholder="Login untuk membalas komentar" class="replies-inp f5" disabled/>
							<a href="<?= base_url('signin') ?>" class="send-comment" title="Login terlebih dahulu"><i class="bx bx-lock"></i></a>
						<?php endif; ?>
					</div>

					<!-- Tombol toggle replies -->
					<?php if (!empty($comment['replies'])): ?>
						<div class="replies-cmt">
							<button class="toggle-replies-btn" data-id="<?= $comment['id_comment'] ?>">Show <?= count($comment['replies']) ?> Replies </button>
						</div>
						<div class="replies" id="replies-<?= $comment['id_comment'] ?>" style="display: none;">
							<?php foreach ($comment['replies'] as $reply): ?>
								<div class="reply">
									<div class="cmt-ctn2">
										<!-- Foto profil -->
										<?php if (!empty($reply['profile_picture']) && file_exists('assets/uploads/Profil/' . $reply['profile_picture'])): ?>
											<img src="<?= base_url('assets/uploads/Profil/' . $reply['profile_picture']) ?>" alt="Foto Profil" class="ctndtl-avt">
										<?php else: ?>
											<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctndtl-avt">
										<?php endif; ?>

										<!-- Nama & komentar -->
										<div class="cmt-ctn-txt">
											<span class="f5">
												<strong><?= $reply['name'] ?></strong>
												<br> @<?= $comment['name'] ?> <?= $reply['comment_text'] ?>
											</span>
											<!-- Tombol hapus (jika pemilik komentar) -->
											<div class="del-cmt">
												<small class="f6"><?= date('d M Y, H:i', strtotime($reply['created_at'])) ?></small>
												<?php if ($reply['id_user'] == $this->session->userdata('user_id')): ?>
													<form action="<?= base_url('admin/content/delete_comment/' . $reply['id_comment']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this reply?');">
														<button type="submit" style="color:red; background:none; border:none; cursor:pointer;"><i class="bx bx-trash"></i></button>
													</form>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>

							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<div class="comment-input">
			<?php if (!empty($user->profile_picture) && file_exists('assets/uploads/Profil/' . $user->profile_picture)): ?>
				<img src="<?= base_url('assets/uploads/Profil/' . $user->profile_picture); ?>" alt="Foto Profil" class="ctndtl-avt">
			<?php else: ?>
				<img src="<?= base_url('assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctndtl-avt">
			<?php endif; ?>

			<?php if (!empty($this->session->userdata('user_id'))): ?>
				<!-- Jika user signin -->
				<form action="<?= base_url('admin/content/add_comment/' . rawurlencode(base64_encode($content->id_content))); ?>" method="POST">
					<input type="text" name="comment" required placeholder="Write your comment..." class="add-comment"/>
					<button type="submit" class="send-comment"><i class="bx bx-send"></i></button>
				</form>
			<?php else: ?>
				<!-- Jika belum signin -->
				<input type="text" placeholder="Login untuk menulis komentar" class="add-comment f5" disabled/>
				<a href="<?= base_url('signin') ?>" class="send-comment" title="Login untuk berkomentar"><i class="bx bx-lock"></i></a>
			<?php endif; ?>
		</div>

	</div>

</div>


<script>
    // Toggle reply form dengan jQuery
    $(document).on('click', '.reply-btn', function() {
        const replyForm = $('#reply-form-' + $(this).data('id'));
        replyForm.toggle();  // toggle display menggunakan jQuery
    });

    // Toggle show/hide replies
    $(document).on('click', '.toggle-replies-btn', function() {
        var id = $(this).data('id');
        var replies = $('#replies-' + id);
        var isVisible = replies.is(':visible');

        replies.slideToggle();
        $(this).text(isVisible ? 'Show Replies' : 'Hide Replies');
    });

    // AJAX like button toggle
    $(document).on('click', '#like-button', function() {
        var id_content = "<?= $content->id_content ?>";
        $.ajax({
            url: "<?= base_url('admin/content/toggle_like/') ?>" + id_content,
            method: "POST",
            success: function(response) {
                response = JSON.parse(response);
				const icon = $('#like-button i');
                if (response.status === 'added') {
					icon.removeClass('bx-heart').addClass('bxs-heart');
				} else if (response.status === 'removed') {
					icon.removeClass('bxs-heart').addClass('bx-heart');
				}
                // Update jumlah like
                $("#like-count").load(location.href + " #like-count");
            }
        });
    });
</script>
