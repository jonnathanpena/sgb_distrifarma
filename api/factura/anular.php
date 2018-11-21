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
$mensaje = '';

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$data['df_edo_factura_fac']= 5;
$factura->df_edo_factura_fac= $data["df_edo_factura_fac"];
$factura->df_num_factura= $data["df_num_factura"];
if (!$factura->estado()) {
    $procesado = false;
    $mensaje = '¡Error al modificar estado Anulado!';
} else {
    $mensaje = '¡Factura anulada exitosamente!';
    if (modificarBanco($db, $data["df_num_factura"], $data["df_valor_total_fac"], $data["usuario"])) {
        for ($i = 0; $i < count($data['productos']); $i++) {
            if (!consultarInventarioProducto($db, $data["productos"][$i], $data["usuario"], $data["df_num_factura"])) {
                $procesado = false;
                $mensaje = '¡Error al intentar reponer inventario!';
            }
        }
    } else {
        $procesado = false;
        $mensaje = '¡Error al insertar banco Factura Anulada!';
    }
}

echo json_encode(array('proceso' => $procesado, 'mensaje' => $mensaje)); 

function modificarBanco($db, $factura, $monto, $usuario) {
    include_once '../objects/banco.php';
    $banco = new Banco($db);
    $stmt = $banco->read();
    $num = $stmt->rowCount();
    $saldo = 0;
    $banco_arr = array();
    $insercion = true;
    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $banco_item = array(
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
        $saldo = $banco_arr[0]["df_saldo_banco"] * 1;
        if(!insertarBanco($db, $factura, $saldo, $monto, $usuario)){
            $insercion = false;
        }
    } else {
        if(!insertarBanco($db, $factura, $saldo, $monto, $usuario)) {
            $insercion = false;
        }
    }
    return $insercion;
}

function insertarBanco($db, $factura, $saldo, $monto, $usuario) {
    include_once '../objects/banco.php';
    $banco = new Banco($db);
    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');
    $suma = ($saldo * 1) +($monto * 1);
    $banco->df_fecha_banco= $fecha;
    $banco->df_usuario_id_banco= $usuario;
    $banco->df_tipo_movimiento= 'Egreso';
    $banco->df_monto_banco= $monto;
    $banco->df_saldo_banco= $suma;
    $banco->df_num_documento_banco= 'Anulada #'.$factura;
    $banco->df_detalle_mov_banco= 'Factura Anulada';
    if (!$banco->insert()) {
        return false;
    } else {
        if (!cajaChica($db, $factura, $monto, $usuario, $saldo)) {
            return false;
        } else {
            return true;
        }
    }
}

function cajaChica($db, $factura, $monto, $usuario, $banco) {
    include_once '../objects/cajaChicaGasto.php';
    $caja = new CajaChicaGasto($db);
    $stmt = $caja->readMes();
    $num = $stmt->rowCount();
    $saldo = 0;
    $caja_arr = array();
    $insercion = true;
    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $caja_item = array(
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
            array_push($caja_arr, $caja_item);
        }
        $saldo = $caja_arr[0]["df_saldo"] * 1;
        if(!insertarLibroDiario($db, $factura, $saldo, $monto, $usuario, $banco)){
            $insercion = false;
        }
    } else {
        if(!insertarLibroDiario($db, $factura, $saldo, $monto, $usuario, $banco)) {
            $insercion = false;
        }
    }
    return $insercion;
}

function insertarLibroDiario($db, $factura, $saldo, $monto, $usuario, $banco) {
    include_once '../objects/libroDiario.php';
    $libroDiario = new LibroDiario($db);
    date_default_timezone_set('America/Bogota');
    $fecha = date('Y-m-d H:i:s');
    $suma = ($saldo * 1) +($banco * 1);
    $libroDiario->df_fuente_ld= 'Banco';
    $libroDiario->df_valor_inicial_ld= $suma;
    $libroDiario->df_fecha_ld= $fecha;
    $libroDiario->df_descipcion_ld= 'Factura Anulada #'.$factura;
    $libroDiario->df_ingreso_ld= 0;
    $libroDiario->df_egreso_ld=$monto;
    $libroDiario->df_usuario_id_ld= $usuario;
    if (!$libroDiario->insert()) {
        return false;
    } else {
        return true;
    }
}

function consultarInventarioProducto($db, $producto, $usuario, $factura_id) {
    include_once '../objects/inventario.php';
    $inventario = new Inventario($db);
    $inventario->df_producto = $producto['df_id_producto'];
    $stmt = $inventario->readByIdProd();
    $inventario_arr = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
    $cantidad = $producto['df_cantidad_detfac'] * 1;
    if ($producto['df_nombre_und_detfac'] == 'CAJA') {
        $cantidad = ($producto['df_cantidad_detfac'] * 1) * ($inventario_arr[0]['df_und_caja'] * 1);
    }
    $inventario_arr[0]["df_cant_bodega"] = ($inventario_arr[0]["df_cant_bodega"] * 1) + $cantidad;
    if(updateInventario($db, $inventario_arr[0], $cantidad, $usuario, $producto['df_nombre_producto'], $factura_id)) {
        return true;
    } else {
        return false;
    }
}

function updateInventario($db, $info, $cantidad, $usuario, $nombre_producto, $factura_id) {
    include_once '../objects/inventario.php';
    $inventario = new Inventario($db);
    $inventario->df_id_inventario= $info["df_id_inventario"];
    $inventario->df_cant_bodega= $info["df_cant_bodega"];
    $inventario->df_cant_transito= $info["df_cant_transito"];
    $inventario->df_producto= $info["df_producto"];
    $inventario->df_ppp_ind= $info["df_ppp_ind"];
    $inventario->df_pvt_ind= $info["df_pvt_ind"];
    $inventario->df_ppp_total= $info["df_ppp_total"];
    $inventario->df_pvt_total= $info["df_pvt_total"];
    $inventario->df_minimo_sug= $info["df_minimo_sug"];
    $inventario->df_und_caja= $info["df_und_caja"];
    $inventario->df_bodega= $info["df_bodega"];
    if(!$inventario->update()) {
        return false;
    } else {        
        date_default_timezone_set('America/Bogota');
        $kardex = array(
            "df_fecha_kar"=>date('Y-m-d H:i:s'),
            "df_producto_cod_kar"=>$info["df_producto"],
            "df_producto"=>$nombre_producto,
            "df_factura_kar"=>$factura_id,
            "df_ingresa_kar"=>$cantidad,
            "df_existencia_kar"=>$info["df_cant_bodega"],
            "df_creadoBy_kar"=>$usuario,
            "df_kardex_codigo"=>''
        );
        //return true;
        if(paraKardex($db, $kardex)) {
            return true;
        } else {
            return false;
        }
    }
}

function paraKardex($db, $info) {
    include_once '../objects/kardex.php';
    $kardex = new Kardex($db);
    $stmt = $kardex->readIdMax();
    $num = $stmt->rowCount();
    $max = '';
    $kardex_arr = array();
    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $kardex_item=array(
                "df_kardex_id"=>$df_kardex_id * 1
            );
            array_push($kardex_arr, $kardex_item);
        }
        $id = ($kardex_arr[0]['df_kardex_id'] * 1) + 1;
        if ($id > 0 && $id < 10) {
            $max = 'KAR-00'.$id;
        } else if ($id > 9 && $id < 100) {
            $max = 'KAR-0'.$id;
        } else if ($id > 99) {
            $max = 'KAR-'.$id;
        }
    } else {
        $max = 'KAR-001';
    }
    if(insertKardex($db, $info, $max)) {
        return true;
    } else {
        return false;
    }
}

function insertKardex($db, $info, $codigo) {
    include_once '../objects/kardex.php';
    $kardex = new Kardex($db);
    $kardex->df_kardex_codigo= $codigo;
    $kardex->df_fecha_kar= $info["df_fecha_kar"];
    $kardex->df_producto_cod_kar= $info["df_producto_cod_kar"];
    $kardex->df_producto= $info["df_producto"];
    $kardex->df_factura_kar= $info["df_factura_kar"];
    $kardex->df_ingresa_kar= $info["df_ingresa_kar"];
    $kardex->df_egresa_kar=0;
    $kardex->df_existencia_kar= $info["df_existencia_kar"];
    $kardex->df_creadoBy_kar= $info["df_creadoBy_kar"];
    $kardex->df_edo_kardex= 3;
    if(!$kardex->insert()) {
        return false;
    } else {
        return true;
    }
}

?>