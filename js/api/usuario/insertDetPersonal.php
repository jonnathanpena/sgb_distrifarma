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
$usuario->df_sueldo_detper= $info[0]["df_sueldo_detper"];
$usuario->df_bono_detper= $info[0]["df_bono_detper"];
$usuario->df_anticipo_detper= $info[0]["df_anticipo_detper"];
$usuario->df_descuento_detper= $info[0]["df_descuento_detper"];
$usuario->df_decimos_detper= $info[0]["df_decimos_detper"];
$usuario->df_vacaciones_detper= $info[0]["df_vacaciones_detper"];
$usuario->df_tabala_comision_detper= $info[0]["df_tabala_comision_detper"];
$usuario->df_comisiones_detper= $info[0]["df_comisiones_detper"];
$usuario->df_personal_cod= $info[0]["df_personal_cod"];
$usuario->df_usuario_detper= $info[0]["df_usuario_detper"];
$usuario->df_fecha_proceso= $info[0]["df_fecha_proceso"];
              
// insert usuario
$response = $usuario->insertDetPersonal();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>