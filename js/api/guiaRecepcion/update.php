<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaRecepcion = new GuiaRecepcion($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$guiaRecepcion->df_repartidor_rec= $info[0]["df_repartidor_rec"];
$guiaRecepcion->df_valor_recaudado= $info[0]["df_valor_recaudado"];
$guiaRecepcion->df_valor_efectivo= $info[0]["df_valor_efectivo"];
$guiaRecepcion->df_valor_cheque= $info[0]["df_valor_cheque"];
$guiaRecepcion->df_retenciones= $info[0]["df_retenciones"];
$guiaRecepcion->df_descuento_rec= $info[0]["df_descuento_rec"];
$guiaRecepcion->df_diferencia_rec= $info[0]["df_diferencia_rec"];
$guiaRecepcion->df_remision_rec= $info[0]["df_remision_rec"];
$guiaRecepcion->df_entrega_rec= $info[0]["df_entrega_rec"];
$guiaRecepcion->df_num_guia= $info[0]["df_num_guia"];
$guiaRecepcion->df_modificadoBy_rec= $info[0]["df_modificadoBy_rec"];
$guiaRecepcion->df_edo_factura_rec= $info[0]["df_edo_factura_rec"];
$guiaRecepcion->df_guia_recepcion= $info[0]["df_guia_recepcion"];

// modificar guiaRecepcion
$response = $guiaRecepcion->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>