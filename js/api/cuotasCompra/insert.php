<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cuotasCompra.php';

// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cuotasCompra = new CuotasCompra($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$cuotasCompra->compra_id = $info[0]["compra_id"];
$cuotasCompra->df_fecha_cc = $info[0]["df_fecha_cc"];
$cuotasCompra->df_monto_cc = $info[0]["df_monto_cc"];
$cuotasCompra->df_estado_cc = $info[0]["df_estado_cc"];

// insert cuotasCompra
$response = $cuotasCompra->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>