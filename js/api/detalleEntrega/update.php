<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleEntrega.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleEntrega = new DetalleEntrega($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$detalleEntrega->df_guia_entrega= $info[0]["df_guia_entrega"];
$detalleEntrega->df_cod_producto= $info[0]["df_cod_producto"];
$detalleEntrega->df_cant_producto_detent= $info[0]["df_cant_producto_detent"];
$detalleEntrega->df_factura_detent= $info[0]["df_factura_detent"];
$detalleEntrega->df_nom_producto_detent= $info[0]["df_nom_producto_detent"];
$detalleEntrega->df_num_factura_detent= $info[0]["df_num_factura_detent"];
$detalleEntrega->df_id_detent= $info[0]["df_id_detent"];
$detalleEntrega->df_unidad_detent= $info[0]["df_unidad_detent"];


// insert detalleEntrega
$response = $detalleEntrega->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}

?>