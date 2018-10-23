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
                DETALLE CXC <?php echo $cliente['nombre_cliente']; ?>
            </th>
        </tr>
    </table>    

    <?php

    for ($i = 0; $i < count($cxc); $i++) {

    ?>
    
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th class='silver' style="width: 33.3%; text-align: left">Fecha</th>
            <th class='silver' style="width: 33.3%; text-align: left">Nº Factura</th>
            <th class='silver' style="width: 33.3%; text-align: right;">Total Compras ($)</th>
        </tr> 
    
    <?php

        $num_factura = $cxc[$i]['numero_factura'];
        $fecha_factura = date("d/m/Y", strtotime($cxc[$i]['fecha_factura']));
        $total_venta = number_format($cxc[$i]['total_venta'],2);

    ?>

        <tr>
            <td style="width: 33.3%; text-align: center"><?php echo $fecha_factura; ?></td>
            <td style="width: 33.3%; text-align: center"><?php echo $num_factura; ?></td>
            <td style="width: 33.3%; text-align: center"><?php echo $total_venta; ?></td>
        </tr> 
    </table>

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th class='clouds' style="width: 20%; text-align: left">Código Producto</th>
            <th class='clouds' style="width: 40%; text-align: left">Nombre Producto</th>
            <th class='clouds' style="width: 20%; text-align: center;">Cantidad</th>
            <th class='clouds' style="width: 20%; text-align: right;">Precio Venta</th>
        </tr> 
    
    <?php
        for ($j = 0; $j < count($cxc[$i]['detalles']); $j++) {
    ?>

        <tr>
            <td style="width: 20%; text-align: left"><?php echo $cxc[$i]['detalles'][$j]['codigo_producto']; ?></td>
            <td style="width: 40%; text-align: left"><?php echo $cxc[$i]['detalles'][$j]['nombre_producto']; ?></td>
            <td style="width: 20%; text-align: center"><?php echo $cxc[$i]['detalles'][$j]['cantidad']; ?></td>
            <td style="width: 20%; text-align: right"><?php echo $cxc[$i]['detalles'][$j]['precio_venta']; ?></td>
        </tr> 
    
        <?php
        }
        ?>

        <tr>
            <th colspan="3" style="width: 80%; text-align: right" class="silver">Total Venta</th>
            <th style="width: 20%; text-align: right" class="silver">$<?php echo $cxc[$i]['total_venta']; ?></th>
        </tr> 
        <tr>
            <th colspan="3" style="width: 80%; text-align: right" class='silver'>Total Abonado Factura</th>
            <th style="width: 20%; text-align: right" class="silver">-$<?php echo $cxc[$i]['total_abonos']; ?></th>
        </tr> 
        <tr>
            <th colspan="3" style="width: 80%; text-align: right" class='silver'>Resta Factura</th>
            <th style="width: 20%; text-align: right" class="silver">$<?php echo $cxc[$i]['resta']; ?></th>
        </tr> 

    </table>
    
    <?php
    }
    ?>

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