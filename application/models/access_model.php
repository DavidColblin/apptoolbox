<?php

class Access_model extends CI_Model 
{

	//User Logs in application
	function login($username, $password)
	{
		$this->db->where('user_username', $username);
		$this->db->where('user_password', $password);
		$user = $this->db->get('access_user');
		
		//user record found in database
		if ($user->num_rows() > 0)
		{
			return $user;
		}
		else
		{
			return false;
		}
	}	
	
	//Saves the new user in database after checking if username already exists
	function saveUser($username, $password, $role, $team, $name, $surname)
	{
		//verifies if username already exists
		$this->db->where('user_username', $username);
		$users = $this->db->get('access_user');
		if ($users->num_rows() > 0 )
		{
			//i.e username already exists in database
			return 0;
		}
		else
		{
			//if user is a Director or Admin
			if ($role < 3)
			{
				$team = "0";
			} 
			
			$data = array(
				'user_username' => $username,
				'user_password' => $password,
				'role_id' => $role,
				'team_id' => $team,
				'user_name' => $name,
				'user_surname' => $surname
			);
			
			
			$this->db->insert('access_user', $data);
			
			$userId = $this->db->insert_id();
			
			//log action: Disabled, to be implemented in next version
			//$this->log_model->logUser($userId, "created to", $team);
			
			return true;
		}
	}
	
	function updateTeamById($teamId, $teamName, $teamDescription)
	{
		$this->db->where('team_id !=', $teamId);
		$this->db->where('team_name', $teamName);
		$teams = $this->db->get('access_team');
		if($teams->num_rows() > 0 )
		{
			//i.e. team name already exists
			return 0;
		}
		else
		{
			$data = array( 
						'team_name' => $teamName,
						'team_description' => $teamDescription
					);
					
			$this->db->where('team_id', $teamId);
			return $this->db->update('access_team', $data);
		}
	}
	
	function updateUserById($userId, $username, $password, $role, $team, $name, $surname)
	{
		//verifies if username already exists
		$this->db->where('user_id !=', $userId);
		$this->db->where('user_username', $username);
		$users = $this->db->get('access_user');
		if ($users->num_rows() > 0 )
		{
			//i.e username already exists in database
			return 0;
		}
		else
		{
			//if user is a Director or Admin
			if ($role < 3)
			{
				$team = "0";
			} 
			
			if (!$password)
			{
				$data = array(
					'user_username' => $username,
					'role_id' => $role,
					'team_id' => $team,
					'user_name' => $name,
					'user_surname' => $surname
				);
			}
			else
			{
				//log action: Disabled, to be implemented in next version
				//$this->log_model->logUser($userId, "updated password", "");
			
				$data = array(
					'user_username' => $username,
					'user_password' => $password,
					'role_id' => $role,
					'team_id' => $team,
					'user_name' => $name,
					'user_surname' => $surname
				);
			}
			
			//log action: Disabled, to be implemented in next version
			//$this->log_model->logUser($userId, "updated details", "");
			
			$this->db->where('user_id', $userId);
			return $this->db->update('access_user', $data);
			
			
		}
	}	
	
	function deleteTeamById($teamId)
	{
		//Verifies if there are users in the team
		$users = $this->getUserByTeam($teamId);
		if ($users->num_rows() > 0 )
		{
			return false;
		}
		else
		{
			$this->db->where('team_id', $teamId);
			return $this->db->delete('access_team');
		}
		
		
		
	}
	
	function deleteUserById($userId)
	{
		$this->db->where('user_id', $userId);
		return $this->db->delete('access_user');
	}
	
	function saveTeam($teamName, $teamDescription)
	{
		//Saves new team in database after checking if teamname already exists
		$this->db->where('team_name', $teamName);
		$teams = $this->db->get('access_team');
		
		if($teams->num_rows() > 0)
		{
			return 0;
		}
		else
		{
			$data = array( 
				'team_name' => $teamName,
				'team_description' => $teamDescription);
			return $this->db->insert('access_team' , $data);
		}
	}
	
	function getTeams()
	{
		$this->db->order_by('team_name', "asc");
		return $this->db->get('access_team');
	}
	
	//return the team details from a userid in the team
	public function getTeamByUserId($userId)
	{
		$userDetails = $this->getUserById($userId);
		foreach ($userDetails->result() as $user)
		{
			$teamId = $user->team_id;
		}
		
		return $this->getTeamById($teamId);
	}

	
	public function getTeamById($teamId)
	{
		$this->db->where('team_id', $teamId);
		return $this->db->get('access_team');
	}
	
	public function getTeamNameById($teamId)
	{
		$teams = $this->getTeamById($teamId);
		foreach($teams->result() as $team)
		{
			$teamName = $team->team_name;
		}
		
		return @$teamName;
	}
	
	public function getUserByUsername($username)
	{
		$this->db->where('user_username', $username);
		return $this->db->get('access_user');
	}
	
	public function getUserByTeam($teamId)
	{
		$this->db->where('team_id', $teamId);
		$this->db->order_by('role_id', 'DESC');
		
		return $this->db->get('access_user');
	}
	
	public function getDirectors()
	{
		$this->db->where('role_id' , '2');
		return $this->db->get('access_user');
	}
	
	public function getUserById($userId)
	{
		$this->db->where('user_id', $userId);
		return $this->db->get('access_user');
	}
	
	//return Username by id
	public function getUsernameById($userId)
	{
		$userDetails = $this->getUserById($userId);
		foreach($userDetails->result() as $user)
		{
			$username = $user->user_username;
		}
		
		return $username;
	}
	
	public function getAdmins()
	{
		$this->db->where('role_id', '1');
		return $this->db->get('access_user');
	}

	/*ACCESS RIGHTS*/
	public function getTeamAccessOption($teamId, $formId)
	{
		$this->db->where('team_id', $teamId);
		$this->db->where('form_id', $formId);
		return $this->db->get('access_form_team');
	}
	
	public function getUserAccessOption($userId, $formId)
	{
		$this->db->where('user_id', $userId);
		$this->db->where('form_id', $formId);
		return $this->db->get('access_form_user');
	}
	
	public function updateUserRight($userId, $formId, $right)
	{
		$this->db->where('user_id', $userId);
		$this->db->where('form_id', $formId);
		$user = $this->db->get('access_form_user');
		
		if($user->num_rows() > 0)
		{
			//if right is 'inherited' , deleted record
			if($right == 4)
			{
				$this->db->where('form_id', $formId);
				$this->db->where('user_id', $userId);
				return $this->db->delete('access_form_user');
			}
			else 
			{//Updates current right only
				$updateArray  = array(
							'access_right' => $right
					);
				
				$this->db->where('user_id', $userId);
				$this->db->where('form_id', $formId);
				return $this->db->update('access_form_user', $updateArray);
			}
		}
		else
		{ //if no records exists, creates a new one for user.
			$insertArray = array (
				'form_id' => $formId,
				'user_id'	=> $userId,
				'access_right' => $right
			);
			
			return $this->db->insert('access_form_user' , $insertArray);
		}
	}
	
	public function updateTeamRight($teamId, $formId, $right)
	{
		$this->db->where('team_id', $teamId);
		$this->db->where('form_id', $formId);
		$team = $this->db->get('access_form_team');
		
		if($team->num_rows() > 0)
		{
			$updateArray  = array(
						'access_right' => $right
				);
			$this->db->where('team_id', $teamId);
			$this->db->where('form_id', $formId);
			return $this->db->update('access_form_team', $updateArray);
		}
		else
		{
			$insertArray = array (
				'form_id' => $formId,
				'team_id'	=> $teamId,
				'access_right' => $right
			);
			
			return $this->db->insert('access_form_team' , $insertArray);
		}
	}
	
	
}//ends Access_model class