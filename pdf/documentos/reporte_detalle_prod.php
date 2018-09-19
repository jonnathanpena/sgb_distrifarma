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
    
    $sql_count=mysqli_query($con,"SELECT (select us.user_name from users us where us.user_id = fac.id_vendedor) as vendedor
    ,date(fac.fecha_factura) fechafac, pr.nombre_producto, sum(det.cantidad),sum(det.precio_venta)
    FROM facturas fac, detalle_factura det , products pr
    where det.numero_factura = fac.numero_factura
    and det.id_producto = pr.id_producto
    AND fac.fecha_factura >= '".$fecha_ini." 00:00:00' 
    AND fac.fecha_factura <= '".$fecha_fin." 23:59:59'
    group by vendedor, fechafac, pr.nombre_producto
    order by vendedor");
	$count=mysqli_num_rows($sql_count);

	if ($count==0)

	{

	echo "<script>alert('No existen registros para la Fecha Seleccionada')</script>";

	echo "<script>window.close();</script>";

	exit;

	}

	
	require_once(dirname(__FILE__).'/../html2pdf.class.php');

    // get the HTML

     ob_start();

     include(dirname('__FILE__').'/res/ver_productos_detalle.php');

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

        $html2pdf->Output('Kardex.pdf');

    }

    catch(HTML2PDF_exception $e) {

        echo $e;

        exit;

    }
?>


