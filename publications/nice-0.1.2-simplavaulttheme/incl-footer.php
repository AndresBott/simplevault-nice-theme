	<script>
		$(document).ready(function(){
			$("#categories").click(function(){
				$("#submenu").slideToggle(100);
			});
			$(".warn .icon-remove, .info .icon-remove").click(this,function(){
				$(this).parent().fadeOut(500);
			})

		});
		
	</script>	
</footer>
</body>
</html>