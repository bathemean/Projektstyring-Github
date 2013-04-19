<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>NailBeauty</title>

    <link rel="stylesheet" href="css/main.css" type="text/css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    
</head>

<body>

    <div id="main">
        <?php 
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            require('classes/Database.php');
            $db = new Database();

            if(isset($_POST['submit'])){
              
             $query = $db->get()->prepare("
                INSERT INTO bookings VALUES (?,?,?)
              ");
              $query->execute(array($_POST['bookingdate'],$_POST['employeeid'],$_POST['treatmentName']));

            }
        ?>
        <form method="POST">
        <div id="optionsContainer">

            <div class="optionsPanel">
                <h1>Vælg behandlinger</h1>

                <?php

                    $getTreatments = $db->get()->query("
                            SELECT treatmentname name, treatmentduration duration
                            FROM treatments
                        ");
                    $treatments = $getTreatments->fetchAll();

                    $n = 0; // create a counter so we only check the first treatment

                    foreach($treatments as $t) {

                        echo '<input type="hidden" id="treatmentDuration" 
                            name="'. $t['name'] .'" value="'. $t['duration'] .'" />';

                        echo '<input type="radio" name="treatmentName" value="'. $t['name'] .'" 
                         '. ($n ==0 ? 'checked' : false) .' /> ';

                        echo $t['name'] .' ('. $t['duration'] .' min)';

                        echo '<br />';

                        $n++;

                    }

                ?>

            </div><!-- .optionsPanel ends -->

            <div class="optionsPanel">
                <h1>Vælg medarbejder(e)</h1>
                
                <?php

                    $getEmployees = $db->get()->query("
                        SELECT employeeid id, employeename name
                        FROM employees
                    ");
                    $employees = $getEmployees->fetchAll();

                    $n = 0;

                    foreach($employees as $e) {

                        $getSchedule = $db->get()->prepare("
                                SELECT day, TIME_TO_SEC(start) start, TIME_TO_SEC(end) end
                                FROM employeeschedule
                                WHERE employeeid = {$e['id']}
                            ");
                        $getSchedule->execute();
                        $schedule = $getSchedule->fetchAll();

                        foreach($schedule as $s) {
                            echo '<input type="hidden" id="'. $e['id'] .'"  
                                name="scheduleStart" day="'.$s['day'].'" value="'. $s['start'] .'" />';
                            echo '<input type="hidden" id="'. $e['id'] .'" 
                                name="scheduleEnd" day="'.$s['day'].'" value="'. $s['end'] .'" />';

                        }


                        echo '<input type="radio" name="employeeid" value="'. $e['id'] .'" 
                         '. ($n ==0 ? 'checked' : false) .' /> ';

                        echo $e['name'];

                        echo '<br />';

                        $n++;

                    }

                ?>

            </div><!-- .optionsPanel ends -->

            <div style="text-align: center">
                <br />
                <input type="submit" name="submit" value="Book tid" />  
            </div>

        </div><!-- #optionsContainer ends -->

        <script>
            // spawn the calendar on page load
            $(document).ready(function() {
                $('div#calendarContainer').load('calendar.php');
            });
        </script>
        
        <div id="calendarContainer">
        </div>

           
        
    </form>
    </div><!-- #main ends -->

</body>