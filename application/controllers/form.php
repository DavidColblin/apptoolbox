<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form extends CI_Controller {

	//Lists the templates by default from which form are created
	public function index() 
	{
		$data['formList'] = $this->form_model->getForms();
		$data['pageTitle'] = 'Forms List';
		
		$link = rolesort($this->session->userdata('userrole'), 'formlist');
		$this->load->view($link, $data);
	}
	
	function create($templateId='')
	{
		// Example URL = http://localhost/CDFS/form/create/$TEMPLATEID
		
		if ($templateId == '')
		{
			show_error("Please choose a template from the <a href='". base_url() ."template'>template list</a> before creating a form");
		}
		else
		{
			$data['templateHistory'] = $this->log_model->getTemplateHistory($templateId);
			$data['pageTitle'] = "Form Create";
			$data['templateDetails'] =  $this->form_model->getTemplateDetails($templateId);
			$data['populateForm'] =  $this->form_model->populateForm($templateId);
			
			$link = rolesort($this->session->userdata('userrole'), 'formcreate');
			$this->load->view($link, $data);
		}
	}

	//Response to an ajax call when saving a fresh new form.
	function formsave()
	{
		return $this->_formSave();
	}
	
	//Response to an ajax call when updating an existing form
	function formupdate()
	{
		$templateId = $this->input->post('template');
		$formId = $this->input->post('form');
		$postItems = $this->input->post();
		
		echo $this->form_model->formUpdate($templateId, $formId, $postItems);
	}
	
	//Response to an ajava call when updating details (name and description) of existing form
	function formeditdetails()
	{
		$formName = $this->input->post('formname');
		$formId = $this->input->post('formid');
		$templateId = $this->input->post('templateid');
		
		$nameDuplicationCheck = $this->form_model->verifyFormNameDuplication($formName, $templateId);
		if ($nameDuplicationCheck) //if there is duplication of form name
		{
			echo false;
		}
		else
		{
			$this->form_model->formDescriptionUpdate($formId, $formName);
			echo true;
		}
	
	}
	
	//View one form. $formId is passed as parameter in the URL
	// Example URL = http://localhost/CDFS/form/view/$FORMID
	function view($formId='')
	{
		//if no form code is inserted OR USER IS UNAUTHORIZED, show error.
		if (($formId=='') || ($this->form_model->authorizedForm($formId) == 3) )
		{
			show_error("Please choose again from <a href='". base_url() ."form'>form list</a>");
		}
		else
		{
			$tidQuery = $this->form_model->getFormDetails($formId);
			
			if($tidQuery->num_rows()>0)
			{
				foreach($tidQuery->result() as $tid)
				{
					$templateId = $tid->template_id;
				}
			}
			else
			{
				show_error("<h2>Form Code (#$formId) not found.</h2><br />Please choose again from <a href='". base_url() ."form'>form list</a>");
			}
			
			$data['pageTitle'] = "Form View";
			$data['populateForm'] = $this->_populateViewForm($templateId, $formId);
			$data['formHistory'] = $this->log_model->getFormHistory($formId);
			$data['formDetails'] = $this->form_model->getFormDetails($formId);
			$data['templateDetails'] = $this->form_model->getTemplateDetails($templateId);
			
			//defines whether the user sees a READONLY page or READ/WRITE page.
			//this is based upon his current access rights upon the form
			if($this->form_model->authorizedForm($formId) == 1) //if READWRITE mode
			{
				$link = rolesort($this->session->userdata('userrole'), 'formview');
			}
			else //else if form is READONLY
			{
				$link = rolesort($this->session->userdata('userrole'), 'formview_readonly');
			}
			$this->load->view($link, $data);
			
		}
	}
	
	function _populateViewForm($templateId,$formId)
	{
		$formTableName = "template". $templateId;
		$componentHTML = "<form id='form'>"; // Container for html components
		$columns = $this->form_model->getColumnsNames($templateId);
		
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
					//fetches the component properties
					$componentProperties = $this->form_model->getTemplateComponents($templateId,$fieldName);
					
					foreach($componentProperties->result() as $comp)
					{
					
						/* THIS PART DIFFERS FROM FORM CREATOR WHERE IT FETCHES DATA */
							
							$componentDataResult = $this->form_model->getComponentDataQuery($fieldName, $templateId, $formId);
							if ($componentDataResult->num_rows() > 0)
							{
								foreach ($componentDataResult->result() as $componentDataArray)
								{
									$componentData = $componentDataArray->$fieldName;
								}
							}
							else
							{
								$componentData = "X";
							}
							
							
						/* THIS PART DIFFERS FROM FORM CREATOR WHERE IT FETCHES DATA */
						
						$label = $comp->t_component_label;
						$fontsize = $comp->t_component_fontsize;
						$type  = $componentType;
						
							$componentHTML .= "<input type='hidden' name='formName' value='". $formTableName ."'/>";
							$componentHTML .= "<input type='hidden' name='templateId' value='" . $templateId . "'/>";
						
					/*ENGINE TO POPULATE COMPONENT HTML*/
						switch ($type)
						{
							case "header":
								$fontsize = ($fontsize == "")?"":"font-size:".$fontsize."px;";
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span style="display: inline;' . $fontsize . '" class="component_label" title="header">'. $label .'</span></li>';
							break;
							case "input":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="input">' . $label .'</span>';
								$componentHTML .= '<input type="text" name="' . $fieldName . '" value="' .$componentData. '" class="ui-widget-content ui-corner-all"/></li>';
							break;
							case "separator":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span style="display: inline;" class="component_label" title="separator">'; 
								$componentHTML .= '<b style="color: rgb(221, 221, 221); vertical-align: middle;" type="text">___________________________________________</b></span></li>';
							break;
							case "datepicker":
								$componentHTML .= '<li class="component ui-corner-all">';
								$componentHTML .= '<span class="component_label" title="datepicker">'. $label .'</span>';
								$componentHTML .= '<input type="text" name='. $fieldName .' class="datepicker ui-widget-content ui-corner-all" value="' .$componentData. '"></li>';
							break;
							case "attach":
									$browseAttachIcon = "<b title='Browse in new window' class='icon ui-widget-content ui-corner-all'><span title='Browse in new window' class='ui-icon ui-icon-folder-open'></span></b>";
									$attachTemplateId = $comp->t_component_attachid;
							
									$componentHTML .= '<li class="component ui-corner-all">';
									$componentHTML .= '<span class="component_label" title="attach">' . $label . '</span>';
									$componentHTML .= '<select id="" name="' . $fieldName .'" class="ui-widget-content ui-corner-all">';
										
										//returns the templateID of the attach
										$attachForms = $this->form_model->getFormByTemplate($attachTemplateId);
										
										foreach($attachForms->result() as $attachOption)
										{
											if ($componentData == $attachOption->form_id)
											{//if true, current option is selected
												$componentHTML .= "<option selected='selected' value='" . $attachOption->form_id . "'>" . "(" . $attachOption->form_id . ") " .$attachOption->form_name . "</option>";
											}
											else
											{
												$componentHTML .= "<option value='" . $attachOption->form_id . "'>" . "(" . $attachOption->form_id . ") " .$attachOption->form_name . "</option>";
											}
											
										}
										$componentHTML .= "</select>" . $browseAttachIcon  . "</li>";
							break;
							default:
								$componentHTML .= '<li class="component ui-corner-all"><span class="component_label" title="input">Unknown Component</span></li>';
							break;
						}  //ends switch
					} //ends while
				}//ends if num_rows
			}//ends while fetch(columns)
		}//ends if columns
		
		$componentHTML .= "</form>";
		return $componentHTML;
	
	}
	
	public function _formSave()
	{
		$creator = $this->session->userdata('username');
		$dateCreated = date("d.m.y H:i:s");
		$fields =  "";
		$values =  "";
		
		$arrayCount = count($_POST);
		foreach ($_POST as $key => $value){
		
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
		} //ends foreach
		
		//$nameDuplicationCheck = $this->db->query("SELECT form_name FROM template_form_properties where form_name = '$form' AND template_id = '$templateId'");
		$nameDuplicationCheck = $this->form_model->verifyFormNameDuplication($form, $templateId);
		if (!$nameDuplicationCheck) //if there is no duplication
		{
			//returns id of the created form from the template_form_properties table
			$formId = $this->form_model->insertTemplateFormProperties($form, $creator, $dateCreated, $templateId);
			
			echo $this->form_model->insertNewForm($formName, $fields, $form, $values, $formId);
			
			//TEAM RIGHT OWNERSHIP
				//As for is create, update team's ownership rights in database
				if ($this->session->userdata('userrole') == 4)
				{
					$userDetails = $this->access_model->getUserById($this->session->userdata('userid'));
					foreach($userDetails->result() as $user)
					{
						$teamId = $user->team_id;
					}
					$this->access_model->updateTeamRight($teamId, $formId, 1);
				}
			//ENDS TEAM RIGHT OWNERSHIP
			
			//Log action
			$this->log_model->logForm($formId, "created form ", $form);
		}
		else
		{
			return false;
		}
	}

	
} //ENDS CLASS FORM