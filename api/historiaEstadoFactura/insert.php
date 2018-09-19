<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/historiaEstadoFactura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$historiaEstadoFactura = new HistoriaEstadoFactura($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$historiaEstadoFactura->df_num_factura= $info[0]["df_num_factura"];
$historiaEstadoFactura->df_edo_factura= $info[0]["df_edo_factura"];
$historiaEstadoFactura->df_edo_impresion= $info[0]["df_edo_impresion"];
$historiaEstadoFactura->df_usuario_id= $info[0]["df_usuario_id"];
$historiaEstadoFactura->df_fecha_proceso= $info[0]["df_fecha_proceso"];

// insert historiaEstadoFactura
$response = $historiaEstadoFactura->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false);
}
?>