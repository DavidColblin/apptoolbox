$('#compo_create_save').click(function()
{
	// DOM trick to change message box contents
	$("#dialog-confirm").attr('title',"Saving Components Confirmation");
	$("#dialog-confirm #dialogMessage").html("Are you sure to save the component <b>" + $("#tabs-2name").val() +"</b>?");
	
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:140,
		modal: true,
		buttons: {
			"Save Items": function() {
			
				var serial = $('#compo_create_form').serialize();	
					//console.log(serial);
				$.ajax({
				   type: "POST",
				   url: "phpscripts/componentsMgt.php",
				   data: serial,
				   async: false,
				   success: function(response)
					{
						$('#compo_create_form .message').css({"display" : "block"}).delay(10000).fadeOut(1000);
						//console.log("AJAX response: "+response)
					}
				}) //ends ajax
				
				$(this).dialog( "close" );
			},
			Cancel: function() {
				$(this).dialog( "close" );
			}
		}
	});
}); //ends compo_create_form

$('#typeselect').change(function()
{
	var type = $(this).val();
	var area = $("#createUponType");
	var elements = "";
	$("#addMoreOptions").css("display","none");
	switch (type)
	{
		/*case "header": 
			elements += "";
		break;
		case "input": 
			elements += "<td><span class='label'>Data</span></td><td><input type='text' name='compo_create_data' id='' value='' class='componentSearch text ui-widget-content ui-corner-all' /></td>";
		break;*/
		case "option": 
			elements += "<td><span class='label'>Data  </span></td><td><input type='text' name='' id='' value='' class='componentSearch text ui-widget-content ui-corner-all' /></td>";
			$("#addMoreOptions").css("display", "block");
		break;
		default:
			elements += "<td><span class='label'>Data</span></td><td><input type='text' name='compo_create_data' id='' value='' class='componentSearch text ui-widget-content ui-corner-all' /></td>";
			
	}
	area.html(elements);
	$(".button").button();
	
	$("#addMoreOptions").click(function(){	
		$("#createUponType").prepend("<td><span class='label'>Data  </span></td><td><input type='text' name='' id='' value='' class='componentSearch text ui-widget-content ui-corner-all' /></td>");
	});
	
}); //ends $('#typeselect').change()

$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var self = this,
			currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			self._renderItem( ul, item );
		});
	}
});

/*SEARCH DATABASE STORAGE IN VARIABLE "data" */
$(function() {
	var data = [
		{ label: "annhhx10", category: "Input" },
		{ label: "annk K12", category: "Input" },
		{ label: "annttop C13", category: "Header" },
		{ label: "anders andersson", category: "Option" },
		{ label: "andreas andersson", category: "Option" },
		{ label: "andreas johnson", category: "Option: Cars" }
	];
	
	$( ".componentSearch" ).catcomplete({
		delay: 0,
		source: data
	});
});