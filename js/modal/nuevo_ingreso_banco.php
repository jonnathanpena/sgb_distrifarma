<?php

$fecha = date("d/m/Y");

?>
<!-- Modal -->
<div class="modal fade" id="nuevoIngresoBanco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Nuevo ingreso</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_ingreso" name="guardar_ingreso">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="usuario" class="col-sm-3 control-label">Usuario</label>
                            <div class="col-sm-8">
                                <select name="usuario" id="usuario" class="form-control" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha" class="col-sm-3 control-label">Fecha<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>" require>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detalle" class="col-sm-3 control-label">Detalle<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" id="detalle" name="detalle"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="documento" class="col-sm-3 control-label">Número documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="documento" name="documento" placeholder="Número documento" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="valor" class="col-sm-3 control-label">Valor<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="valor" name="valor" placeholder="Valor" min="0.01" step="0.01" onkeyup="calcularIngreso()" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="saldo_ingreso" class="col-sm-3 control-label">Saldo</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="saldo_ingreso" name="saldo_ingreso" min="0.01" step="0.01" disabled>
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



