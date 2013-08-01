<?php

class Template_model extends CI_Model 
{
	function getTemplates()
	{
		return $this->db->get('template_properties');
	}// ENDS getTemplates
	
	function getComponents()
	{
		return $this->db->query("SELECT * FROM components ORDER BY comp_label ASC");
	}
	
	function getComponentDataWHEREID($id)
	{
		return $this->db->query("SELECT * FROM componentdata WHERE comp_id = '$id'");
	}
	
	function templateCreate()
	{
		
			/*INFORMATION
				1) this page takes json object from POST and converts to array
				2) array of template model traversed and variables assigned
				3) Verifies if name is duplicated
				4) INSERT table TEMPLATE_PROPERTIES
				5) CREATE DYNAMIC table from form_name and template_name
				6) INSERT table TEMPLATE_COMPONENTS for template
				7) INSERT table TEMPLATE_COMPONENTS_OPTIONS if options present on template.
			*/

				$template 		= json_decode($this->input->post('jsonobj'), true); // true creates associative arrays
				$creator 		= $this->session->userdata('username'); //FETCHES FROM CURRENT/SESSION USER
				$dateCreated 	= date("d.m.y H:i:s"); //current date and time
				$description	= $template["description"];
				$templateName 	= $template["templateName"];
				
			//TEMPLATE_PROPERTIES TABLE
				$nameDuplicationCheck = $this->db->query("SELECT template_name FROM template_properties where template_name = '$templateName'");
				if ($nameDuplicationCheck->num_rows() > 0)
				{
					$templateProperties = false;
				}
				else
				{
					$templateProperties = $this->db->query("INSERT INTO template_properties(
																template_creator, 
																template_date_created,
																template_description,
																template_name) 
														VALUES ('$creator',
																'$dateCreated',
																'$description',
																'$templateName')"
																);
					$templateId = mysql_insert_id();	
				}
			
			//ENDS TEMPLATE PROPERTIES

				if ($templateProperties){
					if($this->generateFields($template,$templateId,$templateName)){
						if($this->generateTemplateComponents($template,$templateId)){
							//log action
								$this->log_model->logTemplate($templateId, "created", "");
							return true;
						} else{ return false;	}
					} else{ return false;	}
				}else{ return false;	}
	
	}//ends templateCreate function
	
	function generateTemplateComponents($template, $templateId)
	{
		/* THE WHOOOLE MAGIC is about
			pulling the template components and place it in a huge array before pushing it
			that's all..
			
			I used associative arrays instead of objects as this 'engine' comes from the former prototype1.
			If time allows, i will enhance the engine, but no performance will be gained.
		*/
		$numOfFields = count($template["components"]);
		$query = "";
		for($i = 0; $i < $numOfFields; ++$i)
		{
			$type = $template["components"][$i]["type"];
			$label = $template["components"][$i]["label"];
			$field = $type."_". $i;
			$fontsize = ($type == "header")?$template["components"][$i]["data"][0]["font-size"]:"";
			$attachId = ($type == "attach")?$template["components"][$i]["data"][0]["attachid"]:"";
			$position = $template["components"][$i]["position"];
				
			if ($type == "option"){			
				$options = $template["components"][$i]["data"];
				
				for($j=0; $j < count($options); ++$j){
					$dataOpt = $options[$j]["option"];
					
					$this->db->query("INSERT INTO template_components_options(
													template_id, 
													t_component_field,
													t_component_field_option) 
											VALUES ('$templateId',
													'$field',
													'$dataOpt')"
													);
				}//ends for
			}//ends if
			
				$this->db->query("INSERT INTO template_components(
													template_id, 
													t_component_field,
													t_component_label,
													t_component_fontsize,
													t_component_attachid,
													t_component_position) 
											VALUES ('$templateId',
													'$field',
													'$label',
													'$fontsize',
													'$attachId',
													'$position')"
													);
			
			
		}//ends for fields
		
		return true;
	}//ends generateTemplateComponents
	
	function generateFields($template,$templateId,$templateName)
	{
		$numOfFields = count($template["components"]);
		$query = "";
		for($i = 0; $i < $numOfFields; ++$i)
		{
			$type = $template["components"][$i]["type"];
			$length = 70;
			$field = $type."_". $i;
			
			$query .= $field . " VARCHAR(".$length.")";
			$query .= (($numOfFields-1) != $i)?", ":""; //adds comma if there are more items to come
		}
		
		$formTemplateName = "template". $templateId; //example: template16, template203
		$query2 = "CREATE TABLE $formTemplateName( form_id INT(4), form_name VARCHAR(100),". $query . ")" ;
		$createTable = $this->db->query($query2); 
		
		return $createTable;
	}
}//ENDS Template_model CLASS