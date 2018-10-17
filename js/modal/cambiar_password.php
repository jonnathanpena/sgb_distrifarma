<!-- Modal -->
<div class="modal fade" id="editarClave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Cambiar clave usuario</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_clave" name="modificar_clave">
                    <div id="resultados_ajax"></div>
                        <div class="form-group">
                            <label for="editarClave" class="col-sm-3 control-label">Nueva clave<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="editaClave" name="editaClave" placeholder="Nueva Clave" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editarConfirme" class="col-sm-3 control-label">Confirmar clave<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="editarConfirme" name="editarConfirme" placeholder="Confirmar Clave" required>
                            </div>
                        </div>                        
                    </div>
                <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" id="editar">Guardar</button>
        </div>
    </form>
</div>
</div>
</div>



