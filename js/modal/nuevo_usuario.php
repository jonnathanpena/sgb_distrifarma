<!-- Modal -->
<div class="modal fade" id="nuevoUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo usuario</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_usuario" name="guardar_usuario">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="tipo_documento" class="col-sm-3 control-label">Tipo Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                                    <option value="null">Seleccione...</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="documento" class="col-sm-3 control-label">Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="documento" placeholder="Documento" name="documento" min="1" step="1" max="9999999999" onkeyup="getByDocumento()">
                                <input type="text" class="form-control" id="pasaporte" placeholder="Documento" name="pasaporte" onkeyup="getByDocumento()" >
                                <span style="color: red;" id="span_documento">¡Documento ya registrado!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="apellido" class="col-sm-3 control-label">Apellido<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="usuario" class="col-sm-3 control-label">Usuario<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="usuario" name="usuario" onkeyup="getByUsuario()" placeholder="Usuario" required>
                                <span style="color: red;" id="span_usuario">¡Usuario ya registrado!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="perfil" class="col-sm-3 control-label">Perfil<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select name="perfil" id="perfil" class="form-control">
                                    <option value="null">Seleccione...</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Ventas">Ventas</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="clave" class="col-sm-3 control-label">Contraseña<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirme" class="col-sm-3 control-label">Repetir Contraseña<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="confirme" name="confirme" placeholder="Repetir Contraseña">
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" id="guardar">Guardar</button>
        </div>
    </form>
</div>
</div>
</div>



