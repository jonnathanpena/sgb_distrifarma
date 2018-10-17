<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "active";
	$active_reportes_usuarios = "";
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
                    <label for="q" class="col-md-1 control-label">Seleccione: </label>
                    <div class="col-md-2">
                        <select onchange="seleccionaTipoReporte()" id="selec-tipo-reporte" class="form-control">
							<option value="null">Seleccione...</option>
							<option value="banco">Banco</option>
						</select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default" onclick='load()' style="display: none;">
                            <span class="glyphicon glyphicon-search" ></span> Buscar</button>
                            <span id="loader"></span>
                    </div>
                </div>
				<div class="form-group row" id="falores-banco">
					<label class="col-md-1 control-label">Desde </label>
                    <div class="col-md-2">
                        <input type="date" id="desde_banco" class="form-control">
                    </div>
					<label class="col-md-1 control-label">Hasta </label>
                    <div class="col-md-2">
                        <input type="date" id="hasta_banco" class="form-control">
                    </div>
					<div class="col-md-2">
						<button class="btn btn-success" type="button" onclick="procesar()">
							<span class="glyphicon glyphicon-search"></span> Obtener Reporte
						</button>
					</div>
				</div>
            </form>
        </div>
    </div>
</div>
<hr>
<?php
include("footer.php");
?>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/reportes.js"></script>
</body>
</html>