<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
$usuario = $_GET['usuario'];
//Count the total number of row in your table*/
$query   = mysqli_query($con, "SELECT `id_acc` FROM `apertura_cierre_caja` WHERE `usuario_id` = ".$usuario." AND `estado_acc` = 0");
$numrows = mysqli_num_rows($query);
if ($numrows>0){
    echo true;
} else {
    echo false;
}
?>