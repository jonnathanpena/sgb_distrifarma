<!-- Modal -->
<div class="modal fade" id="editarProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar producto</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_producto" name="modificar_producto">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="codigo" class="col-sm-3 control-label">Código</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="codigo" name="codigo" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editNombre" class="col-sm-3 control-label">Nombre</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editNombre" name="editNombre" placeholder="Nombre" autofocus disabled> 
                                <input type="hidden" class="form-control" id="id" >
                                <input type="hidden" class="form-control" id="id_precio" >
                                <input type="hidden" class="form-control" id="editPpp" >
                                <input type="hidden" class="form-control" id="editMin" >
                                <input type="hidden" class="form-control" id="editUtilidad" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editPvt1" class="col-sm-3 control-label">Normal x Caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editPvt1" name="editPvt1" min="0.01" step="0.01" placeholder="Precio Normal x Caja" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editPvt2" class="col-sm-3 control-label">Descuento x Caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editPvt2" name="editPvt2" min="0.01" step="0.01" placeholder="Precio Descuento x Caja" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editPvp" class="col-sm-3 control-label">PVP<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editPvp" name="editPvp" min="0.01" step="0.01" placeholder="PVP" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editIva" class="col-sm-3 control-label">IVA</label>
                            <div class="col-sm-8">
                                <select name="editIva" id="editIva" class="form-control" required></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editUnidad_caja" class="col-sm-3 control-label">Unidad x caja<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editUnidad_caja" name="editUnidad_caja" min="1" step="1" placeholder="Unidad x Caja" required>
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



