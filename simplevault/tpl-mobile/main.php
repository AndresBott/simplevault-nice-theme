<!DOCTYPE html> 
<html> 
<head>
<?php include "$template/incl-head.php"; ?>
</head>
<body> 
<div data-role="page">

  <div data-role="header" data-theme="b">
    <h1>Simplevault</h1>
    <a href="?new=1" data-icon="add" class="ui-btn-right jqm-add" >New</a>
  </div><!-- /header -->

  <div data-role="content">	

  <?php
  if (isset($search) and strlen($search)>1){
    //*** flat list of search results
    print "<ul data-role='listview'>";
    $currcat = "";
    foreach ($records as $sres){ // search result
      $atitle = $sres['t1'];
      if ($sres['t2']){ $atitle .= " - ".$sres['t2'];}
      print "  <li><a href=\"?cat=".escape_for_html($sres['cat'])."&t1=".escape_for_html($sres['t1'])."&t2=".escape_for_html($sres['t2'])."\"  data-ajax='false' >{$atitle}</a></li>\n";
    }
    print "</ul>";
  }
  else{
    //*** nested list of categories and items 
    print '
      <form action="index.php" method="get" data-ajax="false" class="srch">
      <label for="username" class="ui-hidden-accessible">Username:</label>
      <input type="text" name="s" id="username" value="" placeholder="Search"/>
      </form>
      ';
    print "<ul data-role='listview'>";
    $currcat = "";
    foreach ($vlt as $record){
      $recfields = explode ("\t", $record, $nbfields);
      if($currcat != $recfields[0]){ // new category has started
        if($currcat != ""){print "</li>\n</ul>\n";} 
        print "<li>{$recfields[0]}";
        print "<ul>";
        //print "<ul id=\"".strtr($recfields[0], " ", "_")."\" title=\"{$recfields[0]}\">\n";
        $currcat = $recfields[0];
      }
      $subtitle = "";
      if ($recfields[2]){ $subtitle = " - ".$recfields[2];}
      print "  <li><a href=\"?cat=".escape_for_html($recfields[0])."&t1=".escape_for_html($recfields[1])."&t2=".escape_for_html($recfields[2])."\"  data-ajax='false' >{$recfields[1]} $subtitle</a></li>\n";
    }
    print "</ul>";
  }
  ?>
  </div><!-- /content -->

</div><!-- /page -->

</body>
</html>

