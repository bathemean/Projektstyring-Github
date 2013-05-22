<?php

  class Auth {

    private $db;

    public function __construct() {
        include_once('Database.php');
        $this->db = new Database();
        $this->db = $this->db->get();
    }

    public function checkCredentials($post) {

      $password = md5($_POST['password']);

    	$query = $this->db->prepare("
    		SELECT clientid id
        FROM clients
        WHERE clientemail = :email AND clientpassword = :password
    	");

      try {
        $query->execute( array(':email' => $post['email'],
                               ':password' => $password) );
        
        $c = $query->rowCount();

        if($c == 0) {
          return false; // email and password combo does not exist
        } else {
          
          $res = $query->fetch();
          $this->login($res['id']); // log user in


        }

      } catch(PDOExepction $e) {
        return false;
      }

    }

    private function login($id) {

      $_SESSION['id'] = $id;

    }

    public function logout() {
      session_destroy();
      return true;
    }

  }
?>