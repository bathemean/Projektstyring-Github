<h1>Medarbejder administration</h1>

<a href="">Tilf&oslash;j</a>

<br /><br />

<?php
	include_once('classes/EmployeeManager.php');
	$emp = new EmployeeManager();
	include_once('classes/Database.php');
	$db = new Database();

	$emp->employeeList();

?>