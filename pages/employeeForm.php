<script src="js/scheduleDayManager.js"></script>

<a href="?p=employeeAdmin">< tilbage</a><br /><br />

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


		if(isset($_POST['submit']) && $mode == 'insert') {

			try{
				$db->get()->beginTransaction();

				$query = $db->get()->prepare("
						INSERT INTO employees
						(employeename)
						VALUES (?)
					");
				$query->execute( array($_POST['employeename']) );

				$id = $db->get()->lastInsertId();

				$n = 1;
				while($_POST[ $n . '-start-hours']) {

					$start = ($_POST[$n .'-start-hours'] * 3600) + ($_POST[$n .'-start-min'] * 60);
					$end = ($_POST[$n .'-end-hours'] * 3600) + ($_POST[$n .'-end-min'] * 60);
					$end -= 900; // subtract 15 minutes from end time, since calendar hands time slots by quarters
					
					$query = $db->get()->prepare("
							INSERT INTO empSchedule
							(employeeid, day, start, end) VALUES
							(:id, :day, :start, :end)
						");
					$query->execute(array(':id' => $id,
										  ':day' => $_POST[ $n . '-day'],
										  ':start' => $start,
										  ':end' => $end));


					$n++;
				}
			} catch(PDOException $e) {
				$db->get()->rollback();
				echo $e->getMessage();
			}

		}

	?>

	<div style="clear: both"></div>

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

				$startPieces = explode(':', $s['start']);
				$endPieces = explode(':', $s['end']);
				
				echo '<tr id="'. $n .'">';

				echo '<td><label for="'. $s['day'] .'">';
					echo '<select id="'. $n .'" name="'. $n .'-day">';
					foreach($weekdays as $key => $wkday) {
						echo '<option value="'. ($key + 1) .'" '. ($s['day'] == $key ? "selected" : "") .'>'. $wkday .'</option>';
					}
					echo '</select>';
				echo '</label></td>';

				echo '<td>';
					echo '<select name="'. $n .'-start-hour">';
						for($h = 1; $h <= 24; $h++) {
							echo '<option '. ($mode == 'insert' ? ($h == 10 ? 'selected' : '') : ($h == $startPieces[0] ? 'selected' : '')) .' value="'. $h .'">'. $h .'</option>';
						}
					echo '</select>:';
					echo '<select name="'. $n .'-start-min">';
						for($m = 0; $m < 4; $m++) {
							echo '<option '. ($mode == 'update' && ($m * 15) == $startPieces[1] ? 'selected' : '') .' value="'. ($m * 15) .'">'. ($m * 15) .'</option>';
						}
					echo '</select>';
				echo '</td>';

				echo '<td>';
					echo '<select name="'. $n .'-end-hour">';
						for($h = 1; $h <= 24; $h++) {
							echo '<option '. ($mode == 'insert' ? ($h == 10 ? 'selected' : '') : ($h == $endPieces[0] ? 'selected' : '')) .' value="'. $h .'">'. $h .'</option>';
						}
					echo '</select>:';
					echo '<select name="'. $n .'-end-min">';
						for($m = 0; $m < 4; $m++) {
							echo '<option '. ($mode == 'update' && ($m * 15) == $endPieces[1] ? 'selected' : '') .' value="'. ($m * 15) .'">'. ($m * 15) .'</option>';
						}
					echo '</select>';
				echo '</td>';

				//echo '<td><input type="text" id="'. $n .'" name="'. $n .'-start" value="'. $s['start'] .'" /></td>';
				//echo '<td><input type="text" id="'. $n .'" name="'. $n .'-end" value="'. $s['end'] .'" /></td>';
				
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