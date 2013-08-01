<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access extends CI_Controller {

	public function index()
	{
		return $this->listusers();
		
	}
	
	public function formaccess($formId)
	{	
	
		$formDetails = $this->form_model->getFormDetails($formId);
		foreach($formDetails->result() as $form)
		{
			$data['formName'] = $form->form_name;
			$data['formId'] = $formId;
			
			$templateDetails = $this->form_model->getTemplateDetails($form->template_id);
			foreach($templateDetails->result() as $template)
			{
				$data['templateDescription'] = $template->template_description;
				$data['templateName'] = $template->template_name;
			}
		}
			
		$data['pageTitle'] = 'Form Accesses';
		$data['userlist'] = $this->_listAccessUsers($formId);
		
		$link = rolesort($this->session->userdata('userrole'), 'formaccess');
		$this->load->view($link, $data);
	}
	
	public function createuser()
	{
		$data['pageTitle'] = "Create New User";
		$data['teams'] = $this->_getTeamsInOptions();
		$data['userteamtree'] = $this->_userteamtree();
		
		$link = rolesort($this->session->userdata('userrole'), 'usercreate');
		$this->load->view($link, $data);
	}
	
	public function listusers()
	{
		$data['pageTitle'] = "User List";
		$data['userlist'] = $this->_listUsers();
		
		$link = rolesort($this->session->userdata('userrole'), 'userlist');
		$this->load->view($link, $data);
	}
	
	public function createteam()
	{
		$data['pageTitle'] = "Create New Team";
		$data['userteamtree'] = $this->_userteamtree();
		
		$link = rolesort($this->session->userdata('userrole'), 'teamcreate');
		$this->load->view($link, $data);
	}
	
	public function saveuser()
	{
		$username 	= $this->input->post('username');
		$password 	= md5($this->input->post('password'));
		$role 		= $this->input->post('role');
		$team 		= $this->input->post('team');
		$name 		= $this->input->post('name');
		$surname 	= $this->input->post('surname');
		
		echo $this->access_model->saveUser($username, $password, $role, $team, $name, $surname);
	}
	
	public function saveteam()
	{
		$teamName = $this->input->post('teamname');
		$teamDescription = $this->input->post('teamdescription');
		echo $this->access_model->saveteam($teamName, $teamDescription);
	}
	
	public function updateTeam()
	{
		$teamId = $this->input->post('teamId');
		$teamName = $this->input->post('teamname');
		$teamDescription = $this->input->post('teamdescription');
		
		echo $this->access_model->updateTeamById($teamId, $teamName, $teamDescription);
	}
	
	public function deleteTeam()
	{
		$teamId = $this->input->post('teamId');
		echo $this->access_model->deleteTeamById($teamId);
	}
	
	public function user($userId)
	{
		$user = $this->access_model->getUserById($userId);
		
		if ($user->num_rows() > 0)
		{
		
			foreach($user->result() as $userDetails)
			{
				$data['userId'] = $userDetails->user_id;
				$data['username'] = $userDetails->user_username;
				$data['password'] = $userDetails->user_password;
				$data['name'] = $userDetails->user_name;
				$data['surname'] = $userDetails->user_surname;
				$data['roleId'] = $userDetails->role_id; //necessary to define showing teams or not
				
				//Option for role
				switch ($userDetails->role_id)
				{
					case 1:
						$data['role'] = "<option value='1' selected='selected'>Administrator</option>";
					break;
					case 2:
						$data['role'] = "<option value='2' selected='selected'>Director</option>";
					break;
					case 3:
						$data['role'] = "<option value='3' selected='selected'>Teamleader</option>";
					break;
					case 4:
						$data['role'] = "<option value='4' selected='selected'>Staff</option>";
					break;
				}
				
				//Option for team
				if ($userDetails->team_id == 0)
				{
					$data['team'] = "";
				}
				else
				{
					$team = $this->access_model->getTeamById($userDetails->team_id);
					foreach($team->result() as $teamDetails)
					{
						$data['team'] = "<option selected='selected' value='" . $teamDetails->team_id . "'>" . $teamDetails->team_name . "</option>";
					}
				}
				
			}
			
			$data['userteamtree'] = $this->_userteamtree();
			$data['teams'] = $this->_getTeamsInOptions();
			$data['pageTitle'] = "View User";
			
			$link = rolesort($this->session->userdata('userrole'), 'userview');
			$this->load->view($link, $data);
		}//ends if user->num_rows >0
		else
		{
			show_error("<h2>User not found.</h2><br />Please choose again from <a href='". base_url() ."access/listusers'>user list</a>");
		}
	}
	
	public function updateuser()
	{
		$userId 	= $this->input->post('userId');
		$username 	= $this->input->post('username');
		$password 	= ($this->input->post('password')=='')?false:md5($this->input->post('password'));//if password is not altered, said false;
		$role 		= $this->input->post('role');
		$team 		= $this->input->post('team');
		$name 		= $this->input->post('name');
		$surname 	= $this->input->post('surname');
		
		echo $this->access_model->updateUserById($userId, $username, $password, $role, $team, $name, $surname);
	}
	
	public function deleteuser()
	{
		return $this->access_model->deleteUserById($this->input->post('userId'));
	}
	
	public function team($teamId)
	{
		$teams = $this->access_model->getTeamById($teamId);
		foreach($teams->result() as $team)
		{
			$data['teamName'] = $team->team_name;
			$data['teamDescription'] = $team->team_description;
		}
		
		$data['teamId'] = $teamId;
		$data['pageTitle'] = "View Team";
		$data['userteamtree'] = $this->_userteamtree();
	
		$link = rolesort($this->session->userdata('userrole'), 'teamview');
		$this->load->view($link, $data);
	}

	public function updateuserright()
	{
		$formId = $this->input->post('accessFormId');
		$userId = $this->input->post('accessUserId');
		$right = $this->input->post('right');
		
		echo $this->access_model->updateUserRight($userId, $formId, $right);
		
	}
	
	public function updateteamright()
	{
		
		$formId = $this->input->post('accessFormId');
		$teamId = $this->input->post('accessTeamId');
		$right = $this->input->post('right');
		
		echo $this->access_model->updateTeamRight($teamId, $formId, $right);
	}
	
	public function _getTeamsInOptions()
	{
		$teams = $this->access_model->getTeams();
		$optionHtml = "";
		
		if ($teams->num_rows() > 0){
			foreach ($teams->result() as $team)
			{
				$optionHtml .= "<option value='" . $team->team_id . "'>" . $team->team_name . "</option>";
			}
		}
		else
		{
			return "No teams registered";
		}
		return $optionHtml;
	}

	public function _userteamtree()
	{
		//THIS WILL PRODUCE THIS KIND OF TREE FOR JQUERYTREEVIEW
		/*<li class="closed"><span class="team">Finance</span>
			<ul>
				<li><span class="userAdmin">Jeremy</span></li>
				<li><span class="userDirector">Rishi</span></li>
				<li><span class="user">Royce</span></li>
			</ul>
		</li> */
		
		$treeHTML= "";
		
		/*FETCHES THE ADMINISTRATORS*/
		$admins = $this->access_model->getAdmins();
		$treeHTML .= "<li class='closed'><span class='team'> Administrators </span><ul>";
		foreach($admins->result() as $admin)
		{
			$treeHTML .= "<li><span class='userAdmin'>" . $admin->user_username .  "</span></li>";
		}
		$treeHTML .= "</ul></li>";
		
		/*FETCHES THE DIRECTORS*/
		$directors = $this->access_model->getDirectors();
		$treeHTML .= "<li class='closed'><span class='team'> Directors </span><ul>";
		foreach($directors->result() as $director)
		{
			$treeHTML .= "<li><span class='userDirector'>" . $director->user_username .  "</span></li>";
		}
		$treeHTML .= "</ul></li>";
		
		/*FETCHES THE TEAMS AND ITS MEMBERS*/
		$teams = $this->access_model->getTeams();
		foreach($teams->result() as $team)
		{
			$users = $this->access_model->getUserByTeam($team->team_id);
			$treeHTML .= "<li class='closed'><span class='team'>" . $team->team_name . "</span><ul>";
			
			if ($users->num_rows() > 0)
			{
				//each member of the team
				foreach($users->result() as $user)
				{
					//defines if is a teamleader or not
					if ($user->role_id == 3){ $iconClass = "userTL"; } else { $iconClass = "user"; }
					$treeHTML .= "<li><span class='" . $iconClass ."'>" . $user->user_username .  "</span></li>";
				}
			}
			else
			{
				$treeHTML .= "<li><span> Empty team. <a href='". base_url() ."access/team/". $team->team_id . "'> Delete Team? </a> </span></li>";
			}
			
			$treeHTML .= "</ul></li>";
		}
		
		return $treeHTML;
	}

	public function _listUsers()
	{
		$iconTL = famicon('user_red');
		$iconStaff = famicon('user');
		$iconTeam = famicon('group');
		$iconEDITTEAM =  "<img class='listEditIcon' src='" . base_url() . "plugins/famfamfam_silkicons/icons/group_edit.png' alt='' /> ";
		
		$table = "";
		$temp_templateName = ""; $templateChangeFlag = true;
		
		$query = $this->access_model->getTeams();
		
		$table .= $this->_listAdmins();
		$table .= $this->_listDirectors();
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result() as $team)
			{
				
				$teamId = $team->team_id;
				$result = $this->access_model->getUserByTeam($teamId);
				
				foreach($result->result() as $user)
				{
					$teamName = $team->team_name;
					
					//verifies if form is within same template.
					// if no, creates a new template list type.
					if ($temp_templateName == $teamName)
					{
						$templateChangeFlag = false;
					}
					else
					{
						//Placement Fix for </table>: if this is not the first row
						if ($temp_templateName != "")
						{
							$table .= "</table></li></ul></li>";
						}
						
						$temp_templateName = $teamName;
						$templateChangeFlag = true;
						$table .= "<li><input type='hidden' class='t_teamid' value='" . $team->team_id . "' /><span class='listTemplateName'>" . $iconTeam ." " . $teamName .  "</span><span class='templateDescription'>" . $team->team_description . $iconEDITTEAM . "</span>
									<ul>
									<li>
										<table class='columnList'>
											<tr> <!-- HEADERS -->
												<th class='u_username'>Username</th>
												<th class='u_name'>Name</th>
												<th class='u_surname'>Surname</th>
											</tr>";
					}
					
					$teamLeaderBold =  (($user->role_id)==3)?"style='font-weight:bold' ":"";
					$iconUser = (($user->role_id)==3)?$iconTL:$iconStaff;
					
					$table .= "<tr class='userrow'>";
							$table .= "<input class='u_userid' type='hidden' value='" . $user->user_id . "'/>";
							$table .= "<td class='u_username'" . $teamLeaderBold . " >" . $iconUser . " " . $user->user_username . "</td>";
							$table .= "<td class='u_name'>" . 			$user->user_name . "</td>";
							$table .= "<td class='u_surname'>" . 		$user->user_surname . "</td>";
					$table .= "</tr>";
				}
				
				
			}
		}
		else
		{
			$table .= "<h3>No Tables were returned</h3>";
		}
		
		$table .= "</table></li></ul></li>";
		return $table;
	}//ends listUsers
	
	public function _listAccessUsers($formId)
	{
		/*TODO: Can apply a per user access right. The only issue is "can a user read/write to a form and his team denied all access? */
		
		$iconTL = famicon('user_red') . " ";
		$iconStaff = famicon('user') . " "; 
		$iconTeam = famicon('group') . " "; 
		$accessOptions = ""; //Later in the function, the options will be generated with their respective team id
		
		$table = "";
		$temp_templateName = ""; $templateChangeFlag = true;
		
		$query = $this->access_model->getTeams();
		
		$table .= $this->_listAdmins();
		$table .= $this->_listDirectors();
		
		if ($query->num_rows() > 0)
		{
			foreach($query->result() as $team)
			{
				$result = $this->access_model->getUserByTeam($team->team_id);
				
				foreach($result->result() as $user)
				{
					$teamName = $team->team_name;
					
					//verifies if form is within same template.
					// if no, creates a new template list type.
					if ($temp_templateName == $teamName)
					{
						$templateChangeFlag = false;
					}
					else
					{
						//Placement Fix for </table>: if this is not the first row
						if ($temp_templateName != "")
						{
							$table .= "</table></li></ul></li>";
						}
						
						
						
						$temp_templateName = $teamName;
						$templateChangeFlag = true;
						$table .= "<li><input type='hidden' class='t_teamid' value='" . $team->team_id . "' /><span class='listTemplateName'>" . $iconTeam . $teamName .  "</span><span class='templateDescription'>" . $team->team_description . $this->_generateTeamAccessOptions($team->team_id, $formId) . "</span>
									<ul>
									<li>
										<table class='columnList'>
											<tr> <!-- HEADERS -->
												<th class='u_username'>Username</th>
												<th class='u_name'>Name</th>
												<th class='u_surname'>Surname</th>
												<th>Access Right</th>
											</tr>";
					}
					
					//do not apply access customisation to teamleaders as they can view all forms
					if ($user->role_id < 4)
					{
						$userAccessOptions = "";
					}
					
					$teamLeaderBold =  (($user->role_id)==3)?"style='font-weight:bold' ":"";
					$iconUser = (($user->role_id)==3)?$iconTL:$iconStaff;
					
					$teamAccessOptions = 	$this->_generateTeamAccessOptions($team->team_id, $formId);
					$userAccessOptions = 	$this->_generateUserAccessOptions($user->user_id, $formId);
					
					$table .= "<tr class='userrow'>";
							$table .= "<input class='u_userid' type='hidden' value='" . $user->user_id . "'/>";
							$table .= "<td class='u_username'" . $teamLeaderBold . " >" . $iconUser . $user->user_username . "</td>";
							$table .= "<td class='u_name'>" . 		$user->user_name . "</td>";
							$table .= "<td class='u_surname'>" . 		$user->user_surname . "</td>";
							
							if ($user->role_id > 3)
							{
								$table .= "<td>" . 		$userAccessOptions . "</td>";
							}
							else
							{
								$table .= "<td>" . 		"" . "</td>";
							}
							
							
							//$table .= "<td>" . 		$this->_generateUserAccessOptions($user->user_id, $formId) . "</td>";
					$table .= "</tr>";
				}
				
				
			}
		}
		else
		{
			$table .= "<h3>No Tables were returned</h3>";
		}
		
		$table .= "</table></li></ul></li>";
		return $table;
	}//ends list Access Users
	
	public function _generateTeamAccessOptions($teamId, $formId)
	{
		$noaccess = "";
		$read = "";
		$write = "";
		
		$teamAccessOpt = $this->access_model->getTeamAccessOption($teamId, $formId);
		if($teamAccessOpt->num_rows() > 0) //if no right stored, declare as no access
		{
			foreach($teamAccessOpt->result() as $teamAcc)
			{
				switch ($teamAcc->access_right)
				{
					case "1":
						$write = "checked";
					break;
					case "2":
						$read = "checked";
					break;
					case "3":
						$noaccess = "checked";
					break;
				}
			}
		}
		else
		{
			$noaccess = "checked";
		}
		
		/*return "<form class='teamAccessOptions' name='teamAccessOptForm' id='team" . $teamId . "'><b class='saveMsg'></b>
					<input type='hidden' name='accessTeamId' value='". $teamId ."'>
					<input type='hidden' name='accessFormId' value='". $formId ."'>
					<input type='radio' " . $noaccess. " name='right' value='3' /> NoAccess
					<input type='radio' " . $read. "  name='right' value='2' /> Read 
					<input type='radio' " . $write. "  name='right' value='1' /> Write
				</form>";*/
		return "<form class='teamAccessOptions' name='teamAccessOptForm' id='team" . $teamId . "'><b class='saveMsg'></b>
			<input type='hidden' name='accessTeamId' value='". $teamId ."'>
			<input type='hidden' name='accessFormId' value='". $formId ."'>
			<input class='teamAccessOptionsRadio' type='radio' " . $noaccess. " name='right' value='3' /> NoAccess
			<input class='teamAccessOptionsRadio' type='radio' " . $read. "  name='right' value='2' /> Read 
			<input class='teamAccessOptionsRadio' type='radio' " . $write. "  name='right' value='1' /> Write
		</form>";
	}
	
	private function _generateUserAccessOptions($userId, $formId)
	{
		$inherited = "";
		$noaccess = "";
		$read = "";
		$write = "";
		
		$userAccessOpt = $this->access_model->getUserAccessOption($userId, $formId);
		if($userAccessOpt->num_rows() > 0) //if no right stored, declare as no access
		{
			foreach($userAccessOpt->result() as $userAcc)
			{
				switch ($userAcc->access_right)
				{
					case "1":
						$write = "checked";
					break;
					case "2":
						$read = "checked";
					break;
					case "3":
						$noaccess = "checked";
					break;
				}
			}
		}
		else
		{
			$inherited = "checked";
		}
		
		return "<form  class='userAccessOptions' name='userAccessOptForm' id='user" . $userId . "'><b class='saveMsg'></b>
					<input type='hidden' name='accessUserId' value='". $userId ."'>
					<input type='hidden' name='accessFormId' value='". $formId ."'>
					<input class='userAccessOptionsRadio' type='radio' " . $inherited. " name='right' value='4' /> Inherited
					<input class='userAccessOptionsRadio' type='radio' " . $noaccess. " name='right' value='3' /> NoAccess
					<input class='userAccessOptionsRadio' type='radio' " . $read. "  name='right' value='2' /> Read 
					<input class='userAccessOptionsRadio' type='radio' " . $write. "  name='right' value='1' /> Write
				</form>";
	}
	
	public function _listDirectors()
	{
		$iconDirector = famicon('user_gray');
		
		$query = $this->access_model->getDirectors();
		
		$table = "<li><span class='listTemplateName'>" . $iconDirector . " Directors" .  "</span><span class='templateDescription'>" . "The Eagle Eyes." . "</span>
									<ul>
									<li>
										<table class='columnList'>
											<tr> <!-- HEADERS -->
												<th class='u_username'>Username</th>
												<th class='u_name'>Name</th>
												<th class='u_surname'>Surname</th>
											</tr>";
		
		foreach($query->result() as $directors)
		{
			$table .= "<tr>";
				$table .= "<input class='u_userid' type='hidden' value='" . $directors->user_id . "'/>";
				$table .= "<td class='u_username'><b>" . $iconDirector . $directors->user_username . "</b></td>";
				$table .= "<td class='u_name'>" . 			$directors->user_name . "</td>";
				$table .= "<td class='u_surname'>" . 		$directors->user_surname . "</td>";
			$table .= "</tr>";
		}
		
		$table .= "</table></li></ul></li>";
		return $table;
	}
	
	public function _listAdmins()
	{
		$iconAdmins = famicon('user_suit');
		
		$query = $this->access_model->getAdmins();
		$table = "<li><span class='listTemplateName'>" . $iconAdmins . " Administrators" .  "</span><span class='templateDescription'>" . "The Ones." . "</span>
									<ul>
									<li>
										<table class='columnList'>
											<tr> <!-- HEADERS -->
												<th class='u_username'>Username</th>
												<th class='u_name'>Name</th>
												<th class='u_surname'>Surname</th>
											</tr>";
		
		foreach($query->result() as $admins)
		{
			$table .= "<tr>";
				$table .= "<input class='u_userid' type='hidden' value='" . $admins->user_id . "'/>";
				$table .= "<td class='u_username'><b>" . $iconAdmins . $admins->user_username . "</b></td>";
				$table .= "<td class='u_name'>" . 			$admins->user_name . "</td>";
				$table .= "<td class='u_surname'>" . 		$admins->user_surname . "</td>";
			$table .= "</tr>";
		}
		
		$table .= "</table></li></ul></li>";
		return $table;
	}
}//ends Access class