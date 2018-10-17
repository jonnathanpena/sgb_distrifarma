<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/productoDevueltoRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$productoDevueltoRecepcion = new productoDevueltoRecepcion($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$productoDevueltoRecepcion->df_guia_rec= $info[0]["df_guia_rec"];
$productoDevueltoRecepcion->df_cant_und_rec= $info[0]["df_cant_und_rec"];
$productoDevueltoRecepcion->df_producto_id_rec= $info[0]["df_producto_id_rec"];
$productoDevueltoRecepcion->df_id_prod_dev_rec= $info[0]["df_id_prod_dev_rec"];

// insert productoDevueltoRecepcion
$response = $productoDevueltoRecepcion->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}

?>