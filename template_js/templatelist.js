//Hides the list columns
$('.t_created_date, .t_last_editor, .t_last_edited, #filter').toggle();

//Filter button function
$('#filterButton').click(function(){
	$('#filter').toggle();
});

//To activate/desactivate columns. Remind that each td of each column shares a common class.
$("#columnActivators input[type='checkbox']").click(function(){
	var columnClass = "." + $(this).attr('title');
	$(columnClass).toggle();
});

//Click 'Search Text' button
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

//When livesearch mode is on, then do the change upon keyup on textfield.
$("#searchtext").keyup(function(){
	if ($("#livesearch").attr("checked"))
	{
		scanList();
	}
});

//Searches the table for the text.
function scanList()
{
	var search = $("#searchtext").val();
	$("#columnList tr").hide(0);
	$("#columnList tr:first-child").show(0); //Show the table headers
	$("#columnList td:contains(" + search +")").parent().show(0);
	//console.log(search)
}