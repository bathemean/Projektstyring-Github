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
              echo '<td>'. $t['name'] .'</td>';
              echo '<td>'. $t['duration'] .'</td>';
              echo '<td><a href="?p=treatmentForm&name='. $t['name'] .'">Rediger</a></td>';
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

    /**
    * Inserts new record based on post data.
    **/
    public function insert($post) {

      $query = $this->db->prepare("
          INSERT INTO treatments
          VALUES (?, ?)
        ");
      $query->execute( array($post['name'], $post['duration']) );

      return true;
    
    }

    /**
    * Updates existing record based on post data.
    **/
    public function update($name, $post) {

      $query = $this->db->prepare("
                UPDATE treatments
                SET treatmentname = :name,
                    treatmentduration = :duration
                WHERE treatmentname = :orgname
              ");
      $query->execute( array(':orgname' => $name,
                             ':name' => $post['name'],
                             ':duration' => $post['duration']) );

      return true;

    }

    /**
    * Removes record from database based on treatmentname.
    **/
    public function delete($name) {

      $query = $this->db->prepare("
          DELETE FROM treatments WHERE treatmentname = ?
        ");
      $query->execute( array($name) );

      return true;

    }

  }

?>