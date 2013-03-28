<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>NailBeauty</title>

    <link rel="stylesheet" href="css/main.css" type="text/css" />
</head>

<body>

    <div id="main">
        <?php 
            if(isset($_POST['submit'])){
              $db = new PDO('mysql:host=localhost;dbname=NailBeauty', "root", "");
	          $query = $db->prepare("
	            INSERT INTO bookings VALUES (?,?,?)
	          ");
              $query->execute(array($_POST['bookingdate'],$_POST['employee'],$_POST['treatment']));
            }
		?>
        <form method="POST">
		<div id="optionsContainer">

            <div class="optionsPanel">
                <h1>Vælg behandlinger</h1>
                
                <input type="radio" name="treatment" value="1"/> Span-manicure (60 min)
                <br />
                <input type="radio" name="treatment" value="2"/> Manicure (30 min)
                <br />
                <input type="radio" name="treatment" value="3"/> Pedicure (30 min)
                <br />
                <input type="radio" name="treatment" value="4"/> Fodbehandling v/ statsaut. fodterapeaut (60 min)
            </div><!-- .optionsPanel ends -->

            <div class="optionsPanel">
                <h1>Vælg medarbejder(e)</h1>
                
                <input type="radio" name="employee" value="a" /> Medarbejder A
                <br />
                <input type="radio" name="employee" value="b" /> Medarbejder B
                <br />
                <input type="radio" name="employee" value="c" /> Medarbejder C
                <br />
                <input type="radio" name="employee" value="d" /> Medarbejder D
            </div><!-- .optionsPanel ends -->

        </div><!-- #optionsContainer ends -->

        <div id="calendarContainer">

            <h1>calendar</h1>

            <table cellspacing="0">

              <?php

                $weekdays = array('','Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag', 'Søndag');
				$time = 930;
				$n = 2;
                for($i=0; $i<=32; $i++) {
					
					$n++;
					$time += 15;
					echo '<tr>';
					
                    for($j=0; $j<=7; $j++) {
                            echo '<td>';
							if ($i==0) 
								echo $weekdays[$j];
							
							if ($j==0 && $n==4) {
								$time += 40; $n=0;	echo $time/100 . '.00';
								}
							
							if ($j!=0 && $i!=0) 
								echo '<input type="checkbox" name="bookingdate" value="'. $time .'-'. $j .'" />';
								
                            echo '</td>';
					}
					echo '</tr>';
					
				}

              ?>

            </table>
         <input type="submit" name="submit"/>  
        </div><!-- #calendarContainer ends -->
    </form>
    </div><!-- #main ends -->

</body>