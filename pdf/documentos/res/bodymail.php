<?php

$sql_cliente=mysqli_query($con,"select * from clientes where id_cliente='$id_cliente'");

$rw_cliente=mysqli_fetch_array($sql_cliente);

$nombre_cliente = $rw_cliente['nombre_cliente'];

$direccion_cliente = $rw_cliente['direccion_cliente'];

$telefono_cliente = $rw_cliente['telefono_cliente'];

$email_cliente = $rw_cliente['email_cliente'];



$sql_user=mysqli_query($con,"select * from users where user_id='$id_vendedor'");

$rw_user=mysqli_fetch_array($sql_user);

$vendedor = $rw_user['firstname']." ".$rw_user['lastname'];

$fecha = date("d/m/Y");



$nums=1;

$sumador_total=0;

$sql=mysqli_query($con, "select * from products, tmp where products.id_producto=tmp.id_producto and tmp.session_id='".$session_id."'");
$productos = array();

while ($row=mysqli_fetch_array($sql))

	{

		$id_tmp=$row["id_tmp"];

		$id_producto=$row["id_producto"];

		$codigo_producto=$row['codigo_producto'];

		$cantidad=$row['cantidad_tmp'];

		$nombre_producto=$row['nombre_producto'];

		

		$precio_venta=$row['precio_tmp'];

		$precio_venta_f=number_format($precio_venta,2);//Formateo variables

		$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas

		$precio_total=$precio_venta_r*$cantidad;

		$precio_total_f=number_format($precio_total,2);//Precio total formateado

		$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas

		$sumador_total+=$precio_total_r;//Sumador

		if ($nums%2==0){

			$clase="clouds";

		} else {

			$clase="silver";

		}

		$producto_item = array(
			"id_tmp" => $id_tmp,
			"id_producto" => $id_producto,
			"codigo_producto" => $codigo_producto,
			"cantidad" => $cantidad,
			"nombre_producto" => $nombre_producto,
			"precio_venta" => $precio_venta,
			"precio_venta_f" => $precio_venta_f,
			"precio_venta_r" => $precio_venta_r,
			"precio_total" => $precio_total,
			"precio_total_f" => $precio_total_f,
			"precio_total_r" => $precio_total_r,
			"sumador_total" => $sumador_total,
			"clase" => $clase
		);

		array_push($productos, $producto_item);

		$nums++;

	}



	$impuesto=get_row('perfil','impuesto', 'id_perfil', 1);

	$subtotal=number_format($sumador_total,2,'.','');

	$total_iva=($subtotal * $impuesto )/100;

	$total_iva=number_format($total_iva,2,'.','');

	$total_factura=$subtotal+$total_iva;

								



//Encabezado Factura



//Encabezado Factura

$logo_mail = get_row('perfil','logo_url', 'id_perfil', 1);

$nombre_empresa_mail = get_row('perfil','nombre_empresa', 'id_perfil', 1);

$direccion_mail = get_row('perfil','direccion', 'id_perfil', 1).", ". get_row('perfil','ciudad', 'id_perfil', 1)." ".get_row('perfil','estado', 'id_perfil', 1);

$telefono_mail = get_row('perfil','telefono', 'id_perfil', 1);

$email_mail = get_row('perfil','email', 'id_perfil', 1);



								

								

								

try

	{

	

		require_once "../../PHPMailer/class.phpmailer.php";



		$server = $_SERVER["HTTP_HOST"];



		$email = "noreply@proconty.com";

		$mail = new PHPMailer;

		$mail->Host = "$server";

		$mail->From = "$email";

		$mail->FromName = "Bar Corporativo";

		$mail->Subject = "Registro de Consumos";

		//$mail->addAddress("christian.teran@proconty.com", "Cliente Interno");

		$mail->addAddress("$email_cliente", "$nombre_cliente");

		$mail->IsHTML(true);

		$cuerpo='

				<style type="text/css">

					<!--

					table { vertical-align: top; }

					tr    { vertical-align: top; }

					td    { vertical-align: top; }

					.midnight-blue{

						background:#2c3e50;

						padding: 4px 4px 4px;

						color:white;

						font-weight:bold;

						font-size:12px;

					}

					.silver{

						background:white;

						padding: 3px 4px 3px;

					}

					.clouds{

						background:#ecf0f1;

						padding: 3px 4px 3px;

					}

					.border-top{

						border-top: solid 1px #bdc3c7;

						

					}

					.border-left{

						border-left: solid 1px #bdc3c7;

					}

					.border-right{

						border-right: solid 1px #bdc3c7;

					}

					.border-bottom{

						border-bottom: solid 1px #bdc3c7;

					}

					table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}

					}

					-->

					</style>

					  <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >

						<br>

						<table cellspacing="0" style="width: 100%;">

							<tr>



								<td style="width: 25%; color: #444444;">

									<img style="width: 100%;" src="http://www.proconty.com/SGB/img/logo_amaretti.png" alt="Logo"><br>

									

								</td>

								<td style="width: 50%; color: #34495e;font-size:12px;text-align:center">

									<span style="color: #34495e;font-size:14px;font-weight:bold">'.$nombre_empresa_mail.'</span>

									<br>'.$direccion_mail.'<br> 

									Teléfono: '.$telefono_mail.'<br>

									Email: '.$email_mail.'

									

								</td>

								<td style="width: 25%;text-align:right">

								DOCUMENTO Nº '.$numero_factura.'

								</td>

								

							</tr>

						</table>

						<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">

							<tr>

							   <td style="width:50%;" class="midnight-blue">CLIENTE</td>

							</tr>

							<tr>

							   <td style="width:50%;" >

 								  <br>'.$nombre_cliente.'<br>'.$direccion_cliente.'<br> Teléfono: '.$telefono_cliente.'<br> Email: '.$email_cliente.'<br>

							   </td>

							</tr>

						</table>

						<br>

						<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">

							<tr>

							   <td style="width:35%;" class="midnight-blue">VENDEDOR</td>

							  <td style="width:25%;" class="midnight-blue">FECHA</td>

							   <td style="width:40%;" class="midnight-blue">FORMA DE PAGO</td>

							</tr>

							<tr>

							   <td style="width:35%;">'.$vendedor.'</td>

							  <td style="width:25%;">'.$fecha.'</td>

							   <td style="width:40%;" >Consumo Interno</td>

							</tr>

						</table>

						

						<table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">

							<tr>

								<th style="width: 10%;text-align:center" class="midnight-blue">CANT.</th>

								<th style="width: 60%" class="midnight-blue">DESCRIPCION</th>

								<th style="width: 15%;text-align: right" class="midnight-blue">PRECIO UNIT.</th>

								<th style="width: 15%;text-align: right" class="midnight-blue">PRECIO TOTAL</th>

							</tr>';

							for($i = 0; $i < count($productos); $i++) {

								$cuerpo .='
									<tr>

										<td class='.$productos[$i]["clase"].' style="width: 10%; text-align: center">'.$productos[$i]["cantidad"].'</td>

										<td class='.$productos[$i]["clase"].' style="width: 60%; text-align: left">'.$productos[$i]["nombre_producto"].'</td>

										<td class='.$productos[$i]["clase"].' style="width: 15%; text-align: right">'.$productos[$i]["precio_venta_f"].'</td>

										<td class='.$productos[$i]["clase"].' style="width: 15%; text-align: right">'.$productos[$i]["precio_total_f"].'</td>

								

									</tr>
								';
							}								

							$cuerpo .='
									<tr>

										<td colspan="3" style="widtd: 85%; text-align: right;">SUBTOTAL $ </td>

										<td style="widtd: 15%; text-align: right;"> '.number_format($subtotal,2).'</td>

									</tr>

						</table>

						<br>

							<div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>

					  </page>	

'; // Cerramos la comilla simple. Con la comilla simple y el punto y coma se finaliza el cuerpo del mensaje html.  





// Asignamos al atributo Body, la variable $cuerpo.

$mail->Body = utf8_decode($cuerpo); 





		//if ($mail->Send()) {

			//echo "Enviado con éxito";

		//} else {

			//echo "Venta Registrada correctamente. No se pudo realizar el envio";

		//}

	

	

	

	} catch (Exception $e) {

		echo 'Excepción capturada: ',  $e->getMessage(), "\n";

		exit;

	}

?>

