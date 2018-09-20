<!-- Modal -->
<div class="modal fade" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar usuario</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_usuario" name="modificar_usuario">
                    <input type="hidden" id="id">
                    <div class="form-group">
                        <label for="editTipo_documento" class="col-sm-3 control-label">Tipo Documento</label>
                        <div class="col-sm-8">
                            <select name="editTipo_documento" id="editTipo_documento" class="form-control" required>
                                <option value="null">Seleccione...</option>
                                <option value="Cedula">Cédula</option>
                                <option value="Pasaporte">Pasaporte</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editDocumento" class="col-sm-3 control-label">Documento</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="editDocumento" name="editDocumento" min="1" step="1" max="9999999999">
                            <input type="text" class="form-control" id="editPasaporte" name="editPasaporte">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editNombre" class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editNombre" name="editNombre" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editApellido" class="col-sm-3 control-label">Apellido</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editApellido" name="editApellido" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editUsuario" class="col-sm-3 control-label">Usuario</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editUsuario" name="editUsuario" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editMail" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="editMail" class="form-control" id="editMail" name="editMail" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editPerfil" class="col-sm-3 control-label">Perfil</label>
                        <div class="col-sm-8">
                            <select name="editPerfil" id="editPerfil" class="form-control">
                                <option value="null">Seleccione...</option>
                                <option value="Administrador">Adminitrador</option>
                                <option value="Funcionario">Funcionario</option>
                            </select>
                        </div>
					</div>
                    <div class="form-group">
                        <label for="editPerfil" class="col-sm-3 control-label">Activo</label>
                        <div class="col-sm-8">
                            <div class="form-check">
                                <input type="checkbox" data-toggle="toggle" id="toggle-activo" data-on="SI" data-off="NO" data-onstyle="info" data-size="small">
                                <input type="hidden" name="editActivo" id="editActivo">
                            </div> 
                        </div>
					</div>
					<input type="hidden" id="editClave">
					<input type="hidden" id="editId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editar()" >Modificar</button>
            </div>                
        </div>
    </div>
</div>



						
                        
                


