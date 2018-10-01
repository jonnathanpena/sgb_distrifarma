<?php
   $active_administracion = "active";
   $active_facturas = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "";
   $active_bodega = "";
   $active_reportes = "";
   $active_reportes_usuarios = "";
   $title="Nuevo Personal | SGB";   
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
               <h4><i class='glyphicon glyphicon-edit'></i> Nuevo Personal</h4>
            </div>
            <div class="panel-body">
               <form class="form-horizontal" role="form" id="form_nuevo_personal">
                  <div class="form-group row">
                     <label for="tipo_documento" class="col-md-1 control-label">Tipo Documento<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <select name="tipo_documento" id="tipo_documento" class="form-control">
                            <option value="null">Seleccione...</option>
                            <option value="Cedula">Cédula</option>
                            <option value="RUC">RUC</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                     </div>
                     <label for="documento" class="col-md-1 control-label">Documento<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="documento" name="documento" placeholder="Documento del cliente" onkeyup="getDocumento()" required>
                        <span style="color: red;" id="span_documento">¡Documento ya registrado!</span>
                     </div>
                     <label for="nombre" class="col-md-1 control-label">Nombre<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="nombre" nombre="nombre" placeholder="Nombre" required>
                     </div>
                     <label for="apellido" class="col-md-1 control-label">Apellido<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="apellido" name="apellido" placeholder="Apellido" required>
                     </div>
                  </div>
                  <div class="form-group row">
                    <label for="email" class="col-md-1 control-label">Email<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="email" class="form-control input-sm" id="email" name="email" placeholder="Email" required>
                     </div>
                    <label for="cargo" class="col-md-1 control-label">Cargo<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <select name="cargo" id="cargo" class="form-control">
                            <option value="null">Seleccione...</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Supervisor">Supervisor</option>
                            <option value="Secretaria">Secretaria</option>
                            <option value="Vendedor">Vendedor</option>
                            <option value="Repartidor">Repartidor</option>
                        </select>
                     </div>
                     <label for="fecha_ingreso" class="col-md-1 control-label">F.Ingreso<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="date" class="form-control input-sm" id="fecha_ingreso" name="fecha_ingreso" required>
                     </div>
                     <label for="sueldo" class="col-md-1 control-label">Sueldo<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="sueldo" name="sueldo" placeholder="Sueldo" min="0.01" step="0.01" required>
                     </div>                                                           
                  </div>                  
                  <div class="form-group row">
                     <label for="bono" class="col-md-1 control-label">Bono</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="bono" name="bono" min="0" step="0.01" placeholder="Bono personal">
                     </div>  
                     <label for="anticipo" class="col-md-1 control-label">Anticipo Personal</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="anticipo" name="anticipo" min="0" placeholder="Anticipo">
                     </div>  
                     <label for="descuento" class="col-md-1 control-label">Descuento(%)</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="descuento" name="descuento" min="0" placeholder="Descuento(%)">
                     </div>
                     <label for="decimos" class="col-md-1 control-label">Décimos</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="decimos" name="decimos" placeholder="Décimos" min="0" >
                     </div>                                    
                   </div>
                   <div class="form-group row">     
                     <label for="vacaciones" class="col-md-1 control-label">Vacaciones</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="vacaciones" name="vacaciones" min="0" placeholder="Vacaciones">
                     </div>                  
                     <label for="comisiones" class="col-md-1 control-label">Comisiones</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="comisiones" name="comisiones" placeholder="Comisiones" min="0" >
                     </div>   
                     <label for="es_usuario" class="col-md-1 control-label">¿Usuario?</label>
                     <div class="form-check col-md-1">
                        <input type="checkbox" data-toggle="toggle" id="toggle-usuario" data-on="SI" data-off="NO" data-onstyle="info" data-size="small">
                        <input type="hidden" name="es_usuario" id="es_usuario" value="0">
                     </div> 
                     <label for="usuario" class="col-md-1 control-label usuario">Usuario<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm usuario" id="usuario" name="usuario" placeholder="Usuario">
                     </div>                                   
                   </div>
                   <div class="form-group row">
                        <label for="clave" class="col-md-1 control-label usuario">Contraseña<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="password" class="form-control input-sm usuario" id="clave" name="clave" placeholder="Contraseña">
                        </div>  
                        <label for="confirme" class="col-md-1 control-label usuario">Repetir Clave<span class="obligatorio">*</span></label>
                        <div class="col-md-2">
                            <input type="password" name="confirme" id="confirme" class="form-control usuario" placeholder="Repetir Clave">
                            <input type="hidden" id="perfil" >
                        </div>
                        <!--<div class="form-group">
                            <label for="perfil" class="col-md-1 control-label usuario">Perfil</label>
                            <div class="col-md-2">
                                <select name="perfil" id="perfil" class="form-control usuario" disabled>
                                    <option value="null">Seleccione...</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Ventas">Ventas</option>
                                </select>
                            </div>
                        </div>-->
                   </div>
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="personal.php"  class="btn btn-danger">
                                <span class="glyphicon glyphicon-remove"></span> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success" id="btn-guardar">
                                <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
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
      <script type="text/javascript" src="js/nuevo_personal.js"></script>
   </body>
</html>

