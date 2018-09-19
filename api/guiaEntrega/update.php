<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaEntrega.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaEntrega = new GuiaEntrega($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$guiaEntrega->df_repartidor_ent= $info[0]["df_repartidor_ent"];
$guiaEntrega->df_cant_total_producto_ent= $info[0]["df_cant_total_producto_ent"];
$guiaEntrega->df_cant_facturas_ent= $info[0]["df_cant_facturas_ent"];
$guiaEntrega->df_modificadoBy_ent= $info[0]["df_modificadoBy_ent"];
$guiaEntrega->df_guia_ent_recibido= $info[0]["df_guia_ent_recibido"];
$guiaEntrega->df_num_guia_entrega= $info[0]["df_num_guia_entrega"];

// modificar guiaEntrega
$response = $guiaEntrega->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>