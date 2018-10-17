<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/banco.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$banco = new Banco($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$banco->df_fecha_banco= $info[0]["df_fecha_banco"];
$banco->df_usuario_id_banco= $info[0]["df_usuario_id_banco"];
$banco->df_tipo_movimiento= $info[0]["df_tipo_movimiento"];
$banco->df_monto_banco= $info[0]["df_monto_banco"];
$banco->df_saldo_banco= $info[0]["df_saldo_banco"];
$banco->df_num_documento_banco= $info[0]["df_num_documento_banco"];
$banco->df_detalle_mov_banco= $info[0]["df_detalle_mov_banco"];

// insert banco
$response = $banco->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>