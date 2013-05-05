<script src="js/treatmentlistManager.js"></script>
<h1>Behandlings administration</h1>

<a href="?p=home">< tilbage </a> - 

<br /><br />

<?php
	include_once('classes/TreatmentManager.php');
	$tr = new TreatmentManager();
	include_once('classes/Database.php');
	$db = new Database();

	if(isset($_POST['submit'])) {

		for($i = 0; $i < sizeof($_POST[])+1; $i++) {
			$data[$i] = array('treatment' => $_POST['treatment'][$i],
							  'duration' => $_POST['duration'][$i]);
		}

		$tr->insert($data);

		echo sizeof($_POST).'<pre>';
		print_r($_POST);
		echo '</pre>';
	}

?>


<form method="post">
	<?php
		$tr->treatmentList();
	?>

	<a id="add" href="#">add</a>

	<br />

	<input type="submit" name="submit" value="Gem" />

</form>