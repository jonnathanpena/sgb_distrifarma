<?php

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

    /* Connect To Database*/
    
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	// escaping, additionally removing everything that could be (html/javascript-) code

	$codigo="11111111111111111111";

	$cliente_id="1";

	$monto=mysqli_real_escape_string($con,(strip_tags($_POST["monto"],ENT_QUOTES)));

	$user_solicitud_id="1";

	$sql="INSERT INTO `tarjetas`(`codigo_tarjetas`, `cliente_id`, `monto_tarjetas`, 
            `fecha_solicitud_Tarjetas`, `user_solicitud_id`, `estatus_tarjetas`) VALUES (
                ".$codigo.",
                ".$cliente_id.",
                ".$monto.",
                NOW(),
                ".$user_solicitud_id.",
                1
            )";

	$query_new_insert = mysqli_query($con,$sql);

	if ($query_new_insert){

		$messages[] = "Tarjeta ha sido generada satisfactoriamente.";

	} else{

		$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);

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