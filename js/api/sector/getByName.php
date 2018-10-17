<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/sector.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$sector = new Sector($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$sector->df_nombre_sector= $info[0]["df_nombre_sector"];
// query de lectura
$stmt = $sector->readByName();
$num = $stmt->rowCount();

// sector array
$sector_arr=array();
$sector_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $sector_item=array(
            "df_codigo_sector"=>$df_codigo_sector, 
            "df_nombre_sector"=>$df_nombre_sector
        );
 
        array_push($sector_arr["data"], $sector_item);
    }
 
    echo json_encode($sector_arr);
}
 
else{
    echo json_encode($sector_arr);
}
?>