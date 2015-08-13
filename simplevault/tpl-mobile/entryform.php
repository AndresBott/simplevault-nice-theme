<!DOCTYPE html> 
<html> 
<head>
<?php include "$template/incl-head.php"; ?>
</head>
<body> 
<div data-role="page" >

  <div data-role="header"  data-theme="b">
    <h1>Edit Entry</h1>
    <a href="index.php" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-left jqm-home" data-ajax='false'>Home</a>
  </div><!-- /header -->

  <div data-role="content">    
  <?php include "$template/incl-titlebar.php"; ?>


    <form title="Edit Item" class="panel" method="post" action="index.php">
      <!-- h2><?php echo escape_for_html($_POST["cat"]); ?></h2 -->
      <input name="defcat" type="hidden" value="<?php echo escape_for_html($cat) ?>"/>
      <input name="deft1"  type="hidden" value="<?php echo escape_for_html($t1) ?>"/>
      <input name="deft2"  type="hidden" value="<?php echo escape_for_html($t2) ?>"/>
      <input name="mode"   type="hidden" value="<?php echo escape_for_html($mode) ?>"/>
      <ul data-role="listview">
        <li data-role="fieldcontain">
        <label for="name">Existing Category...</label>
        <select name="catdl" size="1" class="category"><option></option>
        <?php
          foreach ($cats as $currcat){
          print "<option".($cat==$currcat ? " selected":"").">".escape_for_html($currcat)."</option>";
          }
        ?>
        </select>
        </li>
        <li data-role="fieldcontain"><label for="name">... or new category</label><input type="text" name="cat" value="" /></li>

        <li data-role="fieldcontain"><label for="name">Title   </label><input type="text" name="t1" value="<?php echo escape_for_html($t1) ?>" /></li>
        <li data-role="fieldcontain"><label for="name">Subtitle</label><input type="text" name="t2" value="<?php echo escape_for_html($t2) ?>" /></li>
        <li data-role="fieldcontain"><label for="name">URL     </label><input type="text" name="newp2" value="<?php echo escape_for_html($encfields[2]) ?>" /></li>
        <li data-role="fieldcontain"><label for="name">Login   </label><input type="text" name="newp1" value="<?php echo escape_for_html($encfields[1]) ?>" /></li>
        <li data-role="fieldcontain"><label for="name">Password</label><input type="text" name="newp3" value="<?php echo escape_for_html($encfields[3]) ?>" /></li>
        <li data-role="fieldcontain"><label for="name">(Custom)</label><br/>
                                     <input type="text" name="newp8" value="<?php echo escape_for_html($encfields[8]) ?>" class="speclabel" />
                                     <input type="text" name="newp9" value="<?php echo escape_for_html($encfields[9]) ?>" class="speccontent"/></li>
        <li data-role="fieldcontain"><label for="name">Note    </label><br/><textarea name="newnote" cols="40" rows="6"><?php echo escape_for_html($encfields[$nbencfields]) ?></textarea></li>

        <li data-role="fieldcontain"><label for="name">Passphrase</label><input type="password" name="pf" value="" class="passphrase" /></li>
      </ul>

      <br/>
      <input type="submit" data-role="button" name="entrysave" value="save entry" data-icon="check" data-theme="b" /> 
    </form>


  </div><!-- /content -->
</div><!-- /page -->

</body>
</html>
