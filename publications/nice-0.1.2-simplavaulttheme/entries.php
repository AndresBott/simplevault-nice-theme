<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body>
	<div id="wrap" class="entries">
		<?php include "$template/incl-titlebar.php"; ?>
<!-- 			<h2> <i class=" icon-unlock "></i> Decrypted Entries</h2> -->

		<div class="subWrap">	
			<?php foreach($entries as $entry){ 
     			$cat = $entry['recfields'][0];
       			$t1  = $entry['recfields'][1];
        		$t2  = $entry['recfields'][2];
        		$decfields = $entry['decfields'];
				?>	
				

				<div class="tool">
					<h4> <i class=" icon-unlock "></i> <?php echo ucfirst($decrmode) ?> Entry: <?php echo escape_for_html($t1) ?></h4>
					<div class="entryInfo unenc">
						<div class="element">
						<label>Category:</label> <span class="bold"><?php echo escape_for_html($cat) ?></span>
						</div>
						<div class="element">
						<label>Title:</label> <span class="bold"><?php echo escape_for_html($t1) ?></span>
						</div>
						<div class="element">
						<label>Subtitle:</label> <span class="bold"><?php echo escape_for_html($t2) ?></span>
						</div>					
					</div>
					<div class="entryInfo enc">
<!-- 						<h4> <i class=" icon-lock"></i> Encrypted data:</h4>	 -->
						<div class="element">
							<label>URL:</label> <span class="bold"><?php echo print_url(escape_for_html($decfields[2])) ?></span>
						</div>
						<div class="element">
							<label>Login:</label> <span class="bold"><?php echo escape_for_html($decfields[1])  ?></span>
						</div>
						<div class="element">
							<label>Password:</label> <span class="bold"><?php echo escape_for_html($decfields[3])  ?></span>
						</div>
						
						<?php  if(strlen($decfields[8]) > 0){ ?>
						<div class="element">
							<label><?php echo escape_for_html($decfields[8]) ?>:</label> <span class="bold"><?php echo escape_for_html($decfields[9]) ?></span>
						</div>
						<?php  } ?>
						
						<div class="element">
							<label>Note:</label> <span class="bold"><?php echo escape_for_html($decfields[$nbencfields]) ?></span>
						</div>		
					</div>

				</div>					



			<?php } ?>
		</div>	



	</div>
<footer>
<?php include "$template/incl-footer.php"; ?>

