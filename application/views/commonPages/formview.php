<?php 
	//ADMIN AND TEAMLEADER VERSION: ONLY THEM CAN EDIT THE FORM ACCESSES.
?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquerytreeview/jquery.treeview.css" />	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/formcreate.css" />
			<div id='column1' class="ui-widget-content ui-corner-all">
				<p class="ui-widget-header" title="PageAreaTitleBar"><?php //echo $formName; ?> History</p>
				<div id='history'>
					<ul>
						<?php echo $formHistory; ?>
					</ul>
				</div>
			</div>

			<div id='column2' class="ui-widget-content ui-corner-all">
				<div id='page'>
					<p class="ui-widget-header" title="PageAreaTitleBar">
						<b class="button" title='update'><?php echo famicon("disk"); ?> Update and Exit</b>
						<b class="button" title='description'><?php echo famicon("table_sort"); ?> Description</b>
						<b class="button" title='formaccess'><?php echo famicon("group_key"); ?> Edit Form Accesses</b>
						<b class="button" title='editDetails'><?php echo famicon("table_edit"); ?> Edit Form Name</b>
					</p>
					
					<ul class="ui-widget-content ui-corner-all" id="pageul">
						<?php 
							echo $populateForm;
						?>
					</ul>
					
				</div>
			</div>
		<!-- ALERT BOX SYSTEM -->
		<div id="descriptionDialog" title="Form Description" class='hidden'>
			<table>
			<?php
					foreach($formDetails->result() as $formDetails)
					{
						$formName = $formDetails->form_name ;
						echo "<input type='hidden' id='formId' value='". $formDetails->form_id ."' />";
						echo "<tr><td>Form Name </td><td>". $formDetails->form_name . "</td></tr>";
						echo "<tr><td>Form Creator </td><td>". $formDetails->form_creator . "</td></tr>";
						echo "<tr><td>Form Date Created </td><td>". $formDetails->form_date_created . "</td></tr>";	
						echo "<tr><td>Form Last Editor </td><td>". $formDetails->form_editor . "</td></tr>";
						echo "<tr><td>Form Date Edited </td><td>". $formDetails->form_date_edited . "</td></tr>";
					}
					
					echo "<hr />";
					foreach($templateDetails->result() as $templateDetails)
					{
						echo "<input type='hidden' id='templateId' value='". $templateDetails->template_id ."' />";
						
						echo "<tr><td>Template Name: </td><td>" . $templateDetails->template_name  . "</td></tr>";
						echo "<tr><td>Template Creator: </td><td>" . $templateDetails->template_creator . "</td></tr>";
						echo "<tr><td>Template Date Created: </td><td>" . $templateDetails->template_date_created . "</td></tr>";	
						echo "<tr><td>Template Last Editor: </td><td>" . $templateDetails->template_last_editor . "</td></tr>";
						echo "<tr><td>Template Date Edited: </td><td>" . $templateDetails->template_date_edited  . "</td></tr>";
						echo "<tr><td>Template Description: </td><td>" . $templateDetails->template_description . "</td></tr>";
					}
					
				?>
			</table>
		</div>
		<div id="dialog-cancelconfirm" title="Form Discard Confirmation" class='hidden'>
				<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
				<span id="dialogMessage">Are you sure to discard this form and return to <b>Homepage</b>?</span>
		</div>
		<div id="dialog-saveconfirm" title="Form Update Confirmation" class='hidden'>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<span id="dialogMessage">Are you sure to update this form and return to <b>form list</b>?</span>
		</div>
		<div id="generalDialog" title="Form Editor" class='hidden'>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<span id="dialogMessage2">There is no component change to update on the page.</span>
		</div>
		<div id="dialog-editDetails" title="Form Details" class='hidden'>
			<form>
				<table>
					<h4>Edit Form Name</h4>
					<tr>
						<td>Form Name </td>
						<td><input type="text" value='<?php echo $formName; ?>' id='formName' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td>
					</tr>
					<div style="padding: 5px;" class="errorMsg hidden ui-state-error ui-corner-all"> 
						<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
						<b class='formName'>templateName_example020</b> already exists, please find another name.</p>
					</div>
				</table>
			</form>
		</div>
		<!-- ALERT BOX SYSTEM -->
		<script type="text/javascript">
			$(document).ready(function(){
				
				$('#nav ul').superfish();
				$(".button").button();
			
				//Datepickers' icon
				$(".datepicker").datepicker({
					buttonImage: "<?php echo base_url();?>plugins/famfamfam_silkicons/icons/date.png",
					buttonImageOnly: true,
					showOn: "both"
				});
				
				//Attachment Icon
				$('#pageul .icon')
					.hover(function(){
						$(this).toggleClass('ui-state-hover');	})
					.click(function(){
							var attachFormId = $(this).parent().find("select").val();
							window.open('<?php echo base_url();?>form/view/' + attachFormId, "blank")
						});
				
				//Parses which item is edited then passes variables via AJAX
				function updateParser()
				{
					var editedComponents = $("#page").find(".edited").serialize();
					var serial = "template="+ $("#templateId").val() +"&form=" + $("#formId").val() +"&" + editedComponents;
					var creationStatus = false;
								
						$.ajax({
						   url: "<?php echo base_url();?>form/formupdate",
						   type: "POST",
						   data: serial,
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
					
				}//ends updateParser 
				
				function editFormDetails()
				{
					var serial = "formname="+$('#formName').val() + "&" + "templateid="+ $('#templateId').val() + "&" + "formid=" + $('#formId').val();
					
					var editStatus = false;
							
						$.ajax({
						   type: "POST",
						   url: "<?php echo base_url();?>form/formeditdetails",
						   data: serial,
						   async: false, //async to enable variable to be passed to function
							success: function(msg){
								//console.log(msg)
								if (msg)
								{
									editStatus = msg;
								}
							}
						});
					return editStatus;
					
				}//ends editFormDetails

				//Saving the form upon clicking "Save"
				$('#page .button[title="editDetails"]').click(function(){
						$( "#dialog-editDetails" ).dialog({
							resizable: false,
							height:250,
							width:300,
							modal: true,
							buttons: {
								"Update form": function() {
									//if saved, redirected to template view page
									var creationStatus = editFormDetails();
									//console.log("creation status: " + creationStatus)
									if (creationStatus == true){
										$('#dialogMessage2').html("Form Description <b>"+ $('#formName').val()+"</b> saved.");
										$("#generalDialog").dialog({
											resizable: false,
											modal: true
										});
										
										$("#dialog-editDetails").dialog( "close" );
										$("#generalDialog").dialog( "close" );
									}
									
									
									else{
									//displays that name already exists as Error message
										$("#dialog-editDetails .errorMsg")
											.css("display", "block")
											.find('.formName')
											.html($('#formName').val());
									}
								
								},
								Cancel: function() {
									$("#dialog-editDetails .errorMsg").css("display", "none")
									$(this).dialog( "close" );
								}
							}
						}); //ends confirmation dialog
					
				}); //ends button save

				
				//Update Engine
				$('#page .button[title="update"]').click(function(){
					
					var editedComponents = $("#page").find(".edited").serialize();
					
					//if there is no edited component found.
					if (editedComponents == "")
					{
						$('#dialogMessage2').html("There is no component change to update on the page.");
						$("#generalDialog").dialog({
							resizable: false,
							modal: true
						});
						//console.log("No editedComponents")
					}
					else
					{
						//console.log("editedComponents is: " + editedComponents)
						$( "#dialog-saveconfirm" ).dialog({
							resizable: false,
							modal: true,
							buttons: {
								"Save form": function() {
									//if saved, redirected to template view page
									var creationStatus = updateParser();
								//	console.log("Update Status: " + creationStatus)
									if (creationStatus == true)
									{
										$('#dialogMessage2').html("Form saved. Now redirecting to forms page.");
										$("#generalDialog").dialog({
											resizable: false,
											modal: true
										});
										window.location = '<?php echo base_url();?>form';
										//console.log("redirecting")
									}
									else
									{
										//displays that name already exists
										$("#dialog-saveconfirm .errorMsg")
											.css("display", "block")
											.find('.formName')
											.html($('#formName').val());
									}
								},
								Cancel: function() {
									$("#dialog-saveconfirm .errorMsg").css("display", "none")
									$(this).dialog( "close" );
								}//ends saveform
							}//ends buttons
						});
					}//ends if editedComponents empty
				});
				
				$('#page .button[title="formaccess"]').click(function(){
				
					$('#dialogMessage2').html("Are you sure to leave and edit the form's access rights?");
					$("#generalDialog").dialog({
							resizable: false,
							modal: true,
							buttons: {
								"Continue": function()
									{
									window.location = '<?php echo base_url();?>access/formaccess/'+ $("#formId").val();
									},
								Cancel: function() 
									{
									$(this).dialog( "close" );
									}
							}//ends buttons
						});
				});
			});//ends doc.ready
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

	<script type="text/javascript" src="<?php echo base_url();?>template_js/form.js"></script>	

</body>
</html>

