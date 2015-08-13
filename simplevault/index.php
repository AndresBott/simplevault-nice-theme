<?php

################################################################
#
# index.php    
# Simple Vault: online password manager
#
#   Author:  Rolf Brugger
#    Email:  mail at rolfb dot ch
#
# Versions - History:
#           1.8  Jan 12  - Mobile Template: framework iui replaced by jQuery Mobile
#                        - Mobile Template: can now also create, edit and delete entries.
#
#           1.7  Dec 11  - settings are now in a separate file.
#                        - search also works with get parameter (needed for saved search feature of firefox)
#                        - generate passwords based on 3 different character sets 
#                        Many thanks to Gordon K. who contributed the following features
#                        - support for ", ' and other special characters in fields
#                        - added .htaccess file in vault folder to deny access from the internet (works in Apache only, not IIS)
#                        - changed default vault location to /var/lib/simplevault/simplevault.txt
#                        - added ability to change template using "tpl" parameter
#                        - added random small delay when passphrase is wrong (anti brute force)
#                        - added logging to webserver's error log when too many incorrect passphrases are entered
#                        - added warning when connecting using regular HTTP (unencrypted connection)
#                        - various additional error and consistency checks
#
#           1.6  Jun 11: - Bulk decrypt entries shows a list of not decrypted entries too.
#                        - Set cursor to password form field.
#                        Many thanks to Fabio B. who contributed the following features:
#                        - Use the mobile template for Android as well
#                        - Don't lose the inputted data when an entry is not saved
#                        - mark mandatory fields with an asterisk.
#                        - Added a title to the pages.
#                        - Autofocus on the passphrase field
#                        - Checkbox for showing the passphrase when no one is around.
#                        - Button to generate a random password.
#                        - Home page shows all your entries
#                        
#           1.5  Jan 10: - Ignore empty lines in bulk decrypt tool.
#                        - Font and screen size adaped to iPhone.
#                        - Import from text file.
#                        - Internally restructured according to MVC model, and support for templates added.
#                        - iPhone template added.
#                        - Option added that the same pass phrase must be used in the entire vault ($forcesamepf='a') or in a category ($forcesamepf='c')
# 
#           1.4  Feb 08: - Prevent forms form autocompleting fields.
#                        - When an entry is saved, it is checked if the passphrase was used for other entries too.
#                        - New tools area.
#                        - New functions: bulk decrypt, bulk change passphrase.
#                        Many thanks to the contributors Christian R. and Reimar H.
# 
#           1.3  Jan 08: - More secure handling of special characters in title tags
# 
#           1.2  Nov 07: - Editing entires improved
# 
#           1.1  Oct 07: - Edit entires
# 
#           1.0  Sep 07: - Initial release
# 
################################################################

session_start();

// Files should be readable and writable only by the owner.
umask (0177);

// ----------------------------------------------------------------------------------------
// *** Settings
// ----------------------------------------------------------------------------------------

// Default settings. Do not change anything here. 
// Any changes to settings should be done in svconfig.php.
//
$dateformat = 'd-M-Y';          // "09-Sep-2007"
$checkexistingpf = 1;
$forcesamepf = '0';
$wrongpfdelay = '2';
$wrongpfalertthreshold = '10';
$vaultfname  = "/var/lib/simplevault/simplevault.txt";
date_default_timezone_set('Europe/Zurich');

// get settings form configuration file 
if (file_exists('svconfig.php')){
  include 'svconfig.php';
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', '1');

// ----------------------------------------------------------------------------------------
// *** Look and Feel / Templates
// ----------------------------------------------------------------------------------------

if (preg_match("/android|iphone/i", $_SERVER["HTTP_USER_AGENT"])) {
  // 
  $defaulttemplate = 'tpl-mobile';
}
else{
  $defaulttemplate = 'tpl-std';
}

// Override template using HTTP GET variable. Mostly for debugging purposes
if( array_key_exists("tpl",$_GET) ) {
  $defaulttemplate = $_GET["tpl"];
}


// ----------------------------------------------------------------------------------------
// *** Constants
// ----------------------------------------------------------------------------------------

$cipher     = 'rijndael-256';


// ----------------------------------------------------------------------------------------
// *** Constants - Do not change!
// ----------------------------------------------------------------------------------------
$preamble    = "svpwdmanag";
$nbfields    = 10;
$nbencfields = 10;


// ----------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------

// *** Initializations

$template = $defaulttemplate;

// Load the vault file. Ensure it is writable and readable.
if( file_exists($vaultfname) && is_readable($vaultfname) && is_writable($vaultfname) ) {
  $vltcontents = file_get_contents("$vaultfname");
  if( $vltcontents !== FALSE ) {
    $vlt = explode("\n", $vltcontents);
    unset($vltcontents);
  } else {
    die( "Error reading vault file $vaultfname." );
  }
} else {
  echo "<p>Unable to load vault file $vaultfname. Ensure that:</p><ul><li>The folder <b>" . dirname($vaultfname) . "</b> exists and that the webserver has permission to read/write to it</li><li>The file <b>$vaultfname</b> exists and the webserver has permission to write to it.</li><li>If you want to start with an empty vault, simply create a zero-length file called <b>$vaultfname</b></li></ul>";
  die();
}

if(count($vlt)==1 and strlen($vlt[0])==0){$vlt=array();} // fix php-explode bug of an empty file
$cats = categories($vlt);

$myfname = basename($_SERVER["PHP_SELF"]);
$infomsg = array();
$errormsg= array();

//print_r($_REQUEST);

// ----------------------------------------------------------------------------------------
// *** Actions
// ----------------------------------------------------------------------------------------

// *** save an entry
if(isset($_POST['entrysave'])){
  /* Populate variables for entryform template used in case of error */
  if (isset($_POST['catdl']) and strlen($_POST['catdl']) > 0) {
    $cat = $_POST['catdl'];
  }
  if (isset($_POST['cat']) and strlen($_POST['cat']) > 0) {
    $cat = $newcat = $_POST['cat'];
  }
  $t1 = $_POST['t1'];
  $t2 = $_POST['t2'];
  $mode = $_POST['mode'];

  $encfields = array ();
  $encfields[1] = $_POST['newp1'];
  $encfields[2] = $_POST['newp2'];
  $encfields[3] = $_POST['newp3'];
  $encfields[8] = $_POST['newp8'];
  $encfields[9] = $_POST['newp9'];
  $encfields[$nbencfields] = $_POST['newnote'];

  if (!isset($_POST['cat']) or strlen($_POST['cat'])<1){ $_POST['cat']=$_POST['catdl']; $_REQUEST['cat']=$_POST['catdl']; } // take category from dropdown list? category in text field has precedence.

  // *** make checks
  if(!isset($_POST['cat']) or strlen($_POST['cat'])==0){
    $errormsg[] = "Could not save entry: no category set.";
    include "$template/entryform.php";
    return;
  }
  else if(!isset($_POST['t1']) or strlen($_POST['t1'])==0){
    $errormsg[] = "Could not save entry: no title set.";
    include "$template/entryform.php";
    return;
  }
  /*
  else if(!isset($_POST['newp3']) or strlen($_POST['newp3'])==0){
    $errormsg[] = "Could not save entry: no password set.";
    include "$template/entryform.php";
    return;
  }
  */
  else if ($forcesamepf == 'a' and count($vlt)>=1 and svcountdecodeableentries($_POST['pf'], "")==0){
    $errormsg[] = "Could not save entry!";
    $errormsg[] = "You have entered an invalid pass phrase. All entries have to be encoded with the same pass phrase. Go back an use the same pass phrase that was used to encode the other entries.";
    include "$template/entryform.php";
    return;
  }
  else if ($forcesamepf == 'c' and svcountcatentries($_POST['cat'])>=1 and svcountdecodeableentries($_POST['pf'], $_POST['cat'] )==0){
    $errormsg[] = "Could not save entry!";
    $errormsg[] = "You have entered an invalid pass phrase. All entries in a category have to be encoded with the same pass phrase. Go back an use the same pass phrase that was used to encode the other entries in the category {$_POST['cat']}.";
    include "$template/entryform.php";
    return;
  }

  // *** everything checked - save the entry now  
  else{
    if(isset($_POST['mode']) and $_POST['mode']=="modify"){
      // we were editing an existing entry. Delete original entry before saving the modified entry.
      $i = entry_index($vlt, $_POST['defcat'], $_POST['deft1'], $_POST['deft2']);
      if ($i >= 0){
        $res = delete_entry($vlt, $i, $cats);

        if ($res == false) {
          $errormsg[] = "could not delete entry: error writing db file.";
          return;
        }
      }
    }

    // for security reasons, strip off all html special characters
    $_POST['cat'] = escape_for_db($_POST['cat']);
    $_POST['t1']  = escape_for_db($_POST['t1']);
    $_POST['t2']  = escape_for_db($_POST['t2']);
    if(entry_index($vlt, $_POST['cat'], $_POST['t1'], $_POST['t2']) <0){
      // *** create new entry
      $encpart  = svencrypt($_POST['pf'], $preamble, $_POST['newp1'], $_POST['newp2'], $_POST['newp3'], $_POST['newp8'], $_POST['newp9'], $_POST['newnote']);
      $newentry = $_POST['cat']."\t".$_POST['t1']."\t".$_POST['t2']."\t".time()."\t\t\t\t\t\t".$encpart;
    
      // append it to the vault file
      array_push($vlt, $newentry);
      sort($vlt);
      while($vlt[0]==""){array_shift($vlt);};
    
      $res = update_db_file (implode("\n", $vlt));
      if ($res === false) {
        $errormsg[] = "could not save entry: error writing db file.";
        return;
      }

      $cats = categories($vlt);
      $infomsg[] = "Entry ".$_POST['t1']."/".$_POST['t2']." saved.";

      if ($checkexistingpf){
        if (svcountdecodeableentries($_POST['pf'], "") > 1){ // has the passphrase already been used to encode other entries?
          $infomsg[] = "This passphrase has also been used to encode other entries.";
        }
        else{
          $infomsg[] = "This is a new passphrase.";
        }
      }
    }
    else{
      $errormsg[] = "Could not save entry: an entry with this category and title already exists.";
      include "$template/entryform.php";
      return;
    }
  }  
}


// *** Bulk change passphrase
if(isset($_POST['bulkchangepf'])){
  $i=0;
  $count = 0;
  $logmsg = "re-encrypting: ";
  while ($i < count($vlt)){
    $recfields = explode ("\t", $vlt[$i], $nbfields);
    $decfields = svdecrypt($_POST['oldpf'], $recfields[$nbfields-1]);
    if ($decfields[0] == $preamble){
      $encpart  = svencrypt($_POST['newpf'], $preamble, $decfields[1], $decfields[2], $decfields[3], $decfields[8], $decfields[9], $decfields[$nbencfields]);
      $vlt[$i] = $recfields[0]."\t".$recfields[1]."\t".$recfields[2]."\t".time()."\t\t\t\t\t\t".$encpart;
      $logmsg .= $recfields[0]."-".$recfields[1]."-".$recfields[2]."(note".$decfields[$nbencfields]."), &nbsp; ";
      $count++;
    }
    $i++;
  }
  $res = update_db_file (implode("\n", $vlt));
  if ($res === FALSE) {
    $errormsg[] = "Could not save entry: error writing db file.";
    return;
  }

  $infomsg[] = $logmsg;
  $infomsg[] = "$count of ".count($vlt)." entries were re-encrypted with the new passphrase.";
}


// *** Bulk import entries
if(isset($_POST['bulkimportentries'])){
  $count = 0;
  $logmsg = "";
  $errmsg = "";
  //print "<p>importing: '".$_FILES['importfile']['tmp_name']."'";  
  $importcat = escape_for_db($_POST['importcat']);
  // read file line by line
  $handle = fopen ($_FILES['importfile']['tmp_name'], "r");
  $title=""; $subtitle=""; $url=""; $login=""; $password=""; $note="";
  while (!feof($handle)) {
    $line = fgets($handle); // get next line
    if (preg_match("/^Title:\s*(\S.*)$/", $line, $matches) or feof($handle)){
      if ($title!=""){
        // save the entry
        //print "SAVE ENTRY $title - $subtitle - $url - $login - $password - $note <br>";
        $title  = escape_for_db($title);
        $subtitle  = escape_for_db($subtitle);
        if(entry_index($vlt, $importcat, $title, $subtitle) <0){
          // *** create new entry
          $encpart  = svencrypt($_POST['pf'], $preamble, $login, $url, $password, "", "", $note);
          $newentry = $importcat."\t".$title."\t".$subtitle."\t".time()."\t\t\t\t\t\t".$encpart;
          // append it to the vault file
          array_push($vlt, $newentry);
          sort($vlt);
          $logmsg .= "Entry $title $subtitle saved.";
          $count++;
        }
        else{
          $errmsg .= "Could not save entry '$title': an entry with this category and title already exists.";
        }
        
        $title=""; $subtitle=""; $url=""; $login=""; $password=""; $note="";
      }
      $title = trim($matches[1]);
    }
    elseif (preg_match("/^Subtitle:\s*(\S.*)$/", $line, $matches)){
      $subtitle = trim($matches[1]);
    }
    elseif (preg_match("/^URL:\s*(\S.*)$/", $line, $matches)){
      $url = trim($matches[1]);
    }
    elseif (preg_match("/^Login:\s*(\S.*)$/", $line, $matches)){
      $login = trim($matches[1]);
    }
    elseif (preg_match("/^Password:\s*(\S.*)$/", $line, $matches)){
      $password = trim($matches[1]);
    }
    elseif (preg_match("/^Note:(.*)$/", $line, $matches)){
      $note = $matches[1];
    }
    else{
      $note .= $line;
    }
  }
  fclose ($handle);
  
  // save vaultfile
  while($vlt[0]==""){array_shift($vlt);};
  $res = update_db_file (implode("\n", $vlt));
  if ($res === false) {
    $errormsg[] = "could not save entry: error writing db file.";
    return;
  }

  $cats = categories($vlt);
  $infomsg[] = $logmsg;
  $infomsg[] = "$count entries have been imported.";
  $errormsg[] = $errmsg;
}

// Warning if SSL is not detected
if( strtolower($_SERVER['HTTPS']) == "off" || !isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS']) ) {
  if( $suppresshttpswarning == "0" ) {
    $errormsg[] = "Warning: This connection is not encrypted! This is a significant security vulnerability. You should enable HTTPS on your webserver or add SSL encryption to your web hosting package.";
    $errormsg[] = "To suppress this warning, change the \$suppresshttpswarning variable in svconfig.php to \"1\"";
  }
}


// ----------------------------------------------------------------------------------------
// *** Main Pages
// ----------------------------------------------------------------------------------------

// *** Show one entry
if (                       isset($_POST['entrysave'])    ){ $decrmode = "decrypt"; }
if (isset($_GET['dec']) or isset($_POST['entrydecrypt']) ){ $decrmode = "decrypt"; }
if (isset($_GET['del']) or isset($_POST['entrydelete'] ) ){ $decrmode = "delete"; }
if (isset($_GET['edt']) or isset($_POST['entryedit']   ) ){ $decrmode = "edit"; }

if(isset($decrmode)) {
  // decrypt the specified entry
  //print_r($_POST);

  // for security reasons, strip off all html special characters
  $pf  = escape_for_db($_POST['pf']);
  $cat = escape_for_db($_REQUEST['cat']);
  $t1  = escape_for_db($_REQUEST['t1']);
  $t2  = escape_for_db($_REQUEST['t2']);

  $pgtitle = "Decrypt Entry";
  if (isset($_POST['entrydecrypt']) or isset($_POST['entrydelete']) or isset($_POST['entryedit'])){
    // find and decrypt it
    $i = entry_index($vlt, $cat, $t1, $t2);

    if ($i >= 0){
      $recfields = explode ("\t", $vlt[$i], $nbfields);
      $decfields = svdecrypt($pf, $recfields[$nbfields-1]);
      if ($decfields[0] == $preamble){
        // decryption suceeded
        $_SESSION["failcount"] = 0;
        $encfields = $decfields;
        if ($decrmode == "decrypt"){
          // passphrase is ok, show decrypted entry
          $mode = "modify";
          include "$template/entry.php";
        }
        elseif ($decrmode == "delete"){
          // passphrase is ok, delete entry with index $i
          $res = delete_entry($vlt, $i, $cats);
          if ($res == false) {
            $errormsg[] = "could not delete entry: error writing db file.";
            return;
          }

          $entrydeleted = 1;
          include "$template/decryptform.php";
        }
        elseif ($decrmode == "edit"){
          // passphrase is ok, fill in form default values
          $mode = "modify";
          include "$template/entryform.php";
        }
        else{
          $errormsg[] = "<p><span class='warning'>Internal Error: wrong mode.";
        }
      }
      else{

        // decryption failed
        $pwdmsg = "Wrong passphrase!";

        // short delay to prevent brute force attacks
        sleep($wrongpfdelay);

        // keep track of the # of failed logins on this session
        if( ! isset($_SESSION["failcount"]) ) {
          $_SESSION["failcount"] = 0;
        }
        $_SESSION["failcount"]++;

        // report suspiciously high failure count
        if( ($_SESSION["failcount"] > $wrongpfalertthreshold) && ($wrongpfalertthreshold > 0) ) {
          reportVisitor("Too many failed decryption attempts (more than $wrongpfalertthreshold) for SimpleVault entry: [$cat: $t1 - $t2]");
          $_SESSION["failcount"] = 0;
          $pwdmsg .= " You have entered an incorrect passphrase too many times. This incident will be reported.";
        }

        include "$template/decryptform.php";

      }
    }
    else{
      $errormsg[] =  "Internal Error: Could not find an entry to decrypt.";
    }
  }
  else{
    // the entry has just been saved - offer possibility to decrypt it right away
    include "$template/decryptform.php";
  }
}


// *** Bulk decrypt entries
elseif(isset($_POST['bulkdecrypt'])){
  $i=0;
  $count = 0;
  $entries = array();
  $notdecrypted = array();
  while ($i < count($vlt)){
    if (strlen($vlt[$i])>10){  # ignore empty lines
      $recfields = explode ("\t", $vlt[$i], $nbfields);
      $decfields = svdecrypt($_POST['pf'], $recfields[$nbfields-1]);
      if ($decfields[0] == $preamble){
        $entries[] = array('recfields' => $recfields, 'decfields' => $decfields);
        $count++;
      }
      else{
        $notdecrypted[] = array('recfields' => $recfields);
      }
    }
    $i++;
  }
  $infomsg[] = "$count of ".count($vlt)." entries decrypted.";
  include "$template/entries.php";
}


// *** Show form to create a new entry
elseif(isset($_GET['new'])){
  $pgtitle = "Create a New Entry";
  $cat = $_GET['cat'];
  $t1 = "";
  $t2 = "";
  $encfields = array();
  $mode = "";
  include "$template/entryform.php";
}


// *** Show a single undecrypted entry and a form to decrypt/edit/delete it  (only used in mobile template)
elseif(isset($_GET['cat']) and isset($_GET['t1']) and isset($_GET['t2'])){
  $pgtitle = "Create a New Entry";
  $cat = $_GET['cat'];
  $t1 = $_GET['t1'];
  $t2 = $_GET['t2'];
  $mode = "";
  include "$template/decryptform.php";
}


// *** Show admin screen
elseif(isset($_GET['tools'])){
  $pgtitle = "Tools";
  include "$template/tools.php";
}

// *** Browse for existing entries
else{
  $search = $_REQUEST['s'];
  $records = array();
  foreach ($vlt as $record){
    $recfields = explode ("\t", $record, $nbfields);  
    if (   (count ($_GET) === 0     and count ($_POST) === 0)                                       // show all records if no param is set
        or (isset($_GET['cat'])     and $_GET['cat'] == $recfields[0])                              // show category records if category param is set
        or (isset($search)          and strlen($search)>1 and stripos($record, $search) !== false ) // show search result when search param is set
        ){
      array_push($records, array('cat'=>$recfields[0],'t1'=>$recfields[1],'t2'=>$recfields[2],'mdate'=>$recfields[3]));
    }
  }
  $defcat = isset($_GET['cat'])?$_GET['cat']:'';
  $pgtitle = isset($_GET['cat']) ? htmlspecialchars ($_GET['cat']) : "Browse";

  include "$template/main.php";
}



// ----------------------------------------------------------------------------------------
// *** Library, Functions
// ----------------------------------------------------------------------------------------

function svencrypt($pf, $p0, $p1, $p2, $p3, $p8, $p9, $note)
// concatenates p0..note and encrypts it with $pf
{
  global $cipher;

  $data = escape_for_db_enc($p0)."\n".escape_for_db_enc($p1)."\n".escape_for_db_enc($p2)."\n".escape_for_db_enc($p3)."\n\n\n\n\n".escape_for_db_enc($p8)."\n".escape_for_db_enc($p9)."\n".escape_for_db_enc($note);

  /* Open module, and create IV */
  $td = mcrypt_module_open($cipher, '', 'ecb', '');
  $key = substr(md5($pf), 0, mcrypt_enc_get_key_size($td));
  $iv_size = mcrypt_enc_get_iv_size($td);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

  /* Initialize encryption handle */
  if (mcrypt_generic_init($td, $key, $iv) != -1) {

    /* Encrypt data */
    $c_t = mcrypt_generic($td, $data);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
  }
  return urlencode($c_t);
}

/* -------------------------------------------------------------------------- */

function svdecrypt($pf, $data)
// decrypts data and returns an array with p0..note 
{
  global $cipher;
  global $nbencfields;
  
  $td = mcrypt_module_open($cipher, '', 'ecb', '');
  $key = substr(md5($pf), 0, mcrypt_enc_get_key_size($td));
  $iv_size = mcrypt_enc_get_iv_size($td);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

  /* Initialize encryption handle */
  if (mcrypt_generic_init($td, $key, $iv) != -1) {

    /* Reinitialize buffers for decryption */
    mcrypt_generic_init($td, $key, $iv);
    $p_t = mdecrypt_generic($td, urldecode($data));

    /* Clean up */
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
  }
  $p_t = rtrim($p_t);
  return  explode ("\n", rtrim($p_t, "\0"), $nbencfields+1);
}

/* -------------------------------------------------------------------------- */
/* -------------------------------------------------------------------------- */

function svdecodeableentry($pf, $data)
// returns true if the entry can be sucessfully decoded with the passphrase
{
  global $preamble;
  $decfields = svdecrypt($pf, $data);
  return ($decfields[0] == $preamble);
}

/* -------------------------------------------------------------------------- */

function svcountdecodeableentries($pf, $category)
// counts how many entries can be decoded with the passphrase.
// if category is set, then only the decodable entries of the category are counted
// if category is empty, all decodable entries are counted
{
  global $vlt, $nbfields;
  $count = 0;
  foreach ($vlt as $record){
    $recfields = explode ("\t", $record, $nbfields);
    if (($category=="" or $category==$recfields[0]) and svdecodeableentry($pf, $recfields[$nbfields-1])) {$count++;}
  }
  return $count;
}

/* -------------------------------------------------------------------------- */

function svcountcatentries($category)
// counts the entries in the specified category
{
  global $vlt, $nbfields;
  $count = 0;
  foreach ($vlt as $record){
    $recfields = explode ("\t", $record, $nbfields);
    if ($category==$recfields[0]) {$count++;}
  }
  return $count;
}

/* -------------------------------------------------------------------------- */
/* -------------------------------------------------------------------------- */

function delete_entry(&$vlt, $i, &$cats)
// Delete entry with index $i. The vault ($vlt) and category ($cats) arrays are updated. The vault array is saved to the file.
{
  global $vaultfname;
  
  array_splice ($vlt, $i, 1);
  $res = update_db_file (implode("\n", $vlt));
  if ($res === false) {
    return false;
  }

  $cats = categories($vlt);

  return true;
}

/* -------------------------------------------------------------------------- */

function entry_index($vlt, $cat, $tit1, $tit2)
// Returns the index of the entry specified by category-title1-title2.
// If no matching entry was found, -1 is returned.
{
  $pattern = "$cat\t$tit1\t$tit2";
  $i = 0;
  while ($i < count($vlt) and strpos( $vlt[$i], $pattern)!==0){
    $i++;
  }
  
  if ($i < count($vlt)){
    return $i;
  }
  else{
    return -1;
  }
}

/* -------------------------------------------------------------------------- */
/* -------------------------------------------------------------------------- */

function categories ($vault)
// returns an array of categories in the $vault
// precondition: $vault must be lexically ordered
{
  $cats = array();
  foreach($vault as $record){
    $recfields = explode ("\t", $record);
    if (count($cats)==0 or $cats[count($cats)-1]!=$recfields[0]){
      if (strlen($recfields[0])>0){array_push($cats, $recfields[0]);}
    }
  }
  return $cats;
}
/* -------------------------------------------------------------------------- */

function print_url ($string)
// checks, if the string is a URL. If yes, a html link is returned. Otherwise the $string is returned.
{
  $pos = strpos($string, "://");
  if ($pos===false){
    return $string;
  }
  else{
    return "<a href='$string' target='_blank'>$string</a>";
  }
}

/* -------------------------------------------------------------------------- */

// Escape characters to make it safe for showing on webpage
function escape_for_html($string)
{
  return htmlspecialchars($string); // replaces &, ", ', < and >
}

// Escape characters to make it safe for storage in the database (unencrypted part)
function escape_for_db($string)
{
  $string = strtr($string, "\r\n\t", "   ");
  return $string;
}


// Escape characters to make it safe for storage in the database (encrypted part)
function escape_for_db_enc($string)
{
  return $string;
}

// Report suspicious activity
function reportVisitor($reason)
{
  $body = "SimpleVault detected unusual user activity. $reason. User information: ";
  $body .= "IP: " . $_SERVER['REMOTE_ADDR'] . ", ";
  $body .= "Referrer: " . $_SERVER['HTTP_REFERER'] . ", ";
  $body .= "UserAgent: " . $_SERVER['HTTP_USER_AGENT'];
  error_log($body);
}

// Safely updates the password database with the contents of $data.
//
// Returns TRUE if the update was successful, FALSE otherwise
function update_db_file ($data)
{
  global $vaultfname;

  $tmp = tempnam (sys_get_temp_dir (), "simplevault");
  if ($tmp === FALSE) {
    return FALSE;
  }

  $res = file_put_contents ($tmp, $data);
	if ($res === FALSE) {
    return FALSE;
	}
  rename ($tmp, $vaultfname);

  return TRUE;
}

function selfURL(){
    if(!isset($_SERVER['REQUEST_URI'])){
        $serverrequri = $_SERVER['PHP_SELF'];
    }else{
        $serverrequri = $_SERVER['REQUEST_URI'];
    }
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = "HTTP".$s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri;
}

/* -------------------------------------------------------------------------- */
/* -------------------------------------------------------------------------- */
?>
