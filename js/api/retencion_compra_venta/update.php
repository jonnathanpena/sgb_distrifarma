<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/retencion_compra_venta.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$retencion_compra_venta = new RetencionCompraVenta($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$retencion_compra_venta->compra_id= $info[0]["compra_id"];
$retencion_compra_venta->venta_id= $info[0]["venta_id"];
$retencion_compra_venta->serie_retencion= $info[0]["serie_retencion"];
$retencion_compra_venta->num_retencion= $info[0]["num_retencion"];
$retencion_compra_venta->autorizacion_ret= $info[0]["autorizacion_ret"];
$retencion_compra_venta->fecha_ingreso_ret= $info[0]["fecha_ingreso_ret"];
$retencion_compra_venta->fecha_caduca_ret= $info[0]["fecha_caduca_ret"];
$retencion_compra_venta->retencion_iva_id= $info[0]["retencion_iva_id"];
$retencion_compra_venta->porcentaje_iva= $info[0]["porcentaje_iva"];
$retencion_compra_venta->base_imponible= $info[0]["base_imponible"];
$retencion_compra_venta->retencion_ir_id= $info[0]["retencion_ir_id"];
$retencion_compra_venta->porcentaje_ir= $info[0]["porcentaje_ir"];
$retencion_compra_venta->base_imponible_c_iva= $info[0]["base_imponible_c_iva"];
$retencion_compra_venta->base_imponible_s_iva= $info[0]["base_imponible_s_iva"];
$retencion_compra_venta->id_ret_com_ven= $info[0]["id_ret_com_ven"];


// insert retencion_compra_venta
$response = $retencion_compra_venta->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>