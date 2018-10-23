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
    
    $sql_count=mysqli_query($con,"SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, 
    fac.`id_cliente`, cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
    FROM `facturas` as fac
    JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
    WHERE fac.`fecha_factura` >= '".$fecha_ini." 00:00:00' 
    AND fac.`fecha_factura` <= '".$fecha_fin." 23:59:59'");
	$count=mysqli_num_rows($sql_count);

	if ($count==0)

	{

	echo "<script>alert('No existe registros para la Fecha Seleccionada')</script>";

	echo "<script>window.close();</script>";

	exit;

	}

	
	require_once(dirname(__FILE__).'/../html2pdf.class.php');

    // get the HTML

     ob_start();

     include(dirname('__FILE__').'/res/ver_cierre_html.php');

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
?>


