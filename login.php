<?php

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

date_default_timezone_set('America/Bogota');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>SGB | Login</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- CSS  -->
   <link href="css/login.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
 	<div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="img/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form id="form-login" accept-charset="utf-8" name="loginform" autocomplete="off" role="form" class="form-signin">
				<span id="reauth-email" class="reauth-email"></span>
				<input class="form-control" placeholder="Usuario" name="user_name" id="user_name" type="text" value="" autofocus="" required>
				<input class="form-control" placeholder="Contraseña" name="user_password" id="user_password" type="password" value="" autocomplete="off" required>
				<button type="submit" class="btn btn-lg btn-success btn-block btn-signin" name="login" id="submit">Iniciar Sesión</button>
            </form><!-- /form -->  
        </div><!-- /card-container -->
    </div><!-- /container -->

	<script src="js/lib/jquery/dist/jquery-3.1.1.min.js"></script>
	<script src="js/lib/jquery.toaster.js"></script>
    <script type="text/javascript" src="js/lib/flash.min.js"></script>
	<script type="text/javascript" src="js/config.js"></script>	
	<script type="text/javascript" src="js/login.js"></script>	
</body>
</html>





