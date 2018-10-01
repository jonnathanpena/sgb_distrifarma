<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalle_compra_gasto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalle_compra_gasto = new DetalleCompraGasto($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$detalle_compra_gasto->cuenta_dcg = $info[0]["cuenta_dcg"];
$detalle_compra_gasto->subtotal_civa_dcg = $info[0]["subtotal_civa_dcg"];
$detalle_compra_gasto->subtotal_siva_dcg = $info[0]["subtotal_siva_dcg"];
$detalle_compra_gasto->subtotal_iva_cero_dcg = $info[0]["subtotal_iva_cero_dcg"];
$detalle_compra_gasto->total_dcg = $info[0]["total_dcg"];
$detalle_compra_gasto->id_dcg = $info[0]["id_dcg"];

// modificar detalle_compra_gasto
$response = $detalle_compra_gasto->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>