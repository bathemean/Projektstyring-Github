<script src="js/scheduleDayManager.js"></script>

<form method="POST">

	<?php
		include_once('classes/functions.php');
		include_once('classes/Database.php');
		$db = new Database();

		$mode = 'insert';

		if(isset($_GET['id'])) {

			$mode = 'update';

			$getEmployee = $db->get()->prepare("
					SELECT employeeid id, employeename name
					FROM employees
					WHERE employeeid = ?
				");
			$getEmployee->execute( array($_GET['id']) );
			$employee = $getEmployee->fetch();


			$getSchedule = $db->get()->prepare("
					SELECT day, start, end
					FROM empSchedule
					WHERE employeeid = ?
				");
			$getSchedule->execute( array($_GET['id']) );
			$schedule = $getSchedule->fetchAll();

		}

	?>

	<label for="employeename">Navn: </label>
	<input type="text" id="employeename" name="employeename" value="<?php if($mode == 'update') { echo $employee['name']; } ?>" />

	<br /><br />

	<h2>Arbejdstider</h2>

	<table id="scheduleDays" border="0">

		<tr>
			<th></th>
			<th>Start</th>
			<th>Slut</th>
			<th></th>
		</tr>

	<?php
		include_once('classes/globals.php');

		// we move sunday to the back of the array
		$weekdays = $WEEKDAYS;
		$firstDay = array_shift($weekdays);
		array_push($weekdays, $firstDay);
		
		// track number of schedule days we have
		$n = 0;	

		if($mode == 'update') {
			foreach($schedule as $s) {

				$n++;
				
				echo '<tr id="'. $n .'">';

				echo '<td><label for="'. $s['day'] .'">';
					echo '<select id="'. $n .'">';
					foreach($weekdays as $key => $wkday) {
						echo '<option value="'. ($key + 1) .'" '. ($s['day'] == $key ? "selected" : "") .'>'. $wkday .'</option>';
					}
					echo '</select>';
				echo '</label></td>';

				echo '<td><input type="text" id="'. $n .'" name="'. $n .'-start" value="'. $s['start'] .'" /></td>';
				echo '<td><input type="text" id="'. $n .'" name="'. $n .'-end" value="'. $s['end'] .'" /></td>';
				
				echo '<td><a id="'. $n .'" name="removeDay" href="#">fjern</a></td>';

				echo '</tr>';
			}
		}

		// put our day tracker in an input field so we can use it with jquery,
		// put it after the loop so its value is correct
		echo '<input type="hidden" id="dayCounter" value="'. $n .'" />';
	?>

	</table>

	<br />
	<a id="addDay" href="#">Tilføj arbejdsdag</a>

	<br /><br />

	<input type="submit" name="submit" value="Gem" />

</form>