<?php

    
    /**
    * Database access class.
    * 
    */
    class Database {
        private $DSN = 'mysql:dbname=k3nailbeauty;host=web2.ybnet.dk';
        private $USERNAME = 'k3nailbeauty';
        private $PASSWORD = 'vandfald';

        private static $instance;

        function __construct() {

            $this->connect();

        }

        private function connect() {
            try {
                self::$instance = new PDO($this->DSN, $this->USERNAME, $this->PASSWORD);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                exit;
            }

        }

        public function get() {
            return self::$instance;
        }

    }

?>