<script src="js/calendarPicker.js"></script>
<script src="js/calendarScheduleLimiter.js"></script>
<script src="js/calendarNavigator.js"></script>
<script src="js/calendarBookingHandler.js"></script>

    

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
