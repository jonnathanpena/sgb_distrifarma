<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/proveedor.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$proveedor = new Proveedor($db);
 
// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$proveedor->df_codigo_proveedor= $info[0]["df_codigo_proveedor"];
$proveedor->df_nombre_empresa= $info[0]["df_nombre_empresa"];
$proveedor->df_tlf_empresa= $info[0]["df_tlf_empresa"];
$proveedor->df_direccion_empresa= $info[0]["df_direccion_empresa"];
$proveedor->df_nombre_contacto= $info[0]["df_nombre_contacto"];
$proveedor->df_tlf_contacto= $info[0]["df_tlf_contacto"];
$proveedor->df_documento_prov= $info[0]["df_documento_prov"];
$proveedor->df_id_proveedor= $info[0]["df_id_proveedor"];

// modificar proveedor
$response = $proveedor->update();
if($response == true){
    echo json_encode(true); 
}else{
    // Error en caso de que no se pueda modificar
    echo json_encode(false); 
}
?>