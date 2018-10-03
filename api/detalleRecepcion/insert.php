<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleRecepcion = new DetalleRecepcion($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalleRecepcion->df_guia_recepcion_detrec= $info[0]["df_guia_recepcion_detrec"];
$detalleRecepcion->df_factura_rec= $info[0]["df_factura_rec"];
$detalleRecepcion->df_cant_producto_detrec= $info[0]["df_cant_producto_detrec"];
$detalleRecepcion->df_producto_cod_detrec= $info[0]["df_producto_cod_detrec"];
$detalleRecepcion->df_nueva_fecha= $info[0]["df_nueva_fecha"];
$detalleRecepcion->df_detalleRemision_detrec= $info[0]["df_detalleRemision_detrec"];
$detalleRecepcion->df_edo_prod_fact_detrec= $info[0]["df_edo_prod_fact_detrec"];

// insert detalleRecepcion
$response = $detalleRecepcion->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}

?>