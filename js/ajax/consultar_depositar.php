<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id= session_id();
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include("../funciones.php");

$id = $_GET['id'];

$sql = "SELECT `monto_cierre` FROM `apertura_cierre_caja` WHERE `id_acc` = ".$id;
$query = mysqli_query($con, $sql);
$row = mysqli_fetch_array($query);
echo $row['monto_cierre'] * 1;

?>





