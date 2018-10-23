<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleCompraProducto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleCompraProducto = new DetalleCompraProducto($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalleCompraProducto->compra_id= $info[0]["compra_id"];
$detalleCompraProducto->producto_id= $info[0]["producto_id"];
$detalleCompraProducto->bonificacion_dcp= $info[0]["bonificacion_dcp"];
$detalleCompraProducto->cantidad_dcp= $info[0]["cantidad_dcp"];
$detalleCompraProducto->precio_unitario_dcp= $info[0]["precio_unitario_dcp"];
$detalleCompraProducto->descuento_dcp= $info[0]["descuento_dcp"];
$detalleCompraProducto->iva_dcp= $info[0]["iva_dcp"];
$detalleCompraProducto->subtotal_dcp= $info[0]["subtotal_dcp"];

// insert detalleCompraProducto
$response = $detalleCompraProducto->insert();

if($response == true){
    echo json_encode(true); 
}else{
    echo json_encode(false); 
}
?>