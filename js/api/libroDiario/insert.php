<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/libroDiario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$libroDiario = new LibroDiario($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$libroDiario->df_fuente_ld= $info[0]["df_fuente_ld"];
$libroDiario->df_valor_inicial_ld= $info[0]["df_valor_inicial_ld"];
$libroDiario->df_fecha_ld= $info[0]["df_fecha_ld"];
$libroDiario->df_descipcion_ld= $info[0]["df_descipcion_ld"];
$libroDiario->df_ingreso_ld= $info[0]["df_ingreso_ld"];
$libroDiario->df_egreso_ld= $info[0]["df_egreso_ld"];
$libroDiario->df_usuario_id_ld= $info[0]["df_usuario_id_ld"];

// insert libroDiario
$response = $libroDiario->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>