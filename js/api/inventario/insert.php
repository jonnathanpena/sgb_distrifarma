<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/inventario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$inventario = new Inventario($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$inventario->df_cant_bodega= $info[0]["df_cant_bodega"];
$inventario->df_cant_transito= $info[0]["df_cant_transito"];
$inventario->df_producto= $info[0]["df_producto"];
$inventario->df_ppp_ind= $info[0]["df_ppp_ind"];
$inventario->df_pvt_ind= $info[0]["df_pvt_ind"];
$inventario->df_ppp_total= $info[0]["df_ppp_total"];
$inventario->df_pvt_total= $info[0]["df_pvt_total"];
$inventario->df_minimo_sug= $info[0]["df_minimo_sug"];
$inventario->df_und_caja= $info[0]["df_und_caja"];
$inventario->df_bodega= $info[0]["df_bodega"];

// insert inventario
$response = $inventario->insert();
if($response != false){
    $response = $response * 1;
    echo json_encode($response); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>