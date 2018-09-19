<?php
		if (isset($con))
		{
	?>
	<!-- Modal -->
	<div class="modal fade" id="productoKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar producto al kardex</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="guardar_kardex" name="guardar_kardex">
			<div id="resultados_ajax"></div>			  
			  <div class="form-group">
				<label for="documento" class="col-sm-3 control-label">Producto</label>
				<div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_nombre_producto" required>
                    <input id="mod_id_producto" type='hidden' name="id_producto">	
				</div>
			  </div> 

              <div class="form-group">
				<label for="documento" class="col-sm-3 control-label">Cantidad Actual</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_cantidad" name="cantidad" 
                        required pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números" maxlength="8" 
                        style="width: 77%; display: inline-block;" disabled>
                    <button type="button" class="btn btn-danger" title="Descontar" style="display: inline-block; width: 10%;" 
                        id="btn-bajar" onclick="bajar()">
                        <i class="glyphicon glyphicon-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-success" title="Aumentar" style="display: inline-block; width: 10%;" 
                        id="btn-subir" onclick="subir()">
                        <i class="glyphicon glyphicon-arrow-up"></i>
                    </button>
				</div>
			  </div> 

              <div class="form-group" id="aumentar">
				<label for="documento" class="col-sm-3 control-label">Cantidad</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" id="cantidad_aumenta" name="cantidad_aumenta" pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números" maxlength="8">
				</div>
			  </div> 

              <div class="form-group" id="disminuir">
				<label for="documento" class="col-sm-3 control-label">Cantidad</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" id="cantidad_disminuye" name="cantidad_disminuye" pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números" maxlength="8">
				</div>
			  </div> 

              <div class="form-group" id="motivo">
				<label for="documento" class="col-sm-3 control-label">Motivo</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="input_motivo" name="motivo" >
				</div>
			  </div> 
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
	<?php
		}
	?>