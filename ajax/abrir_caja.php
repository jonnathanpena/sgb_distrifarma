<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id= session_id();
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include("../funciones.php");

$usuario = $_GET['usuario'];
$monto = $_GET['monto'];
$fecha = date('Y-m-d H:i:s');

$sql = "INSERT INTO `apertura_cierre_caja`(`usuario_id`, `fecha_apertura`, `monto_apertura`, `fecha_cierre`, `monto_cierre`, `estado_acc`) VALUES (
            ".$usuario.",
            '".$fecha."',
            ".$monto.",
            NULL,
            NULL,
            0
        )";
mysqli_query($con, $sql);
echo true;

?>





