<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleFactura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleFactura = new detalleFactura($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalleFactura->df_num_factura_detfac= $info[0]["df_num_factura_detfac"];
$detalleFactura->df_prod_precio_detfac= $info[0]["df_prod_precio_detfac"];
$detalleFactura->df_precio_prod_detfac= $info[0]["df_precio_prod_detfac"];
$detalleFactura->df_cantidad_detfac= $info[0]["df_cantidad_detfac"];
$detalleFactura->df_nombre_und_detfac= $info[0]["df_nombre_und_detfac"];
$detalleFactura->df_cant_x_und_detfac= $info[0]["df_cant_x_und_detfac"];
$detalleFactura->df_edo_entrega_prod_detfac= $info[0]["df_edo_entrega_prod_detfac"];
$detalleFactura->df_valor_sin_iva_detfac= $info[0]["df_valor_sin_iva_detfac"];
$detalleFactura->df_iva_detfac= $info[0]["df_iva_detfac"];
$detalleFactura->df_valor_total_detfac= $info[0]["df_valor_total_detfac"];

// insert detalleFactura
$response = $detalleFactura->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false);
}
?>