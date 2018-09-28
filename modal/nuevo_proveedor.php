<!-- Modal -->
<div class="modal fade" id="nuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo proveedor</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="guardar_proveedor" name="guardar_proveedor">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="ruc" class="col-sm-3 control-label">RUC<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="ruc" name="ruc" min="1" step="1" onkeyup="consultarRUC()" placeholder="RUC" required>
                                <span style="color: red;" id="span_ruc">¡Proveedor ya registrado!</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="col-sm-3 control-label">Dirección<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre_contacto" class="col-sm-3 control-label">Nombre Contacto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" placeholder="Nombre Contacto">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono_contacto" class="col-sm-3 control-label">Teléfono Contacto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" placeholder="Teléfono Contacto">
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



