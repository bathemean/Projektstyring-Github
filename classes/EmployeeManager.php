<?php

	class EmployeeManager {

        private $db;

        public function __construct() {
            include_once('Database.php');
            $this->db = new Database();
            $this->db = $this->db->get();
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


    }

?>