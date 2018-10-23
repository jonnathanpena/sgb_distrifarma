<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cajaChicaGasto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cajaChicaGasto = new CajaChicaGasto($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$cajaChicaGasto->df_usuario_id= $info[0]["df_usuario_id"];
$cajaChicaGasto->df_movimiento= $info[0]["df_movimiento"];
$cajaChicaGasto->df_gasto= $info[0]["df_gasto"];
$cajaChicaGasto->df_saldo= $info[0]["df_saldo"];
$cajaChicaGasto->df_fecha_gasto= $info[0]["df_fecha_gasto"];
$cajaChicaGasto->df_num_documento= $info[0]["df_num_documento"];
$cajaChicaGasto->df_ingreso_id= $info[0]["df_ingreso_id"];

// insert cajaChicaGasto
$response = $cajaChicaGasto->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>