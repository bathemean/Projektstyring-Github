<?php
	/*$t = time();
		echo date('d-m H:i', $t);
		echo '<br>';*/

	$t = strtotime('monday this week');
		echo date('d-m H:i', $t);
echo '<br>';

	$t = strtotime("10:00", $t);

	echo date('d-m H:i', $t);


echo '<br>';
	

	$nt = strtotime("+15 minutes", $t);
	echo date('d-m H:i', $nt);
echo '<br>';
	$nt = strtotime("+15 minutes", $nt);
	echo date('d-m H:i', $nt);
echo '<br>';
	$nt = strtotime("+15 minutes", $nt);
	echo date('d-m H:i', $nt);
echo '<br>';
	$nt = strtotime("+15 minutes", $nt);
	echo date('d-m H:i', $nt);

?>