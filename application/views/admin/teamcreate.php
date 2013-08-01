<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("admin/header.php", $pageTitle);
?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />

<div id='column1' class="ui-widget-content ui-corner-all">
	<p class="ui-widget-header" title="PageAreaTitleBar">Teams and Users</p>
	<div>
		<ul id="usertree" class="filetree">
			<?php 
				echo $userteamtree;
			?>	
		</ul>
	</div>
</div>
<div id='column2' class="ui-widget-content ui-corner-all">
	<div id='page'>
		<p class="ui-widget-header" title="PageAreaTitleBar">
			<b class="button" title='save'><?php echo famicon("disk");?> Save Team</b>
		</p>
		
			<ul class="ui-widget-content ui-corner-all">
				<table id='accesstable'>
					<form>
						<tr>
							<td>Team Name</td>
							<td><input name='teamname' class="ui-widget-content ui-corner-all" type="text"/></td>
						</tr>
						<tr>
							<td>Team Description</td>
							<td><input name='teamdescription' class="ui-widget-content ui-corner-all" type="text"/></td>
						</tr>
					</form>
				</table>
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
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquerytreeview/jquery.treeview.css" />
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquerytreeview/jquery.treeview.js"></script>

	
	<div id="dialog-saveconfirm" title="Team Create Confirmation" class='hidden'>
		<div style="padding: 5px;" id='errorDialog' class="errorMsg hidden ui-state-error ui-corner-all"> 
			<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
			Team name already exists, please find another name.</p>
		</div>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage">Are you sure to save this team?</span>
	</div>
	<div id="generalDialog" title="Creating Team" class='hidden'>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<span id="dialogMessage2">There is no team change to update on the page.</span>
	</div>
	
	<style type="text/css">
		#usertree{
			margin: 20px;
		}
	</style>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#usertree").treeview();
			$('#nav ul').superfish();
			$(".button").button();
			
			//Parses which item is edited then passes variables via AJAX
				function saveUser()
				{
					var components = $("#accesstable form").serialize();
					var creationStatus = false;
								
						$.ajax({
						   url: "<?php echo base_url();?>access/saveteam",
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
					
				}//ends saveUser 

				//Save Engine
				$('#page .button[title="save"]').click(function(){
					$( "#dialog-saveconfirm" ).dialog({
						resizable: false,
						modal: true,
						buttons: {
							"Save Team": function() {
								//if saved, redirected to template view page
								var creationStatus = saveUser();
								if (creationStatus == true)
								{
									$('#dialogMessage2').html("Team saved. Now redirecting to teams and users page.");
									$("#generalDialog").dialog({
										resizable: false,
										modal: true
									});
									window.location = '<?php echo base_url();?>access/listusers';
									//console.log("redirecting")
								}
								else
								{
									//displays that name already exists
									$(".errorMsg").css("display", "block");
								}
							},
							Cancel: function() {
								$("#dialog-saveconfirm .errorMsg").css("display", "none");
								$(this).dialog( "close" );
							}//ends saveform
						}//ends buttons
					});
				});//ends button save click
		});
		
	</script>
</body>
</html>