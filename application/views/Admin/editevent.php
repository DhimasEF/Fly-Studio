<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3">
			<a class="no-deco" href="<?= base_url('Admin/transaction') ?>"><i class='bx bx-transfer'></i>Edit Event</a> /
			<span class="text2"> <?php echo htmlspecialchars($event->event_name); ?> 
			</span>
		</h1>
    </a>
	</div>

<?php if ($this->session->flashdata('success')): ?>
    <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
<?php endif; ?>

<div class="mt-2">
    <form action="<?= site_url('Admin/event/update_event/' . rawurlencode(base64_encode($event->id_event))); ?>" method="post" enctype="multipart/form-data" class="add-content trans-edit">
        
        <div class="form-group">
            <label for="event_name" title="Event Name"><i class='bx bx-edit'></i></label>
            <input type="text" name="event_name" id="event_name" value="<?= htmlspecialchars($event->event_name); ?>" required>
        </div>

        <div class="form-group">
            <label for="description" title="Description"><i class='bx bx-detail'></i></label>
            <textarea class="f5" name="description" id="description" maxlength="200" required><?= htmlspecialchars($event->description); ?></textarea>
        </div>

        <div class="form-group">
            <label for="start_date" title="Start Date"><i class='bx bx-calendar'></i></label>
            <input type="datetime-local" name="start_date" id="start_date" value="<?= date('Y-m-d\TH:i', strtotime($event->start_date)); ?>" required>
        </div>

        <div class="form-group">
            <label for="end_date" title="End Date"><i class='bx bx-calendar-alt'></i></label>
            <input type="datetime-local" name="end_date" id="end_date" value="<?= date('Y-m-d\TH:i', strtotime($event->end_date)); ?>" required>
        </div>

        <div class="form-group">
            <label for="category_name" title="Category"><i class='bx bx-category'></i></label>
            <input type="text" id="category_name" value="<?= htmlspecialchars(strtoupper($event->category_name)); ?>" disabled>
        </div>

        <?php if (in_array(strtolower($event->category_name), ['mep', 'battle', 'collab'])): ?>
            <div class="form-group">
                <label for="max_participants" title="Max Participants"><i class='bx bx-user-plus'></i></label>
                <input type="number" name="max_participants" id="max_participants" value="<?= $event->max_participants; ?>" required>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="banner" title="Banner"><i class='bx bx-image-add'></i></label>
            <input type="file" name="banner" id="banner" accept="image/*">
        </div>

        <button type="submit"><i class='bx bx-save'></i> Update Event</button>
    </form>
</div>
