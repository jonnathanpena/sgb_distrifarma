<!-- Modal -->
<div class="modal fade" id="editarProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar proveedor</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="modificar_proveedor" name="modificar_proveedor">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="codigo" class="col-sm-3 control-label">CÓDIGO</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="codigo" name="codigo" min="1" step="1" disabled>
                                <input type="hidden" id="id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editRuc" class="col-sm-3 control-label">RUC</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editRuc" name="editRuc" min="1" step="1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editNombre" class="col-sm-3 control-label">Nombre</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editNombre" name="editNombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editDireccion" class="col-sm-3 control-label">Dirección</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editDireccion" name="editDireccion" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editTelefono" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editTelefono" name="editTelefono">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editNombre_contacto" class="col-sm-3 control-label">Nombre Contacto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editNombre_contacto" name="editNombre_contacto">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editTelefono_contacto" class="col-sm-3 control-label">Teléfono Contacto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editTelefono_contacto" name="editTelefono_contacto">
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" id="modificar">Modificar</button>
        </div>
    </form>
</div>
</div>
</div>



