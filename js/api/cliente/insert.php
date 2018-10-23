<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cliente.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cliente = new Cliente($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$cliente->df_id_cliente= $info[0]["df_id_cliente"];
$cliente->df_codigo_cliente= $info[0]["df_codigo_cliente"];
$cliente->df_nombre_cli= $info[0]["df_nombre_cli"];
$cliente->df_razon_social_cli= $info[0]["df_razon_social_cli"];
$cliente->df_tipo_documento_cli= $info[0]["df_tipo_documento_cli"];
$cliente->df_documento_cli= $info[0]["df_documento_cli"];
$cliente->df_direccion_cli= $info[0]["df_direccion_cli"];
$cliente->df_referencia_cli= $info[0]["df_referencia_cli"];
$cliente->df_sector_cod= $info[0]["df_sector_cod"];
$cliente->df_email_cli= $info[0]["df_email_cli"];
$cliente->df_telefono_cli= $info[0]["df_telefono_cli"];
$cliente->df_celular_cli= $info[0]["df_celular_cli"];

// insert cliente
$response = $cliente->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>