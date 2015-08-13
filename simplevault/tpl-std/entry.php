<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body onload="document.getElementById('inpfield').focus()">

<?php include "$template/incl-titlebar.php"; ?>

<h1><?php echo ucfirst($decrmode) ?> Entry</h1>
<?php include "$template/incl-entry-title.php"; ?>
<br/>
<?php include "$template/incl-entry-body.php"; ?>

</body>
</html>
