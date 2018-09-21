<div class="modal fade bs-example-modal-lg" id="agregarProductoCompra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="q" placeholder="Buscar productos" onkeyup="getAllProductos()">
						</div>
                        <button type="button" class="btn btn-default" onclick=""><span class='glyphicon glyphicon-search'></span> Buscar</button>
                    </div>
                </form>
                <div class="outer_div" >
                    <table class="table" id="productos_agregar_compra">
                        <thead>
                            <tr class="info">
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Cant</th>
                                <th>P.Unitario</th>
                                <th>IVA</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div id="pager" style="float: right;">
                        <ul id="pagination" class="pagination-sm"></ul>
                    </div>
                </div><!-- Datos ajax Final -->
            </div>   
            <div class="modal-footer">
            </div>     
        </div>
    </div>
</div>