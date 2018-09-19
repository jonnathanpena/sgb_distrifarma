<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/usuario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$usuario = new Usuario($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$usuario->df_nombre_per= $info[0]["df_nombre_per"];
$usuario->df_cargo_per= $info[0]["df_cargo_per"];
$usuario->df_fecha_ingreso= $info[0]["df_fecha_ingreso"];
$usuario->df_fecha_ingreso= $info[0]["df_fecha_ingreso"];
$usuario->df_codigo_per= $info[0]["df_codigo_per"];

// modificar usuario
$response = $usuario->updatePersonal();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>