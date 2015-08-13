<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<?php include "$template/incl-head.php"; ?>
</head>

<body>
	<div id="wrap">
		<?php include "$template/incl-titlebar.php"; ?>
		
		
		




		<div id="keytable">
			<table>
				<tr>
					<th>Category</th>
					<th>Title</th>
					<th>Subtitle</th>
					<th>Date</th>
					<th class="icons" >Action</th>
				</tr>
				<?php foreach ($records as $record){?>
					<tr>
						<td class="catlink"><a href="?cat=<?php echo urlencode(escape_for_html($record["cat"])); ?>"><?php echo escape_for_html($record["cat"])?></a></td>
						<td><?php echo escape_for_html($record["t1"])?></td>
						<td><?php echo escape_for_html($record["t2"]) ?></td>
						<td><?php echo date($dateformat,$record["mdate"]) ?></td>
						<td class="icons" >
							<a class="btn btn-danger" href="?dec=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>">	<i class="icon-unlock"></i></a>					
							<a class="btn btn-danger" href="?edt=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>">	<i class="icon-pencil"></i></a>					
							<a class="btn btn-danger" href="?del=1&amp;cat=<?php echo urlencode($record["cat"]); ?>&amp;t1=<?php echo urlencode($record["t1"]); ?>&amp;t2=<?php echo urlencode($record["t2"]); ?>">	<i class="icon-remove"></i></a>					
						</td>
	
					</tr>
				<?php }?>
			</table> 
			
		</div>
		<div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a> - using Theme Nice 0.1.2</span></div>
	</div>

<footer>
<?php include "$template/incl-footer.php"; ?>

