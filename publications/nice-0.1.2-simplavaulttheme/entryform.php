<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<head>
<?php include "$template/incl-head.php"; ?>
</head>

<body>
	<div id="wrap">
		<?php include "$template/incl-titlebar.php"; ?>

			<form id="form" action="<?php echo $myfname ?>" method="post" autocomplete="off">
			<div class="tool">
				<h2> <i class=" icon-plus"></i> <?php echo $pgtitle ?></h2>

					<input name="defcat" type="hidden" value="<?php echo escape_for_html($cat) ?>"/>
					<input name="deft1"  type="hidden" value="<?php echo escape_for_html($t1) ?>"/>
					<input name="deft2"  type="hidden" value="<?php echo escape_for_html($t2) ?>"/>
					<input name="mode"   type="hidden" value="<?php echo escape_for_html($mode) ?>"/>
					<input name="entrysave"   type="hidden" value="true"/>
					
					<div class="element">
						<label> Category:  <i class="icon-asterisk small-icon abstop"></i></label>
						<select id = "catdl" name="catdl" size="1">
						    <option></option>
						    <?php
						      foreach ($cats as $currcat){
						      print "<option".($cat==$currcat ? " selected":"").">" . escape_for_html($currcat) . "</option>";
						      }
						    ?>
					    </select> 
					     or 
					    <input id = "cat1"name="cat" type="text" value="<?php echo escape_for_html($newcat) ?>" size="20" maxlength="200" placeholder=" new..."/>
					    <i id="catok" class="icon-ok" style="font-size: 1.3em; color:#49A318; display: none;"></i>
					</div>

					<div class="element">
						<label>Title: <i class="icon-asterisk small-icon abstop"></i></label>
						<input id="t1" name="t1" type="text" value="<?php echo escape_for_html($t1) ?>" size="20" maxlength="200"/>
						<i id="t1ok" class="icon-ok" style="font-size: 1.3em; color:#49A318; display: none;"></i>
					</div>
					
					<div class="element">
						<label>Subtitle: </label>
						<input name="t2" type="text" value="<?php echo escape_for_html($t2) ?>" size="20" maxlength="200"/>
					</div>
			</div>
			<div class="tool encrypted follow">
				<h4> <i class=" icon-lock"></i> Encrypted data:</h4>	
				
				<div class="element">
					<label>URL:</label>
					<input name="newp2"    type="text" value="<?php echo escape_for_html($encfields[2]) ?>" size="20" maxlength="200"/>
				</div>	
					
				<div class="element">
					<label>Login:</label>
  					<input name="newp1"    type="text" value="<?php echo escape_for_html($encfields[1]) ?>" size="20" maxlength="200"/>
				</div>						
								
				<div class="element">
					<label>Password: <i class="icon-asterisk small-icon abstop"></i></label>
  					<input id="newp3" name="newp3"    type="text" value="<?php echo escape_for_html($encfields[3]) ?>" size="20" maxlength="200"/>
					<i id="newp3ok" class="icon-ok" style="font-size: 1.3em; color:#49A318; display: none;"></i>
					<br>
  					<span style="display: inline-block; margin: 15px 5px 10px 114px ; mar">Generate:</span>
	  				<button id="genSimple" class="btn btn-success" name="bulkdecrypt" >
	               			cQ3
					</button> 
	  				<button  id="genMed" class="btn btn-success" name="bulkdecrypt" >
	               			cQ3_!
					</button> 
	  				<button  id="genHigh" class="btn btn-success" name="bulkdecrypt" >
	               			cQ3_!<£
					</button> 	           			           		
				</div>						

				<div class="element">
					<label>Custom Field:</label>
					<input name="newp8"    type="text" value="<?php echo escape_for_html($encfields[8]) ?>" size="6" maxlength="30"/>
					:
					<input name="newp9"    type="text" value="<?php echo escape_for_html($encfields[9]) ?>" size="20" maxlength="200"/>

				</div>					
								
				<div class="element">
					<label style="vertical-align: top">Note:</label>
 					<textarea name="newnote" cols="40" rows="6"><?php echo escape_for_html($encfields[$nbencfields]) ?></textarea>
				</div>						
					
			</div>
			<div class="tool follow">		
				<h4> <i class=" icon-key"></i> Passphrase:</h4>	

				<div class="element">
					<label >Passphrase:</label>
					<input id="pf" name="pf" type="password" value="" size="20" maxlength="200"/>
					<input id="display_pf" style="width: 20px;" type="checkbox"  />
					<label style="width: 120px;" for="display_pf">Display Passphrase</label>
		
				</div>		
				
				<div class="element">
					<button id="send"  class="btn btn-success" name="entrysave">
	               			 <i class="icon-ok "></i> Save entry
	           		</button>
					
				</div>
			</div>
			</form>
			
		<div class="footer"><span>Password manager powered by <a href="http://simplevault.sourceforge.net">SimpleVault</a></span></div>
	</div>
<footer>
<script>
	$(document).ready(function(){
		
		function generate_password( quality){
			switch (quality) {
			  case 'c':
			    var chars = "abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
			    break
			  case 's':
			    var chars = "!%=#-?;,:.+_abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
			    break
			  default:
			    var chars = "\\|!\"£$%&/()(=#-?^[];,:.<>+*/@abcdefghijmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
			    break
		  	}
 			var len = 22;
			var pass = "";
			for (var i = 0; i < len; i++) {
    			pass += chars.charAt(Math.floor(Math.random() * chars.length));
  			}
  			return pass;
		}
 
 
		var $CategorySelection1 = "";
		var $CategorySelection2 = "";
		
		function showHideCat(){
			if($CategorySelection1 != "" || $CategorySelection2 != ""){
				$("#catok").fadeIn("fast");
			}else{
				$("#catok").hide();
			}
			
		}
		
		$("#catdl").change(function(){
			$CategorySelection1 = $(this).val();
			showHideCat()			
		})
		
		
 		$("#cat1").change(function(){
			$CategorySelection2 = $(this).val();
			showHideCat()	
		})
		

		
  
		$("#t1").change(function(){
			if($(this).val() != ""){
				$("#t1ok").fadeIn("fast");
			}else{
				$("#t1ok").hide();
				
			}
		})
								

		
		
		$("#newp3").change(function(){
			if($(this).val() != ""){
				$("#newp3ok").fadeIn("fast");
			}else{
				$("#newp3ok").hide();
				
			}
		})			
				
				
								
		$("#genSimple").click(function(e){
			$("#newp3").val(generate_password("c")).change();
			e.preventDefault()
		})
		$("#genMed").click(function(e){
			$("#newp3").val(generate_password("s")).change();
			e.preventDefault()
		})
		$("#genHigh").click(function(e){
			$("#newp3").val(generate_password("a")).change();
			e.preventDefault()
		})
		$("#send").click(function(e){
			e.preventDefault();

			if($("#pf").val() == ""){
				var confirmar =	confirm("You have set an empty passphrase. Do you want to continue?");
			}else{
				var confirmar = true;
			}
			
			if(confirmar == true){
				$("#form").submit();
			}

		})
		
		$("input").keypress(function(event) {
			if (event.which == 13) {
			    event.preventDefault();
			    //$("form").submit();
			}
		});
		
		$("#pf").keypress(function(event) {
			if (event.which == 13) {
			    event.preventDefault();
			    $("#send").click();
			}
		});	
		
		
		$("#display_pf").click(function(){
			$("#display_pf").attr("checked", !$("#display_pf").attr("checked"))
			if($("#pf").attr("type") == "password"){
				$("#pf").attr("type","text");
			}else{
				$("#pf").attr("type","password");
			}
		})
		
		
		
	});

</script>
<?php include "$template/incl-footer.php"; ?>


