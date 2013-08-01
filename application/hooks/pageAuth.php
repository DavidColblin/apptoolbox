<?php

//this is a hook. It is rendered BEFORE EACH CONTROLLER>>>
//this will be used to verify if the user is trying to force url or is legitimate
// it is named as model since the hook is post-controller rendered.

class Verify_Auth extends CI_Model{

	public function verify_auth()
	{
		$userRole = $this->session->userdata('userrole');
		
		//THIS LISTS ALL PAGES THAT ARE ACCESSED VIA URL (CLASSES AND METHODS)
		//THE VALUE GIVEN DEFINES WHICH ACCESS ROLE USER CAN ACCESS THE PAGE
		//ROLES: 1:ADMIN , 2:DIRECTOR, 3:TEAMLEADER, 4:STAFF/ALLUSERS
		//NOTE: ALL UPPER USER INHERITATES FROM LOWER USER PAGES
		//remember: they are case-sensitive.
		
		$pages['login']['index'] = 4;
		$pages['login']['log'] = 4;
		$pages['login']['logout'] = 4;
		
		$pages['template']['index'] = 4;
		$pages['template']['create']= 1;
		$pages['template']['edit']= 1;
		$pages['template']['templateCreatefunction'] = 1;
		
		$pages['form']['index'] = 4;
		$pages['form']['create'] = 4;
		$pages['form']['formsave'] = 4;
		$pages['form']['formupdate'] = 4;
		$pages['form']['formeditdetails'] = 3;
		$pages['form']['view'] = 4;
		
		$pages['access']['index'] = 4; //loginpage
		$pages['access']['formaccess'] = 3;
		$pages['access']['listusers'] = 4;
		$pages['access']['team'] = 1;
		$pages['access']['user'] = 1;
		$pages['access']['createteam'] = 1;
		$pages['access']['createuser'] = 1;
		$pages['access']['deleteTeam'] = 1;
		$pages['access']['deleteuser'] = 1;
		$pages['access']['saveteam'] = 1;
		$pages['access']['saveuser'] = 1;
		$pages['access']['updateTeam'] = 1;
		$pages['access']['updateteamright'] = 3;
		$pages['access']['updateuser'] = 1;
		$pages['access']['updateuserright'] = 3;
		
		$pages['log']['index'] = 1;
		$pages['log']['forms'] = 1;
		$pages['log']['templates'] = 1;
		$pages['log']['users'] = 1;
		
		//if user is not logged
		if (!$userRole) 
		{
			// if page isnt already login page
			if ($this->router->class != 'login') 
			{
				redirect(base_url());
			}
		}
		else
		{
			//echo the current page role access from the array
			//@ silents in case the URL route does not exists.
			$pageRole = @$pages[$this->router->class][$this->router->method];
			
			//if the URL cannot be viewed by this user with such role ($userrole)
			if ($pageRole < $userRole)
			{
				show_error( '<h1>You are not authorized to view this page.</h1>' , 500);
			}
		}
	}
}//ends Class