<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cajaChicaIngreso.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cajaChicaIngreso = new CajaChicaIngreso($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$cajaChicaIngreso->df_fecha_ingreso= $info[0]["df_fecha_ingreso"];
$cajaChicaIngreso->df_usuario_id_ingreso= $info[0]["df_usuario_id_ingreso"];
$cajaChicaIngreso->df_num_cheque= $info[0]["df_num_cheque"];
$cajaChicaIngreso->df_valor_cheque= $info[0]["df_valor_cheque"];
$cajaChicaIngreso->df_saldo_cc= $info[0]["df_saldo_cc"];

// insert cajaChicaIngreso
$response = $cajaChicaIngreso->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>