		<div id="head"> <div class="logo"><a href="index.php"><img src="tpl-std/img/logo.png" alt="simplevault logo"/></a></div></div>
		<div id="menu">
			<form id="searchForm" action="index.php" method="get">
				<div class="leftmenu">
					<div id="cat" class="categories element">
						<div id="categories" class="elediv">
							Categories <div class="icon-angle-down"></div>
						</div>
						<div id="submenu">
							 <?php foreach ($cats as $currcat){ ?>
								<a href="?cat=<?php echo urlencode($currcat); ?>" class="menuentry">
									<?php echo escape_for_html($currcat);  echo " (".svcountcatentries($currcat).")"; ?>
								</a>  
						<?php } ?>
						</div>
					</div>
	
					<div  class="element">
						<div id="search" class="elediv">
							<input id="inputIcon"  placeholder="seach.." type="text" name="s" value="<?php echo htmlspecialchars ($filter) ?>" size ="7" id="inpfield" />
							<div id="perform-search" class="icon-search"></div>
							<script>
								$(document).ready(function(){
									$("#perform-search").click(function(){
										$("#searchForm").submit();
									
									});
								});
							</script>
						</div>
					</div>
	
				</div>
				<div class="rightmenu">
					<a href="?new=1&amp;cat=<?php echo urlencode($defcat) ?>" class="Add element">
						<div id="add" class="elediv" >
							<div class="icon-plus"></div>  Add new entry
						</div>
					</a>
					<a href="?tools" class="tools element last">
						<div id="add" class="elediv">
							<div class="icon-wrench"></div>  Tools
						</div>
					</a>	
				</div>
			</form>
		</div>
		



<?php foreach($errormsg as $msg){ ?>
<div class="warn"><span class="icon-warning-sign icon-2x pull-left"></span> <span><?php echo escape_for_html($msg) ?></span> <span class="icon-remove icon-2x pull-right"></span></div>
<?php } ?>

<?php foreach($infomsg as $msg){ ?>
<div class="info"><span class="icon-info-sign icon-2x pull-left"></span> <span><?php echo escape_for_html($msg) ?></span> <span class="icon-remove icon-2x pull-right"></span></div>
<?php } ?>

