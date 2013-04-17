<?php

    class Calendar {

        private $WEEKDAYS = array('','Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag', 'Søndag');

        public function render() {

$i = 0;            
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
                        echo '<td id="'. $x .','. $y .'" value="'. ($date + $time) .'">';                        
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