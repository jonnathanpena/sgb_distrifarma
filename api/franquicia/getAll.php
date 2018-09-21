<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/franquicia.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$franquicia = new Franquicia($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$franquicia->nombre_franq= $info[0]["nombre_franq"];
// query de lectura
$stmt = $franquicia->readAll();
$num = $stmt->rowCount();

// franquicia array
$franquicia_arr=array();
$franquicia_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $franquicia_item=array(
            "id_franquicia"=>$id_franquicia, 
            "nombre_franq"=>$nombre_franq
        );
 
        array_push($franquicia_arr["data"], $franquicia_item);
    }
 
    echo json_encode($franquicia_arr);
}
 
else{
    echo json_encode($franquicia_arr);
}
?>