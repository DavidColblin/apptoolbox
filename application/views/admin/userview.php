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
			<b class="button" title='update'><?php echo famicon("disk");?>Update User</b>
			<b class="button" title='delete'><?php echo famicon("cross");?>Delete User</b>
		</p>
		
			<ul class="ui-widget-content ui-corner-all">
				<table id='accesstable'>
					<form>
						<input type="hidden" name='userId' id='userId' value="<?php echo $userId; ?>"/>
						<tr>
							<td>Name</td>
							<td><input name='name' value='<?php echo $name; ?>' class="ui-widget-content ui-corner-all" type="text"/></td>
						</tr>
						<tr>
							<td>Surname</td>
							<td><input name='surname' value='<?php echo $surname; ?>' class="ui-widget-content ui-corner-all" type="text"/></td>
						</tr>
						<tr>
							<td>Username</td>
							<td><input name='username' value='<?php echo $username; ?>' class="ui-widget-content ui-corner-all" type="text"/></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input name='password' value='' class="ui-widget-content ui-corner-all" type="password"/>(alter if you want to change user password)</td>
						</tr>
						<tr>
							<td>Role</td>
							<td>
								<select id='role' class="ui-widget-content ui-corner-all" name="role">
									 <?php echo $role; ?>
									<option value="1">Administrator</option>
									<option value="2">Director</option>
									<option value="3">Teamleader</option>
									<option value="4">Staff</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Team</td>
							<td>
								<select id='team' class="ui-widget-content ui-corner-all" name="team">
									<?php 
										echo $team; //the current user team [with attrib 'selected']
										
										//if the role is either admin or director, team options is hidden by default
										if ($roleId < 3 )
										{
											echo '<script> 
													$(document).ready(function(){
														$("#team").parents("tr").hide(0); 
													});
													</script>';
										}
										
										echo $teams; //all teams available
									?>
								</select>
							</td>
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

	<div id="dialog-saveconfirm" title="User Update Confirmation" class='hidden'>
		<div style="padding: 5px;" id='errorDialog' class="errorMsg hidden ui-state-error ui-corner-all"> 
			<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
			Username already exists, please find another name.</p>
		</div>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage">Are you sure to update this user?</span>
	</div>
	<div id="generalDialog" title="Creating User" class='hidden'>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage2">There is no component change to update on the page.</span>
	</div>
	<style type="text/css">
		#usertree{
			margin: 20px;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#nav ul').superfish();
			$(".button").button();
			$("#usertree").treeview();
			
			$("#role").change(function(){
				//if option role is either Teamleader(#3) or Staff(#4)
				if ($(this).val() > 2){
					//console.log('yeah')
					$("#team").parents("tr").show(0);
				}
				else
				{
					$("#team").parents("tr").hide(0);
				}
			});
			//Parses which item is edited then passes variables via AJAX
				function updateUser()
				{
					var components = $("#accesstable form").serialize();
					var creationStatus = false;
								
						$.ajax({
						   url: "<?php echo base_url();?>access/updateuser",
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
					
				}//ends updateUser 
				
				function deleteUser()
				{
					var components = $("#userId").serialize();
					var creationStatus = false;
								
						$.ajax({
						   url: "<?php echo base_url();?>access/deleteuser",
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
					
				}//ends updateUser

				//Save Engine
				$('#page .button[title="update"]').click(function(){
					$( "#dialog-saveconfirm" ).dialog({
						resizable: false,
						modal: true,
						buttons: {
							"Update User": function() {
								//if saved, redirected to template view page
								var creationStatus = updateUser();
								if (creationStatus == true)
								{
									$('#dialogMessage2').html("User updated. Now redirecting to user list page.");
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
		
				$('#page .button[title="delete"]').click(function(){
					$('#dialogMessage2').html("Are you sure to delete this user? Deleted users cannot be recovered.");
					$( "#generalDialog" ).dialog({
						resizable: false,
						modal: true,
						buttons: {
							"Delete User": function() {
								//if saved, redirected to template view page
								var creationStatus = deleteUser();
								
								$('#dialogMessage2').html("User Deleted. Now redirecting to user list page.");
								$("#generalDialog").dialog({
									resizable: false,
									modal: true
								});
								window.location = '<?php echo base_url();?>access/listusers';
								//console.log("redirecting")
							},
							Cancel: function() {
								$("#dialog-saveconfirm .errorMsg").css("display", "none");
								$(this).dialog( "close" );
							}//ends saveform
						}//ends buttons
					});
				});//ends button delete click
		});
		
	</script>
</body>
</html>
