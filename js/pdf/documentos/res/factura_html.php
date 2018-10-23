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



        <page_footer>



        <table class="page_footer">



            <tr>







                <td style="width: 50%; text-align: left">



                    P&aacute;gina [[page_cu]]/[[page_nb]]



                </td>



                <td style="width: 50%; text-align: right">



                    &copy; <?php echo "www.proconty.com |"; echo  $anio=date('Y'); ?>



                </td>



            </tr>



        </table>



    </page_footer>



    <?php include("encabezado_factura.php");?>



    <br>



    







	



    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">



        <tr>



           <td style="width:50%;" class='midnight-blue'>CLIENTE</td>



        </tr>



		<tr>



           <td style="width:50%;" >



			<?php 



			date_default_timezone_set('America/Bogota');



				$sql_cliente=mysqli_query($con,"select * from clientes where id_cliente='$id_cliente'");



				$rw_cliente=mysqli_fetch_array($sql_cliente);



				echo "<br>";



				echo $rw_cliente['nombre_cliente'];

				$documento_cliente = $rw_cliente['documento_cliente'];



				echo "<br>";



				echo $rw_cliente['direccion_cliente'];



				echo "<br> Teléfono: ";



				echo $rw_cliente['telefono_cliente'];



				echo "<br> Email: ";



				echo $rw_cliente['email_cliente'];



			?>



			



		   </td>



        </tr>



        



   



    </table>



    



       <br>



		<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">



        <tr>



           <td style="width:35%;" class='midnight-blue'>VENDEDOR</td>



		  <td style="width:25%;" class='midnight-blue'>FECHA</td>



		   <td style="width:40%;" class='midnight-blue'>FORMA DE PAGO</td>



        </tr>



		<tr>



           <td style="width:35%;">



			<?php 



				$sql_user=mysqli_query($con,"select * from users where user_id='$id_vendedor'");



				$rw_user=mysqli_fetch_array($sql_user);



				echo $rw_user['firstname']." ".$rw_user['lastname'];



			?>



		   </td>



		  <td style="width:25%;"><?php echo date("d/m/Y");?></td>



		   <td style="width:40%;" >



				<?php 



				if($aplica_tarjeta == 'si'){

					$condiciones = 3;

				}



				if ($condiciones==1){

					echo "Efectivo";

				}elseif ($condiciones==2){

					echo "Transferencia bancaria";

				}elseif ($condiciones==3){

					echo "Tarjeta Prepago";

				}	



				?>



		   </td>



        </tr>



    </table>



	<br>



  



    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">



        <tr>



            <th style="width: 10%;text-align:center" class='midnight-blue'>CANT.</th>



            <th style="width: 60%" class='midnight-blue'>DESCRIPCION</th>



            <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO UNIT.</th>



            <th style="width: 15%;text-align: right" class='midnight-blue'>PRECIO TOTAL</th>



            



        </tr>







		<?php



		$nums=1;



		$sumador_total=0;



		$sql=mysqli_query($con, "select * from products, tmp where (products.id_producto=tmp.id_producto and tmp.session_id='".$session_id."')");



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



			?>







				<tr>



					<td class='<?php echo $clase;?>' style="width: 10%; text-align: center"><?php echo $cantidad; ?></td>



					<td class='<?php echo $clase;?>' style="width: 60%; text-align: left"><?php echo $nombre_producto;?></td>



					<td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo $precio_venta_f;?></td>



					<td class='<?php echo $clase;?>' style="width: 15%; text-align: right"><?php echo $precio_total_f;?></td>



					



				</tr>







			<?php 



			//Insert en la tabla detalle_cotizacion



			$insert_detail=mysqli_query($con, "INSERT INTO detalle_factura VALUES ('','$numero_factura','$id_producto','$cantidad','$precio_venta_r')");



			



			$nums++;



			}



			$sql=mysqli_query($con, "select * from tmp where tmp.tarjeta_temp = 1 and tmp.session_id='".$session_id."'");



			while ($row=mysqli_fetch_array($sql)){

				$contador = mysqli_query($con, "SELECT COUNT(*) + 1 as terminal FROM `tarjetas` WHERE `cliente_id` = ".$id_cliente);

				$fila = mysqli_fetch_array($contador);

				$codigo_tarjeta = $documento_cliente.$fila['terminal'];

				$insert_tarjeta=mysqli_query($con, "INSERT INTO `tarjetas`(`codigo_tarjetas`, `cliente_id`, 

					`monto_tarjetas`, `fecha_solicitud_Tarjetas`, `numero_factura`, `user_solicitud_id`, `estatus_tarjetas`) VALUES (

						'$codigo_tarjeta',

						'$id_cliente',

						'$row[precio_tmp]',

						NOW(),

						'$numero_factura',

						'$id_vendedor',

						1)");

			}



			if($aplica_tarjeta == 'si'){

				$sql=mysqli_query($con, "SELECT * FROM `tarjetas` WHERE `cliente_id` = ".$id_cliente);

				$apagar = $total_compra;

				while ($row=mysqli_fetch_array($sql)){

					if($apagar > 0) {

						if($row['monto_tarjetas'] >= $apagar){

							$resto = $row['monto_tarjetas'] - $apagar;

							mysqli_query($con, "UPDATE `tarjetas` SET `monto_tarjetas`= $resto WHERE `id_tarjetas` = ".$row['id_tarjetas']);

							if($resto == 0){

								mysqli_query($con, "UPDATE `tarjetas` SET `estatus_tarjetas`= 2 WHERE `id_tarjetas` = ".$row['id_tarjetas']);

							}

							$apagar = 0;

						}else{

							$resto = $apagar - $row['monto_tarjetas'];

							mysqli_query($con, "UPDATE `tarjetas` SET `monto_tarjetas`= 0 WHERE `id_tarjetas` = ".$row['id_tarjetas']);

							mysqli_query($con, "UPDATE `tarjetas` SET `estatus_tarjetas`= 2 WHERE `id_tarjetas` = ".$row['id_tarjetas']);

							$apagar = $resto;

						}

					}				

				}

			}			



			if($aplica_iva == 0){

				$impuesto = 0;

			} else {

				$impuesto=get_row('perfil','impuesto', 'id_perfil', 1);

			}	



			$subtotal=number_format($sumador_total,2,'.','');



			$total_iva=($subtotal * $impuesto )/100;



			$total_iva=number_format($total_iva,2,'.','');



			$total_factura=$subtotal+$total_iva;



		?>



			  



				<tr>



					<td colspan="3" style="widtd: 85%; text-align: right;">SUBTOTAL <?php echo $simbolo_moneda;?> </td>



					<td style="widtd: 15%; text-align: right;"> <?php echo number_format($subtotal,2);?></td>



				</tr>



				



    </table>



	<br>



	<div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>

</page>







<?php //include("bodymail.php");?>







<?php



$saldo = $rw_cliente['saldo_cliente'] - $total_factura;



$date=date("Y-m-d H:i:s");



$insert=mysqli_query($con,"INSERT INTO facturas VALUES (NULL,'$numero_factura','$date','$id_cliente','$id_vendedor','$condiciones','$total_factura','1')");



$delete=mysqli_query($con,"DELETE FROM tmp WHERE session_id='".$session_id."'");



$update=mysqli_query($con,"UPDATE clientes SET saldo_cliente='".$saldo."' WHERE id_cliente='".$id_cliente."'");

$sqlIdFac=mysqli_query($con, "SELECT * FROM `facturas` WHERE `numero_factura` = ".$numero_factura);
$row=mysqli_fetch_array($sqlIdFac);
$fac=$row['id_factura'];

mysqli_query($con, "INSERT INTO `cxc`(`factura_id`, `estado_cxc`) VALUES (".$fac.",0)");

?>