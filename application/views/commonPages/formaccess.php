	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/formlist.css" />
		
		<div id='column2' class="ui-widget-content ui-corner-all">
				
				<div id='page'>
				
					<p class="ui-widget-header" title="PageAreaTitleBar">
						Toggle Column:
						<span id="columnActivators">
							<input type="checkbox" title='u_name' id="check4" checked="checked"/><label for="check4">Name</label>
							<input type="checkbox" title='u_surname' id="check5" checked="checked"/><label for="check5">Surname</label>
						</span>
						<button class='button' id='filterButton'>Filter</button>
						
						<div id="formTable">
							
							<img src="<?php echo base_url(); ?>plugins/icons/notepad.png" alt="" />
							<table>
								<tr><td>Form Id: </td><td><?php echo $formId ?></td></tr>
								<tr><td>Form Name: </td><td><?php echo $formName ?></td></tr>
								<tr><td>Template Name: </td><td><?php echo $templateName ?></td></tr>
								<tr><td>Template Description: </td><td><?php echo $templateDescription ?></td></tr>
							</table>
						</div>
						
						<div id="dialog-filter" title="List Filter" class='hidden'>
							<form action="">
								<table>
									<tr><td>Search text</td><td><input type="text" id='searchtext' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td></tr>
									<tr><td>Search as I type</td><td><input type="checkbox" id='livesearch' class='ui-widget-content ui-corner-all' style="margin: 10px;"/></td></tr>
								</table>
							</form>
						</div>
					</p>
			<ul id="browser">
				<?php
					echo $userlist
				?>
			</ul>
			</div>
			</div>
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
	
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquerytreeview/jquery.treeview.js"></script>
	
	
	<!-- For save dialog box and autocomplete-->	
	<script type="text/javascript" src="<?php echo base_url();?>template_js/form.js"></script>	
	<style type="text/css">
				
		#formTable{
			margin: 3%;
		}
		
		#formTable img {
			float: left;
			margin: 1%;
		}
		
		#formTable td {
			padding: 3px 4px 0px 0px;
		}
		
		#formTable tr td:nth-child(2){
			font-weight: bold
		}
		
		.teamAccessOptions, .userAccessOptions {
			float: right;
			display: inline-block;
			font-size: 10px;
		}
	</style>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#nav ul').superfish();
			$(".button").button();
			
			//$(".teamAccessOptions input[type='radio']").change(function(){
			$(".teamAccessOptionsRadio").change(function(){
				var teamId = $(this).parents('.teamAccessOptions').attr('id');
				var choice = $("#"+teamId).serialize();
				$(this).parents('.teamAccessOptions').find('.saveMsg').html("Saving..");
				
				if (saveTeamRight(choice)){
					$(this).parents('.teamAccessOptions').find('.saveMsg').html("Saved!");
				}
				else
				{
					$(this).parents('.teamAccessOptions').find('.saveMsg').html("Failed.");
				}
			});
			
			function saveTeamRight(choice)
			{
				var components = choice;
				//console.log(components)
				var creationStatus = false;
							
					$.ajax({
					   url: "<?php echo base_url();?>access/updateteamright",
					   type: "POST",
					   data: components,
					   async: false,
						success: function(msg){
							//console.log("AJAX Updated: " + msg)
							if (msg)
							{
								creationStatus = msg;
							}
						}
					}); 
				return creationStatus;
				
			}//ends saveTeamRight 
			
			
			//$(".userAccessOptions input[type='radio']").change(function(){
			$(".userAccessOptionsRadio").change(function(){
				var userId = $(this).parent('.userAccessOptions').attr('id');
				var choice = $("#"+ userId).serialize();
				//console.log("choice is" + choice)
				$(this).parents('.userAccessOptions').find('.saveMsg').html("Saving..");
				
				if (saveUserRight(choice)){
					$(this).parents('.userAccessOptions').find('.saveMsg').html("Saved!");
				}
				else
				{
					$(this).parents('.userAccessOptions').find('.saveMsg').html("Failed.");
				}
			});
			
			function saveUserRight(choice)
			{
				var components = choice;
				<?php /*console.log(components) */?>
				var creationStatus = false;
				
					$.ajax({
					   url: "<?php echo base_url();?>access/updateuserright",
					   type: "POST",
					   data: components,
					   async: false,
						success: function(msg){
							<?php /*console.log("AJAX Updated: " + msg)*/?>
							if (msg)
							{
								creationStatus = msg;
							}
						}
					}); 
				return creationStatus;
				
			}<?php /*ends saveTeamRight */?>
			
			
			$("#browser").treeview({
				collapsed: true
			});
		});
	</script>
	
	
	
</body>
</html>

