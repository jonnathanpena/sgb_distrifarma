<?php
   $active_administracion = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "";
   $active_bodega = "active";
   $active_reportes = "";
   $title="Nueva Guía Remisión | SGB";  
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
               <h4><i class='glyphicon glyphicon-edit'></i> Nueva Guía de Remisión</h4>
            </div>
            <div class="panel-body">
<?php
include("modal/consultar_productos.php");
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
                     <label for="personal" class="col-md-1 control-label">Vendedor</label>
                     <div class="col-md-2">
                        <select name="personal" id="personal" class="form-control"></select>
                     </div>
                     <label for="sector" class="col-md-1 control-label">Zona</label>
                     <div class="col-md-2">
                        <select name="sector" id="sector" class="form-control">
                        </select>
                     </div>
                  </div>
                  <div class="form-group row">
                    <label for="cantidad" class="col-md-2 control-label">Cantidad de Productos</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="cantidad" name="cantidad" value="0" readonly>
                     </div>   
                     <label for="valor" class="col-md-2 control-label">Valor Efectivo</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="valor" name="valor" min="0.01" max="1000000" step="0.01" value="0.00" required>
                     </div>                                                       
                   </div>                     
                   <div class="col-md-12" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-success add-new-producto" onclick="buscarProductos()">
                                            <i class="fa fa-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table id="table_productos" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="100">Código</th>
                                        <th>Producto</th>
                                        <th width="100">Unidad</th>
                                        <th width="100">Cantidad</th>
                                        <th width="100">P.Unitario $</th>
                                        <th width="100">Total $</th>
                                        <th width="100">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="guia_remision.php"  class="btn btn-danger">
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
      <script type="text/javascript" src="js/nueva_remision.js"></script>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   </body>
</html>

