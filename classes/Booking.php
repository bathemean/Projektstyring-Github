<?php

	class Booking {

		private $db;

	    public function __construct() {
	        include_once('Database.php');
	        $this->db = new Database();
	        $this->db = $this->db->get();
	    }

	    public function insert($post, $client) {

	    	$end = $_POST['bookingdate'] + 
	    	  $this->getTreatmentDur( $_POST['treatmentName'] );

	    	$query = $this->db->prepare("
		        INSERT INTO bookings 
		        (bookingdate, bookingend, employeeid, treatmentname, clientid) 
		        VALUES (:date, :end, :employeeid, :treatment, :client)
		    ");

	    	if( $this->checkAvailable($_POST['bookingdate'], 
	    		  $_POST['employeeid'], $_POST['treatmentName']) ) {
			    
			    try {
			    $query->execute( array(':date' => $post['bookingdate'],
			    					   ':end' => $end,
			    					   ':employeeid' => $post['employeeid'],
			    					   ':treatment' => $post['treatmentName'],
			    					   ':client' => $client) );

			    } catch(PDOException $e) {
			    	die($e->getMessage());
			    }

		    	return true;
		    }

		    return false;

	    }

	    /**
	    * Returns duration of treatment.
	    **/
	    private function getTreatmentDur($treatment) {
	    	
	    	if(empty($treatment))
	    		return;

	    	$getTrDuration = $this->db->prepare("
	    		SELECT treatmentDuration duration
	    		FROM treatments
	    		WHERE treatmentName = ?
	    	");
	    	$getTrDuration->execute( array($treatment) );
	    	$duration = $getTrDuration->fetchColumn();
	    	$duration = ($duration * 60) - 900; // convert minutes to seconds,
	    										// and subtract 15 minutes to 
	    										// make up for preceding quarters

	    	return $duration;
	    }

	    /**
	    * Checks if the selected treatment fits in the slot based on treatment,
	    * employee and date.
	    **/
	    private function checkAvailable($date, $employee, $treatment) {

	    	// make sure that we get a treatment duration
	    	if(!$duration = $this->getTreatmentDur($treatment)) {
	    		return false;
	    	}

	    	$query = $this->db->prepare("
	    		SELECT COUNT(bookingid) c
	    		FROM bookings
	    		WHERE (bookingdate <= :end AND bookingdate >= :start)
	    		  OR (bookingdate <= :start AND bookingend >= :start)
	    		  AND employeeid = :employee
	    	");
	    	$query->execute( array(':start' => $date,
	    						   ':employee' => $employee,
	    						   ':end' => ($date + $duration)) );

	    	$b = $query->fetch();
	    	
	    	if( $b['c'] == 0 ) {
	    		return true;
	    	}
	    	
	    	return false;
	    	

	    }


	}

?>