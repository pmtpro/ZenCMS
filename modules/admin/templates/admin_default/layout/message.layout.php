<?php foreach ($errors as $error): ?>
    <div class="error"><?php echo $error ?></div>
<?php endforeach; ?>

<?php foreach ($notices as $notice): ?>
    <div class="notice"><?php echo $notice ?></div>
<?php endforeach; ?>

<?php foreach ($success as $success): ?>
    <div class="success"><?php echo $success ?></div>
<?php endforeach; ?>