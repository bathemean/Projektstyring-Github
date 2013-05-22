<?php ob_start(); ?>

<a href="?p=home">< tilbage</a> 


<?php

  include_once('classes/functions.php');
  include_once('classes/Database.php');
  $db = new Database();

  include_once('classes/Client.php');
  $client = new Client();

  if( isset($_POST) ) {

    if($client->insert($_POST)) {
      header('Location: index.php?p=clientForm&r=success');
    }
    
  }

?>

<form method="post">

  <label for="name">Navn:</label>
  <input type="text" id="name" name="name" />

  <br />

  <label for="email">Email:</label>
  <input type="text" id="email" name="email" />

  <br />

  <label for="password1">Password:</label>
  <input type="text" id="password1" name="password1" />

  <br />

  <label for="password2">Bekr&aelig;ft password:</label>
  <input type="text" id="password2" name="password2" />

  <br />

  <input type="submit" name="submit" value="Gem" />

</form>

<?php ob_flush(); ?>