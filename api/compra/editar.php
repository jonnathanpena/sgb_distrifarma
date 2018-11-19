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

$procesado = true;

// get posted data
$data = json_decode(file_get_contents('php://input'), true);
$compra_anterior = $data['compra_anterior'];
$compra_actual = $data['compra_actual'];
$pago = $data['pago'];

$compra->fecha_compra = $compra_actual['fecha_compra'];
$compra->proveedor_id = $compra_actual['proveedor_id'] ;
$compra->detalle_sustento_comprobante_id = $compra_actual['detalle_sustento_comprobante_id'] ;
$compra->serie_compra = $compra_actual['serie_compra'] ;
$compra->documento_compra = $compra_actual['documento_compra'] ;
$compra->autorizacion_compra = $compra_actual['autorizacion_compra'] ;
$compra->fecha_comprobante_compra = $compra_actual['fecha_comprobante_compra'] ;
$compra->fecha_ingreso_bodega_compra = $compra_actual['fecha_ingreso_bodega_compra'] ;
$compra->fecha_caducidad_compra = $compra_actual['fecha_caducidad_compra'] ;
$compra->vencimiento_compra = $compra_actual['vencimiento_compra'] ;
$compra->descripcion_compra = $compra_actual['descripcion_compra'] ;
$compra->condiciones_compra = $compra_actual['condiciones_compra'] ;
$compra->st_con_iva_compra = $compra_actual['st_con_iva_compra'] ;
$compra->descuento_con_iva_compra = $compra_actual['descuento_con_iva_compra'] ;
$compra->total_con_iva_compra = $compra_actual['total_con_iva_compra'] ;
$compra->st_sin_iva_compra = $compra_actual['st_sin_iva_compra'];
$compra->descuento_sin_iva_compra = $compra_actual['descuento_sin_iva_compra'];
$compra->total_sin_iva_compra = $compra_actual['total_sin_iva_compra'];
$compra->st_iva_cero_compra = $compra_actual['st_iva_cero_compra'];
$compra->descuento_iva_cero_compra = $compra_actual['descuento_iva_cero_compra'];
$compra->total_iva_cero = $compra_actual['total_iva_cero'];
$compra->ice_cc_compra = $compra_actual['ice_cc_compra'];
$compra->imp_verde_compra = $compra_actual['imp_verde_compra'];
$compra->otros_compra = $compra_actual['otros_compra'];
$compra->interes_compra = $compra_actual['interes_compra'];
$compra->total_compra = $compra_actual['total_compra'];
$compra->id_compra = $compra_anterior['id_compra'];

//editar compra
$response = $compra->update();

if($response == false){
    $procesado = false;
}

$mensaje = '';

if ($compra_anterior['condiciones_compra'] != $compra_actual['condiciones_compra']) {
    if ($compra_anterior['condiciones_compra'] == 4) {
        $mensaje = 'No puede cambiar de crédito a otros métodos de pago';
    } else {
        if ($compra_actual['condiciones_compra'] == 4) {
            if (insertarCuotas($db, $data['cuotas'], $compra_anterior['id_compra'])) {
                $mensaje = 'Método de pago cambiado exitosamente';
            } else {
                $procesado = false;
                $mensaje = "Cuotas incorrectas, intente nuevamente por favor";
            }
        } else {
            if (eliminarDetallePago($db, $compra_anterior['detalles_pago']['id_dpc'])) {
                if(insertarPago($db, $pago)) {
                    $mensaje = 'Método de pago cambiado exitosamente';
                } else {
                    $procesado = false;
                    $mensaje = 'No se pudo cambiar el método de pago';
                }
            } else {
                $mensaje = 'No se pudo eliminar la forma de pago';
            }
        }
    }
}

echo json_encode(array('proceso' => $procesado, 'mensaje' => $mensaje)); 

function eliminarDetallePago($db, $id) {
    include_once '../objects/detalle_pago_compra.php';
    $detalle_pago = new DetallePagoCompra($db);
    $detalle_pago->id_dpc = $id;
    return $detalle_pago->delete();
}

function insertarPago($db, $pago) {
    include_once '../objects/detalle_pago_compra.php';
    $detalle_pago_compra = new DetallePagoCompra($db);
    $detalle_pago_compra->compra_id = $pago['compra_id'];
    $detalle_pago_compra->metodo_pago_id = $pago['metodo_pago_id'];
    $detalle_pago_compra->banco_emisor = $pago['banco_emisor'];
    $detalle_pago_compra->banco_receptor = $pago['banco_receptor'];
    $detalle_pago_compra->codigo = $pago['codigo'];
    $detalle_pago_compra->fecha = $pago['fecha'];
    $detalle_pago_compra->tipo_tarjeta = $pago['tipo_tarjeta'];
    $detalle_pago_compra->franquicia = $pago['franquicia'];
    $detalle_pago_compra->recibo = $pago['recibo'];
    $detalle_pago_compra->titular = $pago['titular'];
    $detalle_pago_compra->cheque = $pago['cheque'];
    return $detalle_pago_compra->insert();
}

function insertarCuotas($db, $cuotas, $compra_id) {
    include_once '../objects/cuotasCompra.php';
    $agrego = true;
    for ($i = 0; $i < count($cuotas); $i++) {
        $cuota = new CuotasCompra($db);
        $cuota->compra_id = $compra_id;
        $cuota->df_fecha_cc = $cuotas[$i]['fecha'];
        $cuota->df_monto_cc = $cuotas[$i]['cuota'];
        $cuota->descripcion = $cuotas[$i]['descripcion'];
        $cuota->descuento = 0;
        $cuota->df_estado_cc = 'PENDIENTE';
        if(!$cuota->insert()) {
            $agrego = false;
        }
    }
    return $agrego;
}

?>