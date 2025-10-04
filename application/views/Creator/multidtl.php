<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Creator/collaboration') ?>"><i class='bx bxs-videos'></i>Detail Collaboration</a> /
			<span class="text2"> <?= $content->id_file; ?> 
			</span>
		</h1>
    </a>
	</div>
	
<div class="contentdtl-grid mt-2">
    <div class="ctndtl-container">
		<div class="contentdtl-title">
			<div class="ctndtl-acc">
				<img src="<?= base_url('assets/uploads/Profil/Grup/defaultgrup.png'); ?>" alt="Foto Default" class="ctndtl-avt">
				<div class="ctndtl-acc-txt">
					<span class="f4">Fly Studio</span>
					<span class="f5"><?= $content->title ?></span>
				</div>
			</div>
			<div class="ctndtl-act">
				<?php if ($user->role == "admin"): ?>
					<p>
						<a href="<?= base_url('Creator/collaboration/edit/' .rawurlencode(base64_encode( $content->id_file))); ?>">
							<i class="bx bx-edit"></i>
						</a>
						<a href="<?= base_url('Creator/collaboration/manage_participant/' . rawurlencode(base64_encode($content->id_file))); ?>">
							<i class="bx bx-group"></i>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php if ($content->file_type == 'Image'): ?>
			<img class="ctndtl-resource" src="<?= base_url('assets/uploads/MultiProject//' . $content->file_name) ?>" alt="<?= htmlspecialchars($content->title) ?>">
		<?php else: ?>
			<video class="ctndtl-resource" controls poster="<?= base_url('assets/uploads/MultiProject/Thumbnail/' . $content->thumbnail) ?>">
				<source src="<?= base_url('assets/uploads/MultiProject/' . $content->file_name) ?>" type="video/mp4">
				Your browser does not support the video tag.
			</video>
		<?php endif; ?>
		<div class="ctndtl-data">
			<div class="ctndtl-desc">
				<strong><?= $content->name ?></strong> <?= $content->description ?>
			</div>
			<div class="ctndtl-lv">
				<button id="like-button" class="like-ctn">
					<i class='bx <?= $is_liked ? "bxs-heart" : "bx-heart" ?>'></i>
				</button>
				<span id="like-count"><?= $content->like_count ?></span>
				<div class="like-ctn">
					<i class='bx bx-play-circle'></i>
				</div>
				<span><?= $content->view_count ?></span>
			 </div>
		</div>
		<h3 class="f4 mt-2">List Participant</h3>
		<div class="participants-container mt-1">
			<?php foreach ($participants as $p): ?>
				<div class="participant-card2">
					<!-- Tooltip -->
					<span class="tooltip-text"><?= htmlspecialchars($p['part_label'] ?? '-') ?></span>
					<div class="participant-info">
						<?php if (!empty($p['profile_picture']) && file_exists('assets/uploads/Profil/' . $p['profile_picture'])): ?>
							<img src="<?= base_url('assets/uploads/Profil/' . $p['profile_picture']) ?>" alt="Foto Profil" class="participant-avatar">
						<?php else: ?>
							<img src="<?= base_url('assets/uploads/Profil/default.jpg') ?>" alt="Foto Default" class="participant-avatar">
						<?php endif; ?>

						<div class="participant-name">
							<?= $p['name'] ?? 'User #' . $p['id_user'] ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
    </div>

    <!-- Komentar -->
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
							<br>
							<div class="del-cmt">
								<small class="f6 "><?= date('d M Y, H:i', strtotime($comment['created_at'])) ?></small>
								<?php if ($comment['id_user'] == $this->session->userdata('user_id')): ?>
									<form action="<?= base_url('Creator/collaboration/delete_comment/' . $comment['id_comment']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
										<button type="submit" style="color:red; background:none; border:none; cursor:pointer;"><i class="bx bx-trash"></i></button>
									</form>
								<?php endif; ?>
							</div>
						</div>

					</div>

					<div class="replies-cmt">
						<!-- Form reply -->
						<form action="<?= base_url('Creator/collaboration/add_comment/' . rawurlencode(base64_encode($content->id_file)) . '/' . $comment['id_comment']) ?>" method="POST">
							<input type="text" name="comment" placeholder="Reply to @<?= htmlspecialchars($comment['name']) ?>" required class="replies-inp"/>
							<button class="send-comment2" type="submit"><i class="bx bx-send"></i></button>
						</form>
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
													<form action="<?= base_url('Creator/collaboration/delete_comment/' . $reply['id_comment']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this reply?');">
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
			<?php if (!empty($user['profile_picture']) && file_exists('assets/uploads/Profil/' . $user['profile_picture'])): ?>
				<img src="<?= base_url('assets/uploads/Profil/' . $user['profile_picture']); ?>" alt="Foto Profil" class="ctndtl-avt">
			<?php else: ?>
				<img src="<?= base_url('.assets/uploads/Profil/default.jpg'); ?>" alt="Foto Default" class="ctndtl-avt">
			<?php endif; ?>
			<form action="<?= base_url('Creator/collaboration/add_comment/' . rawurlencode(base64_encode($content->id_file))); ?>" method="POST">
				<input type="text" name="comment" required placeholder="Write your comment..." class="add-comment"/>
				<button type="submit" class="send-comment"><i class="bx bx-send"></i></button>
			</form>
		</div>
	</div>

</div>

<!-- SCRIPT -->
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
        var id_content = "<?= $content->id_file ?>";
        $.ajax({
            url: "<?= base_url('Creator/collaboration/toggle_like/') ?>" + id_content,
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