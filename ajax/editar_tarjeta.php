<?php

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/*Inicia validacion del lado del servidor*/

	if (empty($_POST['mod_id_tarjetas'])) {

           $errors[] = "ID vacío";

        }else if (empty($_POST['mod_monto'])) {

           $errors[] = "Monto vacío";

        }  else if ($_POST['mod_estado']==""){

			$errors[] = "Selecciona el estado de la tarjeta";

		}  else if (

			!empty($_POST['mod_id_tarjetas']) &&

			!empty($_POST['mod_monto']) &&

			$_POST['mod_estado']!="" 

		){

		/* Connect To Database*/

		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

		// escaping, additionally removing everything that could be (html/javascript-) code

        $id_tarjetas=mysqli_real_escape_string($con,(strip_tags($_POST["mod_id_tarjetas"],ENT_QUOTES)));
        
		$monto_tarjetas=mysqli_real_escape_string($con,(strip_tags($_POST["mod_monto"],ENT_QUOTES)));

		$estatus_tarjetas=intval($_POST['mod_estado']);

		$sql="UPDATE `tarjetas` 
                SET `monto_tarjetas`= ".$monto_tarjetas.",
                `estatus_tarjetas`= ".$estatus_tarjetas."
                WHERE `id_tarjetas` = ".$id_tarjetas;

		$query_update = mysqli_query($con,$sql);

			if ($query_update){

				$messages[] = "Tarjeta ha sido actualizada satisfactoriamente.";

			} else{

				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);

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