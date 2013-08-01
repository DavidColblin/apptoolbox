<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("admin/header.php", $pageTitle);
?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	
<div id='column1' class="ui-widget-content ui-corner-all">
				<div id="accordion">
					<h3><a href="#">General Components</a></h3>
					<div>
						<ul class="componentList">
							<ul>
								<li>
									<span class='componentheader'>
										<b class="inputsIcon"></b>
										<b class='plus_icon ui-widget-content ui-corner-all'>
											<span class='ui-icon ui-icon-circle-plus'></span>
										</b> 
										Blank Input
									</span>
									<span class='hidden' title='input'>
										<div class='componentlabel'> Label </div>
										<div class='componentdata'>
											<input class='ui-widget-content ui-corner-all' type='text' value=''/>
										</div>
									</span>
								</li>
								<li>
									<span class='componentheader'>
										<b class="headersIcon"></b>
										<b class='plus_icon ui-widget-content ui-corner-all'>
											<span  class='ui-icon ui-icon-circle-plus'></span>
										</b> 
										Blank Header
									</span>
									<span class='hidden' title='header'>
										<div class='componentlabel'>header text</div>
										<div class='componentdata'></div>
									</span>
								</li>
								<li>
									<span class='componentheader'>
										<b class="separatorsIcon"></b>
										<b class='plus_icon ui-widget-content ui-corner-all'>
											<span  class='ui-icon ui-icon-circle-plus'></span>
										</b> 
										Separator
									</span>
									<span class='hidden' title='separator'>
										<div class='componentlabel'> <b type='text' style="color:#DDD; vertical-align: middle">___________________________________________</b></div>
										<div class='componentdata'></div>
									</span>
								</li>
								<li>
									<span class='componentheader'>
										<b class="datepickersIcon"></b>
										<b class='plus_icon ui-widget-content ui-corner-all'>
											<span  class='ui-icon ui-icon-circle-plus'></span>
										</b> 
										Datepicker
									</span>
									<span class='hidden' title='datepicker'>
										<div class='componentlabel'>Date</div>
										<div class='componentdata'><input type='text' class='datepicker ui-widget-content ui-corner-all' value='enter date'></div>
									</span>
								</li>
							</ul>
						</ul>
					</div>
					<?php echo $populateAccordion; ?>
				</div>
			</div>

			<div id='column2' class="ui-widget-content ui-corner-all">
				<div id='page'>
					<p class="ui-widget-header" title="PageAreaTitleBar">
						<b class="button" title='save'><?php echo famicon("disk");?> Save and Exit</b>
						<b class="button" title='cancel'><?php echo famicon("cross");?> Cancel</b>
					</p>
					<ul id='pageul' class="ui-widget-content ui-corner-all"></ul>
				</div>
			</div>

	<!-- ALERT BOX SYSTEM -->
	<div id="dialog-saveconfirm" title="Template Save Confirmation" class='hidden'>
		<form action="">
			<table>
				<h4>Saving Template</h4>
				<tr><td>Template Name </td><td><input type="text" id='templateName' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td></tr>
				<tr><td>Template Description</td><td><input type="text" id='templateDescription' class='ui-widget-content ui-corner-all' style="margin: 10px; width:150px"/></td></tr>
				<div style="padding: 5px;" class="errorMsg hidden ui-state-error ui-corner-all"> 
					<p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
					<b class='templateName'>templateName_example020</b> already exists, please find another name.</p>
				</div>
			</table>
		</form>
	</div>
	<div id="dialog-cancelconfirm" title="Template Discard Confirmation" class='hidden'>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage">Are you sure to discard this template and return to <b>Homepage</b>?</span>
	</div>
	
	<div id="generalDialog" title="Template Editor" class='hidden'>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage2">There are no components to save on the page.</span>
	</div>
	<div id="componentPropertiesArea" title="Configure component" class='hidden'>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		<span id="dialogMessage2">There are no components to save on the page.</span>
	</div>
	<!-- ENDS ALERT BOX SYSTEM -->
	
	<script type="text/javascript">
	<?php //FORCED BEACAUSE OF AJAX DYNAMIC URL ADDRESS RELATIVE TO APPLICATION ?>
			$('#page .button[title="save"]').click(function(){
				// DOM trick to change message box contents
				var components = $("#pageul").find(".component_label");
				var size = components.size();
				if (size == 0 )
				{
					$('#dialogMessage2').html("There is no component to save on the template.");
					$("#generalDialog").dialog({
						resizable: false,
						height:100,
						modal: true
					});
				}
				else
				{
					$( "#dialog-saveconfirm" ).dialog({
						resizable: false,
						height:250,
						width:400,
						modal: true,
						buttons: {
							"Save Template": function() {
							
								//if saved, redirected to template view page
								var creationStatus = pageScanner($('#templateName').val() , $('#templateDescription').val());
								if (!creationStatus){
									//displays that name already exists
									$("#dialog-saveconfirm .errorMsg")
										.css("display", "block")
										.find('.templateName')
										.html($('#templateName').val());
								}
								else{
									$('#dialogMessage2').html("Template <b>"+ $('#templateName').val()+"</b> saved. Now redirecting to templates page.");
									$("#generalDialog").dialog({
										resizable: false,
										height:100,
										modal: true
									});
									window.location = '<?php echo base_url();?>template';
									
								}
							
							},
							Cancel: function() {
								$("#dialog-saveconfirm .errorMsg").css("display", "none")
								$(this).dialog( "close" );
							}
						}
					});
				}
			});

			$('#page .button[title="cancel"]').click(function(){
				$( "#dialog-cancelconfirm" ).dialog({
					resizable: false,
					height:200,
					modal: true,
					buttons: {
						"Discard": function() {
							$(this).dialog( "close" );
							//and be redirected to homepage
						},
						Cancel: function() {
							$(this).dialog( "close" );
						}
					}
				});
			});
	
			//scans the page for all the components and pushes it to server
			function pageScanner(templateName, description){
				var components = $("#pageul").find(".component_label");
				var size = components.size();
				
				var item = '{ "templateName" : "'+ templateName +'", "description" : "'+ description +'","components": [';
				
				//problem with multi-dimensional associative array in native JS, fix is to build JSON from scratch
				if (size > 0){
					for (var i=0 ; i < size ; ++i)
					{
						var label = $(components[i]).html();
						var type = $(components[i]).attr('title');
						var additionalData = ""; //additional data such as fontsize
						
						switch(type){
							case "attach":
								var attachId = $(components[i]).attr('name');
								additionalData += ',"data":[{"attachid": "' + attachId + '"}]';
							break;
							case "separator":
								label = "";
							break;
							case "header":								
								var fontsize = $(components[i]).css('font-size').replace("px", "");
								additionalData += ',"data":[{"font-size": "' + fontsize + '"}]';
							break;
						}//ends switch
						
						item += '{"type":"'+type+'","label":"'+label+'","position":"'+ i +'"' + additionalData +'}';
						
						//if not last json item, add comma
						((size-1) != i)?item += ",":"";
					}//ends for
					item += ']}';
					
					//THAT'S IT! item is the JSON of the components on the page.
					//stringify not necessary but recommended. turns json in text for transfer (but still recognizable as json for php)
					var jsondata = JSON.stringify($.parseJSON(item));
					var creationStatus = false;
						
					$.ajax({
					   type: "POST",
					   url: "<?php echo base_url();?>template/templateCreatefunction",
					   dataType: "json",
					   data: {jsonobj: jsondata},
					   async: false,
						success: function(msg){
							if (msg)
							{
								creationStatus = msg;
							}
						}
					});
					//console.log("Creation is " + creationStatus)
					return creationStatus;
				}
				else
				{
					return false;
				}

			}//ends pageScanner
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
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.mouse.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.draggable.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.sortable.js"></script>	
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jqueryui/ui/jquery.ui.dialog.js"></script>
	
	<!-- For save dialog box and autocomplete-->	
	<script type="text/javascript" src="<?php echo base_url();?>template_js/json2.js"></script> <!-- for sending JSON via AJAX-->
	<script type="text/javascript" src="<?php echo base_url();?>template_js/template_create.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#nav ul').superfish();
			$(".button").button();
		});
	</script>
</body>
</html>
