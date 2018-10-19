<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "";
    $active_guias = "";
    $active_bodega = "active";
    $active_reportes = "";
    $active_reportes_usuarios = "";
	$title="Kardex | SGI";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include("head.php");?>
  </head>
  <body>
 	<?php
	include("navbar.php");
	?> 
    <div class="container">
		<div class="panel panel-info">
		<div class="panel-heading">
		    <div class="btn-group pull-right">
				<!--<button type='button' class="btn btn-info" onclick="nuevoProducto()">
                    <span class="glyphicon glyphicon-plus" ></span> Nuevo Producto
                </button> -->
			</div>
			<h4><i class='glyphicon glyphicon-list'></i> Kardex</h4>
		</div>	
		<div class="panel-body">
<?php
include("modal/nuevo_producto.php");
include("modal/editar_producto.php");
?>
            <form class="form-horizontal" role="form" id="datos_cotizacion">
                <div class="form-group row">
                    <label for="q" class="col-md-2 control-label">Nombre o Código</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="q" placeholder="Nombre o Código" onkeyup='load()'>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default" onclick='load()' style="display: none;">
                            <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                            <span id="loader"></span>
                    </div>
                </div>
            </form>
            <div id="resultados">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="info">
                                <th>Fecha</th>
                                <th>Cod Producto</th>
                                <th>Producto</th>
                                <th>Factura</th>
                                <th>Usuario</th>
                                <th class="text-center">Ingreso</th>
                                <th class="text-center">Egreso</th>
                                <th class='text-right'>Existencia</th>
                                <th class='text-right'>Movimiento</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="pager" style="float: right;">
                    <ul id="pagination" class="pagination-sm"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<?php
include("footer.php");
?>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/kardex.js"></script>
</body>
</html>