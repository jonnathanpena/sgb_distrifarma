<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title></title>
			<style>
				@page { 
					size: letter;
					margin-left: 29mm;
					margin-right: 62mm;
					margin-bottom: 78mm;
					margin-top: 33mm;
				} /* output size */
				body.receipt .sheet { 
					width: 0mm; 
					height: 0mm; 
				} /* sheet size */
				@media print { 
					body.receipt { 
						width: 48mm 
					} 
				} /* fix for Chrome */
  			</style>		
		</head>
		<body onload="imprimir()">
        <?php 
			$data = json_decode($_POST['data'], true);
        ?>
			<table style="width: 100%;">
				<tr>
					<td style="width: 75%; text-align: left;"></td>
					<td style="width: 25%; text-align: center; padding-top: 1mm; font-wieght: bold; padding-left: 1mm; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo substr($data['df_fecha_fac'], 8,-9).'-'.substr($data['df_fecha_fac'], 5,-12).'-'.substr($data['df_fecha_fac'], 0,-15) ?></FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%; margin-top: -3mm;">
				<tr>
					<td style="width: 13%; text-align: left;"></td>
					<td style="width: 87%; text-align: left; font-wieght: bold;">
					    <FONT FACE="Arial" SIZE="2"><?php echo strtoupper($data['cliente']['df_nombre_cli']) ?></FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td style="width: 14%; text-align: left;"></td>
					<td style="width: 23%; text-align: left; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo $data['cliente']['df_documento_cli'] ?></FONT>
					</td>
					<td style="width: 15%; text-align: left;"></td>
					<td style="width: 15%; text-align: left; font-wieght: bold;">
						<FONT FACE="Arial new" SIZE="2"><?php
							if ($data['cliente']['df_telefono_cli'] == '') {
								echo $data['cliente']['df_celular_cli'];
							} else {
								echo $data['cliente']['df_telefono_cli'];
							}
						?></FONT>
					</td>
					<td style="width: 33%; text-align: left;"></td>
				</tr>
			</table>

			<?php	
			$contadorf = count($data['historiaEstadoFactura']);	
			for ($i= 0; $i < $contadorf; $i++) {				
				$detallef = $data['historiaEstadoFactura'][$i];
			}
			?>

			<table style="width: 100%; margin-top: -0.5mm">
				<tr>
					<td style="width: 12%; text-align: left;"></td>
					<td style="width: 88%; text-align: left; font-wieght: bold;">
					<FONT FACE="Arial" SIZE="1"><?php echo strtoupper($detallef['df_direccion_factura']) ?></FONT>
					</td>
				</tr>
			</table>			
		<table style="width: 100%; margin-top: 5mm;">
		<?php	
			$contador = count($data['detalles']);	
			$iva_cero	= 0;
			for ($i= 0; $i < $contador; $i++) {				
				$detalle = $data['detalles'][$i];
				if ($detalle['df_iva_detfac'] == 0){
					$valor = $detalle['df_valor_sin_iva_detfac'] * 1;
					$iva_cero = $iva_cero + $valor;
				}
		?>
				<tr>
					<td style="width: 9%; text-align: left; font-wieght: bold; padding-left: 1mm; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo $detalle['df_cantidad_detfac']?></FONT>
					</td>
					<td style="width: 53%; text-align: left; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo $detalle['df_nombre_producto']?></FONT>
					</td>
					<td style="width: 19%; text-align: right; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($detalle['df_precio_prod_detfac'],2)?></FONT>
					</td>
					<td style="width: 19%; text-align: right; font-wieght: bold;">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($detalle['df_valor_sin_iva_detfac'],2)?></FONT>
					</td>
				</tr>
		<?php
			}
			if ($contador <= 17) {
				for ($j= 0; $j < (17-$contador); $j++) {
		?>
			<tr>
					<td style="width: 8%; text-align: left;">
					    <FONT FACE="Arial" SIZE="2">&nbsp;</FONT>
					</td>
					<td style="width: 56%; text-align: left;">
					    <FONT FACE="Arial" SIZE="2">&nbsp;</FONT>
					</td>
					<td style="width: 18%; text-align: right;">
					    <FONT FACE="Arial" SIZE="2">&nbsp;</FONT>
					</td>
					<td style="width: 18%; text-align: right;">
					    <FONT FACE="Arial" SIZE="2">&nbsp;</FONT>
					</td>
			</tr>
		<?php
				}
			}
		?>
			</table>
			<table style="width: 100%;">
				<tr>
					<td style="width: 60%; text-align: center; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo strtoupper($data['cliente']['df_referencia_cli'])?></FONT>
					</td>
					<td style="width: 40%; text-align: right;">&nbsp;</td>
				</table>							
			<table style="width: 100%; margin-top: 4mm;">
					<td style="width: 30%; text-align: center;">
					</td>
					<td style="width: 20%; text-align: left; padding-top: 1mm; padding-left: 20mm; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="5"><?php echo strtoupper($data['personal']['df_nombre_per'][0]).'.'.strtoupper($data['personal']['df_apellido_per'][0])?></FONT>
					</td>
					<td style="width: 50%; text-align: right; margin-right: -5mm; font-wieght: bold; ">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($data['df_subtotal_fac'],2)?></FONT>
					</td>
					</td>
				</tr>
				<tr>
					<td style="width: 30%; text-align: center;">
					</td>
					<td style="width: 20%; text-align: center; ">
					    <FONT FACE="Arial" SIZE="2"></FONT>
					</td>
					<td style="width: 50%; text-align: right; margin-right: -5mm; font-wieght: bold;">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($iva_cero,2)?></FONT>
					</td>
				</tr>
				<tr>
					<td style="width: 30%; text-align: center;">
					</td>
					<td style="width: 50%; text-align: right;">
					    <FONT FACE="Arial" SIZE="2"></FONT>
					</td>
					<td style="width: 50%; text-align: right; margin-right: -5mm; font-wieght: bold;">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($data['df_iva_fac'],2)?></FONT>
					</td>
				</tr>
				<tr>
					<td style="width: 30%; text-align: center;">
					</td>
					<td style="width: 50%; text-align: right;">
					    <FONT FACE="Arial" SIZE="2"></FONT>
					</td>
					<td style="width: 50%; text-align: right; margin-right: -5mm; font-wieght: bold;">
					    <FONT FACE="Arial" SIZE="2"><?php echo Number_format($data['df_valor_total_fac'],2)?></FONT>
					</td>
				</tr>
			</table> 
			<script type="text/javascript">
				var tituloOriginal;
				function beforeprint(){
					tituloOriginal = document.title;
					document.title = "";
				}
				function imprimir() {
					if (window.print) {
						beforeprint();
						window.focus();
						window.print();
						window.location.href = "../facturas.php"
					} else {
						alert("La función de impresion no esta soportada por su navegador.");
					}
				}
				function afterprint() {
					document.title = tituloOriginal;
				}
			</script>
		</body>
	</html>