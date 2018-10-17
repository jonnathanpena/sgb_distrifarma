<?php



include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado



// checking for minimum PHP version



if (version_compare(PHP_VERSION, '5.3.7', '<')) {



    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");



} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {



    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php



    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)



    require_once("../libraries/password_compatibility_library.php");



}	



        require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos



        require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos



        if(isset($_GET['motivo'])){

            

            $id_producto = $_GET['id'];

            $cantidad = $_GET['cantidad'];

            $cant = $_GET['cant'];



            $sqlExiste = mysqli_query($con,"SELECT `id_inventario`, `cantidad_inventario`, `producto_inventario` 

                    FROM `inventario` WHERE `producto_inventario` = ".$id_producto);

            if($row=mysqli_fetch_array($sqlExiste)){

                $sqlUpdate = mysqli_query($con,"UPDATE `inventario` SET 

                    `cantidad_inventario`= ".$cantidad." WHERE `id_inventario` = ".$row['id_inventario']);

                $sqlUpdate = mysqli_query($con,"INSERT INTO `log_inventario`(`fecha_loginv`, `producto_loginv`, 

                    `cantidad_loginv`, `tipo_loginv`, `motivo`) VALUES (

                        NOW(),

                        ".$id_producto.",

                        ".$cant.",

                        'Descuento',

                        '".$_GET['motivo']."'

                    )");

                if($sqlUpdate){

                    $messages[] = "Inventario actualizado exitosamente.";

                }else{

                    $errors[] = "Lo sentimos , existió un error";

                }

            }else{

                $sqlInsert = mysqli_query($con,"INSERT INTO `inventario`(`cantidad_inventario`, `producto_inventario`) 

                    VALUES (".$cantidad.",".$id_producto.")");

                if($sqlInsert){

                    $messages[] = "Inventario actualizado exitosamente.";

                }else{

                    $errors[] = "Lo sentimos , existió un error";

                }

            }



        }else{



            $id_producto = $_GET['id'];

            $cantidad = $_GET['cantidad'];

            $cant = $_GET['cant'];



            $sqlExiste = mysqli_query($con,"SELECT `id_inventario`, `cantidad_inventario`, `producto_inventario` 

                    FROM `inventario` WHERE `producto_inventario` = ".$id_producto);

            if($row=mysqli_fetch_array($sqlExiste)){

                $sqlUpdate = mysqli_query($con,"UPDATE `inventario` SET 

                    `cantidad_inventario`= ".$cantidad." WHERE `id_inventario` = ".$row['id_inventario']);

                $sqlUpdate = mysqli_query($con,"INSERT INTO `log_inventario`(`fecha_loginv`, `producto_loginv`, 

                `cantidad_loginv`, `tipo_loginv`, `motivo`) VALUES (

                    NOW(),

                    ".$id_producto.",

                    ".$cant.",

                    'Incremento',

                    'Agregar al inventario'

                )");

                if($sqlUpdate){

                    $messages[] = "Inventario actualizado exitosamente.";

                }else{

                    $errors[] = "Lo sentimos , existió un error";

                }

            }else{

                $sqlInsert = mysqli_query($con,"INSERT INTO `inventario`(`cantidad_inventario`, `producto_inventario`) 

                    VALUES (".$cantidad.",".$id_producto.")");

                $sqlInsert = mysqli_query($con,"INSERT INTO `log_inventario`(`fecha_loginv`, `producto_loginv`, 

                `cantidad_loginv`, `tipo_loginv`, `motivo`) VALUES (

                    NOW(),

                    ".$id_producto.",

                    ".$cant.",

                    'Incremento',

                    'Agregar al inventario'

                )");

                if($sqlInsert){

                    $messages[] = "Inventario actualizado exitosamente.";

                }else{

                    $errors[] = "Lo sentimos , existió un error";

                }

            }



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