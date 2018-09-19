<?php
   $active_administracion = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "";
   $active_bodega = "active";
   $active_reportes = "";
   $title="Nueva Guía Recepción | SGB";  
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
               <h4><i class='glyphicon glyphicon-edit'></i> Nueva Guía de Recepción</h4>
            </div>
            <div class="panel-body">
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
                     <label for="tipo_guia" class="col-md-1 control-label">Tipo Guía</label>
                     <div class="col-md-2">
                        <select name="tipo_guia" id="tipo_guia" class="form-control" onchange="cambioTipoGuia()">
                            <option value="null">Seleccione...</option>
                            <option value="Entrega">Guía de Entrega</option>
                            <option value="Remision">Guía de Remisión</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group row" id="seleccionGuiaEntrega">
                     <label for="num_gui_ent" class="col-md-1 control-label">No. Guía</label>
                     <div class="col-md-2">
                        <select name="num_gui_ent" id="num_gui_ent" class="form-control">
                        </select>
                     </div>
                     <label for="repartidor" class="col-md-1 control-label">Repartidor</label>
                     <div class="col-md-2">
                        <select name="repartidor" id="repartidor" class="form-control" disabled>
                        </select>
                     </div>
                  </div>
                   <div class="col-md-12" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <table id="table_guias" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="300"> No. Factura</th>
                                        <th colSpan="4" class="text-center;">Acción</th>
                                        <th width="500">Nueva Fecha</th>
                                        <th width="400" colSpan="4" class="text-center;">Forma Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="300"> No. Factura</td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> 1
                                            </label>  
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> 2
                                            </label>  
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> 3
                                            </label>  
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> 4
                                            </label>  
                                        </td>
                                        <td width="500">
                                            <input type="date" class="form-control">
                                        </td>
                                        <td width="100">
                                            <label for="check">
                                                <input type="checkbox"> A
                                            </label>                                            
                                        </td>
                                        <td width="100">
                                            <label for="check">
                                                <input type="checkbox"> B
                                            </label>  
                                        </td>
                                        <td width="100">
                                            <label for="check">
                                                <input type="checkbox"> C
                                            </label>  
                                        </td>
                                        <td width="100">
                                            <label for="check">
                                                <input type="checkbox"> D
                                            </label>  
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                   </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="guia_recepcion.php"  class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success" id="btn-guardar">
                                    <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                                </button>
                            </div>
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
      <script type="text/javascript" src="js/nueva_recepcion.js"></script>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   </body>
</html>

