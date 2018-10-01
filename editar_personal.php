<?php
   $active_administracion = "active";
   $active_facturas = "";
   $active_ingresos = "";
   $active_egresos = "";
   $active_guias = "";
   $active_bodega = "";
   $active_reportes = "";
   $active_reportes_usuarios = "";
   $title="Modificar Personal | SGB";   
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
               <h4><i class='glyphicon glyphicon-edit'></i> Modificar Personal</h4>
            </div>
            <div class="panel-body">
               <form class="form-horizontal" role="form" id="form_modificar_personal">
                  <div class="form-group row">
                    <label for="codigo" class="col-md-1 control-label">Código</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="codigo" name="codigo" disabled>
                     </div>
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
                        <input type="text" class="form-control input-sm" id="documento" name="documento" placeholder="Documento del cliente" required>
                        <input type="hidden" id="id" name="id">
                     </div>
                     <label for="nombre" class="col-md-1 control-label">Nombre<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="nombre" nombre="nombre" placeholder="Nombre" required>
                     </div>                     
                  </div>
                  <div class="form-group row">
                    <label for="apellido" class="col-md-1 control-label">Apellido<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="apellido" name="apellido" placeholder="Apellido" required>
                     </div>
                    <label for="email" class="col-md-1 control-label">Email<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="email" class="form-control input-sm" id="email" name="email" placeholder="Email" required>
                     </div>
                    <label for="cargo" class="col-md-1 control-label">Cargo<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <select name="cargo" id="cargo" class="form-control">
                            <option value="null">Seleccione...</option>
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
                  </div>                  
                  <div class="form-group row">
                     <label for="sueldo" class="col-md-1 control-label">Sueldo<span class="obligatorio">*</span></label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="sueldo" name="sueldo" placeholder="Sueldo" min="0.01" step="0.01" required>
                     </div>  
                     <label for="bono" class="col-md-1 control-label">Bono</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="bono" name="bono" min="0" step="0.01" placeholder="Bono personal">
                     </div>  
                     <label for="anticipo" class="col-md-1 control-label">Anticipo Personal</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="anticipo" name="anticipo" min="0" step="0.01" placeholder="Anticipo">
                     </div>  
                     <label for="descuento" class="col-md-1 control-label">Descuento(%)</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="descuento" name="descuento" min="0" step="0.01" placeholder="Descuento(%)">
                     </div>                                                        
                   </div>
                   <div class="form-group row">   
                     <label for="decimos" class="col-md-1 control-label">Décimos</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="decimos" name="decimos" placeholder="Décimos" min="0" step="0.01">
                     </div>   
                     <label for="vacaciones" class="col-md-1 control-label">Vacaciones</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="vacaciones" name="vacaciones" min="0" step="0.01" placeholder="Vacaciones">
                     </div>                  
                     <label for="comisiones" class="col-md-1 control-label">Comisiones</label>
                     <div class="col-md-2">
                        <input type="number" class="form-control input-sm" id="comisiones" name="comisiones" placeholder="Comisiones" min="0" step="0.01">
                        <input type="hidden" id="tabla_comisiones" name="tabla_comisiones">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                     </div>  
                     <label for="usuario" class="col-md-1 control-label" id="label_usuario">Usuario</label>
                     <div class="col-md-2">
                        <input type="text" class="form-control input-sm" id="usuario" name="usuario" placeholder="usuario">
                     </div>                                                    
                   </div>
                   <!--<div class="form-group row">
                   <div class="form-group row" style="display: none;">
                     <label for="perfil" class="col-md-1 control-label" id="label_perfil">Perfil</label>
                     <div class="col-md-2">
                        <select name="perfil" id="perfil" class="form-control">
                            <option value="null">Seleccione...</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Funcionario">Funcionario</option>
                        </select>
                        <input type="hidden" id="clave">
                     </div>
                   </div> -->
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
      <script type="text/javascript" src="js/editar_personal.js"></script>
   </body>
</html>

