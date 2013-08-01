	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/editor.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>template_css/templatelist.css" />
	
	<div id='column2' class="ui-widget-content ui-corner-all">
		<div id='page'>
			<p class="ui-widget-header" title="PageAreaTitleBar">
				Toggle Column:
				<span id="columnActivators">
					<input type="checkbox" title='t_created_by'  checked="checked" id="check2"/><label for="check2">Created By</label>
					<input type="checkbox" title='t_created_date' id="check3"/><label for="check3">Created Date</label>
					<input type="checkbox" title='t_last_editor' id="check4" /><label for="check4">Last Editor</label>
					<input type="checkbox" title='t_last_edited' id="check5" /><label for="check5">Last Date Edited</label>
					<input type="checkbox" title='t_description' checked="checked" id="check7"/><label for="check7">Description</label>
				</span>
					<button class='button' id='filterButton'>Filter</button>
			</p>
	
			<table id="columnList">
				<tr> <!-- HEADERS -->
					<th class='t_id'>Template Id</th>
					<th class='t_name'>Template Name</th>
					<th class='t_created_by'>Created by</th>
					<th class='t_created_date'>Create Date</th>
					<th class='t_last_editor'>Last Editor</th>
					<th class='t_last_edited'>Last Edited</th>
					<th class='t_description'>Description </th>
				</tr>
				<?php 
					echo $templateList;
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
			
			//HTML doesnt allow rows nested in anchors. So programmatically bind row to link.
			$('#columnList tr').bind('click', function(){
				window.location = "<?php echo base_url();?>form/create/" + $(this).find(".t_id").html();
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
