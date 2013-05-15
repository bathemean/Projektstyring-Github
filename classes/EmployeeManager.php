<?php

  class EmployeeManager {

        private $db;

        public function __construct() {
            include_once('Database.php');
            $this->db = new Database();
            $this->db = $this->db->get();
        }

        public function insert($post) {

          try{
            $this->db->beginTransaction();

            $query = $this->db->prepare("
                INSERT INTO employees
                (employeename)
                VALUES (?)
              ");
            $query->execute( array($post['employeename']) );

            $id = $this->db->lastInsertId();


            $this->insertSchedule($id, $post);


            $this->db->commit();

            return true;

          } catch(PDOException $e) {
            $this->db->rollBack();
            return $e->getMessage();
          }

        }

        public function update($id, $post) {

          try {
            $this->db->beginTransaction();

            $query = $this->db->prepare("
                UPDATE employees
                SET employeename = :name
                WHERE employeeid = :id
              ");
            $query->execute( array(':id' => $id,
                                   ':name' => $post['employeename']) );

            $this->removeSchedule($id);

            $this->insertSchedule($id, $post);
            
            $this->db->commit();

            return true;     

          } catch(PDOException $e) {
            $this->db->rollBack();
            return $e->getMessage();
          }

        }

        public function delete($id) {

          try{
            $this->db->beginTransaction();

            $query = $this->db->prepare("
                DELETE FROM employees
                WHERE employeeid = ?
              ");
            $query->execute( array($id) );

            $this->removeSchedule($id);

            $this->db->commit();

            return true;
          } catch(PDOException $e) {
            $this->db->rollBack();
            return $e->getMessage();
          }

        }


        private function insertSchedule($id, $post) {

          $n = 1;
            while($_POST[$n.'-start-hours']) {
              
              $start = ($post[$n.'-start-hours'] * 3600) + ($post[$n.'-start-min'] * 60);
              $end = ($post[$n.'-end-hours'] * 3600) + ($post[$n.'-end-min'] * 60);
              $end -= 900; // subtract 15 minutes from end time, since calendar 
                           //handless time slots by quarters

              $query = $this->db->prepare("
                INSERT INTO empSchedule
                (employeeid, day, start, end, fieldid) VALUES
                (:id, :day, :start, :end, :f)
              ");
              if(!$query->execute(array(':id' => $id,
                                       ':day' => $post[$n.'-day'],
                                       ':start' => $start,
                                       ':end' => $end,
                                       ':f' => $n))) {
                return false;
              }
                
              $n++;
            }

            return true;

        }

        public function getSchedule($employee) {
          
          $getSchedule = $this->db->prepare("
                            SELECT scheduleid, day, start, end
                            FROM empSchedule
                            WHERE employeeid = ?
                        ");
          $getSchedule->execute( array($employee) );
          $schedule = $getSchedule->fetchAll();

          return $schedule;

        }

        private function removeSchedule($id) {
          $query = $this->db->prepare("
                DELETE FROM empSchedule
                WHERE employeeid = ?
              ");
          if(!$query->execute( array($id) )) {
            return false;
          }

          return true;
        }


        public function getEmployees() {

            $getEmployees = $this->db->query("
                    SELECT employeeid id, employeename name
                    FROM employees
                ");
            $employees = $getEmployees->fetchAll();

            return $employees;

        }

        public function getException($id) {

          $getException = $this->db->prepare("
            SELECT excDate date, excStart start, excEnd end, excNote note
            FROM empScheduleExc
            WHERE exceptionid = ?
          ");
          $getException->execute( array($id) );
          $exception = $getException->fetch();

          return $exception;

        }

        public function getExceptions($employee) {

          $getExceptions = $this->db->prepare("
            SELECT exceptionid id, excDate date, excStart start, excEnd end, excNote note
            FROM empScheduleExc
            WHERE employeeid = ?
          ");
          $getExceptions->execute( array($employee) );
          $exceptions = $getExceptions->fetchAll();

          return $exceptions;

        }

        public function insertException($post) {

          $date = strtotime($_POST['date']);
          
          $start = $this->timeConvert($post['-start-hours'], $post['-start-min']);
          $end = $this->timeConvert($post['-end-hours'], $post['-end-min']);
          $end -= 900; // subtract 15 minutes from end time, since calendar 
                           //handless time slots by quarters

          $query = $this->db->prepare("
            INSERT INTO empScheduleExc
            (employeeid, excDate, excStart, excEnd, excNote)
            VALUES (:employee, :date, :start, :end, :note)
          ");
          
          if( $query->execute( array(':employee' => $post['employeeid'],
                                     ':date' => $date,
                                     ':start' => $start,
                                     ':end' => $end,
                                     ':note' => $post['note']) ) ) {

            return true;

          }

          return false;

        }

        public function updateException($post) {

          $date = strtotime($_POST['date']);

          $start = $this->timeConvert($post['-start-hours'], $post['-start-min']);
          $end = $this->timeConvert($post['-end-hours'], $post['-end-min']);
          $end -= 900;

          $query = $this->db->prepare("
            UPDATE empScheduleExc
            SET excDate = :date,
                excStart = :start,
                excEnd = :end,
                excNote = :note
            WHERE exceptionid = :id
          ");

          /*if(  ) {
            return true;
          }
          return false;*/

          if( $query->execute( array(':id' => $post['exceptionid'],
                                     ':date' => $date,
                                     ':start' => $start,
                                     ':end' => $end,
                                     ':note' => $post['note']) ) ) {
            return true;
          }

          return false;

        }



        private function timeConvert($hours, $minutes) {
          return ($hours * 3600) + ($minutes * 60);
        }

        public function timeSelect($id, $mode, $compare) {

          echo '<select id="timeselect" name="'. $id .'-'. $mode .'">';
            
            if($mode == 'start-hours' || $mode == 'end-hours') {
              for($u = 1; $u <= 24; $u++) {
                echo '<option '. ($u == $compare ? 'selected' : '') .' value="'. $u .'">'. $u .'</option>';
              }
            } else {
              for($u = 0; $u < 4; $u++){
                echo '<option '. ( ($u * 15) == $compare ? 'selected' : '') .' value="'. ($u * 15) .'">'. ($u * 15) .'</option>';
               }
            }
              
         echo '</select>';

       }


    }

?>