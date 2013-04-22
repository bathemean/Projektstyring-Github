<?php
	include_once('classes/EmployeeManager.php');
  	$emp = new EmployeeManager();
  	
  	if($emp->delete($_GET['id'])) {
  		header('Location: index.php?p=employeeAdmin&r=success');
  	}
  ?>