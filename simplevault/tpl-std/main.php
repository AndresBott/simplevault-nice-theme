<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body onload="document.getElementById('inpfield').focus()">

<?php include "$template/incl-titlebar.php"; ?>

<h1>Browse</h1>
<table class="entry">
<?php foreach ($records as $record){?>
  <tr>
	<td><?php echo escape_for_html($record["cat"])?></td>
	<td><?php echo escape_for_html($record["t1"])?></td>
	<td><?php echo escape_for_html($record["t2"]) ?></td>
	<td><?php echo date($dateformat,$record["mdate"]) ?></td>
	<td><a href="?dec=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>"><img src="img/decrypt.png"  title="decrypt" alt="decrypt"/></a></td>
	<td><a href="?edt=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>"><img src="img/edit.png"  title="edit" alt="edit"/></a></td>
	<td><a href="?del=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>"><img src="img/del.png"  title="delete" alt="delete"/></a></td>
  </tr>
<?php }?>
</table>

<?php if(count($records)==0){ ?>
  <div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a></span></div>
<?php } ?>

</body>
</html>
