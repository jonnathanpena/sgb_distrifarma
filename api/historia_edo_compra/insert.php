<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/historia_edo_compra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$historia_edo = new HistoriaEdoCompra($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$historia_edo->compra_id_hist= $info[0]["compra_id_hist"];
$historia_edo->venta_id_hist= $info[0]["venta_id_hist"];
$historia_edo->retencion_id_hist= $info[0]["retencion_id_hist"];
$historia_edo->id_edo_entrega_hist= $info[0]["id_edo_entrega_hist"];
$historia_edo->id_edo_pago_hist= $info[0]["id_edo_pago_hist"];
$historia_edo->fecha_hist= $info[0]["fecha_hist"];

// insert historia_edo
$response = $historia_edo->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false);
}
?>