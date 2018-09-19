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
$proveedor->df_documento_prov= $info[0]["df_documento_prov"];
// query de lectura
$stmt = $proveedor->readByRUC();
$num = $stmt->rowCount();

// proveedor array
$proveedor_arr=array();
$proveedor_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $proveedor_item=array(
            "df_id_proveedor"=>$df_id_proveedor,
            "df_codigo_proveedor"=>$df_codigo_proveedor, 
            "df_nombre_empresa"=>$df_nombre_empresa,
            "df_tlf_empresa"=>$df_tlf_empresa,
            "df_direccion_empresa"=>$df_direccion_empresa,
            "df_nombre_contacto"=>$df_nombre_contacto,
            "df_tlf_contacto"=>$df_tlf_contacto,
            "df_documento_prov"=>$df_documento_prov
        );
 
        array_push($proveedor_arr["data"], $proveedor_item);
    }
 
    echo json_encode($proveedor_arr);
}
 
else{
    echo json_encode($proveedor_arr);
}
?>