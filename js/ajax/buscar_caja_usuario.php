<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
include 'pagination.php'; //include pagination file
//pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
$per_page = 10; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;
$usuario = $_GET['usuario'];
//Count the total number of row in your table*/
$query   = mysqli_query($con, "SELECT `id_acc`, `fecha_apertura`, `monto_apertura`, `fecha_cierre`, `monto_cierre`, `estado_acc` 
            FROM `apertura_cierre_caja` WHERE `usuario_id` = ".$usuario." ORDER BY `estado_acc` ASC");
$numrows = mysqli_num_rows($query);
$total_pages = ceil($numrows/$per_page);
$reload = './caja.php';
//loop through fetched data
if ($numrows>0){
?>
<div class="table-responsive">
    <table class="table">
        <tr  class="info">
            <th>Fecha Apertura</th>
            <th>Monto Apertura</th>
            <th>Fecha Cierre</th>
            <th>Monto Cierre</th>
            <th>Estado</th>
            <th class='text-right'>Acciones</th>
        </tr>
<?php
    while ($row=mysqli_fetch_array($query)){
        $id_acc=$row['id_acc'];
        $fecha_apertura=date("d/m/Y", strtotime($row['fecha_apertura']));
        $monto_apertura=number_format($row['monto_apertura'],2);
        $fecha_cierre=date("d/m/Y", strtotime($row['fecha_cierre']));
        $monto_cierre=number_format($row['monto_cierre'],2);
        $estado = $row['estado_acc'];
?>
        <tr>
            <td><?php echo $fecha_apertura; ?></td>
            <td>$<?php echo $monto_apertura; ?></td>
            <td><?php echo $fecha_cierre; ?> </td>
            <td>$<?php echo $monto_cierre; ?></td>

<?php

        if ($estado == 0) {

?>

            <td>
                <span class="label label-danger">
                    Abierta
                </span>
            </td>
<?php

        } else if ($estado == 1) {

?>

            <td>
                <span class="label label-warning">
                    Cerrada
                </span>
            </td>

<?php

        } else if ($estado == 2) {

?>

            <td>
                <span class="label label-success">
                    Depositada
                </span>
            </td>

<?php

        }

?>

            <td >

<?php

            if ($estado == 0) {

?>

                <span class="pull-right">
                    <a href="#" class='btn btn-warning' title='Cerrar Caja' onclick="cerrar_caja('<?php echo $id_acc; ?>')" data-toggle="modal" data-target="#cierreCaja">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                    </a>   
                </span>

<?php

            } else if ($estado == 1) {

?>

                <span class="pull-right">
                    <a href="#" class='btn btn-success' title='Registrar Depósito' onclick="depositar_caja('<?php echo $id_acc; ?>')" data-toggle="modal" data-target="#depositarCaja">
                        <i class="glyphicon glyphicon-usd"></i>
                    </a>   
                </span>

<?php

            }

?>
            </td>
        </tr>
<?php
}
?>
        <tr>
            <td colspan=6>
                <span class="pull-right">
            
<?php
    echo paginate($reload, $page, $total_pages, $adjacents);
?>
                </span>
            </td>
        </tr>
    </table>
</div>

<?php
}
?>