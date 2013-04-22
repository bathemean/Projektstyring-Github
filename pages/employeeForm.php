<?php ob_start(); ?>
<script src="js/scheduleDayManager.js"></script>

<a href="?p=employeeAdmin">< tilbage</a> 


<?php
  include_once('classes/functions.php');
  include_once('classes/Database.php');
  $db = new Database();

  include_once('classes/EmployeeManager.php');
  $emp = new EmployeeManager();

  $mode = 'insert';

  if(isset($_GET['id'])) {

    
    echo ' - <a href="?p=removeEmployee&id='. $_GET['id'] .'">slet medarbejder</a>';

    $mode = 'update';

    $getEmployee = $db->get()->prepare("
        SELECT employeeid id, employeename name
        FROM employees
        WHERE employeeid = ?
      ");
    $getEmployee->execute( array($_GET['id']) );
    $employee = $getEmployee->fetch();


    $getSchedule = $db->get()->prepare("
        SELECT day, start, end
        FROM empSchedule
        WHERE employeeid = ?
      ");
    $getSchedule->execute( array($_GET['id']) );
    $schedule = $getSchedule->fetchAll();

  }


  if(isset($_POST['submit']) && $mode == 'insert') {

    if($emp->insert($_POST)) {
      header('Location: index.php?p=employeeForm&r=success');
    }
    
  } elseif(isset($_POST['submit']) && $mode = 'update') {
    
    if($emp->update($_GET['id'], $_POST)) {
      header('Location: index.php?p=employeeForm&id='.$_GET['id'].'&r=sucess');
    }

  }

?>

<br /><br />

<div style="clear: both"></div>

<form method="POST">
  
  <label for="employeename">Navn: </label>
  <input type="text" id="employeename" name="employeename" value="<?php if($mode == 'update') { echo $employee['name']; } ?>" />

  <br /><br />

  <h2>Arbejdstider</h2>

  <table id="scheduleDays" border="0">

    <tr>
      <th></th>
      <th>Start</th>
      <th>Slut</th>
      <th></th>
    </tr>

  <?php
    include_once('classes/globals.php');

    // we move sunday to the back of the array
    $weekdays = $WEEKDAYS;
    $firstDay = array_shift($weekdays);
    array_push($weekdays, $firstDay);
    
    // track number of schedule days we have
    $n = 0; 

    if($mode == 'update') {
      foreach($schedule as $s) {

        $n++;


        $startHour = gmdate('H', $s['start']);
        $startMin = gmdate('i', $s['start']);

        // add 15 minutes
        $endHour = gmdate('H', ($s['end'] + 900) );
        $endMin = gmdate('i', ($s['end'] + 900) );

        
        echo '<tr id="'. $n .'">';

        echo '<td><label for="'. $s['day'] .'">';
          echo '<select id="'. $n .'" name="'. $n .'-day">';
          foreach($weekdays as $key => $wkday) {
            $key++;
            echo '<option value="'. $key .'" '. ($s['day'] == $key ? "selected" : "") .'>'. 
            $wkday 
            .'</option>';
          }
          echo '</select>';
        echo '</label></td>';

        echo '<td>';
          $emp->timeSelect($n, 'start-hours', $startHour);
          echo ':';
          $emp->timeSelect($n, 'start-min', $startMin);
        echo '</td>';

        echo '<td>';
          $emp->timeSelect($n, 'end-hours', $endHour);
          echo ':';
          $emp->timeSelect($n, 'end-min', $endMin);
        echo '</td>';

        echo '<td><a id="'. $n .'" name="removeDay" href="#">fjern</a></td>';

        echo '</tr>';
      }
    }

    // put our day tracker in an input field so we can use it with jquery,
    // put it after the loop so its value is correct
    echo '<input type="hidden" id="dayCounter" value="'. $n .'" />';
  ?>

  </table>

  <br />
  <a id="addDay" href="#">Tilføj arbejdsdag</a>

  <br /><br />

  <input type="submit" name="submit" value="Gem" />

</form>
<?php ob_flush(); ?>