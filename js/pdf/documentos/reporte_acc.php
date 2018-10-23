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

    $fecha_ini= $_GET['fecha_ini'];

    $fecha_fin= $_GET['fecha_fin'];

    $estado = $_GET['estado'];

    

    if ($estado == 'all') {
        $sql = "SELECT acc.`id_acc`, acc.`usuario_id`, acc.`fecha_apertura`, acc.`monto_apertura`, acc.`fecha_cierre`, acc.`monto_cierre`, 
                    acc.`estado_acc`, usu.`firstname`, usu.`lastname`, usu.`user_name`
                    FROM `apertura_cierre_caja` as acc
                    JOIN `users` as usu ON (acc.`usuario_id` = usu.`user_id`)
                    WHERE acc.`fecha_apertura` >= '".$fecha_ini."' AND acc.`fecha_apertura` <= '".$fecha_fin."'";
    } else {
        $sql = "SELECT acc.`id_acc`, acc.`usuario_id`, acc.`fecha_apertura`, acc.`monto_apertura`, acc.`fecha_cierre`, acc.`monto_cierre`, 
                    acc.`estado_acc`, usu.`firstname`, usu.`lastname`, usu.`user_name`
                    FROM `apertura_cierre_caja` as acc
                    JOIN `users` as usu ON (acc.`usuario_id` = usu.`user_id`)
                    WHERE acc.`estado_acc` = ".$estado." AND acc.`fecha_apertura` >= '".$fecha_ini."' 
                    AND acc.`fecha_apertura` <= '".$fecha_fin."'";
    }    

    $acc = array();

    $query = mysqli_query($con, $sql);

    $count=mysqli_num_rows($query);

    if ($estado == 'all') {
        $tipo_detalle = "Todos";
    } else if ($estado == 0) {
        $tipo_detalle = "Abierta";
    } else if ($estado == 1) {
        $tipo_detalle = "Cerrada";
    } else if ($estado == 2) {
        $tipo_detalle = "Depositada";
    }

    if ($fecha_ini == '2018-01-01 01:00:00' && $fecha_fin == '3000-01-01 23:59:59') {
        $fecha_consulta = "Todas";
    } else {
        $fecha_consulta = " Inicio: " .$fecha_ini. " Fin: " .$fecha_fin;
    }
    
    if ($estado == 2) {
        while ($row = mysqli_fetch_array($query)) {
            $usuario = $row['firstname']. ' '.$row['lastname'];
            $fecha_cierre = date("d/m/Y", strtotime($row['fecha_cierre']));
            $monto_cierre = number_format($row['monto_cierre'],2);
            $acc_item = array(
                "usuario" => $usuario,
                "fecha_cierre" => $fecha_cierre,
                "monto_cierre" => $monto_cierre,
                "deposito" => consultarDepositos($con, $row['id_acc']),
            );
            array_push($acc,$acc_item);
        }
    } else {
        while ($row = mysqli_fetch_array($query)) {
            $usuario = $row['firstname']. ' '.$row['lastname'];
            $fecha_apertura = date("d/m/Y", strtotime($row['fecha_apertura']));
            $monto_apertura = number_format($row['monto_apertura'],2);   
            $fecha_cierre = date("d/m/Y", strtotime($row['fecha_cierre']));
            $monto_cierre = number_format($row['monto_cierre'],2);          
            if ($row['estado_acc'] == 0) {
                $est = "Abierta";
            } else if ($row['estado_acc'] == 1) {
                $est = "Cerrada";
            } else if ($row['estado_acc'] == 2) {
                $est = "Depositada";
            }
            $acc_item = array(
                "usuario" => $usuario,
                "fecha_apertura" => $fecha_apertura,
                "monto_apertura" => $monto_apertura,
                "fecha_cierre" => $fecha_cierre,
                "monto_cierre" => $monto_cierre,
                "estado" => $est
            );
            array_push($acc,$acc_item);
        }
    }  


	if ($count==0)



	{



	echo "<script>alert('No existe registros con las opciones seleccionadas')</script>";



	echo "<script>window.close();</script>";



	exit;



	}



	

	require_once(dirname(__FILE__).'/../html2pdf.class.php');



    // get the HTML



     ob_start();



     include(dirname('__FILE__').'/res/ver_acc_html.php');



    $content = ob_get_clean();







    try



    {



        // init HTML2PDF



        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));



        // display the full page



        $html2pdf->pdf->SetDisplayMode('fullpage');



        // convert



        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));



        // send the PDF



        $html2pdf->Output('Factura.pdf');



    }



    catch(HTML2PDF_exception $e) {



        echo $e;



        exit;



    }

    function consultarDepositos($conexion, $acc) {
        $sql = "SELECT `fecha_dep`, `monto_dep`, `banco_dep`, `referencia_dep` FROM `depositos` WHERE `acc_id` =  ".$acc;
        $query = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_array($query);
        $fecha_deposito = date("d/m/Y", strtotime($row['fecha_dep']));
        $monto_dep = number_format($row['monto_dep'],2); 
        $resp = array(
            "fecha_dep" => $fecha_deposito,
            "monto_dep" => $monto_dep,
            "banco_dep" => $row['banco_dep'],
            "referencia_dep" => $row['referencia_dep']
        );
        return $resp;
    }

?>





