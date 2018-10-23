<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cliente.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cliente = new Cliente($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$cliente->df_nombre_cli= $info[0]["df_nombre_cli"];
// query de lectura
$stmt = $cliente->readAll();
$num = $stmt->rowCount();

// cliente array
$cliente_arr=array();
$cliente_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $cliente_item=array(
            "df_id_cliente"=>$df_id_cliente,
            "df_codigo_cliente"=>$df_codigo_cliente,
            "df_nombre_cli"=>$df_nombre_cli,
            "df_razon_social_cli"=>$df_razon_social_cli,
            "df_tipo_documento_cli"=>$df_tipo_documento_cli,
            "df_documento_cli"=>$df_documento_cli,
            "df_direccion_cli"=>$df_direccion_cli,
            "df_referencia_cli"=>$df_referencia_cli,
            "df_sector_cod"=>$df_sector_cod,
            "df_email_cli"=>$df_email_cli,
            "df_telefono_cli"=>$df_telefono_cli,
            "df_celular_cli"=>$df_celular_cli
        );
 
        array_push($cliente_arr["data"], $cliente_item);
    }
 
    echo json_encode($cliente_arr);
}
 
else{
    echo json_encode($cliente_arr);
}
?>