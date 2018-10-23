<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaRemision.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaRemision = new GuiaRemision($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$guiaRemision->df_codigo_rem= $info[0]["df_codigo_rem"];
$guiaRemision->df_fecha_remision= $info[0]["df_fecha_remision"];
$guiaRemision->df_sector_cod_rem= $info[0]["df_sector_cod_rem"];
$guiaRemision->df_vendedor_rem= $info[0]["df_vendedor_rem"];
$guiaRemision->df_cant_total_producto_rem= $info[0]["df_cant_total_producto_rem"];
$guiaRemision->df_valor_efectivo_rem= $info[0]["df_valor_efectivo_rem"];
$guiaRemision->df_creadoBy_rem= $info[0]["df_creadoBy_rem"];

// insert guiaRemision
$response = $guiaRemision->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>