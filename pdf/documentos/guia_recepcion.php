<?php
    $data = json_decode($_POST['data'], true);
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
    ob_start();
    include(dirname('__FILE__').'/res/guia_recepcion_html.php');
    $content = ob_get_clean();
    try
    {
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('guia_recepcion.pdf');
    } catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>