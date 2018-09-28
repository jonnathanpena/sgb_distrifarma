<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalle_compra_gasto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalle_compra_gasto = new DetalleCompraGasto($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$detalle_compra_gasto->compra_id = $info[0]["compra_id"];

// query de lectura
$stmt = $detalle_compra_gasto->readByCompra();
$num = $stmt->rowCount();

// detalle_compra_gasto array
$detalle_compra_gasto_arr=array();
$detalle_compra_gasto_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $detalle_compra_gasto_item=array(
            "id_dcg"=>$id_dcg, 
            "compra_id"=>$compra_id, 
            "cuenta_dcg"=>$cuenta_dcg, 
            "subtotal_civa_dcg"=>$subtotal_civa_dcg, 
            "subtotal_siva_dcg"=>$subtotal_siva_dcg, 
            "subtotal_iva_cero_dcg"=>$subtotal_iva_cero_dcg, 
            "total_dcg"=>$total_dcg
        );
 
        array_push($detalle_compra_gasto_arr["data"], $detalle_compra_gasto_item);
    }
 
    
}
 
echo json_encode($detalle_compra_gasto_arr);

?>