<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cuotasCompra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cuotasCompra = new CuotasCompra($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$cuotasCompra->compra_id = $info[0]["compra_id"];

// query de lectura
$stmt = $cuotasCompra->readByCompra();
$num = $stmt->rowCount();

// cuotasCompra array
$cuotasCompra_arr=array();
$cuotasCompra_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $cuotasCompra_item=array(
            "df_id_cc"=>$df_id_cc, 
            "compra_id"=>$compra_id, 
            "df_fecha_cc"=>$df_fecha_cc, 
            "df_monto_cc"=>$df_monto_cc, 
            "df_estado_cc"=>$df_estado_cc
        ); 
        array_push($cuotasCompra_arr["data"], $cuotasCompra_item);
    } 
    
}
 
echo json_encode($cuotasCompra_arr);

?>