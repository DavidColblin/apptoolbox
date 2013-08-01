	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquerytreeview/jquery.treeview.css" />	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/formcreate.css" />
			<div id='column1' class="ui-widget-content ui-corner-all">
				<p class="ui-widget-header" title="History">History</p>
				<div id='history'>
					<ul>
						<?php echo $templateHistory; ?>
					</ul>
				</div>
			</div>

			<div id='column2' class="ui-widget-content ui-corner-all">
				<div id='page'>
					<p class="ui-widget-header" title="PageAreaTitleBar">
						<b class="button" title='save'><?php echo famicon("disk"); ?> Save and Exit</b>
						<b class="button" title='cancel'><?php echo famicon("cross"); ?> Cancel</b>
						<b class="button" title='description'><?php echo famicon("table_sort"); ?> Description</b>
					</p>
					
					<ul class="ui-widget-content ui-corner-all" id="pageul">
						<?php 
							echo $populateForm;
						?>
					</ul>
					
				</div>
			</div>
		<!-- ALERT BOX SYSTEM -->
			<div id="dialog-saveconfirm" title="Form Save Confirmation" class='hidden'>
				<form>
					<table>
						<h4>Saving Form</h4>
						<tr><td>Form Name </td><td><input type="text" id='formName' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td></tr>
						<div style="padding: 5px;" class="errorMsg hidden ui-state-error ui-corner-all"> 
							<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
							<b class='formName'>templateName_example020</b> already exists, please find another name.</p>
						</div>
					</table>
				</form>
			</div>
			<div id="dialog-cancelconfirm" title="Form Discard Confirmation" class='hidden'>
				<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
				<span id="dialogMessage">Are you sure to discard this form and return to <b>Homepage</b>?</span>
			</div>
			
			<div id="generalDialog" title="Form Editor" class='hidden'>
				<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
				<span id="dialogMessage2">There are no components to save on the page.</span>
			</div>
			<div id="descriptionDialog" title="Form Description" class='hidden'>
				<table>
				<?php
					
					foreach($templateDetails->result() as $templateDetails)
					{
						echo "<input type='hidden' id='templateId' value='". $templateDetails->template_name ."' />";
						
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
		<!-- ENDS ALERT BOX SYSTEM -->
		
		<script type="text/javascript">
			$(document).ready(function(){
				
				//Attachment's icon
				$('#pageul .icon')
					.hover(function(){
						$(this).toggleClass('ui-state-hover');	})
					.click(function(){
							var attachFormId = $(this).parent().find("select").val();
							window.open('<?php echo base_url();?>form/view/' + attachFormId, "blank")
						});
			
				//All date pickers take the appropriate icon
				$(".datepicker").datepicker({
					buttonImage: "<?php echo base_url();?>plugins/famfamfam_silkicons/icons/date.png",
					buttonImageOnly: true,
					showOn: "both"
				});

				//Saving the form upon clicking "Save"
				$('#page .button[title="save"]').click(function(){
					//TODO: replace with if validation true
					if (true == false)
					{
						$('#dialogMessage2').html("Data Missing");
						$("#generalDialog").dialog({
							resizable: false,
							modal: true
						});
					}
					else
					{ //dialog pops
						$( "#dialog-saveconfirm" ).dialog({
							resizable: false,
							height:250,
							width:300,
							modal: true,
							buttons: {
								"Save form": function() {
								
									//if saved, redirected to template view page
									var creationStatus = inputParser($('#formName').val());
									//console.log("creation status: " + creationStatus)
									if (creationStatus == true){
										$('#dialogMessage2').html("Form <b>"+ $('#formName').val()+"</b> saved. Now redirecting to forms page.");
										$("#generalDialog").dialog({
											resizable: false,
											modal: true
										});
										window.location = '<?php echo base_url();?>form';
										//console.log("Now redirecting...")
									}
									else{
									//displays that name already exists as Error message
										$("#dialog-saveconfirm .errorMsg")
											.css("display", "block")
											.find('.formName')
											.html($('#formName').val());
									}
								
								},
								Cancel: function() {
									$("#dialog-saveconfirm .errorMsg").css("display", "none")
									$(this).dialog( "close" );
								}
							}
						}); //ends confirmation dialog
					}
				}); //ends button save

				//if cancel the form creation
				$('#page .button[title="cancel"]').click(function(){
					$( "#dialog-cancelconfirm" ).dialog({
						resizable: false,
						modal: true,
						buttons: {
							"Discard": function() {
								$('#dialogMessage2').html("Form discarded. Now redirecting to forms page.");
								$("#generalDialog").dialog({
											resizable: false,
											height:100,
											modal: true
										});
								window.location = '<?php echo base_url();?>form';
							},
							Cancel: function() {
								$(this).dialog( "close" );
							}
						}
					});
				});

				//function which 'parses' the whole form and passes it via AJAX to PHP function
				function inputParser(templateName)
				{
					var serial = "form="+$('#formName').val()+ "&" + $('#form').serialize();
					
					var creationStatus = false;
							
						$.ajax({
						   type: "POST",
						   url: "<?php echo base_url();?>form/formsave",
						   data: serial,
						   async: false, //async to enable variable to be passed to function
							success: function(msg){
								//console.log(msg)
								if (msg)
								{
									creationStatus = msg;
								}
							}
						});
					return creationStatus;
					
				}//ends inputParser
			}); //ENDS DOCUMENT.READY
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
	
	<!-- For save dialog box and autocomplete-->	
	<script type="text/javascript" src="<?php echo base_url();?>template_js/form.js"></script>	
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#nav ul').superfish();
			$(".button").button();
		});
	</script>
</body>
</html>

