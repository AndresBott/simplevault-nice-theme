<div class="logo"><a href="index.php"><img src="img/logo.png" alt="simplevault logo"/></a></div>

<form action="index.php" method="get">
<div class="topbar">
  <div class="leftblock">
    <span>
    <a href="index.php"><b>Home</b></a> &nbsp;  &nbsp; 
    <input type="text" name="s" value="<?= htmlspecialchars ($filter) ?>" size ="7" id="inpfield" />
    <input type="image" src="img/go-arr.png" name="image"  title="run filter" />
    </span>
  </div>
  <div class="rightblock"><span><a href="?tools">Tools</a></span></div>
</div>
<div class="catbar">
  <span>Categories: &nbsp; 
    <?php foreach ($cats as $currcat){ ?>
      <a href="?cat=<?php echo urlencode($currcat); ?>"><?php echo escape_for_html($currcat); ?></a> <?php echo " (".svcountcatentries($currcat).")"; ?> &nbsp; 
    <?php } ?>
  </span>
</div>
</form>

<div class="addentry">
  <a href="?new=1&amp;cat=<?php echo urlencode($defcat) ?>"><img class="button" src="img/add.png" title="add new entry" alt="add new entry" /> add new entry</a>
</div>

<?php foreach($errormsg as $msg){ ?>
<p><span class="warning"><?php echo escape_for_html($msg) ?></span></p>
<?php } ?>

<?php foreach($infomsg as $msg){ ?>
<p><span class="info"><?php echo escape_for_html($msg) ?></span></p>
<?php } ?>

