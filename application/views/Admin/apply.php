<!-- recruitment_form.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply Recruitment</title>
</head>
<body>
    <h2>Apply for Recruitment</h2>

    <!-- Flashdata untuk pesan sukses atau error -->
    <?php if ($this->session->flashdata('success')): ?>
        <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php elseif ($this->session->flashdata('error')): ?>
        <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <!-- Tampilkan nama event yang sedang dibuka -->
    <p><strong>Event:</strong> <?php echo $event->event_name; ?></p>
    <p><strong>Periode:</strong> <?php echo $event->start_date . ' to ' . $event->end_date; ?></p>

    <!-- Form Apply -->
    <form action="<?php echo site_url('user/recruitment/apply/' . $event->id_event); ?>" method="post">
        <input type="hidden" name="id_event" value="<?php echo $event->id_event; ?>">

        <label for="work_url">Work URL:</label><br>
        <input type="text" id="work_url" name="work_url" value="<?php echo set_value('work_url'); ?>"><br>
        <?php echo form_error('work_url', '<span style="color: red;">', '</span><br>'); ?>

        <label for="reason_text">Reason for Joining:</label><br>
        <textarea id="reason_text" name="reason_text"><?php echo set_value('reason_text'); ?></textarea><br>
        <?php echo form_error('reason_text', '<span style="color: red;">', '</span><br>'); ?>

        <button type="submit">Submit</button>
    </form>
</body>
</html>

