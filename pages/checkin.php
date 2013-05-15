<script src="js/calendarCheckin.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/datepicker.js"></script>
<script>
  	$(function() {
  		$("#datepicker").datepicker({
  			changeYear: true
  		});

  		<?php
  			(isset($_GET['employeeid']) ? 
  				$employee = '&employeeid='.$_GET['employeeid'] : $employee = '');
  		?>

  		$("#datepicker").change(function() {
  			window.location.href = '?p=checkin<?php echo $employee; ?>&start='+$(this).val();
  		});
  	});
</script>

<a href="?p=home">< Tilbage</a><br /><br />

<?php
    require('classes/Database.php');
    $db = new Database();

?>

<?php
	if(isset($_GET['start'])) {
		$startval = $_GET['start'];
	} else {
		$startval = date( 'd-m-Y', strtotime('today') );
	}
?>

Start: <input id="datepicker" type="text" value="<?php echo $startval; ?>" />

<br /><br />

<?php
	require('classes/EmployeeManager.php');
  $emp = new EmployeeManager();
  $employees = $emp->getEmployees();

    $o = 0;
	foreach($employees as $e) {
		echo (isset($_GET['employeeid']) ? 
			($_GET['employeeid'] == $e['id'] ? '<strong>' : '') : 
			($o == 0 ? '<strong>' : '') );
		echo '<a href="?p=checkin&employeeid='. $e['id'] . (isset($startval) ? '&start='.$startval : '') .'">'. $e['name'] .'</a> ';
		echo (isset($_GET['employeeid']) ? 
			($_GET['employeeid'] == $e['id'] ? '</strong>' : '') : 
			($o == 0 ? '</strong>' : '') );
		$o++;
	}
?>

<br /><br />

<?php
    if(isset($_GET['employeeid'])) {
    	$employeeid = $_GET['employeeid'];
    } else {
    	$employeeid = $employees[0]['id'];
    }

 	if(isset($_GET['start'])) {
    	$start = strtotime($_GET['start']);
    } else {
    	$start = strtotime('today');;
    }
    $end = $start + 86400; // start + 24 hours

    $getBookings = $db->get()->prepare("
    	SELECT bookings.bookingid id, bookingdate date, employeeid, 
    	  treatmentname treatment, state
    	FROM bookings LEFT JOIN bookingCheckins
    	  ON bookings.bookingid = bookingCheckins.bookingid
    	WHERE bookingdate >= :start AND bookingdate <= :end
    	  AND employeeid = :employee
    ");
    $getBookings->execute( array(':start' => $start,
    							 ':end' => $end,
    							 ':employee' => $employeeid) );
    $bookings = $getBookings->fetchAll();

?>

<table id="checkin" cellspacing="0">
	
	<?php
		$n = 1;
		foreach($bookings as $b) {
			$n++;
	?>
		<tr <?php echo ($n %2 != 0 ? 'style="background: #ccc"' : '') ?>>
			<td class="time"><?php echo date('H:i', $b['date']); ?></td>
			<td class="main">
				SomeName <br />
				<?php echo $b['treatment']; ?>
			</td>
			<td>
				<input id="checkin" name="<?php echo $b['id']; ?>" 
				  employeeid="<?php echo $b['employeeid']; ?>"
				  <?php echo (isset($b['state']) ? 'checked' : ''); ?> type="checkbox" /> 
			</td>
		</tr>
	<?php
		}
	?>
</table>
