<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("staff/header.php", $pageTitle);
$this->load->view("commonPages/formlist", $formList);