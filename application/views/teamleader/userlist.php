<?php
//Verifies if pageTitle is set
(!isset($pageTitle))?$pageTitle = "IFS Application Toolbox":"";
$this->load->view("teamleader/header.php", $pageTitle);
$this->load->view("commonPages/userlist", $userlist); // COMMON PAGE STAFF & TEAMLEADER VERSION: cannot edit or access user details