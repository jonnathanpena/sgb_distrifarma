<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/edo_compra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$edoCompra = new EdoCompra($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$edoCompra->id_edo_compra= $info[0]["id_edo_compra"];
// query de lectura
$stmt = $edoCompra->readById();
$num = $stmt->rowCount();

// edoCompra array
$edoCompra_arr=array();
$edoCompra_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $edoCompra_item=array(
            "id_edo_compra"=>$id_edo_compra, 
            "nombre_edo_com"=>$nombre_edo_com
        );
 
        array_push($edoCompra_arr["data"], $edoCompra_item);
    }
 
    echo json_encode($edoCompra_arr);
}
 
else{
    echo json_encode($edoCompra_arr);
}
?>