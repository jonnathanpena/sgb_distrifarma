<?php
   $active_administracion = "";
   $active_facturas = "active";
   $active_ingresos = "active";
   $active_egresos = "";
   $active_guias = "";
   $active_bodega = "";
   $active_reportes = "";
   $title="Nueva Factura | SGB";  
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
               <h4><i class='glyphicon glyphicon-edit'></i> Nueva Factura</h4>
            </div>
            <div class="panel-body">
<?php
include("modal/consultar_clientes.php");
include("modal/consultar_productos.php");
?>
               <form class="form-horizontal" role="form" id="form_nueva_factura">
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
                     <label for="documento_cliente" class="col-md-1 control-label">Documento Cliente</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="documento_cliente" name="documento_cliente" placeholder="Documento Cliente" onclick="consultarCliente()" style="cursor: pointer;" readonly>
                        <input type="hidden" id="cliente_id" >
                     </div>
                  </div>
                  <div class="form-group row">
                    <label for="nombre_cliente" class="col-md-1 control-label">Nombre Cliente</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="nombre_cliente" name="nombre_cliente" readonly>
                     </div>
                     <label for="sector" class="col-md-1 control-label">Sector</label>
                     <div class="col-md-2">
                        <select name="sector" id="sector" class="form-control" disabled>
                            <option value="null">Seleccione...</option>
                        </select>
                     </div>
                     <label for="forma_pago" class="col-md-2 control-label">Forma de Pago</label>
                     <div class="col-md-2">
                        <select name="forma_pago" id="forma_pago" class="form-control">
                            <option value="EFECTIVO" selected>Efectivo</option> 
                            <option value="TRANSFERENCIA">Transferencia</option>
                            <option value="CHEQUE">Cheque</option>                                                       
                        </select>
                     </div>                                                          
                    </div>    
                    <div class="form-group row">
                        <label for="fecha_entrega" class="col-md-1 control-label">Fecha Entrega</label>
                        <div class="col-md-2">
                            <input type="date" class="form-control input-sm" id="fecha_entrega" name="fecha_entrega" required>
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
                    <div class="col-md-12" id="costos">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td class='text-right' colspan=5>SUBTOTAL</td>
                                    <td class='text-right' colspan=1><span id="subtotal">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>DESCUENTO</td>
                                    <td class='text-right' colspan=1><span id="descuento">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>TOTAL IVA (12%)</td>
                                    <td class='text-right' colspan=1><span id="total_iva">0.00</span></td>
                                </tr>
                                <tr>
                                    <td class='text-right' colspan=5>TOTAL</td>
                                    <td class='text-right' colspan=1><span id="total">0.00</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="facturas.php"  class="btn btn-danger">
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
      <script type="text/javascript" src="js/nueva_factura.js"></script>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   </body>
</html>

