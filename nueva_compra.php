<?php
   $active_administracion = "";
   $active_facturas = "";
   $active_ingresos = "";
   $active_egresos = "active";
   $active_guias = "";
   $active_bodega = "";
   $active_reportes = "";
   $active_reportes_usuarios = "";
   $title="Compras | SGB";
   date_default_timezone_set('America/Bogota');
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
               <h4><i class='glyphicon glyphicon-shopping-cart'></i> Nueva Compra</h4>
            </div>
            <div class="panel-body">

            <?php
				include("modal/nuevas_cuotas_credito/compra.php");
				include("modal/agregar_producto_compra.php");
			?>

               <form class="form-horizontal" role="form" id="nueva_compra">
                  <div class="form-group row">					 
					 <label for="usuario" class="col-md-1 control-label">Usuario</label>
                     <div class="col-md-2">
                        <select class="form-control input-sm" id="usuario" name="usuario" readonly>
                        </select>
                     </div>
                     <label class="col-md-1 control-label">Fecha</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="fecha" value="<?php echo date("d/m/Y");?>" readonly>
                        <input type="hidden" id="total" name="total">
                     </div>
                     <label class="col-md-1 control-label">Proveedor<span class="obligatorio">*</span></label>
                    <div class="col-md-4">
                        <select class='form-control input-sm' id="proveedor" name="proveedor">
                        </select>
                     </div>
                  </div>

                  <div class="form-group row">                    
                     <label class="col-md-2 control-label">Sustento Tributario<span class="obligatorio">*</span></label>
                    <div class="col-md-5">
                        <select class='form-control input-sm' id="sustento_tributario" name="sustento_tributario">
                        </select>
                     </div>
                     <label class="col-md-2 control-label">Tipo Comprobante<span class="obligatorio">*</span></label>
                    <div class="col-md-3">
                        <select class='form-control input-sm' id="tipo_comprobante" name="tipo_comprobante">
                           <option value="null">Seleccione...</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group row">                    
                    <label class="col-md-2 control-label">Serie</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="serie" name="serie" placeholder="Serie(000-000)">
                     </div>
                    <label class="col-md-2 control-label">Documento</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="documento" name="documento" placeholder="Documento">
                     </div>
                     <label class="col-md-2 control-label">Autorización SRI</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="autorizacion" name="autorizacion" placeholder="Autorización">
                     </div>
                  </div>

                  <div class="form-group row">
                    
                    <label class="col-md-2 control-label">Fecha comprobante</label>
                    <div class="col-md-2">
                        <input type="date" class="form-control input-sm" id="fecha_comprobante" name="fecha_comprobante">
                     </div>
                    <label class="col-md-2 control-label">Fecha Ingreso Bodega</label>
                    <div class="col-md-2">
                        <input type="date" class="form-control input-sm" id="fecha_ingreso_bodega" name="fecha_ingreso_bodega">   
                     </div>
                     <label class="col-md-2 control-label">Fecha Caducidad Doc.</label>
                    <div class="col-md-2">
                        <input type="date" class="form-control input-sm" id="fecha_caducidad_doc" name="fecha_caducidad_doc"> 
                     </div>
                  </div>   

                  <div class="form-group row">                    
                    <label class="col-md-2 control-label">Vencimiento</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="vencimiento" name="vencimiento" placeholder="Vencimiento">
                     </div>
                    <label class="col-md-2 control-label">Descripción</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control input-sm" id="descripcion" name="descripcion" placeholder="Descripción">   
                     </div>                    
                  </div>               

                  <div class="form-group row">
                    <label class="col-md-2 control-label">Forma de Pago</label>
                    <div class="col-md-2">
                        <select class='form-control input-sm' id="condiciones" name="condiciones">
                            <option value="3" selected>Efectivo</option>
                            <option value="1">Cheque</option>
                            <option value="2">Transferencia bancaria</option>                            
                            <option value="4">Crédito</option>
                        </select>                         
                    </div>
                    <div class="col-md-1">
                        <a href="#" id="pago_credito" class='btn btn-info' title='Detalle cuotas' data-toggle="modal" 
                            data-target="#nuevasCuotasCompra" onclick="initTablaCuotas()">
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    </div>
                  </div>

                  <div id="pago_transferencia">
                    <div class="form-group row">
                        <label for="banco_emisor" class="col-md-1 control-label">Del<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <select class='form-control input-sm' id="banco_emisor" name="banco_emisor">
                            </select>
                        </div>
                        <label for="banco_receptor" class="col-md-1 control-label">Al<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <select id="banco_receptor" class='form-control input-sm' name="banco_receptor">
                            </select>
                        </div>
                        <label for="monto" class="col-md-1 control-label">Monto<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <input type="number" min="0.01" step="0.01" class="form-control input-sm" name="monto" id="monto_transferencia" 
                                placeholder="Monto abonado" onkeyup='calcularRestante()'>
                        </div>                        
                    </div>                     

                    <div class="form-group row">
                        <label for="codigo_transferencia" class="col-md-2 control-label">Código Transferencia<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" name="codigo_transferencia" id="codigo_transferencia" 
                                placeholder="Código de transferencia">
                        </div>
                        <label for="fecha_transferencia" class="col-md-2 control-label">Fecha<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="date" class="form-control input-sm" name="fecha" id="fecha" placeholder="Fecha transferencia" >
                            <input type="hidden" name="fecha_transferencia" id="fecha_transferencia">
                        </div>
                        <label for="observacion" class="col-md-2 control-label">Observación<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" name="observacion" id="observacion" placeholder="Observación">
                        </div>
                    </div>  
                  </div>

                  <div id="pago_tarjeta">
                    <div class="form-group row">
                        <label for="banco_tarjeta" class="col-md-1 control-label">Banco<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <select class='form-control input-sm' id="banco_tarjeta" name="banco_tarjeta" >
                            </select>
                        </div>
                        <label for="tipo_tarjeta" class="col-md-1 control-label">Tipo<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <select id="tipo_tarjeta" class='form-control input-sm' name="tipo_tarjeta" >
                                <option>Seleccione tipo de tarjeta...</option>
                                <option value="1">Débito</option>
                                <option value="2">Crédito</option>
                            </select>
                        </div>
                        <label for="marca_tarjeta" class="col-md-2 control-label">Franquicia<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <select id="marca_tarjeta" class='form-control input-sm' name="marca_tarjeta" >
                            </select>
                        </div>                       
                    </div> 

                    <div class="form-group row">
                        <label for="numero_recibo" class="col-md-2 control-label"># Recibo<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="number" min="0" step="1" class="form-control input-sm" name="numero_recibo" id="numero_recibo" 
                                placeholder="Número del Recibo" >
                        </div>
                        <label for="fecha_tarjeta" class="col-md-2 control-label">Fecha<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="date" class="form-control input-sm" name="fecha" id="fecha" placeholder="Fecha pago" >
                            <input type="hidden" name="fecha_tarjeta" id="fecha_tarjeta">
                        </div>
                        <label for="monto" class="col-md-2 control-label">Monto<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="number" min="0.01" step="0.01" class="form-control input-sm" name="monto" id="monto_tarjeta" 
                                placeholder="Monto abonado" onkeyup='calcularRestante()' >
                        </div>
                    </div>     

                    <div class="form-group row">
                        <label for="titular_tarjeta" class="col-md-1 control-label">Titular<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <input type="text" class="form-control input-sm" name="titular_tarjeta" id="titular_tarjeta" 
                                placeholder="Titular de la Tarjeta" >
                        </div>
                        <label for="observacion" class="col-md-2 control-label">Observación<span class="obligatorio">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control input-sm" name="observacion" id="observacion" placeholder="Observación">
                        </div>
                    </div>                       
                  </div>

                  <div id="pago_cheque">
                    <div class="form-group row">
                        <label for="banco_cheque" class="col-md-1 control-label">Banco<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <select class='form-control input-sm' id="banco_cheque" name="banco_cheque">
                            </select>
                        </div> 
                        <label for="numero_cheque" class="col-md-2 control-label"># Cheque<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="number" min="0" step="1" class="form-control input-sm" name="numero_cheque" id="numero_cheque" 
                                placeholder="Número del cheque">
                        </div> 
                        <label for="monto" class="col-md-2 control-label">Monto<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="number" min="0.01" step="0.01" class="form-control input-sm" name="monto" id="monto_cheque" 
                                placeholder="Monto Abonado" onkeyup='calcularRestante()'>
                        </div>                     
                    </div> 

                    <div class="form-group row">
                        <label for="titular_cheque" class="col-md-2 control-label">Titular<span class="obligatorio">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control input-sm" name="titular_cheque" id="titular_cheque" 
                                placeholder="Títular del cheque">
                        </div>
                        <label for="observacion" class="col-md-2 control-label">Observación<span class="obligatorio">*</span></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control input-sm" name="observacion_cheque" id="observacion_cheque" placeholder="Observación">
                        </div>
                    </div>                    
                  </div>

                  <div id="pago_electronico">
                    <div class="form-group row">
                        <label for="empresa" class="col-md-1 control-label">Empresa<span class="obligatorio">*</span></label>
                        <div class="col-md-3">
                            <input type="text" class="form-control input-sm" name="empresa" id="empresa" placeholder="Empresa">
                        </div> 
                        <label for="codigo" class="col-md-2 control-label">Código<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" name="codigo" id="codigo" placeholder="Código">
                        </div> 
                        <label for="monto_electronico" class="col-md-2 control-label">Monto<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" name="monto_electronico" id="monto_electronico" placeholder="Monto">
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label for="observacion_electronico" class="col-md-2 control-label">Observación<span class="obligatorio">*</span></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control input-sm" name="observacion_electronico" id="observacion_electronico" placeholder="Observación">
                        </div>                   
                    </div>
                  </div> 

                  <div class="form-group row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs">
                            <li class="active" id="tab_producto"><a data-toggle="tab" href="#productos">Producto</a></li>
                            <li><a data-toggle="tab" href="#retencion" onclick="irRetencion()">Retención</a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="productos" class="tab-pane fade in active">
                                <div class="form-group row producto">
                                    <label for="cdigo_producto" class="col-md-1 control-label">Código</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control input-sm" id="codigo_producto" name="codigo_producto" placeholder="Código" autofocus>
                                    </div>
                                    <label for="cdigo_producto" class="col-md-1 control-label">Producto</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control input-sm" placeholder="Producto" id='nombre_producto' disabled>
                                    </div>
                                    <label for="cdigo_producto" class="col-md-1 control-label">Cant</label>
                                    <div class="col-md-1">
                                        <input type="number" class="form-control input-sm" id="cantidad_producto" name="cantidad_producto" value="1">
                                    </div>
                                    <label for="cdigo_producto" class="col-md-1 control-label">P. Unit.</label>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control input-sm" id="precio_unitario_producto" name="precio_unitario_producto" value="0.01" step="0.01">
                                    </div>
                                    <label for="iva_producto" class="col-md-1 control-label">IVA</label>
                                    <div class="col-md-2">
                                        <select id="iva_producto" name="iva_producto" class="form-control input-sm">
                                            <option value="0.12" selected>12%</option>
                                            <option value="0">0%</option>
                                        </select>
                                    </div>
                                    <label for="cdigo_producto" class="col-md-1 control-label">Bonificación</label>
                                    <div class="col-md-1">
                                        <input type="number" class="form-control input-sm" id="bonificacion_producto" name="bonificacion_producto" value="0" step="1">
                                    </div>
                                    <label for="cdigo_producto" class="col-md-1 control-label">Descuento</label>
                                    <div class="col-md-1">
                                        <input type="number" class="form-control input-sm" id="descuento_producto" name="descuento_producto" placeholder="%" step="0.01">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success" onclick="agregar()"><i class="glyphicon glyphicon-plus"></i></button>
                                    </div>
                                </div>   
                                <div class="table-wrapper">
                                    <table id="table_productos" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="120">Código</th>
                                                <th>Producto</th>
                                                <th width="100">Bonificación</th>
                                                <th width="100">Cantidad</th>
                                                <th width="100">P.Unitario $</th>
                                                <th width="100">IVA $</th>
                                                <th width="120">Descuento($)</th>
                                                <th width="100">Subtotal $</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="retencion" class="tab-pane fade">
                                <div class="table-wrapper">
                                    <div class="table-title">
                                        <div class="form-group row">
                                            <label for="serie_ret" class="col-md-2 control-label">Serie Retención</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control input-sm" name="serie_ret" id="serie_ret" placeholder="Serie Ret">
                                            </div> 
                                            <label for="n_retencion" class="col-md-2 control-label">No. Retención</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control input-sm" name="n_retencion" id="n_retencion" placeholder="No. Retención">
                                            </div> 
                                            <label for="autorizacion_sri_ret" class="col-md-2 control-label">Autorización SRI</label>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control input-sm" name="autorizacion_sri_ret" id="autorizacion_sri_ret" placeholder="Autorización SRI">
                                            </div>   
                                        </div>
                                        <div class="form-group row">
                                            <label for="fecha_ing_ret" class="col-md-3 control-label">Fecha Ingreso Retención</label>
                                            <div class="col-md-3">
                                                <input type="date" class="form-control input-sm" name="fecha_ing_ret" id="fecha_ing_ret" placeholder="Fecha Ingreso Retención">
                                            </div> 
                                            <label for="fecha_caducidad_ret" class="col-md-3 control-label">Fecha Caducidad</label>
                                            <div class="col-md-3">
                                                <input type="date" class="form-control input-sm" name="fecha_caducidad_ret" id="fecha_caducidad_ret" placeholder="Fecha de Caducidad">
                                            </div>  
                                        </div>
                                        <div class="form-group row">
                                            <label for="retencion_iva" class="col-md-1 control-label">Retención IVA</label>
                                            <div class="col-md-3">
                                                <select name="retencion_iva" class="form-control" id="retencion_iva" onchange="seleccionoRetencionIva()"></select>
                                            </div> 
                                            <label for="porcentaje_ret_iva" class="col-md-1 control-label">Porcentaje</label>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control input-sm" name="porcentaje_ret_iva" id="porcentaje_ret_iva" placeholder="Porcentaje" disabled>
                                            </div> 
                                            <label for="base_imponible" class="col-md-1 control-label">Base Imponible</label>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control input-sm" name="base_imponible" id="base_imponible" placeholder="Base Imponible" disabled>
                                            </div>  
                                        </div>
                                        <div class="form-group row">
                                            <label for="retencion_ir" class="col-md-1 control-label">Retención</label>
                                            <div class="col-md-2">
                                                <select name="retencion_ir" id="retencion_ir" class="form-control" onchange="seleccionoRetencionIr()"></select>
                                            </div> 
                                            <label for="porcentaje_retencion" class="col-md-1 control-label">Porcentaje</label>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control input-sm" name="porcentaje_retencion" id="porcentaje_retencion" placeholder="Porcentaje" disabled>
                                            </div> 
                                            <label for="base_imponible_civa" class="col-md-1 control-label">Base Imponible c/iva</label>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control input-sm" name="base_imponible_civa" id="base_imponible_civa" placeholder="Base Imponible C/IVA" disabled>
                                            </div>  
                                            <label for="base_imponible_siva" class="col-md-1 control-label">Base Imponible s/iva</label>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control input-sm" name="base_imponible_siva" id="base_imponible_siva" placeholder="Base Imponible S/IVA" disabled>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>                  
               </form>
               <div id="resultados" class='col-md-12' style="margin-top:2%">
                <table class="table" style="width: 80%; margin-left: 20%;">
                    <tr>
                        <td>
                            <label class="control-label">ST Con IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" value="0.00" id="pre_st_con_iv" disabled> 
                        </td>
                        <td>
                            <label class="control-label">Descuento $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" min="0.00" step="0.00" value="0.000" 
                                id="descuento_st_con_iva" onkeyup='cambioDescuentoConIva();'>
                        </td>
                        <td>
                            <label class="control-label">Total con IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" value="0.00" id="total_st_con_iva" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label">ST Sin IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" value="0.00" id="pre_st_sin_iva" disabled> 
                        </td>
                        <td>
                            <label class="control-label">Descuento $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" min="0.00" step="0.00" value="0.000" 
                                id="descuento_st_sin_iva" onkeyup='cambioDescuentoSinIva();'>
                        </td>
                        <td>
                            <label class="control-label">Total sin IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="total_st_sin_iva" value="0.000" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label">ST 0% IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" value="0.00" id="pre_st_iva_cero" disabled> 
                        </td>
                        <td>
                            <label class="control-label">Descuento $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" min="0.00" step="0.00" value="0.000" 
                                id="descuento_iva_cero" onkeyup='cambioDescuentoIvaCero();'>
                        </td>
                        <td>
                            <label class="control-label">Total 0% IVA $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="total_st_iva_cero" value="0.000" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label">ICE + CC %</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" value="0.00" id="pre_ice_cc" onkeyup="cambioICECC()"> 
                        </td>
                        <td>
                            <label class="control-label">Imp. Verde $</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" min="0.00" step="0.00" value="0.000" 
                                id="imp_verde" onkeyup="calcularResultados()">
                        </td>
                        <td>
                            <label class="control-label">IVA</label> 
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="total_iva" value="0.12" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label">Otros $</label>                             
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="total_otros" value="0.000"
                                onkeyup="calcularResultados()">
                        </td>
                        <td>
                            <label class="control-label">Interés %</label>
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="intereses" value="0.000">
                        </td>
                        <td>
                            <label class="control-label">Descuento (%)</label>
                        </td>
                        <td>
                            <input type="number" class="form-control input-sm" id="bonificacion" value="0.000">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <label class="control-label">Total Compra</label>                             
                        </td>
                        <td colspan="2">
                            <input type="number" class="form-control input-sm" id="total_compra" value="0.00" disabled>
                        </td>
                    </tr>
                </table>
               </div>

               <div class="col-md-12">
                <div class="pull-right">
                    <a class="btn btn-danger" href="compras.php">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success" id="btn-comprar">
                        <span class="glyphicon glyphicon-shopping-cart"></span> Comprar
                    </button>
                    <button type="button" class="btn btn-default" onclick="javascript:window.location.reload();">
                        <span class="glyphicon glyphicon-erase"></span> Limpiar Pantalla
                    </button>
                </div>
               </div>
            </div>
         </div>
         <div class="row-fluid">
            <div class="col-md-12">
            </div>
         </div>
      </div>
      <hr>
      <?php
         include("footer.php");
         ?>      
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script type="text/javascript" src="js/config.js"></script>
        <script type="text/javascript" src="js/nueva_compra.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/nueva_compra.css">
   </body>
</html>

