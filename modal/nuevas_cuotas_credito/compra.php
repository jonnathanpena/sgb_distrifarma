			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="nuevasCuotasCompra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-body">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" id="nueva_cuota" class="btn btn-success">
                                        <i class="fa fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table id="cuotas" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Pago Mínimo</th>
                                    <th>Fecha de Vencimiento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>  
                            </tbody>
                        </table>
                    </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelarCuotas">Cancelar</button>
					<button type="button" class="btn btn-success" id="guardarCuotas" data-dismiss="modal">Guardar</button>
				  </div>
				</div>
			  </div>
			</div>