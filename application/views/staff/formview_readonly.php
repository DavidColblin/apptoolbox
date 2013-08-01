<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("staff/header.php", $pageTitle);
//STAFF VERSION : NO EDIT_FORM_ACCESS BUTTON FOR STAFF
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
		<div id="descriptionDialog" title="Form Description" class='hidden'>
			<table>
			<?php
					foreach($formDetails->result() as $formDetails)
					{
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

