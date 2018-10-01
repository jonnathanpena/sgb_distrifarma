<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalle_compra_producto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalle_compra_producto = new DetalleCompraProducto($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$detalle_compra_producto->compra_id = $info[0]["compra_id"];

// query de lectura
$stmt = $detalle_compra_producto->readByCompra();
$num = $stmt->rowCount();

// detalle_compra_producto array
$detalle_compra_producto_arr=array();
$detalle_compra_producto_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $detalle_compra_producto_item=array(
            "id_dcp"=>$id_dcp, 
            "compra_id"=>$compra_id, 
            "codigo_dcp"=>$codigo_dcp, 
            "cantidad_dcp"=>$cantidad_dcp, 
            "precio_unitario_dcp"=>$precio_unitario_dcp, 
            "total_dcp"=>$total_dcp
        );
 
        array_push($detalle_compra_producto_arr["data"], $detalle_compra_producto_item);
    }
 
    
}
 
echo json_encode($detalle_compra_producto_arr);

?>