<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/metodo_pago.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$metodoPago = new MetodoPago($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$metodoPago->id_metodo_pago= $info[0]["id_metodo_pago"];
// query de lectura
$stmt = $metodoPago->readById();
$num = $stmt->rowCount();

// metodoPago array
$metodoPago_arr=array();
$metodoPago_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $metodoPago_item=array(
            "id_metodo_pago"=>$id_metodo_pago, 
            "nombre_met_pago"=>$nombre_met_pago
        );
 
        array_push($metodoPago_arr["data"], $metodoPago_item);
    }
 
    echo json_encode($metodoPago_arr);
}
 
else{
    echo json_encode($metodoPago_arr);
}
?>