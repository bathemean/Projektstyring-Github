<?php
	include_once('../classes/Calendar.php');
    $calendar = new Calendar();

    $calendar->navigation();

	if(isset($_GET['start'])) {
		$start = $_GET['start'];
	} else {
		$start = 'monday this week';
	}

	$calendar->render($start, 'book');
?>
