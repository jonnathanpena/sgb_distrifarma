<?php
	$active_administracion = "";
    $active_facturas = "";
    $active_ingresos = "";
    $active_egresos = "active";
    $active_guias = "";
    $active_bodega = "";
    $active_reportes = "";
    $active_reportes_usuarios = "";
	$title="Caja Chica | SGB";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
    <link rel="stylesheet" href="libraries/autocomplete/easy-autocomplete.min.css"> 
    <link rel="stylesheet" href="libraries/autocomplete/easy-autocomplete.themes.min.css">
  </head>
  <body>
<?php
	include("navbar.php");
?>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="btn-group pull-right">
                    <button type='button' class="btn btn-default" onclick="nuevoGasto()">
                        <span class="glyphicon glyphicon-minus" ></span> 
                        Nuevo Gasto
                    </button>
                    <button type='button' class="btn btn-info" onclick="nuevoIngreso()">
                        <span class="glyphicon glyphicon-plus" ></span> 
                        Nuevo Ingreso
                    </button>                    
                </div>
                <h4><i class='glyphicon glyphicon-shopping-cart'></i> Caja Chica</h4>
            </div>
            <div class="panel-body">

<?php
	include("modal/nuevo_ingreso.php");
	include("modal/nuevo_egreso.php");
	include("modal/editar_caja_chica.php");
?>

                <form class="form-horizontal" role="form" style="margin-bottom: 25px;">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-default" onclick='load();' style="display: none;">
                                <span class="glyphicon glyphicon-search" ></span> Buscar
                            </button>
                            <span id="loader"></span>
                        </div>
					</div>
                    <div class="for-group row">
                        <div class="col-md-2 pull-right"> 
                            <input type="text" id="saldo_caja" name="saldo_caja" class="form-control" readonly>        
                            <input type="hidden" id="saldo_banco" >
                            <input type="hidden" id="valor_libro" >
                        </div>
                        <div class="col-md-3 pull-right">
                            <label class="pull-right">
                                Saldo Caja Chica
                            </label>
                        </div>
                    </div>
                </form>
                <div id="resultados">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="info">
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Detalle</th>
                                    <th class="text-center">Ingreso ($)</th>
                                    <th class="text-center">Egreso ($)</th>
                                    <th class="text-center">Saldo ($)</th>
                                    <!--<th class='text-right'>Acciones</th>-->
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
    <script src="libraries/autocomplete/jquery.easy-autocomplete.min.js"></script>
    <script type="text/javascript" src="js/config.js"></script>
    <script type="text/javascript" src="js/caja_chica.js"></script>
    </body>
</html>

