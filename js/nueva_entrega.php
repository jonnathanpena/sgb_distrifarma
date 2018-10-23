<?php
   $active_administracion = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "active";
   $active_bodega = "";
   $active_reportes = "";
   $active_reportes_usuarios = "";
   $title="Nueva Guía Entrega | SGI";  
   $fecha = Date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <?php include("head.php");?>
      <link rel="stylesheet" href="./css/nueva_compra.css">
   </head>
   <body>
      <?php
         include("navbar.php");
         ?>  
      <div class="container">
         <div class="panel panel-info">
            <div class="panel-heading">
               <h4><i class='glyphicon glyphicon-edit'></i> Nueva Guía de Entrega</h4>
            </div>
            <div class="panel-body">
<?php
include("modal/consultar_productos_entrega.php");
include("modal/load.php");
?>
               <form class="form-horizontal" role="form" id="form_nueva_guia">
                  <div class="form-group row">
                     <label for="usuario" class="col-md-1 control-label">Usuario</label>
                     <div class="col-md-2">
                        <select name="usuario" id="usuario" class="form-control" readonly>
                        </select>
                     </div>
                     <label for="fecha" class="col-md-1 control-label">Fecha</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="fecha" name="fecha" value="<?php echo $fecha; ?>" readonly>
                     </div>
                     <label for="personal" class="col-md-1 control-label">Repartidor</label>
                     <div class="col-md-2">
                        <select name="personal" id="personal" class="form-control"></select>
                     </div>
                     <label for="fecha_entrega" class="col-md-1 control-label">Entrega</label>
                     <div class="col-md-2">
                        <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" onchange="cambioFechaEntrega()" required>
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="cantidad" class="col-md-2 control-label">Cantidad Unidades</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="cantidad" name="cantidad" value="0" readonly>
                     </div>  
                     <label for="cantidad" class="col-md-2 control-label">Cantidad Cajas</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="cantidad_cajas" name="cantidad_cajas" value="0" readonly>
                     </div>  
                     <label for="facturas" class="col-md-2 control-label">Cantidad de Facturas</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="facturas" name="facturas" min="1" max="1000000" step="1" value="0" readonly>
                     </div>                                                       
                   </div> 
                   <div class="col-md-4" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <table id="table_sectores" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="20"></th>
                                        <th>Sector</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                   </div>   
                   <div class="col-md-8" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="table-wrapper">
                                <table id="table_facturas" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="40"></th>
                                            <th>Nº Factura</th>
                                            <th width="80">Subtotal</th>
                                            <th width="60">IVA</th>
                                            <th width="60">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div> 
                        <div class="col-md-12" style="margin-top: 20px;">
                            <div class="table-wrapper">
                                <table id="table_productos" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Producto</th>
                                            <th>Unidad</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                   </div>
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="guia_entrega.php"  class="btn btn-danger">
                                <span class="glyphicon glyphicon-remove"></span> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success" id="btn-guardar">
                                <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                            </button>
                        </div>
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
      <script type="text/javascript" src="js/nueva_entrega.js"></script>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   </body>
</html>

