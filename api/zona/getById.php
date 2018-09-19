<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/zona.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$zona = new Zona($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$zona->df_codigo_zona= $info[0]["df_codigo_zona"];
// query de lectura
$stmt = $zona->readById();
$num = $stmt->rowCount();

// zona array
$zona_arr=array();
$zona_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $zona_item=array(
            "df_codigo_zona"=>$df_codigo_zona, 
            "df_nombre_zona"=>$df_nombre_zona
        );
 
        array_push($zona_arr["data"], $zona_item);
    }
 
    echo json_encode($zona_arr);
}
 
else{
    echo json_encode($zona_arr);
}
?>