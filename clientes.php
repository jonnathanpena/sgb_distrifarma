<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "active";
    $active_egresos = "";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "";
    $active_reportes_usuarios = "";
    $title="Clientes | SGB";
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
					<button type='button' class="btn btn-info" data-toggle="modal" data-target="#nuevoCliente" onclick="nuevoCliente()">
						<span class="glyphicon glyphicon-plus" ></span> Nuevo Cliente
					</button>
				</div>
				<h4><i class='glyphicon glyphicon-search'></i> Buscar Clientes</h4>
			</div>
			<div class="panel-body">
<?php
	    include("modal/nuevo_cliente.php");
	    include("modal/editar_cliente.php");
?>

				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="form-group row">
						<label for="q" class="col-md-2 control-label">Nombre o Documento:</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="q" placeholder="Nombre o Documento" onkeyup='load();'>
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
									<th>Código</th>
									<th>Tipo Documento</th>
									<th>Documento</th>
									<th>Nombre</th>
									<th>Razón Social</th>
									<th>Dirección</th>
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
	<script type="text/javascript" src="js/clientes.js"></script>
	</body>
</html>