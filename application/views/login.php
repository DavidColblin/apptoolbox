<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>IFS Application Toolbox</title>
	<script type="text/javascript" src="<?php echo base_url();?>template_js/jquery-1.5.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/cssreset.css" />		
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jqueryui/css/ui-lightness/jquery-ui-1.8.9.custom.css"/>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/jquery-ui-1.8.9.custom.min.js"></script>	
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.widget.js"></script>
	<link rel="icon" href="<?php echo base_url() ?>/favicon.ico" type="image/gif">
	</head>
<body>
	

	<div id='loginPanel' class='ui-widget-content ui-corner-all'>
		
		<?php echo $userAgent; ?>
		<h1>IFS Application Toolbox</h1>
		<form action="">
		<table>
			<tr>
				<td>Username</td>
				<td><input class='ui-widget-content ui-corner-all' type='text' name='username' id='username'/></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input class='ui-widget-content ui-corner-all' type='password' name='password' id='password'/></td>
			</tr>
			<tr>
				<td colspan='2'><button id='loginBtn' class='button ui-widget-content ui-corner-all'>Login <?php echo famicon("key");?> </button></td>
			</tr>
			
		</table>
		<div id='errorMsg'><?php echo famicon("cross");?> Wrong credentials, please try again.  </div>
			
		</form>
		
		
	</div>
	
	<style type="text/css">
		#loginPanel {
			padding: 20px;
			margin: 15% auto 0;
			width: 400px
		}
		
		#loginPanel table
		{
			margin: 0 auto;
		}
		
		form {
			padding: 20px;
		}
		
		input {
			padding: 5px;
			font-size: 18px;
		}
		
		.button {
			float: right
		}
		
		td {
		 padding: 5px;
		}
		
		body {
			font-family: tahoma;
			font-size: 12px;
		}
		
		#loginPanel #errorMsg{
			display:none;
			color: red;
			font-size: 10px;
		}
		
	</style>
	
	<script type="text/javascript">
		$('.button').button();
		
		$('#loginBtn').click(function(){
			$("#errorMsg").css('display' , 'none');
			if( ! sendCredentials())
			{
				$("#errorMsg").css('display' , 'inline');
			}else{
				<?php /*cookie will check if authorized anyway.
				couldnt redirect from controller FROM AN AJAX RESPONSE */?>
				window.location = '<?php echo base_url();?>form';
			}
			return false;
		});
		
		
		<?php /*Parses which item is edited then passes variables via AJAX */ ?>
				function sendCredentials()
				{
					var components = $("#loginPanel form").serialize();
					var loginStatus = false;
						$.ajax({
						   url: "<?php echo base_url();?>login/log",
						   type: "POST",
						   data: components,
						   async: false,
							success: function(msg){
								if (msg)
								{
									loginStatus = msg;
								}
							}
						}); 
					return loginStatus;
				}<?php /*ends sendCredentials */?>

	</script>
</body>
</html>