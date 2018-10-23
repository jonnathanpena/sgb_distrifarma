<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/sustento_tributario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$sustento_tributario = new SustentoTributario($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$sustento_tributario->sustento_id = $info[0]["sustento_id"];

// query de lectura
$stmt = $sustento_tributario->readTiposComprobantesBySustento();
$num = $stmt->rowCount();

// sustento_tributario array
$sustento_tributario_arr=array();
$sustento_tributario_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $sustento_tributario_item=array(
            "id_dsco" => $id_dsc,
            "sustento_id" => $sustento_id,
            "id_sustento" => $id_sustento,
            "nombre_sustento" => $nombre_sustento,
            "comprobante_id" => $comprobante_id,
            "nombre_tipocomprobante" => $nombre_tipocomprobante
        );
 
        array_push($sustento_tributario_arr["data"], $sustento_tributario_item);
    }
 
    
}
 
echo json_encode($sustento_tributario_arr);

?>