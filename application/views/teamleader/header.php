<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>IFS Application Toolbox</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/cssreset.css" />		
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jqueryui/css/ui-lightness/jquery-ui-1.8.9.custom.css"/>		
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/superfishMenu/css/superfish.css" media="screen">	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/template.css" media="screen">	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/homepage.css" media="screen">	
	<link rel="icon" href="<?php echo base_url() ?>/favicon.ico" type="image/gif">
	<script type="text/javascript" src="<?php echo base_url();?>template_js/jquery-1.5.1.min.js"></script>

	<?php
		//insert custom css here
		flush();
	?>
</head>
<body>

	<div id="body" class="ui-widget-content ui-corner-all">
		<div id="nav" class="ui-widget-content ui-corner-all">
			<div id="title"> <?php echo (isset($pageTitle))?$pageTitle:"IFS Application Toolbox";?> </div>
			<ul class="sf-menu">
				<li>
					<a href="#"><?php echo famicon('table');?> Forms</a>
					<ul>
						<li><a href="<?php echo base_url();?>form"><?php echo famicon('table_go');?> View form</a></li>
						<li><a href="<?php echo base_url();?>template"><?php echo famicon('table_add');?> Create form</a></li>
					</ul>
				</li>
				<li>
					<a href="#"><?php echo famicon('table_lightning'); ?> Templates</a>
					<ul>
						<li><a href="<?php echo base_url();?>template"><?php echo famicon('table_multiple');?> View Template List</a></li>
					</ul>
				</li>
				<li>
					<a href="#"><?php echo famicon('user');?> Users</a>
					<ul>
						<li><a href="<?php echo base_url();?>access/listusers"><?php echo famicon('user_go');?> View Teams and Users</a></li>
					</ul>
				</li>
				<li>
					<a href="<?php echo base_url();?>logout"><?php echo famicon('key_go');?> Logout <?php echo $this->session->userdata('username'); ?></a>
				</li>
					
			</ul>
		</div>
		
		
		<div id="bottom"><!-- CONTENT AREA -->