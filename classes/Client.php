<?php

  class Client {

        private $db;

        private $id;

        public function __construct() {
          $this->establishDB();
          $this->id = $id;
        }

        private function establishDB() {
          include_once('Database.php');
          $this->db = new Database();
          $this->db = $this->db->get();
        }

        public function insert($post) {

          if( $post['password1'] == $post['password2'] )
            $password = md5($post['password1']);
          else
            return false;

          $query = $this->db->prepare("
            INSERT INTO clients
            (clientname, clientemail, clientpassword)
            VALUES (:name, :email, :password)
          ");

          try {
            $query->execute( array(':name' => $post['name'],
                                   ':email' => $post['email'],
                                   ':password' => $password) );
          } catch(PDOException $e) {
            return false;
          }

          return true;

        }

        public function getID() {
          return $this->id;
        }

    }

?>