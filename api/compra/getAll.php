<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/compra.php';
include_once '../objects/cuotasCompra.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$compra = new Compra($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$compra->df_nombre_empresa= $info[0]["df_nombre_empresa"];
// query de lectura
$stmt = $compra->readAll();
$num = $stmt->rowCount();

// compra array
$compra_arr=array();
$compra_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $pagado = 1;
        if ($condiciones_compra * 1 == 4) {
            $pagado = getCuotas($db, $id_compra);
        }
 
        $compra_item=array(
            "id_compra"=>$id_compra,
            "usuario_id"=>$usuario_id,
            "fecha_compra"=>$fecha_compra,
            "proveedor_id"=>$proveedor_id,
            "detalle_sustento_comprobante_id"=>$detalle_sustento_comprobante_id,
            "serie_compra"=>$serie_compra,
            "documento_compra"=>$documento_compra,
            "autorizacion_compra"=>$autorizacion_compra,
            "fecha_comprobante_compra"=>$fecha_comprobante_compra,
            "fecha_ingreso_bodega_compra"=>$fecha_ingreso_bodega_compra,
            "fecha_caducidad_compra"=>$fecha_caducidad_compra,
            "descripcion_compra"=>$descripcion_compra,
            "vencimiento_compra"=>$vencimiento_compra,
            "condiciones_compra"=>$condiciones_compra,
            "st_con_iva_compra"=>$st_con_iva_compra,
            "descuento_con_iva_compra"=>$descuento_con_iva_compra,
            "total_con_iva_compra"=>$total_con_iva_compra,
            "st_sin_iva_compra"=>$st_sin_iva_compra,
            "descuento_sin_iva_compra"=>$descuento_sin_iva_compra,
            "total_sin_iva_compra"=>$total_sin_iva_compra,
            "st_iva_cero_compra"=>$st_iva_cero_compra,
            "total_iva_cero"=>$total_iva_cero,
            "descuento_iva_cero_compra"=>$descuento_iva_cero_compra,
            "ice_cc_compra"=>$ice_cc_compra,
            "imp_verde_compra"=>$imp_verde_compra,
            "iva_compra"=>$iva_compra,
            "otros_compra"=>$otros_compra,
            "interes_compra"=>$interes_compra,
            "bonificacion_compra"=>$bonificacion_compra,
            "total_compra"=>$total_compra,
            "df_nombre_empresa"=>$df_nombre_empresa,
            "df_codigo_proveedor"=>$df_codigo_proveedor,
            "df_usuario_usuario"=>$df_usuario_usuario,
            "pagado"=>$pagado
        );
 
        array_push($compra_arr["data"], $compra_item);
    }
 
    echo json_encode($compra_arr);
}
 
else{
    echo json_encode($compra_arr);
}

function getCuotas($db, $compra_id) {
    $cuotasCompra = new CuotasCompra($db);
    $cuotasCompra->compra_id = $compra_id;
    $stmt = $cuotasCompra->readByCompra();
    $num = $stmt->rowCount();
    if ($num > 0) {
        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
            if ($df_estado_cc == 'PAGADO') {
                $i++;
            }
        }
        if ($i == $num) {
            return 1;
        } else {
            return 0;
        }
    } else {
        return 1;
    }
}
?>