<div class="content">   
	<div class="bg justify-between align-center">
		<h1 class="f3"><i class='bx bx-bell'></i>Create Event</h1>
	</div>
	
<div class="mt-2">
    <?php echo validation_errors(); ?>
    <?php echo form_open('Admin/event/store', ['class' => 'trans-edit add-content']); ?>

    <div class="form-group">
        <label for="event_name" title="Event Name"><i class='bx bx-edit'></i></label>
        <input type="text" id="event_name" name="event_name" value="<?= set_value('event_name'); ?>" required>
    </div>

    <div class="form-group">
        <label for="description" title="Description"><i class='bx bx-align-left'></i></label>
        <textarea id="description" name="description" rows="3" required><?= set_value('description'); ?></textarea>
    </div>

    <div class="form-group">
        <label for="start_date" title="Start Date"><i class='bx bx-calendar'></i></label>
        <input type="datetime-local" id="start_date" name="start_date" value="<?= set_value('start_date'); ?>" required>
    </div>

    <div class="form-group">
        <label for="end_date" title="End Date"><i class='bx bx-calendar-event'></i></label>
        <input type="datetime-local" id="end_date" name="end_date" value="<?= set_value('end_date'); ?>">
    </div>

    <div class="form-group">
        <label for="id_category" title="Category"><i class='bx bx-category'></i></label>
        <select id="id_category" name="id_category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category->id_category; ?>"><?= $category->category_name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="participant-wrapper" class="form-group">
        <label for="max_participant" title="Max Participant"><i class='bx bx-user-plus'></i></label>
        <input type="number" id="max_participant" name="max_participant" min="1" value="<?= set_value('max_participant'); ?>">
    </div>

    <div class="form-group">
        <label for="id_scope" title="Scope"><i class='bx bx-globe'></i></label>
        <select id="id_scope" name="id_scope" required>
            <?php foreach ($scopes as $scope): ?>
                <option value="<?= $scope->id_scope; ?>"><?= $scope->scope_name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit"><i class='bx bx-send'></i> Create Event</button>

    <?php echo form_close(); ?>
</div>


<script>
    const participantWrapper = document.getElementById("participant-wrapper");
    const maxParticipantInput = document.getElementById("max_participant");
    const categorySelect = document.getElementById("id_category");

    function toggleParticipantField() {
        const selectedText = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase();

        const showParticipant = selectedText.includes("mep") ||
                                selectedText.includes("battle") ||
                                selectedText.includes("collab");

        participantWrapper.style.display = showParticipant ? "flex" : "none";

        if (!showParticipant) {
            maxParticipantInput.value = "";
        }
    }

    // Jalankan saat berubah
    categorySelect.addEventListener("change", toggleParticipantField);

    // Jalankan saat halaman load
    window.addEventListener("DOMContentLoaded", toggleParticipantField);
</script>
