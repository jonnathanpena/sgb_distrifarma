<?php	

	session_start();
    date_default_timezone_set('America/Bogota');
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {

        header("location: ../login.php");

		exit;

    }