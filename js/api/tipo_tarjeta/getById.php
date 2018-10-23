<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/tipo_tarjeta.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$tipoTarjeta = new TipoTarjeta($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$tipoTarjeta->id_tipo_tarjeta= $info[0]["id_tipo_tarjeta"];
// query de lectura
$stmt = $tipoTarjeta->readById();
$num = $stmt->rowCount();

// tipoTarjeta array
$tipoTarjeta_arr=array();
$tipoTarjeta_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $tipoTarjeta_item=array(
            "id_tipo_tarjeta"=>$id_tipo_tarjeta, 
            "nombre_tipo_tarjeta"=>$nombre_tipo_tarjeta
        );
 
        array_push($tipoTarjeta_arr["data"], $tipoTarjeta_item);
    }
 
    echo json_encode($tipoTarjeta_arr);
}
 
else{
    echo json_encode($tipoTarjeta_arr);
}
?>