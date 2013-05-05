<a href="?p=home">< Tilbage</a><br /><br />

<?php
    require('classes/Database.php');
    $db = new Database();


?>
<form method="POST">
    
    <div id="optionsContainer">

        <div class="optionsPanel">
            <h1>Vælg medarbejder</h1>
            
            <?php

                $getEmployees = $db->get()->query("
                    SELECT employeeid id, employeename name
                    FROM employees
                ");
                $employees = $getEmployees->fetchAll();

                $n = 0;

                foreach($employees as $e) {

                    $getSchedule = $db->get()->prepare("
                            SELECT day, start, end
                            FROM empSchedule
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

    <script>
        // spawn the calendar on page load
        $(document).ready(function() {
            $('div#calendarContainer').load('pages/adminCalendar.php');
        });
    </script>
    
    <div id="calendarContainer">
    </div> 
    
</form>