//renders accordions
$( "#accordion").accordion({
	//autoHeight: false
	fillSpace: true
});

//hover effect and trigger a function to append parent item on page
$('#accordion .plus_icon')
	.hover(function(){
		$(this).toggleClass('ui-state-hover');	})
	.click(function(){			
		//find hidden field (containing component markup) and passes them to the place on page parser.
		var componentWrapper = $(this).parent().parent().find(".hidden");
		placeComponentOnPage(componentWrapper);
});

//makes the page components sortable per drag and drop
$('#page ul').sortable({
	placeholder: "ui-state-highlight",
	revert: "true"
});

//Appends the component from the list to the form/page
function placeComponentOnPage(componentHtml)
{
	//Set of buttons
	var button_start = "<div class='button ui-corner-all ui-state-default'>";
	var button_end = "</div>";
	var icon_wrench =  button_start + "<div class='properties ui-icon ui-icon-wrench'></div>" + button_end;
	var icon_delete = button_start + "<div class='delete ui-icon ui-icon-closethick'></div>" + button_end;
	var icon_pack = icon_delete  + icon_wrench;
	
	//component type is hidden in its title.
	var component_data = $(componentHtml).find('.componentdata').html() ;
	var component_type = $(componentHtml).attr('title');
	
	//little exception if header is chosen. The whole text will be displayed as a single line and not constrained within '.componentlabel' container
		var if_header_exception= "";
		if (component_data == "")
		{
			var style = $(componentHtml).find('.componentlabel').attr("style");
			if_header_exception = "style='display:inline;"+ style+"'";
		}
		if (component_type == "attach")
		{
			var attachId = $(componentHtml).find('.componentlabel').attr("name");
			if_header_exception = "name= '" + attachId + "'";
		}
	
	var component_label = "<span title='"+ component_type +"' class='component_label'" + if_header_exception + ">" + $(componentHtml).find('.componentlabel').html()+ "</span>";
	
	//PLACES COMPONENT ON THE PAGE
	$("#page ul").append("<li class='component ui-corner-all'>" + component_label + component_data + icon_pack +"</li>");
	
	//There are buttons and datepickers added dynamically. 
	//They need reinitialising as they are new to DOM.
	$(".button").button();
	$(".datepicker").datepicker({
		//BECAUSE CANNOT GENERATE BASEURL() IN JS
		buttonImage: "../plugins/famfamfam_silkicons/icons/date.png",
		buttonImageOnly: true,
		showOn: "both"});
	
	//because of dynamically inserting items in sortable list,
	//buttons are not clickable unless functions declared AFTER adding.
	$('.component .properties').click(function()
	{			
		//Security Quick Fix to blur componentLabel if user tries to bypass application click edit and properties before JS renders.
		$(".component_label input").blur();
		
		var item_label = $(this).parent().parent().parent().find(".component_label");
		var item_component = $(this).parent().parent().parent();
		var propertiesArea = $("#componentPropertiesArea");
		var propertiesAreaHTML = "";	
		
		propertiesAreaHTML += "Component Label: <input type='text' id='properties_comp_label' class='ui-widget-content ui-corner-all' value='"+ item_label.html() +"'/>";
						
		switch(item_label.attr('title')){
			case "datepicker":
			case "input":
			case "attach":
				propertiesAreaHTML += "";
			break;
			case "header":
				propertiesAreaHTML += "Font Size: <div id='slider'></div>";
			break;	
			default:
				propertiesAreaHTML = "No configuration can be done to this component";
				
		}
		
		//place result on dialog box and reintialise buttons
		propertiesArea.html(propertiesAreaHTML);	
		$(".button").button();
		
		//sliderFontSize is a trick to use the same slider with different values (ex, max: 300 for input and select width, max: 30 for font-size)
		var sliderFontSize = (item_label.attr('title')=="header")? 10: 1;
		$( "#slider" ).slider({
			range: "min",
			value: 180 /sliderFontSize,
			step: 20 /sliderFontSize,
			min: 100 /sliderFontSize,
			max: 300 /sliderFontSize,
			slide: function( event, ui ) {
				switch(item_label.attr('title'))
				{
					case "header":
						$(item_label).css('font-size', ui.value)
					break;
				}
			}
		}); //ends slider 
		
		
		$( "#componentPropertiesArea" ).dialog({
				resizable: false,
				modal: true,
				buttons: {
					"Continue": function() {
						$(this).dialog( "close" );
					}}
			});
			
		$("#properties_comp_label").change(function(){
			$(item_label).html($(this).val());
		});
	}); //ENDS component properties click
	
	//deleting a component on the page
	$('.component .delete').click(function()
	{
		//closest climbs the tree to the nearest "li"
		$(this).closest("li").slideUp(200, function()
		{
			$(this).closest("li").remove();
		});
	});

	$(".component_label").click(function(){
		
		//fix to avoid opening multiple label editing at once.
		$(".component_label input").blur();
			
		var label = $(this).html();
		var type = $(label).attr('type');
		var injector = "<input type='text' class='ui-widget-content ui-corner-all' value='"+ label +"'>";
		
		if (type != "text"){
			$(this)
				.html(injector)
				.find("input")
				.focus()
				.blur(function(){
					var value =  $(this).val();
					$(this).parent().html(value);
					//console.log($(this))
				});
		/*}else{
			console.log("editing mode");*/
		}
		
	});
}//ends placeComponentOnPage

