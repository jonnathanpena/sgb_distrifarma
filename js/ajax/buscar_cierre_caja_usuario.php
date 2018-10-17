<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
$id_acc = $_GET['id'];
//Count the total number of row in your table*/
$query   = mysqli_query($con, "SELECT `id_acc`, `usuario_id`, `fecha_apertura` FROM `apertura_cierre_caja` WHERE `id_acc` = ".$id_acc);
$row = mysqli_fetch_array($query);
$array = array(
    "compras" => getCompras($con, $row['usuario_id'], $row['fecha_apertura']) * 1,
    "efectivo" => ventasEfectivo($con, $row['usuario_id'], $row['fecha_apertura']) * 1,
    "tarjeta" => ventasTarjeta($con, $row['usuario_id'], $row['fecha_apertura']) * 1,
    "transferencias" => ventasTransferencia($con, $row['usuario_id'], $row['fecha_apertura']) * 1,
    "abonos" => abonos($con, $row['usuario_id'], $row['fecha_apertura']) * 1,
    "total" => (ventasEfectivo($con, $row['usuario_id'], $row['fecha_apertura']) * 1) + (abonos($con, $row['usuario_id'], $row['fecha_apertura']) * 1)
);
echo json_encode($array);

function getCompras($conexion,$id_usuario, $fecha_apertura) {
    $fecha_cierre = date('Y-m-d H:i:s');
    $sql = "SELECT SUM(`gasto_total_cc`) as total FROM `caja_chica` 
                WHERE `user_cc` = ".$id_usuario." AND `fecha_cc` >= '".$fecha_apertura."' AND `fecha_cc` <= '".$fecha_cierre."'";
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['total'];
}

function ventasEfectivo($conexion, $id_usuario, $fecha_apertura) {
    $fecha_cierre = date('Y-m-d H:i:s');
    $sql = "SELECT SUM(`total_venta`) as total FROM `facturas` WHERE `estado_factura` = 1 
        AND `id_vendedor` = ".$id_usuario." AND `condiciones` = 1 
        AND `fecha_factura` >= '".$fecha_apertura."' AND `fecha_factura` <= '".$fecha_cierre."'";
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['total'];
}

function ventasTarjeta($conexion, $id_usuario, $fecha_apertura) {
    $fecha_cierre = date('Y-m-d H:i:s');
    $sql = "SELECT SUM(`total_venta`) as total FROM `facturas` WHERE `estado_factura` = 1 
        AND `id_vendedor` = ".$id_usuario." AND `condiciones` = 3 
        AND `fecha_factura` >= '".$fecha_apertura."' AND `fecha_factura` <= '".$fecha_cierre."'";
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['total'];
}

function ventasTransferencia($conexion, $id_usuario, $fecha_apertura) {
    $fecha_cierre = date('Y-m-d H:i:s');
    $sql = "SELECT SUM(`total_venta`) as total FROM `facturas` WHERE `estado_factura` = 1 
        AND `id_vendedor` = ".$id_usuario." AND `condiciones` = 2 
        AND `fecha_factura` >= '".$fecha_apertura."' AND `fecha_factura` <= '".$fecha_cierre."'";
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['total'];
}

function abonos($conexion, $id_usuario, $fecha_apertura) {
    $fecha_cierre = date('Y-m-d H:i:s');
    $sql = "SELECT SUM(`monto_abonos`) as total FROM `abonos` 
        WHERE `usuario_id` = ".$id_usuario." AND `fecha_abonos` >= '".$fecha_apertura."' 
        AND `fecha_abonos` <= '".$fecha_cierre."'";
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['total'];
}

?>