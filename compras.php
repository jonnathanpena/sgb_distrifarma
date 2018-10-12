<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "active";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "";
    $active_reportes_usuarios = "";
	$title="Compras | SGI";
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
                    <a class="btn btn-info" href="nueva_compra.php">
                        <span class="glyphicon glyphicon-plus" ></span> Nueva Compra
                    </a>                
                </div>
                <h4><i class='glyphicon glyphicon-shopping-cart'></i>Compras</h4>
            </div>
            <div class="panel-body">
<?php
    include("modal/cuotas_compras.php");
?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="form-group row">
						<label for="q" class="col-md-2 control-label">No. Compra:</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="q" placeholder="Proveedor o Usuario" onkeyup='load();'>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-default" onclick='load();' style="display: none;">
								<span class="glyphicon glyphicon-search" ></span> Buscar
							</button>
							<span id="loader"></span>
						</div>
					</div>
				</form>
                <div id="resultados">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="info">
                                    <th>No.</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th class="text-center">Consumo ($)</th>
                                    <th class='text-right'>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div id="pager" style="float: right;">
                        <ul id="pagination" class="pagination-sm"></ul>
                    </div>
                </div><!-- Carga los datos ajax -->
            </div>
        </div>
    </div>
    <hr>
<?php
    include("footer.php");
?>
    <script type="text/javascript" src="js/config.js"></script>
    <script type="text/javascript" src="js/compras.js"></script>
    </body>
</html>

