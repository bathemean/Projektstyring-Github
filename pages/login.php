<?php ob_start(); ?>

<a href="?p=home">< tilbage</a> 


<?php

  include_once('classes/functions.php');
  include_once('classes/Database.php');
  $db = new Database();

  include_once('classes/Auth.php');
  $auth = new Auth();

  if( isset($_POST) ) {

    /*if($auth->checkCredentials($_POST))
      header('Location: index.php?p=home&r=success');*/

    echo $auth->checkCredentials($_POST);
  }

?>

<form method="post">

  <label for="email">Email:</label>
  <input type="text" id="email" name="email" />

  <br />

  <label for="password">Password:</label>
  <input type="text" id="password" name="password" />

  <br />

  <input type="submit" name="submit" value="Log in" />

</form>

<?php ob_flush(); ?>