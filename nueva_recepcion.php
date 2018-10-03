<?php
   $active_administracion = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "active";
   $active_bodega = "";
   $active_reportes = "";
   $active_reportes_usuarios = "";
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
                     <label class="col-md-1 control-label num_guia_ent">No. Guía</label>
                     <div class="col-md-2 num_guia_ent">
                        <select name="num_guia_entrega" id="num_guia_entrega" class="form-control" onchange="cambioNumGuiaEntrega()">
                        </select>
                     </div>
                     <label class="col-md-1 control-label num_guia_rem">No. Guía</label>
                     <div class="col-md-2 num_guia_rem">
                        <select name="num_guia_remision" id="num_guia_remision" class="form-control" onchange="cambioNumGuiaRemision()">
                        </select>
                     </div>
                  </div>
                  <div class="form-group row" id="seleccionGuiaRemision">
                    <div class="form-group row">
                        <label for="fecha_remision" class="col-md-2 control-label">Fecha Remisión</label>
                        <div class="col-md-2">
                            <input type="date" id="fecha_remision" class="form-control" disabled>
                        </div>
                        <label for="sector_remision" class="col-md-1 control-label">Sector</label>
                        <div class="col-md-2">
                            <select name="sector_remision" id="sector_remision" class="form-control" disabled>
                            </select>
                        </div>
                        <label for="vendedor_remision" class="col-md-1 control-label">Vendedor</label>
                        <div class="col-md-4">
                            <select name="vendedor_remision" id="vendedor_remision" class="form-control" disabled>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <table id="table_productos" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="100">Código</th>
                                        <th>Producto</th>
                                        <th width="100">Unidad</th>
                                        <th width="100">Cantidad</th>
                                        <th width="100">P.Unitario $</th>
                                        <th width="100">Total $</th>
                                        <th width="120">Vendidos</th>
                                        <th width="120">Devueltos</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" id="costos_remision">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class='text-right' colspan=5>VALOR RECAUDAD</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_recaudado" step="0.01" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>VALOR EN EFECTIVO</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_efectivo" value="0.00" step="0.01" onkeyup="restarRemision()">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>VALOR EN CHEQUE</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_cheque" step="0.01" value="0.00" onkeyup="restarRemision()">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>RETENCIONES</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_retenciones" step="0.01" value="0.00" onkeyup="restarRemision()">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>DESCUENTO</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_descuento" step="0.01" value="0.00" onkeyup="restarRemision()">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>DIFERENCIA</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="diferencia" step="0.01" value="0.00" readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                  </div>
                  <div class="form-group row" id="seleccionGuiaEntrega">
                    <div class="col-md-8 col-sm-12 col-xs-12" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <table id="table_guias" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="300"> No. Factura</th>
                                        <th colSpan="4" class="text-center;" width="120">Acción</th>
                                        <th>Nueva Fecha</th>
                                        <th colSpan="4" class="text-center;">Forma Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="150"> No. Factura</td>
                                        <td width="30">
                                            <label for="check">
                                                <input type="checkbox"> 1
                                            </label>  
                                        </td>
                                        <td width="30">
                                            <label for="check">
                                                <input type="checkbox"> 2
                                            </label>  
                                        </td>
                                        <td width="30">
                                            <label for="check">
                                                <input type="checkbox"> 3
                                            </label>  
                                        </td>
                                        <td width="30">
                                            <label for="check">
                                                <input type="checkbox"> 4
                                            </label>  
                                        </td>
                                        <td>
                                            <input type="date" class="form-control">
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> A
                                            </label>                                            
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> B
                                            </label>  
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> C
                                            </label>  
                                        </td>
                                        <td>
                                            <label for="check">
                                                <input type="checkbox"> D
                                            </label>  
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                    <div class="col-md-4 col-sm-12 col-xs-12" style="margin-top: 20px;">
                        <div class="table-wrapper">
                            <table id="table_resumen_productos" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50"> Cantidad</th>
                                        <th>Producto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12" id="costos_entrega">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class='text-right' colspan=5>VALOR RECAUDAD</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_recaudado" step="0.01" value="0.00" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>VALOR EN EFECTIVO</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_efectivo" value="0.00" step="0.01">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>VALOR EN CHEQUE</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_cheque" step="0.01" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>RETENCIONES</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_retenciones" step="0.01" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>DESCUENTO</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="valor_descuento" step="0.01" value="0.00">
                                    </td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>DIFERENCIA</td>
                                    <td class='text-right' colspan=1>
                                        <input type="number" class="form-control" id="diferencia" step="0.01" value="0.00" readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                  </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="guia_recepcion.php" type="button"  class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                                </a>
                                <button type="button" class="btn btn-success" id="btn-guardar">
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

