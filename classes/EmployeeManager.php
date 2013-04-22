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
              $end -= 900; // subtract 15 minutes from end time, since calendar hands time slots by quarters

              $query = $this->db->prepare("
                INSERT INTO empSchedule
                (employeeid, day, start, end) VALUES
                (:id, :day, :start, :end)
              ");
              if(!$query->execute(array(':id' => $id,
                                       ':day' => $post[$n.'-day'],
                                       ':start' => $start,
                                       ':end' => $end))) {
                return false;
              }
                
              $n++;
            }

            return true;

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


        public function employeeList() {

            $getEmployees = $this->db->query("
                    SELECT employeeid id, employeename name
                    FROM employees
                ");
            $employees = $getEmployees->fetchAll();

            echo '<table border="0">';

                echo '<tr>';
                    echo '<th>Navn</th>';
                    echo '<th></th>';
                echo '</tr>';

            foreach($employees as $e) {
                echo '<tr>';
                    echo '<td>'. $e['name'] .'</td>';
                    echo '<td><a href="?p=employeeForm&id='. $e['id'] .'">Rediger</a></td>';
                echo '</tr>';
            }

            echo '</table>';

        }

        public function timeSelect($id, $mode, $compare) {

          echo '<select name="'. $id .'-'. $mode .'">';
            
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