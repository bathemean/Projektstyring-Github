<?php
	include_once('classes/Auth.php');
	$auth = new Auth();

	if( $auth->logout() )
		header('Location: ?p=home');
?>