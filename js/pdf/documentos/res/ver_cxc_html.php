<style type="text/css">



<!--

table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
.midnight-blue{
	background:#2c3e50;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size:12px;
}
.silver{
	background:white;
	padding: 3px 60px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 50%; text-align: left">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 50%; text-align: right">
                    &copy; <?php echo "www.proconty.com | "; echo  $anio=date('Y'); ?>
                </td>
            </tr>
        </table>
    </page_footer>

	<?php include("encabezado_factura.php");?>

    <br>   

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 100%;text-align:center" class='midnight-blue'>
                DETALLE CXC Estado: <?php echo $tipo_detalle; ?>, Fecha: <?php echo $fecha_consulta; ?>
            </th>
        </tr>
    </table>   

    <table cellspacing="0" style="width: 100%; font-size: 9pt;">
        <tr>            
            <th width="15%" style="text-align: left; padding: 3px 3px 3px;">Doc Cliente</th>
            <th width="25%" style="text-align: left; padding: 3px 3px 3px;">Nombre Cliente</th>
            <th width="10%" style="text-align: left; padding: 3px 3px 3px;">Nº Factura</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">Fecha</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">Estado</th>
            <th width="10%" style="text-align: right; padding: 3px 3px 3px;">Venta</th>
            <th width="10%" style="text-align: right; padding: 3px 3px 3px;">Abonos</th>
            <th width="10%" style="text-align: right; padding: 3px 3px 3px;">Resta</th>
        </tr>  

    <?php

    for ($i = 0; $i < count($cxc); $i++) {

    ?>   
        <tr>            
            <td width="15%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $cxc[$i]['documento_cliente']; ?></td>
            <td width="25%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $cxc[$i]['nombre_cliente']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $cxc[$i]['numero_factura']; ?></td>
            <td width="10%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $cxc[$i]['fecha_factura']; ?></td>
            <td width="10%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $cxc[$i]['estado_cxc']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $cxc[$i]['total_venta']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $cxc[$i]['abonos']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $cxc[$i]['resta']; ?></td>
        </tr>  
    <?php
    }
    ?>
    </table>

	<br>
    <br>
    <br>
    <br>

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th class='silver' style="width: 50%; text-align: left; font-size:11pt">Jefe de Contrato</th>
            <th class='silver' style="width: 40%; text-align: right; font-size:11pt">Recibe</th>
        </tr>
    </table>

</page>