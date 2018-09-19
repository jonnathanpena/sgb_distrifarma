<?php
try
	{
	
		require_once "../PHPMailer/class.phpmailer.php";

		$server = $_SERVER["HTTP_HOST"];

		$email = "noreply@proconty.com";
		$mail = new PHPMailer;
		$mail->Host = "$server";
		$mail->From = "$email";
		$mail->FromName = "Bar Corporativo";
		$mail->Subject = utf8_decode("Notificación de Reverso");
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
						
							</tr>
						</table>
						<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
							<tr>
							   <td style="width:35%;" class="midnight-blue">CLIENTE</td>
							</tr>
							<tr>
							   <td style="width:50%;" >
 								  <br>'.$nombre_cliente.'<br> Teléfono: '.$telefono_cliente.'<br> Email: '.$email_cliente.'<br>
							   </td>
							</tr>
						</table>
						<br>
						<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
							<tr>
							   <td style="width:35%;" class="midnight-blue">DETALLE DEL REVERSO</td>
							  
							</tr>
							<tr>
							   <td style="width:35%;">
							   <br>Nº DOCUMENTO: '.$numero_factura.'<br>VALOR : $'.$valor_factura.'<br>FECHA: '.$fecha_factura.'</td>
							</tr>
						</table>
						
						
					  </page>	
'; // Cerramos la comilla simple. Con la comilla simple y el punto y coma se finaliza el cuerpo del mensaje html.  


// Asignamos al atributo Body, la variable $cuerpo.
$mail->Body = utf8_decode($cuerpo); 


		if ($mail->Send()) {
			//echo "Enviado con éxito";
		} else {
			//echo "Venta Registrada correctamente. No se pudo realizar el envio";
		}
	
	
	
	} catch (Exception $e) {
		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		exit;
	}
?>
