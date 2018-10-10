<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title></title>
			<style type="text/css" media="print">
			@media print {
  				body {
    				width: 148mm;
    				height: 210mm;
  				}
			}
			</style>			
		</head>
		<body onload="imprimir()">
        <?php 
            $data = json_decode($_POST['data'], true);
        ?>
			<table style="width: 100%;">
				<tr>
					<td>
					    <FONT FACE="Arial" SIZE="1"><?php echo $data['cliente']['df_nombre_cli'] ?></FONT>
					</td>
				</tr>
				<tr>
					<td>
					    <FONT FACE="Arial" SIZE="1"><?php echo $data['cliente']['df_documento_cli'] ?></FONT>
					</td>
					<td>
						<FONT FACE="Arial" SIZE="1"><?php
							if ($data['cliente']['df_telefono_cli'] == '') {
								echo $data['cliente']['df_celular_cli'];
							} else {
								echo $data['cliente']['df_telefono_cli'];
							}
						?></FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
		<?php			
			for ($i= 0; $i < count($data['detalles']); $i++) {
				$detalle = $data['detalles'][$i];
		?>
				<tr>
					<td style="width: 15%; text-align: left;">
					    <FONT FACE="Arial" SIZE="1"><?php echo $detalle['df_cantidad_detfac']?></FONT>
					</td>
					<td style="width: 45%; text-align: center;">
					    <FONT FACE="Arial" SIZE="1"><?php echo $detalle['df_nombre_producto']?></FONT>
					</td>
					<td style="width: 20%; text-align: right;">
					    <FONT FACE="Arial" SIZE="1"><?php echo $detalle['df_precio_prod_detfac']?></FONT>
					</td>
					<td style="width: 20%; text-align: right;">
					    <FONT FACE="Arial" SIZE="1"><?php echo $detalle['df_valor_sin_iva_detfac']?></FONT>
					</td>
				</tr>
		<?php
			}
		?>
			</table>
			<!--<table style="width: 100%;">
				<tr>
					<td style="width: 100%; text-align: right;">
					    <FONT FACE="Arial" SIZE="1">Total: </strong> 20</FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td style="width: 100%; text-align: left;">
					    <FONT FACE="Arial" SIZE="1"><strong>Orden N. </strong> 20</FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td style="width: 100%; text-align: center;">
					    <FONT FACE="Arial" SIZE="1">¡¡Gracias por su compra!!</FONT>
					</td>
				</tr>
			</table>-->
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