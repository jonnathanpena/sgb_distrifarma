<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/personal.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$personal = new Personal($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
// configura los valores recibidos en post de la app
$personal->df_tipo_documento_usuario= $info[0]["df_tipo_documento_usuario"];
$personal->df_documento_usuario= $info[0]["df_documento_usuario"];
$personal->df_nombre_usuario= $info[0]["df_nombre_usuario"];
$personal->df_apellido_usuario = $info[0]["df_apellido_usuario"];
$personal->df_usuario_usuario = $info[0]["df_usuario_usuario"];
$personal->df_personal_cod = $info[0]["df_personal_cod"];
$personal->df_clave_usuario = $info[0]["df_clave_usuario"];
$personal->df_activo = $info[0]["df_activo"];
$personal->df_correo = $info[0]["df_correo"];
$personal->df_tipo_usuario = $info[0]["df_tipo_usuario"];

// insert personal
$response = $personal->insertUsuario();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>