<!-- Modal -->
<div class="modal fade" id="nuevoProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Nuevo producto</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_producto" name="guardar_producto">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="codigop" class="col-sm-3 control-label">Código</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="codigop" value="PRO-" name="codigop" placeholder="Código" autofocus disabled> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" autofocus required> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pvt1" class="col-sm-3 control-label">Normal x Caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="pvt1" name="pvt1" min="0.01" step="0.01" placeholder="Precio Normal x Caja" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pvt2" class="col-sm-3 control-label">Descuento x Caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="pvt2" name="pvt2" min="0.01" step="0.01" placeholder="Precio Descuento x Caja" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pvp" class="col-sm-3 control-label">PVP<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="pvp" name="pvp" min="0.01" step="0.01" placeholder="PVP" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="iva" class="col-sm-3 control-label">IVA<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select name="iva" id="iva" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="unidad_caja" class="col-sm-3 control-label">Unidad x caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="unidad_caja" name="unidad_caja" min="1" step="1" placeholder="Unidades x caja" required>
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



