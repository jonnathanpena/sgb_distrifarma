<?php

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/*Inicia validacion del lado del servidor*/

	if (empty($_POST['nombre'])) {

           $errors[] = "Nombre vacío";

        } else if (!empty($_POST['nombre'])){

		/* Connect To Database*/

		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

		// escaping, additionally removing everything that could be (html/javascript-) code

		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));

		$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));

		$telefono=mysqli_real_escape_string($con,(strip_tags($_POST["telefono"],ENT_QUOTES)));

		$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));

		$empresa=mysqli_real_escape_string($con,(strip_tags($_POST["empresa"],ENT_QUOTES)));

		$estado=intval($_POST['estado']);

		$date_added=date("Y-m-d H:i:s");

		$saldo=0;

		$documento_cliente=mysqli_real_escape_string($con,(strip_tags($_POST["documento"],ENT_QUOTES)));

		$descuento=mysqli_real_escape_string($con,(strip_tags($_POST["descuento"],ENT_QUOTES)));

		$descuento = $descuento / 100;

		$descuento = number_format($descuento,2);

		$direccion = "na";

		$sql="INSERT INTO `clientes`(`nombre_cliente`, `documento_cliente`, 
				`telefono_cliente`, `email_cliente`, `direccion_cliente`, `status_cliente`, 
				`date_added`, `codigo`, `saldo_cliente`, `descuento`,`empresa_cliente`) VALUES (
					'".$nombre."',
					'".$documento_cliente."',
					'".$telefono."',
					'".$email."',
					'".$direccion."',
					".$estado.",
					'".$date_added."',
					'".$codigo."',
					".$saldo.",
					".$descuento.",
					".$empresa."
				)";

		$query_new_insert = mysqli_query($con,$sql);

			if ($query_new_insert){

				$messages[] = "Cliente ha sido ingresado satisfactoriamente.";

			} else{

				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".$sql;

			}

		} else {

			$errors []= "Error desconocido.";

		}

		

		if (isset($errors)){

			

			?>

			<div class="alert alert-danger" role="alert">

				<button type="button" class="close" data-dismiss="alert">&times;</button>

					<strong>Error!</strong> 

					<?php

						foreach ($errors as $error) {

								echo $error;

							}

						?>

			</div>

			<?php

			}

			if (isset($messages)){

				

				?>

				<div class="alert alert-success" role="alert">

						<button type="button" class="close" data-dismiss="alert">&times;</button>

						<strong>¡Bien hecho!</strong>

						<?php

							foreach ($messages as $message) {

									echo $message;

								}

							?>

				</div>

				<?php

			}



?>