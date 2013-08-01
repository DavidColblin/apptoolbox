<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Author: David Moutoussamy
	Edited Date: 31 March 2011
	The class comprises of 
	1) index : shows list of created templates
	2) create : allows to create a new template
	3) templateCreateFunction : allow the template to be saved from the page create

*/
class Template extends CI_Controller {

	//list the templates 
	public function index()
	{
		$data['pageTitle'] =  "Templates List";
		$data['templateList'] = $this->_templateList();
		
		$link = rolesort($this->session->userdata('userrole'), 'templatelist');
		$this->load->view($link, $data);
	}
	
	//loads the creator page
	function create()
	{
		
		$data['populateAccordion'] = $this->_populateAccordion();
		$data['pageTitle'] = "Create Template";
		
		$link = rolesort($this->session->userdata('userrole'), 'templatecreate');
		$this->load->view($link, $data);
	}
	
	//edit template
	//THIS VERSION IS UNUSED UNTIL NEXT VERSION OF APPLICATION WHERE TEMPLATE EDITING IS VIABLE
	function edit($templateId='')
	{
		//if no template code is inserted
		if ($templateId=='') 
		{
			show_error("Please choose again from <a href='". base_url() ."template'>template list</a>");
		}
		else
		{
			$data['pageTitle'] = "edit Template";
			$data['populateAccordion'] = $this->_populateAccordion();
			$data['populateForm'] =  $this->form_model->populateForm($templateId);
			
			$link = rolesort($this->session->userdata('userrole'), 'templateedit');
			$this->load->view($link, $data);
		}
	}
	
	// ***** This function is a response to an AJAX call.
	function templateCreatefunction()
	{
		//'echo' because this is an AJAX response. the page will be either 'true' or 'false' only.
		echo $this->template_model->templateCreate();
	}
	
	//Generates the template list in the form of tables.
	public function _templateList()
	{
		$query = $this->template_model->getTemplates();
		$table = "";
		if ($query->num_rows() > 0)
		{
			foreach($query->result() as $template)
			{
				$table .= "<tr>";
					$table .= "<td class='t_id'>" . 			$template->template_id . "</td>";
					$table .= "<td class='t_name'>" . 			$template->template_name . "</td>";
					$table .= "<td class='t_created_by'>" . 	$template->template_creator . "</td>";
					$table .= "<td class='t_created_date'>" . 	$template->template_date_created  . "</td>";
					$table .= "<td class='t_last_editor'>" . 	$template->template_last_editor  . "</td>";
					$table .= "<td class='t_last_edited'>" . 	$template->template_date_edited  . "</td>";
					$table .= "<td class='t_description'>" . 	$template->template_description  . "</td>";
					
					/*//display edit button if user is admin
					FEATURE DISABLED TILL NEW VERSION
					if ($this->session->userdata('userrole') == 1)
					{
						$table .= "<td class='t_edit' name='". $template->template_id ."' >" . famicon('table_edit') . "</td>";
					}*/
					
				$table .= "</tr>";
			}
		}
		return $table;
	}

	//Fetches forms from database and display them in components accordion under form of attachments.
	public function _populateAccordion()
	{
		//html container for <ul> dynamic menu
		$accordion_html = "";
		
		$componentTitle = "";
		$attachment_icon = '<b class="attachmentIcon"></b>';
		$plus_icon = "<b class='plus_icon ui-widget-content ui-corner-all'><span class='ui-icon ui-icon-circle-plus'></span></b>";
			
		$accordion_html .= "<h3><a href='#'>" . $attachment_icon ."Attachments</a></h3>";
		$accordion_html .= "<div>";
		$accordion_html .= '<ul class="filetree componentList"><ul>';
	
		$attachments = $this->template_model->getTemplates();
		
		//lists all templates as components
		foreach ($attachments->result() as $attachment)
		{
			$accordion_html .= "<li class='closed'><span class='componentheader' title='" . $componentTitle . "'>" . $attachment_icon . $plus_icon .  (strlen($attachment->template_name) > 22 ? (substr($attachment->template_name, 0,  22) . "..."): $attachment->template_name) . "</span>"; //ternary means: if string length > 14, then slice it up to the next space. Else, ignore.
				$accordion_html .=	"<span class='hidden' title='attach'>
									<div class='componentlabel' name='" . $attachment->template_id . "'>" . $attachment->template_name . "</div>
									<div class='componentdata'>
									<select  class='ui-widget-content ui-corner-all' id=''>";
										
									$attach = $this->form_model->getFormByTemplate($attachment->template_id);
									
									foreach($attach->result() as $attachOption)
									{
										$accordion_html .= "<option value='" . $attachOption->form_id . "'>" . "(" . $attachOption->form_id . ") " .$attachOption->form_name . "</option>";
									}
									
				$accordion_html .=  "</select>";
				$accordion_html .= " </div></span>";
			$accordion_html .= "</li>";
		}
		
		$accordion_html .= '</ul></ul></div>';
		
		
		return $accordion_html;
	
	} //ENDS _populateAccordion
	
	
} //ENDS CLASS TEMPLATE