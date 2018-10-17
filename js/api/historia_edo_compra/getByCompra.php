<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/historia_edo_compra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$historia_edo = new HistoriaEdoCompra($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$historia_edo->compra_id_hist = $info[0]["compra_id_hist"];

// query de lectura
$stmt = $historia_edo->readByCompra();
$num = $stmt->rowCount();

// historia_edo array
$historia_edo_arr=array();
$historia_edo_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $historia_edo_item=array(
            "id_hist_edo_com"=>$id_hist_edo_com, 
            "compra_id_hist"=>$compra_id_hist, 
            "venta_id_hist"=>$venta_id_hist, 
            "retencion_id_hist"=>$retencion_id_hist, 
            "id_edo_entrega_hist"=>$id_edo_entrega_hist, 
            "id_edo_pago_hist"=>$id_edo_pago_hist, 
            "fecha_hist"=>$fecha_hist
        );
 
        array_push($historia_edo_arr["data"], $historia_edo_item);
    }
 
    
}
 
echo json_encode($historia_edo_arr);

?>