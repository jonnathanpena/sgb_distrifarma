<!-- Modal -->
<div class="modal fade" id="editarIngreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar ingreso</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_ingreso" name="guardar_ingreso">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="editarIngresoUsuario" class="col-sm-3 control-label">Usuario</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editarIngresoUsuario">
                                <input type="hidden" id="editarIngresoUsuarioId">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editarIngresoFecha" class="col-sm-3 control-label">Fecha</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editarIngresoFecha" name="editarIngresoFecha" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editarIngresoDocumento" class="col-sm-3 control-label">Número documento</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="editarIngresoDocumento" name="editarIngresoDocumento" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editarIngresoValor" class="col-sm-3 control-label">Valor</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editarIngresoValor" name="editarIngresoValor" min="0.01" step="0.01" onkeyup="calcularIngresoEditar()" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editarIngresoSaldo" class="col-sm-3 control-label">Saldo</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="editarIngresoSaldo" name="editarIngresoSaldo" min="0.01" step="0.01" disabled>
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



