<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body>
	<div id="wrap">
		<?php include "$template/incl-titlebar.php"; ?>
			<?php if($pwdmsg){ ?>
			<div class="warn"><span class="icon-warning-sign icon-2x pull-left"></span> <span><?php echo $pwdmsg ?></span> <span class="icon-remove icon-2x pull-right"></span></div>
			<?php } ?>		

		<div class="tool">
			<h2> <i class=" icon-unlock "></i> <?php echo ucfirst($decrmode) ?> Entry</h2>

			<?php include "$template/incl-entry-title.php"; ?>


			
			<?php if($entrydeleted){ ?>
				<div class="confirm">
					<a href="index.php">	
					<span class=" icon-arrow-left icon-2x pull-left"></span>
					</a>
					Entry <?php echo escape_for_html($t1)."/".escape_for_html($t2) ?> deleted!
					<span class="icon-info-sign icon-2x pull-right"></span>
				</div>
			<?php }else{ ?>
						 

								


			  <form name="enterpf" action="index.php" method="post"  autocomplete="off">
			  <input name="cat" type="hidden" value="<?php echo escape_for_html($cat) ?>"/>
			  <input name="t1"  type="hidden" value="<?php echo escape_for_html($t1) ?>"/>
			  <input name="t2"  type="hidden" value="<?php echo escape_for_html($t2) ?>"/>
		  
			  	<div class="element">
					<label >Passphrase:</label>
					<input id="pf" name="pf" type="password" value="" size="20" maxlength="200"/>
					<input id="display_pf" style="width: 20px;" type="checkbox"  />
					<label style="width: 120px;" for="display_pf">Display Passphrase</label>
				</div>	
				<div class="element">
					<button id="send" class="btn btn-success" name="entry<?php echo escape_for_html($decrmode) ?>" type="submit">
	               			 <i class="icon-ok "></i> <?php echo escape_for_html($decrmode) ?>
	           		</button>
				</div>
			  </form>
			<?php } ?>
		</div>
	<div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a></span></div>
	</div>
<footer>
<script>
	$(document).ready(function(){

	
		$("#display_pf").click(function(){
			$("#display_pf").attr("checked", !$("#display_pf").attr("checked"))
			if($("#pf").attr("type") == "password"){
				$("#pf").attr("type","text");
			}else{
				$("#pf").attr("type","password");
			}
		})
		
		
		
	});

</script>
<?php include "$template/incl-footer.php"; ?>

