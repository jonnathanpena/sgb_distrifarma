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
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$compra = new Compra($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$compra->id_compra= $info[0]["id_compra"];
// query de lectura
$stmt = $compra->readById();
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
        $proveedor = getProveedor($proveedor_id, $db);
        $sustento = getDetalleSustentoTributario($detalle_sustento_comprobante_id, $db);
        $detalles_productos = getDetalle($id_compra, $db);
        $detalles_pago = getDetallePago($id_compra, $db);
 
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
            "pagado"=>$pagado,
            "proveedor"=>$proveedor,
            "sustento"=>$sustento,
            "productos"=>$detalles_productos,
            "detalles_pago"=>$detalles_pago
        );
 
        array_push($compra_arr["data"], $compra_item);
    }
}

echo json_encode($compra_arr);

function getCuotas($db, $compra_id) {
    include_once '../objects/cuotasCompra.php';
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

function getProveedor($proveedor_id, $db) {
    include_once '../objects/proveedor.php';
    $proveedor = new Proveedor($db);
    $proveedor->df_id_proveedor = $proveedor_id;
    $stmt = $proveedor->readByIdProveedor();
    $num = $stmt->rowCount();
    $proveedor_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $proveedor_item=array(
                "df_id_proveedor"=>$df_id_proveedor,
                "df_codigo_proveedor"=>$df_codigo_proveedor,
                "df_nombre_empresa"=>$df_nombre_empresa,
                "df_tlf_empresa"=>$df_tlf_empresa,
                "df_direccion_empresa"=>$df_direccion_empresa,
                "df_nombre_contacto"=>$df_nombre_contacto,
                "df_tlf_contacto"=>$df_tlf_contacto,
                "df_documento_prov"=>$df_documento_prov
            );
     
            array_push($proveedor_arr, $proveedor_item);
        }
    }
    return $proveedor_arr[0];
}

function getDetalleSustentoTributario($id_detalle, $db) {
    include_once '../objects/sustento_tributario.php';
    $sustento = new SustentoTributario($db);
    $sustento->id_dsc = $id_detalle;
    $stmt = $sustento->readDetalleSustentoTributario();
    $num = $stmt->rowCount();
    $sustento_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $sustento_item=array(
                "id_dsc"=>$id_dsc,
                "sustento_id"=>$sustento_id,
                "comprobante_id"=>$comprobante_id,
                "nombre_sustento"=>$nombre_sustento,
                "nombre_tipocomprobante"=>$nombre_tipocomprobante
            );
     
            array_push($sustento_arr, $sustento_item);
        }
    }
    return $sustento_arr[0];
}

function getDetalle($id_compra, $db) {
    include_once '../objects/detalle_compra_producto.php';
    $detalles_producto = new DetalleCompraProducto($db);
    $detalles_producto->compra_id = $id_compra;
    $stmt = $detalles_producto->readByCompraPrint();
    $num = $stmt->rowCount();
    $detalle_arr=array();
    if($num>0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $porcentaje_descuento = $descuento_dcp/($cantidad_dcp*$precio_unitario_dcp);
            $detalle_item=array(
                "id_dcp"=>$id_dcp,
                "compra_id"=>$compra_id,
                "producto_id"=>$producto_id,
                "bonificacion_dcp"=>$bonificacion_dcp,
                "cantidad_dcp"=>$cantidad_dcp,
                "precio_unitario_dcp"=>$precio_unitario_dcp,
                "descuento_dcp"=>$descuento_dcp,
                "porcentaje_descuento"=>number_format($porcentaje_descuento, 2),
                "iva_dcp"=>$iva_dcp,
                "subtotal_dcp"=>$subtotal_dcp,
                "df_nombre_producto"=>$df_nombre_producto,
                "df_codigo_prod"=>$df_codigo_prod
            );
     
            array_push($detalle_arr, $detalle_item);
        }
     
        return $detalle_arr;
    }
}

function getDetallePago($id_compra, $db) {
    include_once '../objects/detalle_pago_compra.php';
    $detalle_pago_compra = new DetallePagoCompra($db);
    $detalle_pago_compra->compra_id = $id_compra;
    $stmt = $detalle_pago_compra->readByCompra();
    $num = $stmt->rowCount();
    $detalle_arr=array();
    if($num>0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            if ($metodo_pago_id == 3) {
                $detalle_item=array(
                    "id_dpc"=>$id_dpc,
                    "compra_id"=>$compra_id,
                    "metodo_pago_id"=>$metodo_pago_id,
                    "banco_emisor"=>$banco_emisor,
                    "banco_receptor"=>$banco_receptor,
                    "codigo"=>$codigo,
                    "fecha"=>$fecha,
                    "tipo_tarjeta"=>$tipo_tarjeta,
                    "franquicia"=>$franquicia,
                    "recibo"=>$recibo,
                    "titular"=>$titular,
                    "cheque"=>$cheque,
                    "tipo_pago"=>"Efectivo"
                );
            } else if ($metodo_pago_id == 1) {
                $banco = getBanco($banco_emisor, $db);
                $detalle_item=array(
                    "id_dpc"=>$id_dpc,
                    "compra_id"=>$compra_id,
                    "metodo_pago_id"=>$metodo_pago_id,
                    "banco_emisor"=>$banco['nombre_bancos'],
                    "banco_emisor_id"=>$banco['id_bancos'],
                    "banco_receptor"=>$banco_receptor,
                    "codigo"=>$codigo,
                    "fecha"=>$fecha,
                    "tipo_tarjeta"=>$tipo_tarjeta,
                    "franquicia"=>$franquicia,
                    "recibo"=>$recibo,
                    "titular"=>$titular,
                    "cheque"=>$cheque,
                    "tipo_pago"=>"Cheque"
                );
            } else if ($metodo_pago_id == 2) {
                $banco = getBanco($banco_emisor, $db);
                $otroBanco = getBanco($banco_receptor, $db);
                $detalle_item=array(
                    "id_dpc"=>$id_dpc,
                    "compra_id"=>$compra_id,
                    "metodo_pago_id"=>$metodo_pago_id,
                    "banco_emisor"=>$banco['nombre_bancos'],
                    "banco_emisor_id"=>$banco['id_bancos'],
                    "banco_receptor"=>$otroBanco['nombre_bancos'],
                    "banco_receptor_id"=>$otroBanco['id_bancos'],
                    "codigo"=>$codigo,
                    "fecha"=>$fecha,
                    "tipo_tarjeta"=>$tipo_tarjeta,
                    "franquicia"=>$franquicia,
                    "recibo"=>$recibo,
                    "titular"=>$titular,
                    "cheque"=>$cheque,
                    "tipo_pago"=>"Transferencia bancaria"
                );
            } else if ($metodo_pago_id == 4) {
                $detalle_item = getListCuotas($id_compra, $db);
            }
     
            array_push($detalle_arr, $detalle_item);
        }
     
        return $detalle_arr[0];
    }
}

function getBanco($id, $db) {
    include_once '../objects/bancos.php';
    $bancos = new Bancos($db);
    $bancos->id_bancos = $id;
    $stmt = $bancos->readById();
    $banco_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $banco_item=array(
            "nombre_bancos"=>$nombre_bancos,
            "id_bancos"=>$id_bancos
        );
 
        array_push($banco_arr, $banco_item);
    }
 
    return $banco_arr[0];
}

function getTipoTarjeta($id, $db) {
    include_once '../objects/tipo_tarjeta.php';
    $tipo_tarjeta = new TipoTarjeta($db);
    $tipo_tarjeta->id_tipo_tarjeta = $id;
    $stmt = $tipo_tarjeta->readById();
    $tt_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $tt_item = array(
            "nombre_tipo_tarjeta"=>$nombre_tipo_tarjeta
        );
        array_push($tt_arr, $tt_item);
    }
    return $tt_arr[0];
}

function getFranquicia($id, $db) {
    include_once '../objects/franquicia.php';
    $franquicia = new Franquicia($db);
    $franquicia->id_franquicia = $id;
    $stmt = $franquicia->readById();
    $franquicia_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $franquicia_item = array(
            "nombre_franq"=>$nombre_franq
        );
        array_push($franquicia_arr, $franquicia_item);
    }
    return $franquicia_arr[0];
}

function getListCuotas($id_compra, $db) {
    include_once '../objects/cuotasCompra.php';
    $cuotasCompra = new CuotasCompra($db);
    $cuotasCompra->compra_id = $id_compra;
    $stmt = $cuotasCompra->readByCompra();
    $cuotas_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $cuotas_item = array(
            "df_fecha_cc"=>$df_fecha_cc,
            "df_monto_cc"=>$df_monto_cc,
            "df_estado_cc"=>$df_estado_cc
        );
        array_push($cuotas_arr, $cuotas_item);
    }
    return $cuotas_arr;
}

?>