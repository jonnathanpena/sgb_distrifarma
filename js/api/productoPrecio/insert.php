<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/productoPrecio.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$productoPrecio = new ProductoPrecio($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$productoPrecio->df_producto_id= $info[0]["df_producto_id"];
$productoPrecio->df_ppp= $info[0]["df_ppp"];
$productoPrecio->df_pvt1= $info[0]["df_pvt1"];
$productoPrecio->df_pvt2= $info[0]["df_pvt2"];
$productoPrecio->df_pvp= $info[0]["df_pvp"];
$productoPrecio->df_iva= $info[0]["df_iva"];
$productoPrecio->df_min_sugerido= $info[0]["df_min_sugerido"];
$productoPrecio->df_und_caja= $info[0]["df_und_caja"];
$productoPrecio->df_utilidad= $info[0]["df_utilidad"];

// insert productoPrecio
$response = $productoPrecio->insert();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false);
}
?>