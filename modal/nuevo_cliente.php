<!-- Modal -->
<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo cliente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_cliente" name="guardar_cliente">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="tipo_documento" class="col-sm-3 control-label">Tipo Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select name="tipo_documento" id="tipo_documento" class="form-control" required>
                                    <option value="null">Seleccione...</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="RUC">R.U.C</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="documento" class="col-sm-3 control-label">Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="documento" name="documento" min="1" step="1" max="9999999999" onkeyup="getByRUC()" placeholder="Documento">
                                <input type="number" class="form-control" id="ruc" name="ruc" min="1" step="1" max=9999999999999 onkeyup="getByRUC()" placeholder="Documento">
                                <input type="text" class="form-control" id="pasaporte" name="pasaporte" onkeyup="getByRUC()" placeholder="Documento">
                                <span style="color: red;" id="span_documento">¡Cliente ya registrado!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="razon_social" class="col-sm-3 control-label">Razón Social</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón Social">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="col-sm-3 control-label">Dirección<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="referencia" class="col-sm-3 control-label">Referencia<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Referencia" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sector" class="col-sm-3 control-label">Sector<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="sector" id="sector" class="sector">                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="celular" class="col-sm-3 control-label">Celular</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="celular" name="celular" placeholder="Celular">
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



