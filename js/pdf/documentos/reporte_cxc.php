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
        $sql = "SELECT cxc.`id_cxc`, cxc.`factura_id`, cxc.`estado_cxc`, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
                    fac.`total_venta`, cli.`documento_cliente`, cli.`nombre_cliente`
                    FROM `cxc` as cxc
                    JOIN `facturas` as fac ON (cxc.`factura_id` = fac.`id_factura`)
                    JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
                    WHERE fac.`estado_factura` = 1
                    AND fac.`fecha_factura` >= '".$fecha_ini."' AND fac.`fecha_factura` <= '".$fecha_fin."'";
    } else {
        $sql = "SELECT cxc.`id_cxc`, cxc.`factura_id`, cxc.`estado_cxc`, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
                    fac.`total_venta`, cli.`documento_cliente`, cli.`nombre_cliente`
                    FROM `cxc` as cxc
                    JOIN `facturas` as fac ON (cxc.`factura_id` = fac.`id_factura`)
                    JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
                    WHERE fac.`estado_factura` = 1
                    AND cxc.`estado_cxc` = ".$estado." AND fac.`fecha_factura` >= '".$fecha_ini."' AND fac.`fecha_factura` <= '".$fecha_fin."'";
    }    

    $cxc = array();

    $query = mysqli_query($con, $sql);

    $count=mysqli_num_rows($query);

    if ($estado == 'all') {
        $tipo_detalle = "Todos";
    } else if ($estado == 0) {
        $tipo_detalle = "Pendientes de Pago";
    } else if ($estado == 1) {
        $tipo_detalle = "Abonados";
    } else if ($estado == 2) {
        $tipo_detalle = "Pagados";
    }

    if ($fecha_ini == '2018-01-01 01:00:00' && $fecha_fin == '3000-01-01 23:59:59') {
        $fecha_consulta = "Todas";
    } else {
        $fecha_consulta = " Inicio: " .$fecha_ini. " Fin: " .$fecha_fin;
    }
    
    while ($row = mysqli_fetch_array($query)) {
        $total_venta = number_format($row['total_venta'],2);
        $total_abonos = number_format(consultarAbonosCXC($con, $row['id_cxc']),2);
        $resta = number_format($total_venta - $total_abonos,2);
        $fecha = date("d/m/Y", strtotime($row['fecha_factura']));
        if ($row['estado_cxc'] == 0) {
            $est = "Pendiente Pago";
        } else if ($row['estado_cxc'] == 1) {
            $est = "Abonado";
        } else if ($row['estado_cxc'] == 2) {
            $est = "Pagado";
        }
        $cxc_item = array(
            "id_cxc" => $row['id_cxc'],
            "estado_cxc" => $est,
            "numero_factura" => $row['numero_factura'],
            "fecha_factura" => $fecha,
            "total_venta" => $total_venta,
            "documento_cliente" => $row['documento_cliente'],
            "nombre_cliente" => $row['nombre_cliente'],
            "abonos" => $total_abonos,
            "resta" => $resta
        );
        array_push($cxc,$cxc_item);
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



     include(dirname('__FILE__').'/res/ver_cxc_html.php');



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

    function consultarAbonosCXC($conexion, $cxc) {
        $sql = "SELECT SUM(`monto_abonos`) as total_abonos FROM `abonos` WHERE `cxc_id` = ".$cxc;
        $query = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_array($query);
        return $row['total_abonos'];
    }

?>





