/**	FORM LIST **/

//Button click effect
$('#filterButton').click(function(){
	$('#filter').toggle();
});

//Hide the columns from the list at start
$('.f_created_date, .f_last_editor, .f_last_edited, #filter').toggle();

//Column Activators switch on and off columns in the list
$("#columnActivators").buttonset();
$("#columnActivators input[type='checkbox']").click(function(){
	var columnClass = "." + $(this).attr('title');
	$(columnClass).toggle();
});

//Filter Dialog Trigger
$('#filterButton').click(function(){
	$( "#dialog-filter" ).dialog({
		resizable: false,
		height:200,
		modal: false,
		buttons: {
			"Search": function() {
				scanList();
			},
			Close: function() {
				$(this).dialog( "close" );
			}
		}
	});
});

//Searches the text on the filter dialog
$("#searchtext").keyup(function(){
	if ($("#livesearch").attr("checked"))
	{
		scanList();
	}
});

//Scans the table/list for the searching value
function scanList()
{
	$("#browser ul").css("display", "block");
			
	var search = $("#searchtext").val();
	
	$(".expandable").hide(0);
	$(".columnList tr").hide(0);
	
	//shows the template Name (as li .expandable) and the table header (as tr:first-child)
	$(".columnList td:contains(" + search +")").parents("tr:first-child , .expandable").show(0);
	
}

/**	FORM CREATE	AND VIEW**/
//History Panel shows time on mouse hover
$('#history li').hover(function(){
	$(this).find('.time').css('display','block');
	}, function(){
		$(this).find('.time').css('display','none');
	}, function(){
});

//Show description Dialog
$('#page .button[title="description"]').click(function(){
	$("#descriptionDialog").dialog({
		resizable: false,
		modal: true
	});
});

//Texts changes to blue when edited
$("#pageul input, select").change(function(){  
   $(this).css('color', 'blue')
		.addClass('edited'); 
});

