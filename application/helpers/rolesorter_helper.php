<?php

//this is a verification if the user is trying to simple url force a page.
//he will be allowed due to his role [fetched from the session userData values]
function rolesort($userrole, $method)
{	
	$link = "";
	switch($userrole)
	{
		case 1:
			$link = "admin";
		break;
		/*case 2:
			$link = "director";
		break;*/
		case 2:
		case 3:
			$link = "teamleader";
		break;
		case 4:
			$link = "staff";
		break;
		default:
			$link = "Unknown";
		break;
	}
		
		return $link . "/" . $method;
}