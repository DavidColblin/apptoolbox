<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("admin/header.php", $pageTitle);
//ADMIN VERSION: HE ONLY CAN EDIT USERS AND TEAMS OF THE LISTS
?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/templatelist.css" />
	
	<div id='column2' class="ui-widget-content ui-corner-all">
		<div id='page'>
			<p class="ui-widget-header" title="PageAreaTitleBar">
				Toggle Column:
				<span id="columnActivators">
					<input type="checkbox" title='l_time' checked="checked" id="check2"/><label for="check2">Date and Time</label>
					<input type="checkbox" title='l_name'  checked="checked" id="check3"/><label for="check3">Username</label>
					<input type="checkbox" title='l_action'  checked="checked" id="check4" /><label for="check4">Action</label>
					<input type="checkbox" title='l_form'  checked="checked" id="check5" /><label for="check5">Form</label>
					<input type="checkbox" title='l_value' checked="checked" id="check7"/><label for="check7">Value</label>
					<input type="checkbox" title='l_template' checked="checked" id="check8"/><label for="check8">Template</label>
				</span>
					<button class='button' id='filterButton'>Filter</button>
			</p>
	
			<table id="columnList">
				<tr>  
					<th class='l_time'>Date and Time</th>
					<th class='l_name'>Username</th>
					<th class='l_action'>Action</th>
					<th class='l_form'>Form</th>
					<th class='l_value'>Value</th>
					<th class='l_template'>Template</th>
				</tr>
				<?php 
					echo $formsLog;
				?>
			</table>
			
		</div>
	</div>
	
	<div id="dialog-filter" title="List Filter" class='hidden'>
		<form action="">
			<table>
				<tr><td>Search text</td><td><input type="text" id='searchtext' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td></tr>
				<tr><td>Search as I type</td><td><input type="checkbox" id='livesearch' class='ui-widget-content ui-corner-all' style="margin: 10px;"/></td></tr>
			</table>
		</form>
	</div>
	<script type="text/javascript" src="<?php echo base_url();?>template_js/templatelist.js"></script>
	<script type="text/javascript">
			
			$(document).ready(function(){
				$("#columnActivators").buttonset();
				$('#nav ul').superfish();
				$(".button").button();
			});
			
	</script>
	
	</div><!-- ENDS CONTENT AREA -->
		
		
	</div>
	
	<!-- As best practice, place Javascript in the end so as document is rendered first (visual aspect) then functionalities added
		http://developer.yahoo.com/performance/rules.html-->
	<script type="text/javascript" src="<?php echo base_url();?>plugins/superfishMenu/js/hoverIntent.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/superfishMenu/js/superfish.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/jquery-ui-1.8.9.custom.min.js"></script>	
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.dialog.js"></script>
	

</body>
</html>
