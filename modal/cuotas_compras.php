<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="cuotasCompra" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		    <div class="modal-body">
                <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
                    <form id="editar-cuota">
                        <div class="col-md-3">
                            <input type="number" class="form-control" id="pago-min" placeholder="Pago mínimo" step="0.001" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="fecha-pago" placeholder="Fecha vencimiento" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="descuento-pago" placeholder="Descuento" step="0.001">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="descripcion-pago" placeholder="Descripción">
                            <input type="hidden" id="id-cuota">
                            <input type="hidden" id="compra-id-cuota">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-success" title="Guardar">
                                <i class="glyphicon glyphicon-floppy-disk"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="table-wrapper">
                    <table id="cuotas" class="table table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Pago Mínimo($)</th>
                                <th>Fecha de Vencimiento</th>
                                <th class="text-center">Descuento($)</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>  
                        </tbody>
                    </table>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 col-md-offset-6 control-label">Restan:</label>
                    <div class="col-md-3">
                        <input type="number" class="form-control" id="restan-cuotas" disabled>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger" style="float: right;" onclick="cerrarPopUpCuotas()">Salir</button>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>