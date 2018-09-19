<?php



	session_start();

    date_default_timezone_set('America/Bogota');

	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {

        header("location: ../login.php");

		exit;

    }

    /* Connect To Database*/

	include("../config/db.php");

	include("../config/conexion.php");

	//Archivo de funciones PHP

	include("../funciones.php");

	

	//Variables por GET

	$id_usuario=intval($_GET['id_usuario']);

	$total_compra=mysqli_real_escape_string($con,(strip_tags($_REQUEST['total'], ENT_QUOTES)));

    $session_id= session_id();
    
    $sql_insert = mysqli_query($con,"INSERT INTO `caja_chica`(`fecha_cc`, `user_cc`, `gasto_total_cc`) VALUES (
                    NOW(),
                    ".$id_usuario.",
                    ".$total_compra.")");
    
    $id_caja_chica = $con->insert_id;

	$sql_count=mysqli_query($con,"SELECT * FROM `tmp_caja_chica` WHERE `sesion__tempcc` ='".$session_id."'");

	$count=mysqli_num_rows($sql_count);

	if ($count==0)

	{

	echo "<script>alert('No hay productos agregados')</script>";

	echo "<script>window.close();</script>";

	exit;

	} else {

        while($row=mysqli_fetch_array($sql_count)){

            $precio_total_producto = $row["cantidad__tempcc"] * $row["precio_unitario__tempcc"];
    
            mysqli_query($con,"INSERT INTO `detalle_caja_chica`(`caja_chica_id`, `producto_dcc`, `cantidad_dcc`, `precio_unitario_dcc`, 
                `precio_total_dcc`) VALUES (
                    ".$id_caja_chica.",
                    '".$row["producto__tempcc"]."',
                    ".$row["cantidad__tempcc"].",
                    ".$row["precio_unitario__tempcc"].",
                    ".$precio_total_producto."
                )");
    
        }	  
        
        echo "<script>alert('Registro Generado con Éxito!!!')</script>";
          
        echo '<script>window.location.href = "../caja_chica.php";</script>';

    }  
    



	