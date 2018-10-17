<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
// escaping, additionally removing everything that could be (html/javascript-) code
$id = mysqli_real_escape_string($con,(strip_tags($_REQUEST['id'], ENT_QUOTES)));
$detalle = mysqli_real_escape_string($con,(strip_tags($_REQUEST['detalle'], ENT_QUOTES)));

if ($detalle == 1 && !isset($_GET['monto'])) {

    $sql = "SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
                fac.`id_vendedor`, fac.`condiciones`, fac.`total_venta`, fac.`estado_factura`, cxc.id_cxc, cxc.estado_cxc
                FROM `facturas` as fac 
                JOIN `cxc` as cxc ON (fac.`id_factura` = cxc.factura_id)
                WHERE fac.`condiciones` = 4 
                AND fac.`id_cliente` =".$id;

    $query   = mysqli_query($con, $sql);
    $numrows= mysqli_num_rows($query);
    //loop through fetched data
    if ($numrows>0){

        ?>



        <div class="table-responsive">



        <table class="table">



            <tr  class="info">



                <th>Fecha</th>
                <th>Nº Factura</th>
                <th>Deuda</th>
                <th>Estado</th>

            </tr>



            <?php



            while ($row=mysqli_fetch_array($query)){


                    $fecha=date("d/m/Y", strtotime($row['fecha_factura']));
                    $factura = $row['numero_factura'];
                    $deuda = number_format ($row['total_venta'],2);
                    $estado = $row['estado_cxc'];
                    if ($estado==0) {
                        $estado="Pendiente Pago";$label_class='label-danger';
                    } else if ($estado == 1) {
                        $estado="Abonado";$label_class='label-warning';
                    } else if ($estado == 2) {
                        $estado="Pagado";$label_class='label-success';
                    }

                ?>


                <tr>


                    <td><?php echo $fecha; ?></td>
                    <td><?php echo $factura; ?></td>
                    <td>$<?php echo $deuda;?></td>
                    <td><span class="label <?php echo $label_class;?>"><?php echo $estado; ?></span></td>              



                </tr>



                <?php



            }



            ?>


        </table>



        </div>



        <?php



    }
} else if ($detalle == 0 && !isset($_GET['monto'])) {
    $total = restar($con, $id);
    echo $total;
} else if ($detalle == 0 && isset($_GET['monto'])) {
    $sql = "SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, fac.`id_vendedor`, 
                fac.`condiciones`, fac.`total_venta`, fac.`estado_factura`, cxc.`id_cxc`, cxc.`estado_cxc`
                FROM `facturas` as fac
                JOIN `cxc` as cxc ON (fac.`id_factura` = cxc.`factura_id`)
                WHERE fac.`condiciones` = 4 AND fac.`id_cliente` = ".$id." 
                AND fac.`estado_factura` = 1
                AND (cxc.`estado_cxc` = 0 OR cxc.estado_cxc = 1)";
    $query = mysqli_query($con, $sql);
    $deudas = [];
    while($row=mysqli_fetch_array($query)) {
        $deuda_item = array(
            "id_cxc" => $row['id_cxc'],
            "total_venta" => $row['total_venta'],
            "estado_cxc" => $row['estado_cxc']
        );
        array_push($deudas,$deuda_item);
    }
    $monto = $_GET['monto'];
    $result = pagar($con, $deudas, $monto);
    echo $result;
}

function pagar($conexion, $deudas_arr, $monto) {
    $paga = $monto;
    $usuario = $_GET['usuario'];
    for ($i = 0; $i < count($deudas_arr); $i++) {
        if ($paga > 0) {
            if ($deudas_arr[$i]['estado_cxc'] == 0) {
                if ($paga > $deudas_arr[$i]['total_venta']) {            
                    abonar($conexion, $deudas_arr[$i]['total_venta'], 2, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = $paga - $deudas_arr[$i]['total_venta'];
                } else if ($paga < $deudas_arr[$i]['total_venta']) {
                    abonar($conexion, $paga, 1, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = 0;
                } else if ($paga == $deudas_arr[$i]['total_venta']) {
                    abonar($conexion, $paga, 2, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = 0;
                }
            } else if ($deudas_arr[$i]['estado_cxc'] == 1) {
                $deuda = $deudas_arr[$i]['total_venta'] - consultarAbonos($conexion, $deudas_arr[$i]['id_cxc']);
                if ($paga > $deuda) {            
                    abonar($conexion, $deuda, 2, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = $paga - $deuda;
                } else if ($paga < $deuda) {
                    abonar($conexion, $paga, 1, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = 0;
                } else if ($paga == $deuda) {
                    abonar($conexion, $paga, 2, $deudas_arr[$i]['id_cxc'], $usuario);
                    $paga = 0;
                }
            }
        }
    }
    return true;
}

function abonar($conexion, $abono, $estado, $cxc_id, $usuario) {
    $fecha = date('Y-m-d H:i:s');
    $sqlAbonar = "INSERT INTO `abonos`(`cxc_id`, `fecha_abonos`, `monto_abonos`, `usuario_id`) VALUES (
            ".$cxc_id.",
            '".$fecha."',
            ".$abono.",
            ".$usuario."
        )";
    mysqli_query($conexion, $sqlAbonar);
    $sqlUpdateCXC = "UPDATE `cxc` SET 
                        `estado_cxc`= ".$estado." 
                        WHERE `id_cxc` = ".$cxc_id;
    mysqli_query($conexion, $sqlUpdateCXC);
}

function restar($conexion, $cliente) {
    $deudaTotal = 0;
    $sqlFacturasPendientePago = "SELECT cxc.factura_id, fac.`total_venta`
                                FROM `cxc` as cxc
                                JOIN `facturas` as fac ON (cxc.factura_id = fac.`id_factura`)
                                WHERE cxc.estado_cxc = 0 
                                AND `id_cliente` = ".$cliente;
    $query = mysqli_query($conexion, $sqlFacturasPendientePago);
    while ($row = mysqli_fetch_array($query)) {
        $deudaTotal = $deudaTotal + $row['total_venta'];
    }
    $sqlFacturasAbonadas = "SELECT cxc.factura_id, fac.`total_venta`, cxc.`id_cxc`
                                FROM `cxc` as cxc
                                JOIN `facturas` as fac ON (cxc.factura_id = fac.`id_factura`)
                                WHERE cxc.estado_cxc = 1
                                AND `id_cliente` = ".$cliente;
    $query = mysqli_query($conexion, $sqlFacturasAbonadas);
    $deudaAbonada = 0;
    while($row = mysqli_fetch_array($query)) {
        $deudaAbonada = $deudaAbonada + ($row['total_venta'] - descontar($conexion, $row['id_cxc']));
    }
    return $deudaTotal + $deudaAbonada;    
}

function descontar($conexion, $cuenta_cobrar) {
    $sql = "SELECT SUM(`monto_abonos`) as abonos FROM `abonos` WHERE `cxc_id` = ".$cuenta_cobrar;
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['abonos'];
}

function consultarAbonos($conexion, $id_cxc) {
    $sql = "SELECT SUM(`monto_abonos`) AS abonos 
                FROM `abonos` WHERE `cxc_id` = ".$id_cxc;
    $query = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_array($query);
    return $row['abonos'];
}

?>