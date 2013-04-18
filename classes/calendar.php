<?php
    include ('time.php');

    class Calendar {

        private $db;
        private $WEEKDAYS = array('','Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag', 'Søndag');
        private $booked;

        public function __construct($database) {
            $this->db = $database->get();
            $this->booked = array();
        }

        public function render() {

            $getBookings = $this->db->query("
                                SELECT UNIX_TIMESTAMP(bookingdate) date, treatmentduration duration
                                FROM bookings JOIN treatments
                                    ON bookings.treatmentname = treatments.treatmentname
                            ");
            $bookings = $getBookings->fetchAll();            


            $date = strtotime('monday this week');
            $time = strtotime('10:00', $time);
            $n = 2;
            
            echo '<table id="calendar" cellspacing="0">';

            for($y = 0; $y<=32; $y++) {
                
                $n++;
                
                echo '<tr>';
                
                for($x = 0; $x <= 7; $x++) {
                                     
                    if ($y == 0) {

                        echo '<th>';
                            echo $this->WEEKDAYS[$x];

                            if($x != 0) {
                                echo '<br />'. date('d-m', $date);
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
                            
                            foreach($bookings as $b) {
                                
                                if( unixTimezoneConvert($b['date'], 
                                    'Europe/Copenhagen') == ($date+$time)) { 
                                    
                                    echo 'booked'; 

                                    array_push($this->booked, ($x.','.($y+1)) );

                                }

                                if( in_array(($x.','.$y), $this->booked) ) {
                                    echo 'booked';
                                }
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