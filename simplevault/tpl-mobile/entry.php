<!DOCTYPE html> 
<html> 
<head>
<?php include "$template/incl-head.php"; ?>
</head>
<body> 
<div data-role="page">

  <div data-role="header" data-theme="b">
    <h1><?php echo escape_for_html($_POST["cat"]); ?></h1>
	<a href="index.php" data-icon="home" data-iconpos="notext" data-direction="reverse" class="ui-btn-left jqm-home" data-ajax='false'>Home</a>
  </div><!-- /header -->

  <div data-role="content">    
  <?php include "$template/incl-titlebar.php"; ?>


    <table class="entry">

	  <tbody>
		  <tr>
		    <th scope="row">Title</th>
		    <td><?php echo escape_for_html($t1) ?></td>
		  </tr>
          <?php  if(strlen($t2) > 0){ ?>
		    <tr>
		      <th scope="row">Subtitle</th>
		      <td><?php echo escape_for_html($t2) ?></td>
		    </tr>
          <?php  } ?>
          <?php  if(strlen($decfields[2]) > 0){ ?>
		    <tr>
		      <th scope="row">URL</th>
		      <td><?php echo escape_for_html( print_url($decfields[2]) ) ?></td>
		    </tr>
          <?php  } ?>
          <?php  if(strlen($decfields[1]) > 0){ ?>
		    <tr>
		      <th scope="row">Login</th>
		      <td><?php echo escape_for_html( $decfields[1] )  ?></td>
		    </tr>
          <?php  } ?>
          <?php  if(strlen($decfields[3]) > 0){ ?>
		    <tr>
		      <th scope="row">Password</th>
		      <td><?php echo escape_for_html( $decfields[3] )  ?></td>
		    </tr>
          <?php  } ?>
          <?php  if(strlen($decfields[8]) > 0){ ?>
		    <tr>
		      <th scope="row"><?php echo escape_for_html( $decfields[8] ) ?></th>
		      <td><?php echo escape_for_html( $decfields[9] ) ?></td>
		    </tr>
          <?php  } ?>
	  </tbody>
    </table>

  <?php  if(strlen($decfields[$nbencfields]) > 0){ ?>
	<p>
	  <pre><?php echo escape_for_html( $decfields[$nbencfields] ) ?></pre>
	</p>
  <?php  } ?>
    

  </div><!-- /content -->
</div><!-- /page -->

</body>
</html>
