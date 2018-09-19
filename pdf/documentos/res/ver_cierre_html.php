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
            <th style="width: 100%;text-align:center" class='midnight-blue'>DETALLE DE CIERRE DE CAJA</th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>DESDE: <?php echo $fecha_ini; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>HASTA: <?php echo $fecha_fin; ?></th>
        </tr>

    </table>    

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <?php
        $vendedor = '';
        
        $sql=mysqli_query($con, "select usr.user_name, fac.condiciones, sum(fac.total_venta) as total_venta,
            (select sum(ca.gasto_total_cc) from caja_chica ca 
            where ca.user_cc = usr.user_id
            and ca.fecha_cc >= '".$fecha_ini." 00:00:00'
            and ca.fecha_cc <= '".$fecha_fin." 23:59:59'    
            ) as cajachica,
            (select sum(ca.gasto_total_cc) from caja_chica ca 
            where ca.fecha_cc >= '".$fecha_ini." 00:00:00'
            and ca.fecha_cc <= '".$fecha_fin." 23:59:59'    
            ) as totalcc
        from facturas fac, users usr
        where usr.user_id = fac.id_vendedor
        and fac.fecha_factura >= '".$fecha_ini." 00:00:00'
        and fac.fecha_factura <= '".$fecha_fin." 23:59:59'
        GROUP by usr.user_name, fac.condiciones");


    $total = 0;
    $i = 0;
    $total_vendedor = 0;
while ($row=mysqli_fetch_array($sql))

	{
        $compras_ini=$row["cajachica"]*-1; 
        $totalcc=$row["totalcc"]*-1; 
        if ($vendedor != $row["user_name"] ){
            $vendedor=$row["user_name"];
            if($i == 0){

        ?>
                <tr>
                <th colspan="2" class='clouds' style="width: 99.9%; text-align: left">Vendedor: <?php echo $vendedor; ?></th>
                <th style="width: 0.1%; text-align: left"></th>
                </tr>
                <tr>
                <th class='silver' style="width: 40%; text-align: left">Compras:</th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo number_format($compras_ini,2); ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr> 
                
        <?php
            }else{

        ?>
        
    
                <tr>
                <th class='silver' style="width: 40%; text-align: left">Total Venta Vendendor2:</th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo number_format($total_vendedor+$compras,2); ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>

                <tr>
                <th colspan="2" class='clouds' style="width: 99.9%; text-align: left">Vendedor: <?php echo $vendedor; ?></th>
                <th style="width: 0.1%; text-align: left"></th>
                </tr>
                <tr>
                <th class='silver' style="width: 40%; text-align: left">Compras:</th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo number_format($compras_ini,2); ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr> 
        
        <?php
                $total_vendedor = 0;
            }
        }

        if ($row["condiciones"] == 1){
            $ventas_efectivo=$row["total_venta"];
            $total += $ventas_efectivo;
            $total_vendedor += $ventas_efectivo;
            $ventas_efectivo=number_format($ventas_efectivo,2);            
        ?>
                <tr>
                <th class='silver' style="width: 40%; text-align: left;">Efectivo: </th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo $ventas_efectivo; ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>
        <?php
        }
        
        if ($row["condiciones"] == 2){
            $ventas_transferencia=$row["total_venta"];
            $total += $ventas_transferencia;
            $total_vendedor += $ventas_transferencia;
            $ventas_transferencia=number_format($ventas_transferencia,2);            
        ?>
                <tr>
                <th class='silver' style="width: 40%; text-align: left">Transferencias: </th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo $ventas_transferencia; ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>
        <?php
        }

        if ($row["condiciones"] == 3){
            $ventas_tarjeta=$row["total_venta"];
            $total += $ventas_tarjeta;
            $total_vendedor += $ventas_tarjeta;
            $ventas_tarjeta=number_format($ventas_tarjeta,2);            
        ?>
                <tr>
                <th class='silver' style="width: 40%; text-align: left">TarjetasPrep: </th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo $ventas_tarjeta; ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>                
        <?php
        }

        ?>                

        <?php
        $i++;
        $compras=$row["cajachica"]*-1;       
	}

        //$total=number_format($total,2);

    ?>

                <tr>
                <th class='silver' style="width: 40%; text-align: left">Total Venta Vendendor:</th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo number_format($total_vendedor+$compras_ini,2); ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>

                <tr style="margin-top: 15%;">
                <th colspan="2" class='midnight-blue' style="width: 99.9%; text-align: left"></th>
                <th style="width: 0.1%; text-align: left"></th>
                </tr>

                <tr>
                <th class='silver' style="width: 40%; text-align: left">Total Venta General: </th>
                <th class='silver' style="width: 10%; text-align: right;">$<?php echo number_format($total+$totalcc,2); ?></th>
                <th class='silver' style="width: 50%; text-align: left;"></th>
                </tr>
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