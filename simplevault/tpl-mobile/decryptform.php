<!DOCTYPE html> 
<html> 
<head>
<?php include "$template/incl-head.php"; ?>
</head>
<body> 
<div data-role="page" data-add-back-btn="true" >

  <div data-role="header">
    <h1>View/Modify Entry</h1>
	<a href="index.php" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-left jqm-home"  data-ajax='false'>Home</a>
  </div><!-- /header -->

  <div data-role="content">    
    <?php include "$template/incl-entry-title.php"; ?>
    <?php include "$template/incl-titlebar.php"; ?>
    
    <?php if($pwdmsg){ ?>
    <p><span class="warning"><?php echo $pwdmsg ?></span></p>
    <?php } ?>
    
    <?php if($entrydeleted){ ?>
      <p><span class="info">Entry <?php echo escape_for_html($t1)."/".escape_for_html($t2) ?> deleted!</span> &nbsp; <a href="index.php" data-role="button" data-icon="home" data-inline="true"  data-ajax='false' data-theme="b">ok</a></p>
    <?php }else{ ?>
      <form name="enterpf" action="index.php" method="post"  autocomplete="off"    data-ajax="false">
      <input name="cat" type="hidden" value="<?php echo escape_for_html($cat) ?>"/>
      <input name="t1"  type="hidden" value="<?php echo escape_for_html($t1) ?>"/>
      <input name="t2"  type="hidden" value="<?php echo escape_for_html($t2) ?>"/>
      <p>
      <div data-role="fieldcontain">
        <label for="name">Passphrase:</label>
        <input id="pf" name="pf" type="password" value="" size="20" maxlength="200" class="passphrase" />
      </div>
    
      <input type="submit" data-role="button" name="entrydecrypt" value="Decrypt" /> 
      <input type="submit" data-role="button" name="entryedit" value="Edit" /> 
      <br/>
      <input type="submit" data-role="button" name="entrydelete" value="Delete" data-icon="delete" data-theme="e" /> 
      </p>
      </form>
    <?php } ?>

  </div><!-- /content -->
</div><!-- /page -->

</body>
</html>
