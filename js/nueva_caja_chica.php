<?php
    $active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "active";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "";
	$title="Nueva Caja Chica | SGI";
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include("head.php");?>
   </head>
   <body>
<?php
    include("navbar.php");
?>      <div class="container">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4><i class='glyphicon glyphicon-shopping-cart'></i> Nueva Compra Caja Chica</h4>
                </div>
                <div class="panel-body">
<?php 
    include("modal/agregar_producto.php");
?>
                    <form class="form-horizontal" role="form" id="datos_factura" method="get" action="./ajax/registrar_caja_chica.php">
                        <div class="form-group row">
                            <label for="id_usuario" class="col-md-1 control-label">Usuario</label>
                            <div class="col-md-2">
                                <select class="form-control input-sm" id="id_usuario" name="id_usuario" readonly>
                                    <option value="1">Usuario</option>
                                </select>
                            </div>
                            <label for="tel2" class="col-md-1 control-label">Fecha</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("d/m/Y");?>" readonly>
                                <input type="hidden" id="total" name="total">
                            </div>
                            <div class="col-md-3">
                                <select class='form-control input-sm' id="condiciones" name="condiciones"  style="visibility:hidden">
                                    <option value="1">Efectivo</option>
                                    <option value="2">Cheque</option>
                                    <option value="3">Transferencia bancaria</option>
                                    <option value="4">Crédito</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modAddProducto" id="addproducto">
                                    <span class="glyphicon glyphicon-plus"></span> Agregar ítem
                                </button>
                                <button type="submit" class="btn btn-success" id="btn-comprar">
                                    <span class="glyphicon glyphicon-shopping-cart"></span> Comprar
                                </button>
                                <button type="button" class="btn btn-default" onclick="javascript:window.location.reload();">
                                    <span class="glyphicon glyphicon-erase"></span> Limpiar Pantalla
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="resultados" class='col-md-12' style="margin-top:10px"></div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="col-md-12">
                </div>
            </div>
        </div>
        <hr>
<?php
    include("footer.php");
?>
    <script type="text/javascript" src="js/config.js"></script>
    <script type="text/javascript" src="js/nueva_caja_chica.js"></script>
    </body>
</html>



