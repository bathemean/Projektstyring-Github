<?php

	if( isset($_GET['date']) && isset($_GET['employeeid']) && isset($_GET['state']) ) {

		$bookingdate = $_GET['date'];
		$employeeid = $_GET['employeeid'];
		$state = $_GET['state'];

		include_once('classes/Database.php');
		$db = new Database();

		if($state == 1) {
			$query = $db->get()->prepare("
					INSERT INTO bookingCheckins
			    	VALUES (?, ?, 1)
			    ");
		} else {
			$query = $db->get()->prepare("
					DELETE FROM bookingCheckins 
			    	WHERE bookingDate = ? && employeeid = ?
			    ");
		}

		$query->execute( array($_GET['date'], $_GET['employeeid']) );

	}

?>