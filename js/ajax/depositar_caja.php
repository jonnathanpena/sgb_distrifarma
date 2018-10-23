<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
$session_id= session_id();
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
//Archivo de funciones PHP
include("../funciones.php");

$id_acc = $_GET['id_acc'];
$banco = $_GET['banco'];
$cuenta = $_GET['cuenta'];
$referencia = $_GET['referencia'];
$monto = $_GET['monto'];
$usuario = $_SESSION['user_id'];
$fecha = date('Y-m-d H:i:s');

$sql = "INSERT INTO `depositos`(`fecha_dep`, `monto_dep`, `acc_id`, `usuario_id`, `banco_dep`, `n_cuenta_dep`, `referencia_dep`) VALUES (
            '".$fecha."',
            ".$monto.",
            ".$id_acc.",
            ".$usuario.",
            '".$banco."',
            ".$cuenta.",
            '".$referencia."'
        )";
mysqli_query($con, $sql);
$sql = "UPDATE `apertura_cierre_caja` SET `estado_acc`= 2 WHERE `id_acc` = ".$id_acc;
mysqli_query($con, $sql);
return true;
?>





