<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title></title>
			<script type="text/javascript">
				function imprimir() {
					if (window.print) {
						window.print();
						window.location.href = "../facturas.php"
					} else {
						alert("La función de impresion no esta soportada por su navegador.");
					}
				}
			</script>
		</head>
		<body>
        <?php 
            $data = json_decode($_POST['data'], true);
        ?>
			<table style="width: 100%;">
				<tr>
					<td>
					    <FONT FACE="Arial" SIZE="1">No. Factura: <?php echo $data['df_num_factura']; ?></FONT>
					</td>
				</tr>
				<tr>
					<td>
					    <FONT FACE="Arial" SIZE="1">Fecha: 21/8/2018</FONT>
					</td>
				</tr>
				<tr>
					<td>
					    <FONT FACE="Arial" SIZE="1">Vendedor: 12587</FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<td>
						<FONT FACE="Arial" SIZE="1">--------------------------------------------------------</FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
				<tr>
					<th style="width: 30%; text-align: left;">
						<FONT FACE="Arial" SIZE="1">Cant</FONT>
					</th>
					<th style="width: 40%; text-align: left;">
						<FONT FACE="Arial" SIZE="1">Prod.</FONT>
					</th>
					<th style="width: 30%; text-align: left;">
						<FONT FACE="Arial" SIZE="1">Total</FONT>
					</th>
				</tr>
		<?php			

		?>
				<tr>
					<td style="width: 30%; text-align: left;">
					    <FONT FACE="Arial" SIZE="1">20</FONT>
					</td>
					<td style="width: 60%; text-align: left;">
					    <FONT FACE="Arial" SIZE="1">20</FONT>
					</td>
					<td style="width: 30%; text-align: left;">
					    <FONT FACE="Arial" SIZE="1">20</FONT>
					</td>
				</tr>
		<?php
		?>
			</table>
			<table style="width: 100%;">
				<tr>
					<td>
						<FONT FACE="Arial" SIZE="1">--------------------------------------------------------</FONT>
					</td>
				</tr>
			</table>
			<table style="width: 100%;">
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
			</table>
		</body>
	</html>