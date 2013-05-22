<?php
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>NailBeauty</title>

    <link rel="stylesheet" href="css/main.css" type="text/css" />

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    
</head>

<body>

    <div id="main">
        
        <?php 
            if(isset($_SESSION['id']))
                echo 'Logget ind som: '.$_SESSION['id'].' - <a href="?p=logout">log ud</a>';
            else
                echo 'Ikke logget ind.';

            echo '<br /><br />';

            error_reporting(E_ERROR | E_WARNING | E_PARSE);

            if(isset($_GET['p'])) {
                include('pages/'. $_GET['p'] .'.php');
            } else {
                include('pages/home.php');
            }

        ?>
        
    </div><!-- #main ends -->

</body>