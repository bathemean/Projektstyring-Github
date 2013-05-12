<?php
	include_once('classes/TreatmentManager.php');
  	$trm = new TreatmentManager();
  	
  	if($trm->delete($_GET['name'])) {
  		header('Location: index.php?p=treatmentAdmin&r=success');
  	}
  ?>