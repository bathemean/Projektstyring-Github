<?php

  class Treatmentmanager {

        private $db;

        public function __construct() {
            include_once('Database.php');
            $this->db = new Database();
            $this->db = $this->db->get();
        }


        /**
        * Display a table of all treaments with their duration, in input fields.
        **/
        public function treatmentList() {

            $getTreatments = $this->db->query("
                    SELECT treatmentname name, treatmentduration duration
                    FROM treatments
                ");
            $treatments = $getTreatments->fetchAll();

            echo '<table id="treatments" border="0">';

                echo '<tr>';
                    echo '<th>Navn</th>';
                    echo '<th>Vargihed</th>';
                    echo '<th></th>';
                echo '</tr>';

            foreach($treatments as $t) {
                echo '<tr>';
                    echo '<td><input name="treatment[]" value="'. $t['name'] .'"</td>';
                    echo '<td><input name="duration[]" value="'. $t['duration'] .'"</td>';
                    echo '<td><a id="remove" href="#">fjern</a></td>';
                echo '</tr>';
            }

            echo '</table>';

        }

        /**
        * Delete all treatments from database.
        **/
        private function clear() {
          
          try {
            $query = $this->db->query("DELETE FROM treatments");
            $query->execute();
            return true;
          } catch(PDOException $e) {
            return false;
          }
        }

        public function insert($data) {

          if($this->clear()) {

            foreach($data as $d) {
              $query = $this->db->prepare("
                  INSERT INTO treatments
                  VALUES (?, ?)
                ");
              $query->execute( array($d['treatment'], $d['duration']) );
            }

          }

        }

    }

?>