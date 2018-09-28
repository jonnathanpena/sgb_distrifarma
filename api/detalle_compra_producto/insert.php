<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalle_compra_producto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalle_compra_producto = new DetalleCompraProducto($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$detalle_compra_producto->compra_id = $info[0]["compra_id"];
$detalle_compra_producto->codigo_dcp = $info[0]["codigo_dcp"];
$detalle_compra_producto->cantidad_dcp = $info[0]["cantidad_dcp"];
$detalle_compra_producto->precio_unitario_dcp = $info[0]["precio_unitario_dcp"];
$detalle_compra_producto->total_dcp = $info[0]["total_dcp"];

// insert detalle_compra_producto
$response = $detalle_compra_producto->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>