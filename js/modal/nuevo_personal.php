<!-- Modal -->
<div class="modal fade" id="nuevoPersonal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo proveedor</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="ruc" class="col-sm-3 control-label">RUC</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="ruc" name="ruc" min="1" step="1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direccion" class="col-sm-3 control-label">Dirección</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="telefono" name="telefono" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="forma_pago" class="col-sm-3 control-label">Forma de Pago</label>
                            <div class="col-sm-8">
                                <select name="forma_pago" id="forma_pago" class="form-control">
                                    <option>Seleccione...</option>
                                    <option value="1">Crédito</option>
                                    <option value="2">De contado</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descuento" class="col-sm-3 control-label">Descuento(%)</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="descuento" name="descuento" min="0.01" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre_contacto" class="col-sm-3 control-label">Nombre Contacto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre_contacto" name="nombre_contacto" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telefono_contacto" class="col-sm-3 control-label">Teléfono Contacto</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="telefono_contacto" name="telefono_contacto" min="1000000" step="1" >
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



