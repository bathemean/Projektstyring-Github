<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>NailBeauty</title>

    <link rel="stylesheet" href="css/main.css" type="text/css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/calendarPicker.js"></script>
    <script src="js/calendarScheduleLimiter.js"></script>
</head>

<body>

    <div id="main">
        <?php 
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            include('classes/Database.php');
            $db = new Database();

            if(isset($_POST['submit'])){
              
             

              

              $query = $db->get()->prepare("
                INSERT INTO bookings VALUES (?,?,?)
              ");
              $query->execute(array($_POST['bookingdate'],$_POST['employee'],$_POST['treatment']));

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

                        echo '<input type="radio" name="treatment" value="'. $t['name'] .'" 
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

        </div><!-- #optionsContainer ends -->

        <div id="calendarContainer">

            <h1>calendar</h1>

            
              <?php

                include('classes/calendar.php');
                    
                $calendar = new Calendar($db);
                $calendar->render();

              ?>

         <input type="submit" name="submit"/>  
        </div><!-- #calendarContainer ends -->
    </form>
    </div><!-- #main ends -->

</body>