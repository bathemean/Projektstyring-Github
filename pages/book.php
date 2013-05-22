<a href="?p=home">< Tilbage</a><br /><br />

<?php
    require('classes/Database.php');
    $db = new Database();

    require('classes/Booking.php');
    $book = new Booking();

    require('classes/EmployeeManager.php');
    $emp = new EmployeeManager();

    if(isset($_POST['submit'])){
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        $book->insert($_POST, $_SESSION['id']);
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

                $employees = $emp->getEmployees();

                $n = 0;

                foreach($employees as $e) {

                    $schedule = $emp->getSchedule($e['id']);
                    
                    foreach($schedule as $s) {
                        echo '<input type="hidden" id="'. $e['id'] .'"  
                            name="scheduleStart" day="'.$s['day'].'" 
                            start="'. $s['start'] .'" end="'. $s['end'] .'" />';

                    }

                    $exceptions = $emp->getExceptions($e['id']);
                    foreach($exceptions as $exc) {

                        echo '<input type="hidden" id="'. $e['id'] .'"  
                            name="exception" date="'. $exc['date'].'" 
                            start="'. $exc['start'] .'" end="'. $exc['end'] .'" />';
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
            $('div#calendarContainer').load('pages/calendar.php');
        });
    </script>
    
    <div id="calendarContainer">
    </div> 
    
</form>