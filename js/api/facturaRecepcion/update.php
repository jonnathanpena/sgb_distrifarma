<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/facturaRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$facturaRecepcion = new FacturaRecepcion($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$facturaRecepcion->df_id_guia_rec= $info[0]["df_id_guia_rec"];
$facturaRecepcion->df_num_factura_rec= $info[0]["df_num_factura_rec"];
$facturaRecepcion->df_tipo_pago_rec= $info[0]["df_tipo_pago_rec"];
$facturaRecepcion->df_monto_rec= $info[0]["df_monto_rec"];
$facturaRecepcion->df_num_documento= $info[0]["df_num_documento"];
$facturaRecepcion->df_id_factura_rec= $info[0]["df_id_factura_rec"];

// insert facturaRecepcion
$response = $facturaRecepcion->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}

?>