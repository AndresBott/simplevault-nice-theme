<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body>
	<div id="wrap">
		<?php include "$template/incl-titlebar.php"; ?>
		
		<div class="tool">
			<form name="enterpf" action="index.php" method="post"  autocomplete="off">
			  <input name="cat" type="hidden" value="<?php echo urlencode($cat);  ?>"/>
			  <input name="t1"  type="hidden" value="<?php echo urlencode($t1);   ?>"/>
			  <input name="t2"  type="hidden" value="<?php echo urlencode($t2);   ?>"/>
			  <input name="pf"  type="hidden" value="<?php echo urlencode($pf);   ?>"/>
			  
			  

				<div class="element" style="float:right;">
					<button id="send" class="btn btn-success" name="entryedit" type="submit">
	               			 <i class="icon-pencil "></i> edit          		</button>
				</div>
			</form>
			<h2> <i class=" icon-unlock "></i> <?php echo ucfirst($decrmode) ?> Entry</h2>
			

			<?php include "$template/incl-entry-title.php"; ?>
		</div>
		
		

		<div class="tool encrypted">
			<h4> <i class=" icon-lock"></i> Encrypted:</h4>	
				<?php include "$template/incl-entry-body.php"; ?>
		</div>
		<div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a></span></div>
	</div>
<footer>
<?php include "$template/incl-footer.php"; ?>