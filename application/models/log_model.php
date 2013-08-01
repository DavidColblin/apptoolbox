<?php

class Log_model extends CI_Model 
{
/*	$this->log_model->logForm($formId, "created", "");

	///LOG_FORM table structure 
	///
	log_form_id	int(5)	
	user_id	int(3)	
	form_id	int(4)	
	log_form_action	varchar(10)	
	log_form_value	varchar(50)	
	log_form_time datetime
	
	actions are: 'edited', 'created', 'deleted'
*/
	public function logForm($formId, $action, $value)
	{
		$role = $this->session->userdata('userrole');
		$time = date("d.m.y H:i:s");
		
		$data['user_id'] 	= $this->session->userdata('userid');
		$data['username'] 	= $this->session->userdata('username');
		$data['form_id']	= $formId;
		$data['action']		= $action;
		$data['value']		= $value;
		$data['time']		= $time;

		$this->db->insert('log_form', $data);
	}
	
	public function logTemplate($templateId, $action, $value)
	{
		$role = $this->session->userdata('userrole');
		$time = date("d.m.y H:i:s");
		
		$data['user_id'] 		= $this->session->userdata('userid');
		//$data['username'] 		= $this->session->userdata('username');
		$data['template_id']	= $templateId;
		$data['action']			= $action;
		$data['value']			= $value;
		$data['time']			= $time;

		$this->db->insert('log_template', $data);
	}
	
	public function logUser($userId, $action, $team)
	{
		$data["admin_user_id"] 	= $this->session->userdata('userid');
		$data["user_id"]		= $userId;
		$data["action"]			= $action;
		$data["team"]			= $team;
		$data["time"]			= date("d.m.y H:i:s");
		
		$this->db->insert('log_user_mgt', $data);
	}
	
	public function getFormsLog($logCount)
	{	
		if ($logCount != "All")
		{
			$this->db->limit($logCount);
		}
		
		$this->db->order_by('time');
		return $this->db->get('log_form');
	}
	
	public function getTemplatesLog($logCount)
	{	
		if ($logCount != "All")
		{
			$this->db->limit($logCount);
		}
		
		$this->db->order_by('time');
		return $this->db->get('log_template');
	}

	//Get information about user creation, editing and others.
	public function getUsersLog($logCount)
	{	
		//NOTE:: this table (log_user_mgt) have been deleted in the database.
		// because of lack of time, this feature is reserved for later.
		
		if ($logCount != "All")
		{
			$this->db->limit($logCount);
		}
		
		$this->db->order_by('time');
		return $this->db->get('log_user_mgt');
	}
	
	//return the log in form of <li> history 
	public function getFormHistory($formId)
	{
		//<li><b class='time'> echo famicon("time");  13/11/11 - 11:30</b><b>User</b> edited myFinance<b> 2011 to 2012</b></li>
		$HTML = "";
		$value = " form";
		
		$this->db->limit(40);
		$this->db->where('form_id', $formId);
		$this->db->order_by('time');
		$logs = $this->db->get('log_form');
		
		foreach($logs->result() as $log)
		{
			//Retrieves the CURRENT name of the user else display his original name if user deleted
			$userDetails = $this->access_model->getUserById($log->user_id);
			if ($userDetails->num_rows() > 0)
			{
				foreach($userDetails->result() as $user)
				{
					$username = $user->user_username;
				}
			}
			else //if user deleted, display his original name
			{
				$username = "<p style='color:grey'>" . $log->username . "</p>";
			}
			
			if ($log->value != "")
			{
				$value = "to <b>" . $log->value . "</b>";
			}
			
			//GENERATE HTML
			$HTML .= "<li>";
				$HTML .= "<b class='time'>" . famicon("time") . $log->time . " </b>";
				$HTML .= "<b> " . $username . "</b>";
				$HTML .= " " . $log->action . " " . $value . "";
			$HTML .= "</li>";
		}
		
		return $HTML;
	}
	
	public function getTemplateHistory($templateId)
	{
		//<li><b class='time'> echo famicon("time");  13/11/11 - 11:30</b><b>User</b> edited myFinance<b> 2011 to 2012</b></li>
		$HTML = "";
		$value = " template";
		
		$this->db->limit(40);
		$this->db->where('template_id', $templateId);
		$this->db->order_by('time');
		$logs = $this->db->get('log_template');
		
		foreach($logs->result() as $log)
		{
			//Retrieves the CURRENT name of the user else display his original name if user deleted
			$userDetails = $this->access_model->getUserById($log->user_id);
			if ($userDetails->num_rows() > 0)
			{
				foreach($userDetails->result() as $user)
				{
					$username = $user->user_username;
				}
			}
			else //if user deleted, display his original name
			{
				$username = "<p style='color:grey'>" . $log->username . "</p>";
			}
			
			if ($log->value != "")
			{
				$value = "to <b>" . $log->value . "</b>";
			}
			
			//GENERATE HTML
			$HTML .= "<li>";
				$HTML .= "<b class='time'>" . famicon("time") . $log->time . " </b>";
				$HTML .= "<b> " . $username . "</b>";
				$HTML .= " " . $log->action . " " . $value . "";
			$HTML .= "</li>";
		}
		
		return $HTML;	
	}
}//ENDS LOG_MODEL CLASS