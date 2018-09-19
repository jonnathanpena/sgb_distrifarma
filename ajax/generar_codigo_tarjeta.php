<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

    $id = $_GET['id'];

    $random = rand(1, 99);

    $codigo = $id.$random;

    echo $codigo;
    

?>