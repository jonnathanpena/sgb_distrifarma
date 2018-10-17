<!-- Modal -->
<div class="modal fade" id="consultarProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Selecciona los productos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form  class="form-horizontal" id="consultar_cliente">
                            <div class="form-group row">
                                <label for="editNombre" class="col-sm-3 control-label">Nombre o Código</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nombreCodigo" placeholder="Nombre o Código" onkeyup="getAllProductos()" required>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table" id="tabla_productos">
                                <thead>
                                    <tr  class="info">
                                        <th width="80">Código</th>
                                        <th>Producto</th>
                                        <th>Unidad</th>
                                        <th width="80">Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th width="10">Agregar</th>
                                    </tr>
                                </thead>  
                                <tbody></tbody> 
                            </table>                             
                        </div>
                        <div id="pager" style="float: right;">
                            <ul id="pagination" class="pagination-sm"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



