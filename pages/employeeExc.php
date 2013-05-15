<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/datepicker.js"></script>
<script>
    $(function() {
        $("#datepicker").datepicker();
    });
</script>

<?php
  include_once('classes/EmployeeManager.php');
  $emp = new EmployeeManager();

  if( $_GET['mode'] == 'insert' && isset($_POST['submit']) ) {
    $emp->insertException($_POST);
  } else if( $_GET['mode'] == 'update' && isset($_POST['submit']) ) {
    echo 'dsa';
    echo $emp->updateException($_POST);
  }
?>

<h1>Medarbejder Exceptions</h1>

 
<?php
    if( isset($_GET['mode']) && $_GET['mode'] == 'insert' || $_GET['mode'] == 'update' ) {

        if($_GET['mode'] == 'update') {
            $exc = $emp->getException( $_GET['excId'] );

            $startHour = gmdate('H', $exc['start']);
            $startMin = gmdate('i', $exc['start']);

            // add 15 minutes
            $endHour = gmdate('H', ($exc['end'] + 900) );
            $endMin = gmdate('i', ($exc['end'] + 900) );
        } else {

            $startHour = 10;
            $endHour = 10;

        }
?>
<a href="?p=employeeExc&id=<?php echo $_GET['id']; ?>">< tilbage </a>

<form method="post">
    <input name="employeeid" type="hidden" value="<?php echo $_GET['id']; ?>" />

    <?php
        if($_GET['mode'] == 'update') {
            echo '<input name="exceptionid" type="hidden" value="'.$_GET['excId'].'" />';
        }
    ?>

    <label for="datepicker">Dato:</label>
    <input id="datepicker" name="date" type="text" value="<?php if($_GET['mode'] == 'update') { echo date('d-m-Y', $exc['date']); } ?>" />

    <br />

    <label for="timeselect">Start:</label>
    <?php
        $emp->timeSelect('', 'start-hours', $startHour);
        echo ':';
        $emp->timeSelect('', 'start-min', $startMin);
    ?>

    <br />

    <label for="timeselect">Slut:</label>
    <?php
        $emp->timeSelect('', 'end-hours', $endHour);
        echo ':';
        $emp->timeSelect('', 'end-min', $endMin);
    ?>

    <br />

    <label for="note">Note:</label>
    <textarea id="note" name="note"><?php echo ($_GET['mode'] == 'update' ? $exc['note'] : ''); ?></textarea>

    <br /><br />

    <input type="submit" name="submit" value="Gem" />

</form>

<?php
    } else {
        $exceptions = $emp->getExceptions($_GET['id']);
?>
<a href="?p=employeeAdmin">< tilbage </a>
- <a href="?p=employeeExc&mode=insert&id=<?php echo $_GET['id']; ?>">Tilf&oslash;j</a>

<br /><br />

<table border="0">

    <tr>
        <th>Dato</th>
        <th>Start</th>
        <th>Slut</th>
        <th style="text-align: left;">Note</th>
        <th></th>
    </tr>

<?php
    foreach($exceptions as $e) {
        
        $start = date('H:m', ($e['start'] + 900) );
        $end = date('H:m', ($e['end'] + 900) );

        echo '<tr>';
            echo '<td style="width: 80px; text-align: center">'. date('d-m-y', $e['date']) .'</td>';
            echo '<td style="width: 60px; text-align: center">'. $start .'</td>';
            echo '<td style="width: 60px; text-align: center">'. $end .'</td>';
            echo '<td style="width: 200px;">'. $e['note'] .'</td>';
            echo '<td><a href="?p=employeeExc&mode=update&id='.$_GET['id'].'&excId='.$e['id'].'">Rediger</a></td>';
        echo '</tr>';
    }
?>

</table>

<?php
    }
?>