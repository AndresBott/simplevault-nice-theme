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
				<h2> <i class=" icon-unlock "></i> Bulk Decrypt Entries</h2>
				<form action="index.php" method="post"  autocomplete="off" class="decryptall">
					<input name="pf" value="" size="20" maxlength="200" type="password"/ placeholder="password">
					<input type="hidden" name="bulkdecrypt" value "true"/>
					<button type="submit" class="btn btn-success" >
               			 <i class="icon-ok "></i> Decrypt all
           			 </button>
           			 
				</form>
				<div class="helptext"><span>This function at once decrypts and displays all entries that can be decrypted with the <i>passphrase</i>.</span></div>
		
			</div>





			<div class="tool">
				<h2><i class=" icon-refresh "></i> Bulk Change Passphrase</h2>
				<form action="index.php" method="post"  autocomplete="off">
				  <input name="oldpf" value="" size="20" maxlength="200" type="password"/ placeholder="Old Passphrase:"> <input name="newpf" value="" size="20" maxlength="200" type="password"/ placeholder=" New Passphrase:">
				  <button type="submit" class="btn btn-success" name="bulkchangepf">
               			 <i class="icon-ok "></i> Change all
           		  </button> 
				</form>
				<div class="helptext"><span>This function scans all entries and sets the <i>new passphrase</i> for those entries that can be decrypted with the <i>old passphrase</i>. Those entries that cannot be decrypted with the <i>old passphrase</i> are not modified.</span></div>
				
			</div>		





			<div class="tool">
				<h2><i class="  icon-download-alt"></i> Import Entries</h2>
				<form action="index.php" method="post" enctype="multipart/form-data"  autocomplete="off">
					<div class="element">
							<label>Import File:</label><input name="MAX_FILE_SIZE" value="30000000" type="hidden"/><input name="importfile" size="50" type="file"/> 

					</div>
					<div class="element">
					<label>Category: </label>
					<select name="importcat" size="1">
						<?php  foreach ($cats as $cat){
					    print "<option>" . escape_for_html($cat) . "</option>";    
					  } ?>
					</select>
					or 
					<input name="cat" type="text" value="<?php echo escape_for_html($newcat) ?>" size="20" maxlength="200" placeholder=" new... (not implemented yet!!)"/>	
					</div>		
					<div class="element">
					<label>Passphrase:</label><input name="pf" value="" size="20" maxlength="200" type="password"/>
					</div>
					<div class="element">
					<button type="submit" class="btn btn-success" name="bulkimportentries">
	               			 <i class="icon-ok "></i> Import
	           		</button> 
	           		</div>
				</form>
				<div class="helptext">This function imports entries from an uploaded file into a category. All imported entries are encrypted with the same <i>passphrase</i>. <br/>
					
				<div class="format">
					<h4>Format of the uploaded file:</h4> 
					Whenever a text line starts with the string <tt>Title:</tt> a new entry is created. <br>
					The respective fields of an entry are set to the values of lines starting with <tt>Subtitle, URL:, Login:, Password: </tt>or<tt> Note: </tt> Note is the only multi-line field. <br/>
					<br/>
					Example (with two entries):<br/>
					<pre>
	Title: Host Pluto
	Login: johndoe
	Password: mypasswd
	URL: http://my.site.org
	Note:
	Here follows some
	multi-line text
	
	Title: Host Janus
	Subtitle: db account
	Login: janedoe
	Password: janespwd
	URL: 
	Note:
					</pre>				
				</div>	
				</div>
			</div>


		<div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a></span></div>
	</div>
<footer>
<footer>
<?php include "$template/incl-footer.php"; ?>

