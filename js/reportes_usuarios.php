<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "";
	$active_reportes_usuarios = "active";
	$title="Reportes | SGI";
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
			<h4><i class='glyphicon glyphicon-paste'></i> Consulta de Reportes</h4>
		</div>	
		<div class="panel-body">
            <form class="form-horizontal" role="form" id="datos_cotizacion">
                <div class="form-group row">
                    <label for="q" class="col-md-2 control-label">Tipo de Guìa: </label>
                    <div class="col-md-3">
                        <select onchange="seleccionaTipoGuia()" id="selec-tipo-reporte" class="form-control">
							<option value="null">Seleccione...</option>
							<option value="entrega">Guías de Entrega</option>
							<option value="remision">Guías de Remisión</option>
						</select>
                    </div>
                    <div class="col-md-2">
						<button class="btn btn-success" type="button" onclick="procesar()">
							<span class="glyphicon glyphicon-search"></span> Consultar
						</button>
					</div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default" onclick='load()' style="display: none;">
                            <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                            <span id="loader"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="q" class="col-md-2 control-label">Código</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="q" placeholder="Código" onkeyup='procesar()'>
                    </div>
                </div>
            </form>
            <div id="resultados">
                <div class="table-responsive" id="resultados_entrega">
                    <table class="table" id="table_entrega">
                        <thead>
                            <tr class="info">
                                <th>Código</th>
                                <th>Fecha</th>
                                <th class="text-center">Cant Productos Und</th>
                                <th class="text-center">Cant Productos Caja</th>
                                <th class="text-center">Cant Facturas Entregadas</th>
                                <th class='text-right'>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="table-responsive" id="resultados_remision">
                    <table class="table" id="table_remision">
                        <thead>
                            <tr class="info">
                                <th>Código</th>
                                <th>Fecha</th>
                                <th class="text-center">Cant Total Producto</th>
                                <th class="text-center">Valor Efectivo</th>
                                <th class='text-right'>Acciones</th>
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
<script type="text/javascript" src="js/reportes_usuarios.js"></script>
</body>
</html>