<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleRemision.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleRemision = new DetalleRemision($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalleRemision->df_guia_remision_detrem= $info[0]["df_guia_remision_detrem"];
$detalleRemision->df_producto_precio_detrem= $info[0]["df_producto_precio_detrem"];
$detalleRemision->df_cant_producto_detrem= $info[0]["df_cant_producto_detrem"];
$detalleRemision->df_nombre_und_detrem= $info[0]["df_nombre_und_detrem"];
$detalleRemision->df_cant_x_und_detrem= $info[0]["df_cant_x_und_detrem"];
$detalleRemision->df_valor_sin_iva_detrem= number_format($info[0]["df_valor_sin_iva_detrem"], 2, '.', '');
$detalleRemision->df_iva_detrem= number_format($info[0]["df_iva_detrem"], 2, '.', '');
$detalleRemision->df_valor_total_detrem= number_format($info[0]["df_valor_total_detrem"], 2, '.', '');

// insert detalleRemision
$response = $detalleRemision->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}

?>