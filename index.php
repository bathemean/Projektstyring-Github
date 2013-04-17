<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>NailBeauty</title>

    <link rel="stylesheet" href="css/main.css" type="text/css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/calendarPicker.js"></script>
</head>

<body>

    <div id="main">
        <?php 
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            include('classes/Database.php');

            if(isset($_POST['submit'])){
              
             

              $db = new Database();

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
                
                <input type="radio" name="treatment" value="1" checked="checked" /> Span-manicure (60 min)
                <br />
                <input type="radio" name="treatment" value="2"/> Manicure (30 min)
                <br />
                <input type="radio" name="treatment" value="3"/> Pedicure (30 min)
                <br />
                <input type="radio" name="treatment" value="4"/> Fodbehandling v/ statsaut. fodterapeaut (60 min)
            </div><!-- .optionsPanel ends -->

            <div class="optionsPanel">
                <h1>Vælg medarbejder(e)</h1>
                
                <input type="radio" name="employee" value="a" checked="checked" /> Medarbejder A
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

            
              <?php

                include('classes/calendar.php');
                    
                $calendar = new Calendar();
                $calendar->render();

              ?>

         <input type="submit" name="submit"/>  
        </div><!-- #calendarContainer ends -->
    </form>
    </div><!-- #main ends -->

</body>