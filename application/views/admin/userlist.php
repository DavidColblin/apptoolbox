<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("admin/header.php", $pageTitle);
//ADMIN VERSION: HE ONLY CAN EDIT USERS AND TEAMS OF THE LISTS
?>
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
		.listEditIcon{
			display: none;
			float: right;
		}
	</style>
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#nav ul').superfish();
			$(".button").button();
			
			//HTML doesnt allow rows nested in anchors. So programmatically bind row to link.
			$('#browser tr').bind('click', function(){
				var userId = $(this).find('.u_userid').val();
				window.location = "<?php echo base_url();?>access/user/" + userId;
			});
			
			$('#browser li').hover(function(){
				$(this).find('.listEditIcon').css('display', 'block');
			}, function(){
				$(this).find('.listEditIcon').css('display', 'none');
			});
			
			$('.listEditIcon').click(function(){
				var teamId = $(this).parents().find('.t_teamid').val();
				window.location = "<?php echo base_url();?>access/team/" + teamId;
			});
			
			$("#browser").treeview({
				collapsed: true
			});
		});
	</script>
	
	
	
</body>
</html>

