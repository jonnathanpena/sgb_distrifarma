<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/kardex.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$kardex = new Kardex($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$kardex->df_kardex_codigo= $info[0]["df_kardex_codigo"];
$kardex->df_fecha_kar= $info[0]["df_fecha_kar"];
$kardex->df_producto_cod_kar= $info[0]["df_producto_cod_kar"];
$kardex->df_producto= $info[0]["df_producto"];
$kardex->df_factura_kar= $info[0]["df_factura_kar"];
$kardex->df_ingresa_kar= $info[0]["df_ingresa_kar"];
$kardex->df_egresa_kar= $info[0]["df_egresa_kar"];
$kardex->df_existencia_kar= $info[0]["df_existencia_kar"];
$kardex->df_creadoBy_kar= $info[0]["df_creadoBy_kar"];
$kardex->df_edo_kardex= $info[0]["df_edo_kardex"];

// insert kardex
$response = $kardex->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>