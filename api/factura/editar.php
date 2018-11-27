<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/factura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$factura = new Factura($db);

$procesado = true;

// get posted data
$data = json_decode(file_get_contents('php://input'), true);
$factura_anterior = $data['factura_anterior'];
$factura_actual = $data['factura_actual'];
$productos_viejos = $data['productos_viejos'];
$productos_nuevos = $data['productos_nuevos'];
$productos_eliminados = $data['productos_eliminados'];

$factura->df_cliente_cod_fac = $factura_actual['df_cliente_cod_fac'];
$factura->df_personal_cod_fac = $factura_actual['df_personal_cod_fac'];
$factura->df_sector_cod_fac = $factura_actual['df_sector_cod_fac'];
$factura->df_forma_pago_fac = $factura_actual['df_forma_pago_fac'];
$factura->df_subtotal_fac = $factura_actual['df_subtotal_fac'];
$factura->df_descuento_fac = $factura_actual['df_descuento_fac'];
$factura->df_valor_total_fac = $factura_actual['df_valor_total_fac'];
$factura->df_edo_factura_fac = $factura_actual['df_edo_factura_fac'];
$factura->df_fecha_entrega_fac = $factura_actual['df_fecha_entrega_fac'];
$factura->df_iva_fac = $factura_actual['df_iva_fac'];
$factura->df_num_factura = $factura_actual['df_num_factura'];

//editar factura
$response = $factura->update();
$mensaje = '';

if($response == false){
    $procesado = false;
    $mensaje = "Error modificar factura";
}

if ($factura_anterior['df_valor_total_fac'] > $factura_actual['df_valor_total_fac']) {
    $total_anterior = $factura_anterior['df_valor_total_fac'] * 1;
    $total_actual = $factura_actual['df_valor_total_fac'] * 1;
    $monto = $total_anterior - $total_actual;
    if (!restarBancos($db, $monto, $factura_actual)) {
        $procesado = false;
        $mensaje = "Problema al restar en bancos/libro diario";
    }
} else if ($factura_anterior['df_valor_total_fac'] < $factura_actual['df_valor_total_fac']){
    $total_anterior = $factura_anterior['df_valor_total_fac'] * 1;
    $total_actual = $factura_actual['df_valor_total_fac'] * 1;
    $monto = $total_anterior + $total_actual;
    if (!agregarBancos($db, $monto, $factura_actual)) {
        $procesado = false;
        $mensaje = "Problema al agregar en bancos/libro diario";
    }
}

for ($i = 0; $i < count($productos_nuevos); $i++) {
    $similitud = false;
    $producto_nuevo = $productos_nuevos[$i];
    for ($j = 0; $j < count($productos_viejos); $j++) {
        $producto_viejo = $productos_viejos[$j];
        if ($producto_nuevo['df_cantidad_detfac'] == $producto_viejo['df_cantidad_detfac']
            && $producto_nuevo['df_prod_precio_detfac'] == $producto_viejo['df_id_producto']
            && $producto_nuevo['nombre_producto'] == $producto_viejo['df_nombre_producto']
            && $producto_nuevo['df_nombre_und_detfac'] == $producto_viejo['df_nombre_und_detfac']) {
                $similitud = true;
        }
    }
    if ($similitud == false) {
        if (!agregarDetalleProducto($db, $producto_nuevo, $factura_actual['df_num_factura'], $factura_actual['df_creadaBy'])) {
            $procesado = false;
            $mensaje = "Problema en insertar nuevo detalle factura";
        }
    }
}

for ($i = 0; $i < count($productos_eliminados); $i++) {
    if (!eliminarDetalleFactura($db, $productos_eliminados[$i], $factura_actual['df_num_factura'], $factura_actual['df_creadaBy'])) {
        $procesado = false;
        $mensaje = "Problemas al eliminar producto";
    }
}

echo json_encode(array('proceso' => $procesado, 'mensaje' => $mensaje)); 

function consultarBancos($db) {
    include_once '../objects/banco.php';
    $banco = new Banco($db);
    $stmt = $banco->read();
    $num = $stmt->rowCount();
    $banco_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row); 
            $banco_item=array(
                "df_id_banco"=>$df_id_banco,
                "df_fecha_banco"=>$df_fecha_banco,
                "df_usuario_id_banco"=>$df_usuario_id_banco,
                "df_tipo_movimiento"=>$df_tipo_movimiento,
                "df_monto_banco"=>$df_monto_banco,
                "df_saldo_banco"=>$df_saldo_banco,
                "df_num_documento_banco"=>$df_num_documento_banco,
                "df_detalle_mov_banco"=>$df_detalle_mov_banco,
                "df_modificadoBy_banco"=>$df_modificadoBy_banco
            );     
            array_push($banco_arr, $banco_item);
        }
        return $banco_arr[0];
    } else {
        return 0;
    }
}

function restarBancos($db, $monto, $factura_actual) {
    include_once '../objects/banco.php';
    $banco = new Banco($db);
    $getBanco = consultarBancos($db);
    $saldo_anterior = 0;
    if ($getBanco != 0 && (count($getBanco) > 0)) {
        $saldo_anterior = $getBanco['df_saldo_banco'] * 1;
    }
    $saldo = $saldo_anterior - ($monto * 1);
    $banco->df_fecha_banco = $factura_actual['df_fecha_fac'];
    $banco->df_usuario_id_banco = $factura_actual['df_creadaBy'];
    $banco->df_tipo_movimiento = 'Egreso';
    $banco->df_monto_banco = $monto;
    $banco->df_saldo_banco = $saldo;
    $banco->df_num_documento_banco = "Factura #" . $factura_actual['df_num_factura'];
    $banco->df_detalle_mov_banco = "Modificación factura";

    $getCajaChica = consultarCajaChica($db);
    $saldo_caja_chica = 0;
    if ($getCajaChica != 0 && (count($getCajaChica) > 0)) {
        $saldo_caja_chica = $getCajaChica['df_saldo'] * 1;
    }
    $valor_inicial = $saldo_caja_chica + $saldo_anterior;
    if (insertarLibroDiario($db, $valor_inicial, $factura_actual, "Egreso", $monto)) {
        $insercion = $banco->insert();
        if ($insercion != false) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function agregarBancos($db, $monto, $factura_actual) {
    include_once '../objects/banco.php';
    $banco = new Banco($db);
    $getBanco = consultarBancos($db);
    $saldo_anterior = 0;
    if ($getBanco != 0 && (count($getBanco) > 0)) {
        $saldo_anterior = $getBanco['df_saldo_banco'] * 1;
    }
    $saldo = $saldo_anterior + ($monto * 1);
    $banco->df_fecha_banco = $factura_actual['df_fecha_fac'];
    $banco->df_usuario_id_banco = $factura_actual['df_creadaBy'];
    $banco->df_tipo_movimiento = 'Ingreso';
    $banco->df_monto_banco = $monto;
    $banco->df_saldo_banco = $saldo;
    $banco->df_num_documento_banco = "Factura #" . $factura_actual['df_num_factura'];
    $banco->df_detalle_mov_banco = "Modificación factura";

    $getCajaChica = consultarCajaChica($db);
    $saldo_caja_chica = 0;
    if ($getCajaChica != 0 && (count($getCajaChica) > 0)) {
        $saldo_caja_chica = $getCajaChica['df_saldo'] * 1;
    }
    $valor_inicial = $saldo_caja_chica + $saldo_anterior;
    if (insertarLibroDiario($db, $valor_inicial, $factura_actual, "Ingreso", $monto)) {
        $insercion = $banco->insert();
        if ($insercion != false) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function consultarCajaChica($db) {
    include_once '../objects/cajaChicaGasto.php';
    $cajaChicaGasto = new CajaChicaGasto($db);
    $stmt = $cajaChicaGasto->readMes();
    $num = $stmt->rowCount();
    $cajaChicaGasto_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $cajaChicaGasto_item=array(
                "df_id_gasto"=>$df_id_gasto, 
                "df_usuario_id"=>$df_usuario_id,
                "df_usuario_usuario"=>$df_usuario_usuario,
                "df_movimiento"=>$df_movimiento,
                "df_gasto"=>$df_gasto,
                "df_saldo"=>$df_saldo,
                "df_fecha_gasto"=>$df_fecha_gasto,
                "df_num_documento"=>$df_num_documento,
                "tipo"=>$tipo
            );     
            array_push($cajaChicaGasto_arr, $cajaChicaGasto_item);
        }
        return $cajaChicaGasto_arr[0];
    } else {
        return 0;
    }
}

function insertarLibroDiario($db, $valor_inicial, $factura_actual, $tipo, $monto) {
    include_once '../objects/libroDiario.php';
    $libroDiario = new LibroDiario($db);
    $libroDiario->df_fuente_ld= "Banco";
    $libroDiario->df_valor_inicial_ld= $valor_inicial;
    $libroDiario->df_fecha_ld= $factura_actual['df_fecha_fac'];
    $libroDiario->df_descipcion_ld= "Modificación factura #".$factura_actual['df_num_factura'];
    if ($tipo == 'Ingreso') {
        $libroDiario->df_ingreso_ld= $monto;
        $libroDiario->df_egreso_ld= 0;
    } else if ($tipo == 'Egreso') {
        $libroDiario->df_ingreso_ld= 0;
        $libroDiario->df_egreso_ld= $monto;
    }
    $libroDiario->df_usuario_id_ld= $factura_actual['df_creadaBy'];
    $response = $libroDiario->insert();
    if($response != false){
        return true;
    }else{
        return false;
    }
}

function agregarDetalleProducto($db, $producto_nuevo, $factura, $usuario) {
    include_once '../objects/detalleFactura.php';
    $detalleFactura = new detalleFactura($db);
    $detalleFactura->df_num_factura_detfac= $producto_nuevo["df_num_factura_detfac"];
    $detalleFactura->df_prod_precio_detfac= $producto_nuevo["df_prod_precio_detfac"];
    $detalleFactura->df_precio_prod_detfac= $producto_nuevo["df_precio_prod_detfac"];
    $detalleFactura->df_cantidad_detfac= $producto_nuevo["df_cantidad_detfac"];
    $detalleFactura->df_nombre_und_detfac= $producto_nuevo["df_nombre_und_detfac"];
    $detalleFactura->df_cant_x_und_detfac= $producto_nuevo["df_cant_x_und_detfac"];
    $detalleFactura->df_edo_entrega_prod_detfac= 0;
    $detalleFactura->df_valor_sin_iva_detfac= $producto_nuevo["df_valor_sin_iva_detfac"];
    $detalleFactura->df_iva_detfac= $producto_nuevo["df_iva_detfac"];
    $detalleFactura->df_valor_total_detfac= $producto_nuevo["df_valor_total_detfac"];

    if (restarEnInventario($db, $producto_nuevo, $factura, $usuario)) {
        // insert detalleFactura
        $response = $detalleFactura->insert();
        if ($response != false){
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function consultarInventario($db, $producto_nuevo) {
    include_once '../objects/inventario.php';
    $inventario = new Inventario($db);
    $inventario->df_producto= $producto_nuevo["df_prod_precio_detfac"];
    $stmt = $inventario->readByIdProd();
    $num = $stmt->rowCount();
    $inventario_arr = array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $inventario_item=array(
                "df_id_inventario"=>$df_id_inventario, 
                "df_cant_bodega"=>$df_cant_bodega,
                "df_cant_transito"=>$df_cant_transito,
                "df_producto"=>$df_producto,
                "df_ppp_ind"=>$df_ppp_ind,
                "df_pvt_ind"=>$df_pvt_ind,
                "df_ppp_total"=>$df_ppp_total,
                "df_pvt_total"=>$df_pvt_total,
                "df_minimo_sug"=>$df_minimo_sug,
                "df_und_caja"=>$df_und_caja * 1,
                "df_bodega"=>$df_bodega
            );
            array_push($inventario_arr, $inventario_item);
        }
    }
    return $inventario_arr[0];
}

function restarEnInventario($db, $producto_nuevo, $factura, $usuario) {
    include_once '../objects/inventario.php';
    $inventario = new Inventario($db);
    $getInventario = consultarInventario($db, $producto_nuevo);
    $cantidad = $producto_nuevo['df_cantidad_detfac'] * 1;
    if ($producto_nuevo['df_nombre_und_detfac'] == "CAJA") {
        $cantidad = $cantidad * $getInventario['df_und_caja'];
    }
    $getInventario['df_cant_bodega'] = ($getInventario['df_cant_bodega'] * 1) - $cantidad;

    $inventario->df_id_inventario= $getInventario["df_id_inventario"];
    $inventario->df_cant_bodega= $getInventario["df_cant_bodega"];
    $inventario->df_cant_transito= $getInventario["df_cant_transito"];
    $inventario->df_producto= $getInventario["df_producto"];
    $inventario->df_ppp_ind= $getInventario["df_ppp_ind"];
    $inventario->df_pvt_ind= $getInventario["df_pvt_ind"];
    $inventario->df_ppp_total= $getInventario["df_ppp_total"];
    $inventario->df_pvt_total= $getInventario["df_pvt_total"];
    $inventario->df_minimo_sug= $getInventario["df_minimo_sug"];
    $inventario->df_und_caja= $getInventario["df_und_caja"];
    $inventario->df_bodega= $getInventario["df_bodega"];

    if (insertKardex($db, $producto_nuevo, $factura, "Egresa", $getInventario["df_cant_bodega"], $usuario)) {
        $response = $inventario->update();
        if ($response == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getCodigoKardex($db) {
    include_once '../objects/kardex.php';
    $kardex = new Kardex($db);
    $stmt = $kardex->readIdMax();
    $num = $stmt->rowCount();
    $kardex_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $kardex_item=array(
                "df_kardex_id"=>$df_kardex_id * 1
            );
            array_push($kardex_arr, $kardex_item);
        }
        return (($kardex_arr[0]['df_kardex_id'] * 1) + 1);
    } else {
        return 1;
    }
}

function eliminarDetalleFactura($db, $producto, $factura, $usuario) {
    include_once '../objects/detalleFactura.php';
    $detalleFactura = new DetalleFactura($db);
    $detalleFactura->df_id_factura_detfac = $producto['df_id_producto'];
    $response = $detalleFactura->delete();

    if (reponerInventario($db, $producto, $factura, $usuario)) {
        if($response == true){
            return true; 
        }else{
            return false;
        }
    } else {
        return false;
    }
}

function reponerInventario($db, $producto_nuevo, $factura, $usuario) {
    include_once '../objects/inventario.php';
    $inventario = new Inventario($db);
    $getInventario = consultarInventario($db, $producto_nuevo);
    $cantidad = $producto_nuevo['df_cantidad_detfac'] * 1;
    if ($producto_nuevo['df_nombre_und_detfac'] == "CAJA") {
        $cantidad = $cantidad * $getInventario['df_und_caja'];
    }
    $getInventario['df_cant_bodega'] = ($getInventario['df_cant_bodega'] * 1) + $cantidad;

    $inventario->df_id_inventario= $getInventario["df_id_inventario"];
    $inventario->df_cant_bodega= $getInventario["df_cant_bodega"];
    $inventario->df_cant_transito= $getInventario["df_cant_transito"];
    $inventario->df_producto= $getInventario["df_producto"];
    $inventario->df_ppp_ind= $getInventario["df_ppp_ind"];
    $inventario->df_pvt_ind= $getInventario["df_pvt_ind"];
    $inventario->df_ppp_total= $getInventario["df_ppp_total"];
    $inventario->df_pvt_total= $getInventario["df_pvt_total"];
    $inventario->df_minimo_sug= $getInventario["df_minimo_sug"];
    $inventario->df_und_caja= $getInventario["df_und_caja"];
    $inventario->df_bodega= $getInventario["df_bodega"];

    if (insertKardex($db, $producto_nuevo, $factura, "Ingresa", $getInventario["df_cant_bodega"], $usuario)) {
        $response = $inventario->update();
        if ($response == true) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function insertKardex($db, $producto_nuevo, $factura, $tipo, $cantidad_bodega, $usuario) {
    include_once '../objects/kardex.php';
    $kardex = new Kardex($db);

    $codigo = getCodigoKardex($db);
    if ($codigo > 0 && $codigo < 10) {
        $codigo = 'KAR-00' . $codigo;
    } else if ($codigo > 9 && $codigo < 100) {
        $codigo = 'KAR-0' . $codigo;
    } else if ($codigo > 99) {
        $codigo = 'KAR-' . $codigo;
    }

    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');

    $kardex->df_kardex_codigo= $codigo;
    $kardex->df_fecha_kar= $fecha;
    $kardex->df_producto_cod_kar= $producto_nuevo["df_prod_precio_detfac"];
    $kardex->df_factura_kar= $factura;
    if ($tipo == 'Ingresa') {
        $kardex->df_producto= $producto_nuevo["df_nombre_producto"];
        $kardex->df_ingresa_kar= $producto_nuevo["df_cantidad_detfac"];
        $kardex->df_egresa_kar= 0;
        $kardex->df_edo_kardex= 3;
    } else {
        $kardex->df_producto= $producto_nuevo["nombre_producto"];
        $kardex->df_ingresa_kar= 0;
        $kardex->df_egresa_kar= $producto_nuevo["df_cantidad_detfac"];
        $kardex->df_edo_kardex= 1;
    }
    $kardex->df_existencia_kar= $cantidad_bodega;
    $kardex->df_creadoBy_kar= $usuario;

    // insert kardex
    $response = $kardex->insert();
    if ($response != false) {
        return true;
    } else {
        return false;
    }
}

?>