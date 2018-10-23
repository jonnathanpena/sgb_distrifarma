<?php







	session_start();



	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {



        header("location: ../../login.php");



		exit;



    }



	



	



	/* Connect To Database*/



	include("../../config/db.php");



	include("../../config/conexion.php");



	//Archivo de funciones PHP



	include("../../funciones.php");



	$session_id= session_id();



	$sql_count=mysqli_query($con,"select * from tmp where session_id='".$session_id."'");



	$count=mysqli_num_rows($sql_count);



	if ($count==0)



	{



	echo "<script>alert('No hay productos agregados a la factura')</script>";



	echo "<script>window.close();</script>";



	exit;



	} else {

		

	  	echo "<script>alert('Consumo Generado con Éxito!!!')</script>";	



	}







	require_once(dirname(__FILE__).'/../html2pdf.class.php');



		



	//Variables por GET



	$id_cliente=intval($_GET['id_cliente']);



	$id_vendedor=intval($_GET['id_vendedor']);



	$condiciones=mysqli_real_escape_string($con,(strip_tags($_REQUEST['condiciones'], ENT_QUOTES)));



	$total_compra=mysqli_real_escape_string($con,(strip_tags($_REQUEST['gastos_producto'], ENT_QUOTES)));



	$aplica_tarjeta=mysqli_real_escape_string($con,(strip_tags($_REQUEST['aplica_tarjeta'], ENT_QUOTES)));



	$aplica_iva=mysqli_real_escape_string($con,(strip_tags($_REQUEST['aplica_iva'], ENT_QUOTES)));





	//Fin de variables por GET



	$sql=mysqli_query($con, "select LAST_INSERT_ID(numero_factura) as last from facturas order by id_factura desc limit 0,1 ");



	$rw=mysqli_fetch_array($sql);



	$numero_factura=$rw['last']+1;	

	$simbolo_moneda=get_row('perfil','moneda', 'id_perfil', 1);



    // get the HTML



     ob_start();



     include(dirname('__FILE__').'/res/factura_html.php');



	$content = ob_get_clean();

	

	$sql_cliente=mysqli_query($con,"select * from clientes where id_cliente='$id_cliente'");

	$rw_cliente=mysqli_fetch_array($sql_cliente);

	$sql_user=mysqli_query($con,"select * from users where user_id='$id_vendedor'");

	$rw_user=mysqli_fetch_array($sql_user);	



	function getCantidad($id_producto, $conexion){

		$sql_cantidad = mysqli_query($conexion, "SELECT `id_inventario`, `cantidad_inventario`, `producto_inventario` 

			FROM `inventario` WHERE `producto_inventario` = ".$id_producto);

		$cantidad = array(

			"cantidad" => "",

			"id_inventario" => 0

		);

		if($fila=mysqli_fetch_array($sql_cantidad)){

			$cantidad = array(

				"cantidad" => $fila['cantidad_inventario'],

				"id_inventario" => $fila['id_inventario']

			);

		}

		return $cantidad;

	}



	function updateInventario($cantidad, $id_inventario, $conexion){

		mysqli_query($conexion, "UPDATE `inventario` SET `cantidad_inventario`= ".$cantidad."

			WHERE `id_inventario` = ".$id_inventario);

	}



	function cantidadConsumida($num_factura, $id_producto, $conexion){

		$sql_cantidad = mysqli_query($conexion, "SELECT `cantidad` FROM `detalle_factura` 

			WHERE `numero_factura` = ".$num_factura." and `id_producto` = ".$id_producto);

		$cantidad = 0;

		if($fila=mysqli_fetch_array($sql_cantidad)){

			$cantidad = $fila['cantidad'];

		}

		return $cantidad;

	}



	function insertLog($id_producto, $cantidad, $conexion){

		mysqli_query($conexion, "INSERT INTO `log_inventario`(`fecha_loginv`, `producto_loginv`, `cantidad_loginv`, 

			`tipo_loginv`, `motivo`) VALUES (NOW(),".$id_producto.",".$cantidad.",'Compra','Compra')");

	}



	$sql_productos_compra=mysqli_query($con, "select * from products, detalle_factura, facturas where products.id_producto=detalle_factura.id_producto and detalle_factura.numero_factura=facturas.numero_factura and facturas.numero_factura=".$numero_factura);

	while($fila=mysqli_fetch_array($sql_productos_compra)){

		$cant = getCantidad($fila['id_producto'], $con);

		if($cant['cantidad'] != ""){

			if($cant['cantidad'] != 0){

				$cantidad_comprada = cantidadConsumida($numero_factura, $fila['id_producto'], $con);

				$cantidad_nueva = $cant['cantidad'] - $cantidad_comprada;

				updateInventario($cantidad_nueva, $cant['id_inventario'], $con);

				insertLog($fila['id_producto'], $cantidad_comprada, $con);				

			}

		}

	}







?>



	<!DOCTYPE html>

	<html>

		<head>

			<meta charset="UTF-8">

			<title></title>

			<script type="text/javascript">

				function imprimir() {

					if (window.print) {

						window.print();

						window.location.href = "../../nueva_factura.php"

					} else {

						alert("La función de impresion no esta soportada por su navegador.");

					}

				}

			</script>

		</head>

		<body onload="imprimir();">

			<table style="width: 100%;">

				<tr>

					<td>

					<FONT FACE="Arial" SIZE="1">Nombre: <?php echo $rw_cliente['nombre_cliente'] ?></FONT>

					</td>

				</tr>

				<tr>

					<td>

					<FONT FACE="Arial" SIZE="1">Fecha: <?php echo "".date("d/m/Y").""; ?></FONT>

					</td>

				</tr>

				<tr>

					<td>

					<FONT FACE="Arial" SIZE="1">Vendedor: <?php echo $rw_user['firstname']." ".$rw_user['lastname']; ?></FONT>

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



			$sumador_total=0;

			$sql=mysqli_query($con, "select * from products, detalle_factura, facturas where products.id_producto=detalle_factura.id_producto and detalle_factura.numero_factura=facturas.numero_factura and facturas.numero_factura=".$numero_factura);



			while ($row=mysqli_fetch_array($sql))



			{



				$id_producto=$row["id_producto"];

				$codigo_producto=$row['codigo_producto'];

				$cantidad=$row['cantidad'];

				$nombre_producto=$row['nombre_producto'];	

				$precio_venta=$row['precio_venta'];

				$precio_venta_f=number_format($precio_venta,2);//Formateo variables

				$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas

				$precio_total=$precio_venta_r*$cantidad;

				$precio_total_f=number_format($precio_total,2);//Precio total formateado

				$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas

				$precio_total_r=number_format($precio_total_r,2);

				$sumador_total+=$precio_total_r;//Sumador

				$total_master_fac = $row['total_venta'];

				

				

		?>

				<tr>

					<td style="width: 30%; text-align: left;">

					<FONT FACE="Arial" SIZE="1"><?php echo $cantidad; ?></FONT>

					</td>

					<td style="width: 60%; text-align: left;">

					<FONT FACE="Arial" SIZE="1"><?php echo $nombre_producto; ?></FONT>

					</td>

					<td style="width: 30%; text-align: left;">

					<FONT FACE="Arial" SIZE="1"><?php echo $precio_total_r ?></FONT>

					</td>

				</tr>

		<?php



			}

				

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

					<FONT FACE="Arial" SIZE="1">Total: </strong> <?php echo $sumador_total ?></FONT>

					</td>

				</tr>

			</table>

			<table style="width: 100%;">

				<tr>

					<td style="width: 100%; text-align: left;">

					<FONT FACE="Arial" SIZE="1"><strong>Orden N. </strong> <?php echo $numero_factura ?></FONT>

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



    







	