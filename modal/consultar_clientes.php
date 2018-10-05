<!-- Modal -->
<div class="modal fade" id="consultarClientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Escoja un cliente</h4>
            </div>
            <div class="modal-body">
                <form  class="form-horizontal" id="consultar_cliente">
                    <div class="form-group row">
                        <label for="editNombre" class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombreDocumento" placeholder="Nombre o Documento" onkeyup="getCliente()" required>
                        </div>
                    </div>
                </form>
                <div id="resultados">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="info">
                                    <th>Código</th>
                                    <th>Tipo Documento</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



