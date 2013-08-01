<?php

class Form_model extends CI_Model 
{
	//returns the authorized forms. only staff are bound to view and use certain forms
	public function authorizedForm($formId)
	{	
		/* RETURNED ACCESS CODES ARE
		1: WRITE
		2: READ
		3: NO ACCESS
		*/
	
		$userId = $this->session->userdata('userid');
		$userRole = $this->session->userdata('userrole');
		
		//fetches team by user id
		$this->db->where('user_id', $userId);
		$userDetails = $this->db->get('access_user');
		foreach($userDetails->result() as $user)
		{
			$teamId = $user->team_id;
		}
			
		//if staff user
		if($userRole == 4)
		{
			$this->db->where('user_id', $userId);
			$this->db->where('form_id', $formId);
			$access_form_user = $this->db->get('access_form_user');
			
			//if no customized access right in access_form_user
			if ($access_form_user->num_rows() == 0)
			{
				//fetches right from team access right
				$this->db->where('team_id', $teamId);
				$this->db->where('form_id', $formId);
				$access_form_team = $this->db->get('access_form_team');
				
				if ($access_form_team->num_rows() > 0)
				{
					foreach($access_form_team->result() as $teamAccess)
					{	
						//return 1, 2 or 3 (write, read or no access)
						return $teamAccess->access_right;
					}
				}
				else
				{
					return 3; //gives no access by default.
				}
			}
			else
			{
				//return customized user form rights
				foreach($access_form_user->result() as $userAccess)
				{	
					//return 1, 2 or 3 (write, read or no access)
					return $userAccess->access_right;
					
				}
			}
		}
		else //if not staff, then it is either teamleader, director or admin
		{
			//thus, we give full rights to these three upper roles
			return 1;
		}
	}//ends authorizedForm
	
	public function getForms()
	{
		//DO NOT FORGET TO PUT ACCESSRIGHTS TABLE

		$ACCESSRIGHTS = "<div class='editRightsIcon'>" .famicon("group_key") . " </div>"; 
		
		$table = "";
		$temp_templateName = ""; $templateChangeFlag = true;
		
		$this->db->order_by('template_id', 'asc');
		$forms = $this->db->get('template_form_properties');
				
		if ($forms->num_rows() > 0)
		{
			foreach($forms->result() as $form)
			{
				//checks if form access is authorized for user
				$authForm = $this->authorizedForm($form->form_id);
				if($authForm != 3) // 3 = no access. returned value is either 1,2 or 3.
				{
					$this->db->where('template_id', $form->template_id);
					$templates = $this->db->get('template_properties');
					
					foreach($templates->result() as $template)
					{
						$templateName = $template->template_name;
						
						//verifies if form is within same template.
						// if no, creates a new template list type.
						if ($temp_templateName == $templateName)
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
							
							$temp_templateName = $templateName;
							$templateChangeFlag = true;
							$table .= "<li><span class='listTemplateName'>" . $templateName .  "</span><span class='templateDescription'>" . $template->template_description . "</span>
										<ul>
										<li>
											<table class='columnList'>
												<tr> <!-- HEADERS -->
													<th class='f_id'>Form Id</th>
													<th class='f_name'>Form Name</th>
													<th class='f_created_by'>Created by</th>
													<th class='f_created_date'>Create Date</th>
													<th class='f_last_editor'>Last Editor</th>
													<th class='f_last_edited'>Last Edited</th>
												</tr>";
						}
					}
					
					$table .= "<tr>";
						$table .= "<td class='f_id'>" . 			$form->form_id . "</td>";
						$table .= "<td class='f_name'>" . 			$form->form_name . "</td>";
						$table .= "<td class='f_created_by'>" . 	$form->form_creator . "</td>";
						$table .= "<td class='f_created_date'>" . 	$form->form_date_created . "</td>";
						$table .= "<td class='f_last_editor'>" . 	$form->form_editor . "</td>";
						$table .= "<td class='f_last_edited'>" . 	$form->form_date_edited . "</td>";
						//$table .= "<td class='f_access_rights'>" . 	$ACCESSRIGHTS . "</td>";
					$table .= "</tr>";
					
					//closes list tags
					if ($templateChangeFlag)
					{
						//$table .= "</table></li></ul></li>";
					}
				}//ends if authorizedForm
			}
		}
		else
		{
			$table .= "<h3>No Tables were returned</h3>";
		}
			$table .= "</table></li></ul></li>";
		return $table;
	}//ends getForms
	
	public function getTemplateComponents($templateId, $fieldName)
	{
	 return $this->db->query("SELECT * FROM template_components 
								WHERE	template_id = $templateId 
								AND		t_component_field = '$fieldName'");
	}
	
	//get template form properties where formname is given
	public function getTemplateFormProperties($formId)
	{
		return $this->db->query("SELECT form_id FROM template_form_properties WHERE form_id = '" . $formId ."'");
	}
	
	public function getFormByTemplate($templateId)
	{
		return $this->db->query("SELECT * FROM template_form_properties WHERE template_id = '$templateId'");
	}
	
	//return the data of a determined dynamic form's table.
	public function getComponentDataQuery($fieldName, $templateId, $formId)
	{
		$templateTable = "template". $templateId;
		return $this->db->query("SELECT " . $fieldName . " FROM " . $templateTable . " WHERE form_id= '" . $formId. "'");
	}
	
	public function getComponentFieldOptions($templateId, $fieldName)
	{
		return $this->db->query("SELECT t_component_field_option 
									FROM template_components_options
									WHERE template_id = $templateId
									AND t_component_field = '$fieldName'");
	}
	
	public function getTemplateDetails($templateId)
	{
		return $this->db->query("SELECT * FROM template_properties WHERE template_id = '$templateId'");
	}
	
	public function getFormDetails($formId)
	{
		return $this->db->query("SELECT * FROM template_form_properties WHERE form_id = '$formId'");
	}

	public function getColumnsNames($templateId)
	{
		$templateTable = "template". $templateId;
		return $this->db->query("SHOW COLUMNS FROM $templateTable");
	}
	
	//return values to be able to render form components
	public function populateForm($templateId)
	{
		$browseAttachIcon = "<b title='Browse in new window' class='icon ui-widget-content ui-corner-all'><span title='Browse in new window' class='ui-icon ui-icon-folder-open'></span></b>";
		$templateTable = "template". $templateId;
	
		$componentHTML = "<form id='form'>"; // Container for html components
			
		$columns =  $this->db->query("SHOW COLUMNS FROM $templateTable");
		
		if ($columns->num_rows() > 0)
		{
			foreach($columns->result() as $fields)
			{
				$fieldName = $fields->Field;
				$field = explode("_", $fieldName);
				$componentType =  $field[0];
				 
				//ignore the 2 first field name which are not components of the template
				if (($fieldName != "form_id") && ($fieldName != "form_name"))
				{
					$query = "SELECT * FROM template_components 
								WHERE	template_id = $templateId 
								AND		t_component_field = '$fieldName'";
								
					//fetches the component properties
					$componentProperties = $this->db->query($query);
					//while($comp = mysql_fetch_array($componentProperties))
					foreach($componentProperties->result() as $comp)
					{
						$label = $comp->t_component_label;
						$fontsize = $comp->t_component_fontsize;
						$type = $componentType;
						
							$componentHTML .= "<input type='hidden' name='formName' value='". $templateTable ."'/>";
							$componentHTML .= "<input type='hidden' name='templateId' value='" . $templateId . "'/>";
						
					/*ENGINE TO POPULATE COMPONENT HTML*/
						switch ($type)
						{
							case "attach": 
							
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="attach">' . $label . '</span>';
								$componentHTML .= '<select id="" name="' . $fieldName .'" class="ui-widget-content ui-corner-all">';
									
									$attach = $this->getFormByTemplate($comp->t_component_attachid);
									foreach($attach->result() as $attachOption)
									{
										$componentHTML .= "<option value='" . $attachOption->form_id . "'>" . "(" . $attachOption->form_id . ") " .$attachOption->form_name . "</option>";
									}
									$componentHTML .= '</select>' . $browseAttachIcon . '</li>';
					
							break;
							case "header":
								$fontsize = ($fontsize == "")?"":"font-size:".$fontsize."px;";
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span style="display: inline;' . $fontsize . '" class="component_label" title="header">'. $label .'</span></li>';
							break;
							case "input":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="input">' . $label .'</span>';
								$componentHTML .= '<input type="text" name="' . $fieldName . '" class="ui-widget-content ui-corner-all"/></li>';
							break;
							case "separator":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span style="display: inline;" class="component_label" title="separator">'; 
								$componentHTML .= '<b style="color: rgb(221, 221, 221); vertical-align: middle;" type="text">___________________________________________</b></span></li>';
							break;
							case "datepicker":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="datepicker">'. $label .'</span>';
								$componentHTML .= '<input type="text" name='. $fieldName .' class="datepicker ui-widget-content ui-corner-all"></li>';
							break;
							case "checkbox":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="checkbox">'. $label .'</span>';
								$componentHTML .= '<input type="checkbox" checked="false" name='. $fieldName .' class="ui-widget-content ui-corner-all"></li>';
							break;
							case "option":
								$queryOptions = "SELECT t_component_field_option 
												FROM template_components_options
												WHERE template_id = $templateId
												AND t_component_field = '$fieldName'";
								$options = $this->db->query($queryOptions);
							
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="option">' . $label . '</span>';
								$componentHTML .= '<select id="" name="' . $fieldName .'" class="ui-widget-content ui-corner-all">';
									foreach($options->result() as $opt)
									{
										$componentHTML .= '<option value="'. $opt->t_component_field_option .'">' . $opt->t_component_field_option .'</option>';
									}
								$componentHTML .= '</select></li>';
							break;
							default:
								$componentHTML .= '<li class="component ui-corner-all"><span class="component_label" title="input">Unknown Component</span></li>';
							break;
						}//ends switch
					} //ends while
				}//ends if num_rows
			}//ends while fetch(columns)
		}//ends if columns
		$componentHTML .= "</form>";
		return $componentHTML;
	}

	public function formSave()
	{
		$creator = $this->session->userdata('username'); 
		$dateCreated = date("d.m.y H:i:s");
		$fields =  "";
		$values =  "";
		
		$arrayCount = count($_POST);
		foreach ($_POST as $key => $value){
			//echo $key . ">" . $data;
			
			if ($key == "formName"){
				$formName = $value;
			}
			elseif($key == "form"){
				$form = $value;
			}
			elseif($key == "templateId"){
				$templateId = $value;
			}
			else{
				$fields .= $key;
				$fields .= ($arrayCount > 1)? ", ": "";
				
				$values .= "'" . $value ."'";
				$values .= ($arrayCount > 1)? ", ": "";
			}
			
			--$arrayCount; 
		}
		
		
		$nameDuplicationCheck = $this->db->query("SELECT form_name FROM template_form_properties where form_name = '$form'");// AND template_id = '$templateId'");
		if ($nameDuplicationCheck->num_rows() == 0) //if there is no duplication
		{
			$query = "INSERT INTO " . $formName . "(form_name," . $fields . ") VALUES ('". $form . "'," . $values . ")";
			$result = $this->db->query($query);

			$formProperties = $this->db->query("INSERT INTO template_form_properties (form_name, form_creator, form_date_created, template_id)
										VALUES ('$form','$creator','$dateCreated','$templateId')");
			
			return $formProperties;
		}
		else
		{
			return false;
		}
	}
	
	public function verifyFormNameDuplication($form,$templateId)
	{
		$query = $this->db->query("SELECT form_name 
									FROM template_form_properties 
									WHERE form_name = '$form' 
										AND template_id = '$templateId'");
										
		return ($query->num_rows() > 0)?true:false;
	}
	
	public function formUpdate($templateId, $formId, $postItems)
	{
		//insert the edited Time
		$arrayUpdate = array (
							'form_date_edited' => date("d.m.y H:i:s"),
							'form_editor' => $this->session->userdata('username')						
							);
							
		$this->db->where('form_id', $formId);
		$this->db->update('template_form_properties', $arrayUpdate);
		
		// Takes in values from post and updates the corresponding table values.
		$templateTable = "template". $templateId;
		
		//$query = "UPDATE $templateTable SET column1=value1, column2=value2 WHERE form_name = 'XXXX' ";
		$query = "UPDATE $templateTable SET ";
		
		$arrayCount = count($postItems) - 2 ; // 2 = templateId + formId
		foreach ($postItems as $item=> $value)
		{
			if(!(($item == "template") || ($item == "form")))
			{
				$query .= $item . "='" . $value . "'";
				$query .= ($arrayCount > 1)? ",": ""; // Avoid SQL syntax error: add comma after each column except last
				
				//log action
				$this->log_model->logForm($formId, "updated", $value);
				
				--$arrayCount;
			}
			
		}
		
		$query .= " WHERE form_id='$formId'";
		
		return $this->db->query($query);
	
	}
	
	public function formDescriptionUpdate($formId, $formName)
	{
		$data = array(
                'form_name' => $formName
             );
		
		$this->db->where('form_id', $formId);
		
		 $this->db->update('template_form_properties', $data);
		
		//log action
		return $this->log_model->logForm($formId, "updated form name", $formName);
		
	}
	
	public function insertNewForm($formName, $fields, $form, $values, $formId)
	{
		
		return $this->db->query("INSERT INTO " . $formName . " (form_id, form_name," . $fields . ") VALUES ('". $formId . "','" .$form . "'," . $values . ")");
		
	}
	
	public function insertTemplateFormProperties($form, $creator, $dateCreated, $templateId)
	{
		$this->db->query("INSERT INTO template_form_properties 
							(form_name, form_creator, form_date_created, template_id)
							VALUES ('$form','$creator','$dateCreated','$templateId')");
		
		//returns the last inserted id. Equals "mysql_insert_id()".
		return $this->db->insert_id(); 
	}
}//ENDS Template_model CLASS