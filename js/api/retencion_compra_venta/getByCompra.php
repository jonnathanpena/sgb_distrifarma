<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/retencion_compra_venta.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$retencion_compra_venta = new RetencionCompraVenta($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$retencion_compra_venta->compra_id = $info[0]["compra_id"];

// query de lectura
$stmt = $retencion_compra_venta->readByCompra();
$num = $stmt->rowCount();

// retencion_compra_venta array
$retencion_compra_venta_arr=array();
$retencion_compra_venta_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        $retencion_compra_venta_item=array(
            "id_ret_com_ven"=>$id_ret_com_ven, 
            "compra_id"=>$compra_id, 
            "venta_id"=>$venta_id, 
            "serie_retencion"=>$serie_retencion, 
            "num_retencion"=>$num_retencion, 
            "autorizacion_ret"=>$autorizacion_ret, 
            "fecha_ingreso_ret"=>$fecha_ingreso_ret,
            "fecha_caduca_ret"=>$fecha_caduca_ret,
            "retencion_iva_id"=>$retencion_iva_id,
            "porcentaje_iva"=>$porcentaje_iva,
            "base_imponible"=>$base_imponible,
            "retencion_ir_id"=>$retencion_ir_id,
            "porcentaje_ir"=>$porcentaje_ir,
            "base_imponible_c_iva"=>$base_imponible_c_iva,
            "base_imponible_s_iva"=>$base_imponible_s_iva
        );
 
        array_push($retencion_compra_venta_arr["data"], $retencion_compra_venta_item);
    }
 
    
}
 
echo json_encode($retencion_compra_venta_arr);

?>