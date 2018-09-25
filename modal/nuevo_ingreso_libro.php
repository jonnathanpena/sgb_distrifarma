<?php

$fecha = date("d/m/Y");

?>
<!-- Modal -->
<div class="modal fade" id="nuevoIngresoLD" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Nuevo ingreso</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_ingresoLD" name="guardar_ingresoLD">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="usuarioLD" class="col-sm-3 control-label">Usuario</label>
                            <div class="col-sm-8">
                                <select name="usuarioLD" id="usuarioLD" class="form-control" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaLD" class="col-sm-3 control-label">Fecha</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="fechaLD" name="fechaLD" value="<?php echo $fecha; ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detalleLD" class="col-sm-3 control-label">Detalle</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="detalleLD" name="detalleLD" placeholder="Detalle" required>                        
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="valorInicial" class="col-sm-3 control-label">Valor Inicial</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="valorInicial" name="valorInicial" min="0" step="0.01" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="saldo_ingreso" class="col-sm-3 control-label">Ingreso</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="saldo_ingreso" name="saldo_ingreso" placeholder="Ingreso" min="0" step="0.01" required>
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



