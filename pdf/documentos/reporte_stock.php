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
    
    $sql_count=mysqli_query($con,"select p.codigo_producto, p.nombre_producto, i.cantidad_inventario, (select max(l.fecha_loginv) from log_inventario l where l.producto_loginv = p.id_producto) ultimo_mov
    from inventario i, products p
    where i.producto_inventario = p.id_producto
    order by p.codigo_producto");

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

     include(dirname('__FILE__').'/res/ver_reporte_stock_html.php');

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

        $html2pdf->Output('Stock.pdf');

    }

    catch(HTML2PDF_exception $e) {

        echo $e;

        exit;

    }
?>


