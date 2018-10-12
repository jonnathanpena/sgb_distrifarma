<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "";
    $active_guias = "active";
    $active_bodega = "";
    $active_reportes = "";
    $active_reportes_usuarios = "";
	$title="Guía Recepción | SGI";
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
				<a href="nueva_recepcion.php"  class="btn btn-info">
                    <span class="glyphicon glyphicon-plus" ></span> Nueva Guía
                </a>
			</div>
			<h4><i class='glyphicon glyphicon-search'></i> Buscar Guía Recepción</h4>
		</div>	
		<div class="panel-body">
            <form class="form-horizontal" role="form" id="datos_remision">
                <div class="form-group row">
                    <label for="q" class="col-md-2 control-label">Código</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="q" placeholder="Código" onkeyup='load()'>
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
                                <th>Código</th>
                                <th>Fecha</th>
                                <th>Repartidor</th>
                                <th class="text-center">Valor Recaudad($)</th>
                                <th class="text-center">Valor Efectivo($)</th>
                                <th class="text-center">Valor Cheque($)</th>
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
<script type="text/javascript" src="js/guia_recepcion.js"></script>
</body>
</html>