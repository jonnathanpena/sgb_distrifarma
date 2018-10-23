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

.gold{

background:white;

padding: 3px 4px 3px;

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

}

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
            <th style="width: 100%;text-align:center" class='midnight-blue'>REPORTE MOVIMIENTOS KARDEX</th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>DESDE: <?php echo $fecha_ini; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>HASTA: <?php echo $fecha_fin; ?></th>
        </tr>
    </table>    

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">
        <?php
        $vendedor = '';
        
        $sql=mysqli_query($con, "SELECT (select us.user_name from users us where us.user_id = fac.id_vendedor) as vendedor
            ,date(fac.fecha_factura) fechafac, pr.nombre_producto, sum(det.cantidad) as cantidad
            ,sum(det.cantidad * det.precio_venta) as precio_venta,
				(SELECT sum(det2.cantidad * det2.precio_venta) 
				FROM facturas fac2, detalle_factura det2
				where det2.numero_factura = fac2.numero_factura
				and fac2.fecha_factura >= '".$fecha_ini." 00:00:00'
				and fac2.fecha_factura <= '".$fecha_fin." 23:59:59'
				and fac2.id_vendedor = fac.id_vendedor) as total_venta
            FROM facturas fac, detalle_factura det , products pr
            where det.numero_factura = fac.numero_factura
            and det.id_producto = pr.id_producto
            and fac.fecha_factura >= '".$fecha_ini." 00:00:00'
            and fac.fecha_factura <= '".$fecha_fin." 23:59:59'
            group by vendedor, fechafac, pr.nombre_producto
            order by vendedor
            ");
    
    ?>

        <tr>
            <th class='clouds' style=" text-align: center">Fecha</th>
            <th class='clouds' style=" text-align: center">Nombre Producto</th>
            <th class='clouds' style=" text-align: center">Cantidad</th>
            <th class='clouds' style="text-align: center">Total</th>
        </tr>

    <?php
    
    
    $vendedor_while = '';
    while ($row=mysqli_fetch_array($sql))

	{
        if ($vendedor_while != $row["vendedor"] ){
            $vendedor=$row["user_name"];
            

        ?>
                <tr>
                <td class='gold' style="width: 25%; text-align: left"><b>Vendedor: <?php echo $row['vendedor'];?></b></td>
                </tr>
				<tr>
                <td class='gold' style="width: 25%; text-align: left"><b>Total Venta: $<?php echo  number_format($row['total_venta'],2);?></b></td>
                </tr>
                
        <?php
        }
        ?>
        <tr>
            <td class='gold' style="width: 25%; text-align: center"><?php echo $row['fechafac'];?></td>
            <td class='gold' style="width: 45%; text-align: left"><?php echo $row['nombre_producto'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['cantidad'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo number_format($row['precio_venta'],2);?></td>
        </tr>

    <?php
    $vendedor_while = $row["vendedor"];
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