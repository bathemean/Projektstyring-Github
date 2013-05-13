<?php
echo 2;
	if( isset($_GET['id']) && isset($_GET['state']) ) {

		include_once('classes/Database.php');
		$db = new Database();

		if($_GET['state'] == 1) {
			$query = $db->get()->prepare("
					INSERT INTO bookingCheckins
			    	VALUES (?, 1)
			    ");
		} else {
			$query = $db->get()->prepare("
					DELETE FROM bookingCheckins 
			    	WHERE bookingid = ?
			    ");
		}

		try {
		$query->execute( array($_GET['id']) );
		echo 1;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}


	}

?>as