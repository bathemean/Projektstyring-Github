<h1>Medarbejder administration</h1>

<a href="?p=home">< tilbage </a> - 
<a href="?p=employeeForm">Tilf&oslash;j</a>

<br /><br />

<?php
	include_once('classes/EmployeeManager.php');
	$emp = new EmployeeManager();
	include_once('classes/Database.php');
	$db = new Database();

	$employees = $emp->getEmployees();
?>

<table border="0">

	<tr>
	    <th>Navn</th>
	    <th></th>
	</tr>

	<?php
		foreach($employees as $e) {
			echo '<tr>';
			    echo '<td>'. $e['name'] .'</td>';
			    echo '<td>
			    		<a href="?p=employeeExc&mode=list&id='. $e['id'] .'">exceptions</a> 
			    		<a href="?p=employeeForm&id='. $e['id'] .'">Rediger</a>
			    	  </td>';
			echo '</tr>';
		}
	?>

</table>