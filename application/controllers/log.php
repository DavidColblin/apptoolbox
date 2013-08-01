<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller 
{
	public function index()
	{
		//redirect
	}
	
	//viewing the templates logs
	public function templates($logCount='')
	{
		if ($logCount == '')
		{
			show_error("Error: Wrong URL");
		}
		else
		{
			$data['pageTitle'] = "Templates Log";
			$data['templatesLog'] = $this->_getTemplatesLog($logCount);
			$this->load->view('admin/templateslogsview', $data);
		}
	}
	
	//viewing the forms logs
	public function forms($logCount='')
	{
		if ($logCount == '')
		{
			show_error("Error: Wrong URL");
		}
		else
		{
			$data['pageTitle'] = "Forms Log";
			$data['formsLog'] = $this->_getFormsLog($logCount);
			$this->load->view('admin/formslogsview', $data);
		}
	}
	
	//this function is not included yet but it works meanwhile for future improvement
	public function users($logCount='')
	{
		if ($logCount == '')
		{
			show_error("Error: Wrong URL");
		}
		else
		{
			$data['pageTitle'] = "Users Log";
			$data['usersLog'] = $this->_getUsersLog($logCount);
			$this->load->view('admin/userslogsview', $data);
		}
	}
	
	public function _getTemplatesLog($logCount)
	{
		$templatesLog = $this->log_model->getTemplatesLog($logCount);
		$tableHTML = "";
		
		foreach($templatesLog->result() as $templatelog)
		{
			$userDetails = $this->access_model->getUserById($templatelog->user_id);
			
			//Retrieves the CURRENT name of the user else display his original name if user deleted
			if ($userDetails->num_rows() > 0)
			{
				foreach($userDetails->result() as $user)
				{
					$username = $user->user_username;
				}
			}
			else //if user deleted, display his original name
			{
				$username = "<p style='color:grey'>" . $templateLog->username . "</p>";
			}
			
			//retrieve the form name and template name
			$templateDetails = $this->form_model->getTemplateDetails($templatelog->template_id);
			foreach($templateDetails->result() as $template)
			{
				$templateName = $template->template_name;
			}
			
			//generate HTML Table
			$tableHTML .= "<tr>";
					$tableHTML .= "<td class='l_time'>" . $templatelog->time . "</td>";
					$tableHTML .= "<td class='l_name'>" . $username . "</td>";
					$tableHTML .= "<td class='l_action'>" . $templatelog->action . "</td>";
					$tableHTML .= "<td class='l_template'>" . $templateName . "</td>";
					$tableHTML .= "<td class='l_value'>" . $templatelog->value . "</td>";
			$tableHTML .= "</tr>";
		}
		
		return $tableHTML;
	}
	
	public function _getFormsLog($logCount)
	{
		$formsLog = $this->log_model->getFormsLog($logCount);
		$tableHTML = "";
		
		foreach($formsLog->result() as $formlog)
		{
			$userDetails = $this->access_model->getUserById($formlog->user_id);
			
			//Retrieves the CURRENT name of the user else display his original name if user deleted
			if ($userDetails->num_rows() > 0)
			{
				foreach($userDetails->result() as $user)
				{
					$username = $user->user_username;
				}
			}
			else //if user deleted, display his original name
			{
				$username = "<p style='color:grey'>" . $formLog->username . "</p>";
			}
			
			//retrieve the form name and template name
			$formDetails = $this->form_model->getFormDetails($formlog->form_id);
			foreach($formDetails->result() as $form)
			{
				$formname = $form->form_name;
				$templateId = $form->template_id;
				$templateDetails = $this->form_model->getTemplateDetails($templateId);
				foreach($templateDetails->result() as $template)
				{
					$templateName = $template->template_name;
				}
			}
			
			//generate HTML Table
			$tableHTML .= "<tr>";
					$tableHTML .= "<td class='l_time'>" . $formlog->time . "</td>";
					$tableHTML .= "<td class='l_name'>" . $username . "</td>";
					$tableHTML .= "<td class='l_action'>" . $formlog->action . "</td>";
					$tableHTML .= "<td class='l_form'>" . $formname . "</td>";
					$tableHTML .= "<td class='l_value'>" . $formlog->value . "</td>";
					$tableHTML .= "<td class='l_template'>" . $templateName . "</td>";
			$tableHTML .= "</tr>";
		}
		
		
		return $tableHTML;
	}

	public function _getUsersLog($logCount)
	{
		$usersLog = $this->log_model->getUsersLog($logCount);
		$tableHTML = "";
		/*
			admin_user_id	int(3)	
			user_id	varchar(3)	
			action	varchar(10)	
			team	varchar(50)	
			time
		*/
		
		foreach($usersLog->result() as $userlog)
		{
			//admin added staff to team teamone
			
			$adminUsername = $this->access_model->getUsernameById($userlog->admin_user_id);
			if (! $adminUsername)
			{
				$adminUsername = "" . $userlog->admin_user_id;
			}
			
			$username = $this->access_model->getUsernameById($userlog->user_id);
			if (! $username)
			{
				$username = "<p style='color:grey'>" . $userlog->user_id . "</p>";
			}
			
			
				$teamName = $this->access_model->getTeamNameById($userlog->team);
				if (!$teamName)
				{
					$teamName = "<p style='color:grey'>" . $userlog->team . "</p>";
				}
			
			//generate HTML Table
			$tableHTML .= "<tr>";
					$tableHTML .= "<td class='l_time'>" . $userlog->time . "</td>";
					$tableHTML .= "<td class='l_adminUsername'>" . $adminUsername . "</td>";
					$tableHTML .= "<td class='l_action'>" . $userlog->action . "</td>";
					$tableHTML .= "<td class='l_user'>" . $username . "</td>";
					//$tableHTML .= "<td class='l_value'>" . $teamName . "</td>";
			$tableHTML .= "</tr>";
		}
		
		return $tableHTML;
	}
	
}//ENDS CLASS LOG
