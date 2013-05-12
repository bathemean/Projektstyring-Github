<?php ob_start(); ?>

<a href="?p=treatmentAdmin">< tilbage</a> 


<?php
  include_once('classes/functions.php');
  include_once('classes/Database.php');
  $db = new Database();

  include_once('classes/TreatmentManager.php');
  $trm = new TreatmentManager();

  $mode = 'insert';

  if(isset($_GET['name'])) {

    echo ' - <a href="?p=treatmentRemove&name='. $_GET['name'] .'">slet behandling</a>';

    $mode = 'update';

    $getTreatment = $db->get()->prepare("
        SELECT treatmentname name, treatmentduration duration
        FROM treatments
        WHERE treatmentname = ?
      ");
    $getTreatment->execute( array($_GET['name'] ));
    $treatment = $getTreatment->fetch();

  }


  if(isset($_POST['submit']) && $mode == 'insert') {
    
    if($trm->insert($_POST)) {
      echo 'dsadsa';
      header('Location: index.php?p=treatmentForm&r=success');
    }
    
  } elseif(isset($_POST['submit']) && $mode = 'update') {

    if($trm->update($_GET['name'], $_POST)) {
      header('Location: index.php?p=treatmentForm&id='.$_GET['name'].'&r=sucess');
    }

  }

?>

<br /><br />

<div style="clear: both"></div>

<form method="POST">
  
  <label for="name">Navn: </label>
  <input type="text" id="name" name="name" 
    value="<?php 
      echo (isset($_POST['treatmentname']) ? $_POST['treatmentname'] : (isset($treatment['name']) ? $treatment['name'] : '')); ?>" />

  <br /><br />

  <label for="duration">Varighed: </label>
  <select id="duration" name="duration">
    <?php
      for($i = 1; $i <= 8; $i++) {
        $d = $i * 15;
        echo '<option '. (isset($_POST['duration']) ? ($_POST['duration'] == $d ? 'selected' : '') : ($treatment['duration'] == $d ? 'selected' : '') ) .' value="'. $d .'">'. $d .'</option>';
      }
    ?>
  </select>
  minutter

  <br /><br />

  <input type="submit" name="submit" value="Gem" />

</form>
<?php ob_flush(); ?>