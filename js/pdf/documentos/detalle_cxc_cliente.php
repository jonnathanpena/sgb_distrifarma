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

    $cliente_id= $_GET['cliente'];

    

    $sql_cliente="SELECT * FROM `clientes` WHERE `id_cliente` = ".$cliente_id;
    $query_cliente = mysqli_query($con, $sql_cliente);
    $cliente = mysqli_fetch_array($query_cliente);

    $sql_cxc = mysqli_query($con, "SELECT cxc.`id_cxc`, cxc.`factura_id`, cxc.`estado_cxc`, fac.`numero_factura`, 
                fac.`fecha_factura`, fac.`total_venta`
                FROM `cxc` as cxc 
                JOIN `facturas` as fac ON (fac.`id_factura` = cxc.`factura_id`)
                WHERE (cxc.estado_cxc = 0 OR cxc.estado_cxc = 1) 
                AND fac.`id_cliente` = ".$cliente_id);

    $count=mysqli_num_rows($sql_cxc);
    $cxc = array();

    while($row=mysqli_fetch_array($sql_cxc)) {
        $cxc_item = array(
            "id_cxc" => $row['id_cxc'],
            "factura_id" => $row['factura_id'],
            "estado_cxc" => $row['estado_cxc'],
            "numero_factura" => $row['numero_factura'],
            "fecha_factura" => $row['fecha_factura'],
            "total_venta" => $row['total_venta'],            
            "total_abonos" => consultarTotalAbonos($con, $row['id_cxc']),
            "resta" => $row['total_venta'] - consultarTotalAbonos($con, $row['id_cxc']),
            "detalles" => detallarFactura($con, $row['numero_factura'])            
        );
        array_push($cxc, $cxc_item);
    }

	if ($count==0)



	{



	echo "<script>alert('No existen créditos para el cliente seleccionado')</script>";



	echo "<script>window.close();</script>";



	exit;



    }

	require_once(dirname(__FILE__).'/../html2pdf.class.php');



    // get the HTML



     ob_start();



     include(dirname('__FILE__').'/res/ver_detalle_cxc_cliente_html.php');



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



        $html2pdf->Output('Detalle_CXC.pdf');



    }



    catch(HTML2PDF_exception $e) {



        echo $e;



        exit;



    }

    function detallarFactura($conexion, $numero_factura) {
        $sql = "SELECT df.`id_detalle`, df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                    prod.`codigo_producto`, prod.`nombre_producto`
                    FROM `detalle_factura` as df
                    JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                    WHERE df.`numero_factura` = ".$numero_factura;
        $query = mysqli_query($conexion, $sql);
        $arreglo_return = array();
        while ($row = mysqli_fetch_array($query)) {
            $item = array(
                "id_detalle" => $row['id_detalle'],
                "numero_factura" => $row['numero_factura'],
                "id_producto" => $row['id_producto'],
                "cantidad" => $row['cantidad'],
                "precio_venta" => $row['precio_venta'],
                "codigo_producto" => $row['codigo_producto'],
                "nombre_producto" => $row['nombre_producto']
            );
            array_push($arreglo_return, $item);
        }
        return $arreglo_return;
    }

    function consultarTotalAbonos($conexion, $cxc) {
        $sql = "SELECT SUM(`monto_abonos`) as total_abonos FROM `abonos` WHERE `cxc_id` = ".$cxc;
        $query = mysqli_query($conexion, $sql);
        $row = mysqli_fetch_array($query);
        return $row['total_abonos'];
    }

?>





