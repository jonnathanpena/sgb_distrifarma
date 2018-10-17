<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/factura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$factura = new Factura($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);  

// configura los valores recibidos en post de la app
$factura->df_cliente_cod_fac= $info[0]["df_cliente_cod_fac"];
$factura->df_personal_cod_fac= $info[0]["df_personal_cod_fac"];
$factura->df_sector_cod_fac= $info[0]["df_sector_cod_fac"];
$factura->df_forma_pago_fac= $info[0]["df_forma_pago_fac"];
$factura->df_subtotal_fac= $info[0]["df_subtotal_fac"];
$factura->df_descuento_fac= $info[0]["df_descuento_fac"];
$factura->df_iva_fac= $info[0]["df_iva_fac"];
$factura->df_valor_total_fac= $info[0]["df_valor_total_fac"];
$factura->df_edo_factura_fac= $info[0]["df_edo_factura_fac"];
$factura->df_fecha_entrega_fac= $info[0]["df_fecha_entrega_fac"];
$factura->df_num_factura= $info[0]["df_num_factura"];

// modificar factura
$response = $factura->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>