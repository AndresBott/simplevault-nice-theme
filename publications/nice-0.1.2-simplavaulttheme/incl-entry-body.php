
<div class="element">
	<label>URL:</label><span class="bold"><?php echo print_url(escape_for_html($decfields[2])) ?></span>
</div>
<div class="element">
	<label>Login:</label><span class="bold"><?php echo escape_for_html($decfields[1])  ?></span>
</div>
<div class="element">
	<label>Password:</label><span class="bold"><?php echo trim(escape_for_html($decfields[3]));  ?></span>
</div>

<?php  if(strlen($decfields[8]) > 0){ ?>
<div class="element">
	<label><?php echo escape_for_html($decfields[8]) ?>:</label> <span class="bold"><?php echo escape_for_html($decfields[9]) ?></span>
</div>
<?php  } ?>

<div class="element">
	<label style = "vertical-align:top;">Note:</label> <span style="display: inline-block" class="bold"><?php echo nl2br( escape_for_html(trim($decfields[$nbencfields])));   ?></span>
</div>