<!-- Modal -->
<div class="modal fade" id="editarCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Modificar cliente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_cliente" name="modificar_cliente">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="editCodigo" class="col-sm-3 control-label">Código</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editCodigo" name="editCodigo" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editTipo_documento" class="col-sm-3 control-label">Tipo Documento</label>
                            <div class="col-sm-8">
                                <select name="editTipo_documento" id="editTipo_documento" class="form-control" required>
                                    <option value="null">Seleccione...</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="RUC">R.U.C</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editDocumento" class="col-sm-3 control-label">Documento</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editDocumento" name="editDocumento" min="1" step="1" max="9999999999">
                                <input type="number" class="form-control" id="editRuc" name="editRuc" min="1" step="1" max=9999999999999>
                                <input type="text" class="form-control" id="editPasaporte" name="editPasaporte" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editNombre" class="col-sm-3 control-label">Nombre</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editNombre" name="editNombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editRazon_social" class="col-sm-3 control-label">Razón Social</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editRazon_social" name="editRazon_social" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editDireccion" class="col-sm-3 control-label">Dirección</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editDireccion" name="editDireccion" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editReferencia" class="col-sm-3 control-label">Referencia</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editReferencia" name="editReferencia" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editSector" class="col-sm-3 control-label">Sector</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="editSector" id="editSector">
                                    <option value="null">Seleccione...</option>
                                    <option value="1">Sector 1</option>
                                    <option value="2">Sector 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editEmail" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="editEmail" name="editEmail" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editTelefono" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editTelefono" name="editTelefono" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editCelular" class="col-sm-3 control-label">Celular</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editCelular" name="editCelular" required>
                                <input type="hidden" class="form-control" id="id">
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" id="modificar">Guardar</button>
        </div>
    </form>
</div>
</div>
</div>



