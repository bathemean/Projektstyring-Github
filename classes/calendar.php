<?php
    include ('time.php');

    class Calendar {

        private $CALENDAR_START = 36000; // 10 hours in seconds
        private $WEEKDAYS = array('Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag');
        
        private $db;
        private $booked;

        public function __construct() {
            include_once('Database.php');
            $this->db = new Database();
            $this->db = $this->db->get();
            $this->booked = array();
        }

        public function navigation() {
            echo '<div id="calendarNavigation">';
                echo '<a href="" id="calNavLeft"><img src="img/arrow-left.png" /></a>';
                echo '<a href="" id="calNavRight"><img src="img/arrow-right.png" /></a>';
            echo '</div>';
        }

        public function render($start) {

            $getBookings = $this->db->query("
                                SELECT bookingdate date, treatmentduration duration
                                FROM bookings JOIN treatments
                                    ON bookings.treatmentname = treatments.treatmentname
                            ");
            $bookings = $getBookings->fetchAll();

            foreach($bookings as $b) {
                $i = $b['duration']/15;

                for($j = 0; $j < $i; $j++) {
                    // Example: a treatment has a duration of 60 minutes (4 quarters).
                    // we add 4 items to the array, one for each calendar cell we want to
                    // mark.
                    array_push($this->booked, ($b['date'] + ($j * 900)) );
                }
            }         


            if($start == 'monday this week') {
                $date = strtotime($start);
            } else {
                $date = date($start);
            }

            $time = $this->CALENDAR_START;
            $n = 2;

            // stores the selected date for data to db query
            echo '<input type="hidden" name="bookingdate" value="" />';
           
            echo '<table id="calendar" cellspacing="0">';

            for($y = 0; $y<=32; $y++) {
                
                $n++;
                
                echo '<tr>';
                
                for($x = 0; $x <= 7; $x++) {
                                     
                    if ($y == 0) {

                        echo '<th>';
                            
                            if($x != 0) {
                                echo $this->WEEKDAYS[date('w', $date)];

                                echo '<input type="hidden" id="'. $x .'" name="unixdate" value="'. $date .'" />';
                                echo '<br /> <span id="date">';
                                echo date('d-m', $date);
                                echo '</span>';
                                $date = strtotime('+1 day', $date);
                            }

                        echo '</th>';

                        if($x == 7)
                            $date = strtotime('monday last week', $date);

                    } else if ($x == 0) {
                       
                        echo '<th>';
                        
                        // print timestamp every 4th row
                        if($n == 4) {
                            $n = 0; 
                            echo date('H:i', $time);
                        }
                        

                        echo '</th>';

                    } else {
                         
                        // Table cells are given an id according to an (x,y) grid starting top left
                        echo '<td id="'. $x .','. $y .'" 
                            time="'.$time.'" 
                            value="'. ($date + $time) .'"
                            class="unavailable ';                       

                                // we check if the current cell is already booked
                                if( in_array(($date + $time), $this->booked) ) {
                                    echo 'booked';
                                }

                        echo '" ">';

                        echo '</td>';

                        $date = strtotime('+1 day', $date);

                        // before going to next row, increment time and reset date
                        if($x == 7) {
                            $time = strtotime('+15 minutes', $time);
                            $date = strtotime('monday last week', $date);

                        }
                    }
                     
                }
                
            echo '</tr>';

            }

        echo '</table>';

        }

    }

?>