<?php

//creates the img tag for the famfamfam silk icons in the plugins folder.
//generates as example : <img src=".....plugins/famfamfam_silkicons/icons/cross.png" alt="" />

function famicon($iconName)
{
	$link = base_url() . "plugins/famfamfam_silkicons/icons/" . $iconName .".png";
	
	return "<img src='". $link ."' alt=''>";
	
}