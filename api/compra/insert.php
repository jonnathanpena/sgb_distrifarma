<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/compra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$compra = new Compra($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$compra->usuario_id= $info[0]["usuario_id"];
$compra->fecha_compra= $info[0]["fecha_compra"];
$compra->proveedor_id= $info[0]["proveedor_id"];
$compra->detalle_sustento_comprobante_id= $info[0]["detalle_sustento_comprobante_id"];
$compra->serie_compra= $info[0]["serie_compra"];
$compra->documento_compra= $info[0]["documento_compra"];
$compra->autorizacion_compra= $info[0]["autorizacion_compra"];
$compra->fecha_comprobante_compra= $info[0]["fecha_comprobante_compra"];
$compra->fecha_ingreso_bodega_compra= $info[0]["fecha_ingreso_bodega_compra"];
$compra->fecha_caducidad_compra= $info[0]["fecha_caducidad_compra"];
$compra->vencimiento_compra= $info[0]["vencimiento_compra"];
$compra->descripcion_compra= $info[0]["descripcion_compra"];
$compra->condiciones_compra= $info[0]["condiciones_compra"];
$compra->st_con_iva_compra= $info[0]["st_con_iva_compra"];
$compra->descuento_con_iva_compra= $info[0]["descuento_con_iva_compra"];
$compra->total_con_iva_compra= $info[0]["total_con_iva_compra"];
$compra->descuento_sin_iva_compra= $info[0]["descuento_sin_iva_compra"];
$compra->st_sin_iva_compra= $info[0]["st_sin_iva_compra"];
$compra->total_sin_iva_compra= $info[0]["total_sin_iva_compra"];
$compra->st_iva_cero_compra= $info[0]["st_iva_cero_compra"];
$compra->descuento_iva_cero_compra= $info[0]["descuento_iva_cero_compra"];
$compra->total_iva_cero= $info[0]["total_iva_cero"];
$compra->ice_cc_compra= $info[0]["ice_cc_compra"];
$compra->imp_verde_compra= $info[0]["imp_verde_compra"];
$compra->iva_compra= $info[0]["iva_compra"];
$compra->otros_compra= $info[0]["otros_compra"];
$compra->interes_compra= $info[0]["interes_compra"];
$compra->bonificacion_compra= $info[0]["bonificacion_compra"];
$compra->total_compra= $info[0]["total_compra"];


// insert compra
$response = $compra->insert();

if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    echo json_encode(false); 
}
?>