<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id= session_id();
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include("../funciones.php");

$id = $_GET['id'];
$monto = $_GET['monto'];
$fecha = date('Y-m-d H:i:s');

$sql = "UPDATE `apertura_cierre_caja` SET 
    `fecha_cierre`= '".$fecha."',
    `monto_cierre`= ".$monto.",
    `estado_acc`= 1 
    WHERE `id_acc` = ".$id;
mysqli_query($con, $sql);
echo true;

?>





