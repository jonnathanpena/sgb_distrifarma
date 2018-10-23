<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalle_pago_compra.php';

// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalle_pago_compra = new DetallePagoCompra($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalle_pago_compra->compra_id = $info[0]["compra_id"];
$detalle_pago_compra->metodo_pago_id = $info[0]["metodo_pago_id"];
$detalle_pago_compra->egreso_id = $info[0]["egreso_id"];
$detalle_pago_compra->banco_emisor = $info[0]["banco_emisor"];
$detalle_pago_compra->banco_receptor = $info[0]["banco_receptor"];
$detalle_pago_compra->codigo = $info[0]["codigo"];
$detalle_pago_compra->fecha = $info[0]["fecha"];
$detalle_pago_compra->tipo_tarjeta = $info[0]["tipo_tarjeta"];
$detalle_pago_compra->franquicia = $info[0]["franquicia"];
$detalle_pago_compra->recibo = $info[0]["recibo"];
$detalle_pago_compra->titular = $info[0]["titular"];
$detalle_pago_compra->cheque = $info[0]["cheque"];
$detalle_pago_compra->id_dpc = $info[0]["id_dpc"];

// insert detalle_pago_compra
$response = $detalle_pago_compra->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>