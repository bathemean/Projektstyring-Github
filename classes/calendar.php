<?php
    include_once('functions.php');

    class Calendar {

        private $CALENDAR_START = 36000; // 10 hours in seconds
        
        private $db;
        private $booked;
        private $weekdays;

        private $bookings;

        public function __construct() {
            include_once('Database.php');
            $this->db = new Database();
            $this->db = $this->db->get();

            include_once('globals.php');
            $this->weekdays = $WEEKDAYS;

            $this->bookedSlots = array();
        }

        public function navigation() {
            echo '<div id="calendarNavigation">';
                echo '<a href="" id="calNavLeft"><img src="img/arrow-left.png" /></a>';
                echo '<a href="" id="calNavRight"><img src="img/arrow-right.png" /></a>';
            echo '</div>';
        }

        public function render($start, $mode) {

            $getBookings = $this->db->query("
                                SELECT bookingdate date, employeeid, treatmentduration duration
                                FROM bookings JOIN treatments
                                    ON bookings.treatmentname = treatments.treatmentname
                            ");
            $bookings = $getBookings->fetchAll();

            foreach($bookings as $b) {
                
                // create an array of all booked cells
                $i = $b['duration']/15;
                for($j = 0; $j < $i; $j++) {
                    // Example: a treatment has a duration of 60 minutes (4 quarters).
                    // we add 4 items to the array, one for each calendar cell we want to
                    // mark.
                    array_push($this->bookedSlots, ($b['date'] + ($j * 900)) );
                }

                $this->bookings[$b['date']] = array('date' => $b['date'],
                                                    'employeeid' => $b['employeeid']);

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
                                echo $this->weekdays[date('w', $date)];

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
                                if( in_array(($date + $time), $this->bookedSlots) ) {
                                    echo 'booked';
                                }

                        echo '" ">';

                        if( $mode == 'admin' && isset($this->bookings[$date+$time]) )
                            echo $this->doCheckinForm($this->bookings[$date+$time]['date'], 
                                                      $this->bookings[$date+$time]['employeeid']);

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

        /**
        * Creates an input field corrosponding to the supplied data, to handle checkin and checkout.
        **/
        private function doCheckinForm($date, $employeeid) {

            $getState = $this->db->prepare("
                SELECT state
                FROM bookingCheckins
                WHERE bookingdate = ? AND employeeid = ?");
            $getState->execute( array($date, $employeeid) );
            $state = $getState->fetch();


            return '<input id="checkin" name="'.$date.'" employeeid="'.$employeeid.'" type="checkbox" 
              '. ($state['state'] == 1 ? 'checked' : '') .' value="1" />';

        }

    }

?>