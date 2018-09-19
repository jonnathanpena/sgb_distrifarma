<?php
		if (isset($con))
		{
	?>	
			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="modAddProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Agregar Producto</h4>
				  </div>
				  <div class="modal-body" id="form_add_producto">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">
						  <input type="text" class="form-control" placeholder="Producto" id="producto">
						</div>
                        <div class="col-sm-3">
						  <input type="number" min="1" step="1" class="form-control" placeholder="Cantidad" id="cantidad">
						</div>
                        <div class="col-sm-3">
						  <input type="number" min="0.01" step="0.01" class="form-control" placeholder="Precio Unitario" id="precio_unitario">
						</div>
					  </div>
					</form>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			        <button type="submit" class="btn btn-primary" id="agregar_item" onclick="agregar()">Agregar</button>
				  </div>
				</div>
			  </div>
			</div>
	<?php
		}
	?>