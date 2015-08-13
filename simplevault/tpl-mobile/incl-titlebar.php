<?php foreach($errormsg as $msg){ ?>
<p><div class="warning"><?php echo escape_for_html($msg) ?></div></p>
<?php } ?>

<?php foreach($infomsg as $msg){ ?>
<p><div class="info"><?php echo escape_for_html($msg) ?></div></p>
<?php } ?>
