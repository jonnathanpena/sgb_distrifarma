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
        
        $sql=mysqli_query($con, "SELECT loginv.`fecha_loginv`, loginv.`producto_loginv`, prod.`codigo_producto`, 
            prod.`nombre_producto`, loginv.`cantidad_loginv`, loginv.`tipo_loginv`, loginv.`motivo` 
            FROM `log_inventario` as loginv
            JOIN `products` as prod ON (loginv.`producto_loginv` = prod.`id_producto`)
            where loginv.`fecha_loginv` >= '".$fecha_ini."'
            and loginv.`fecha_loginv` <= '".$fecha_fin."'");
    
    ?>

        <tr>
            <th class='clouds' style=" text-align: center">Fecha</th>
            <th class='clouds' style="text-align: center">COD Producto</th>
            <th class='clouds' style=" text-align: center">Nombre Producto</th>
            <th class='clouds' style=" text-align: center">Movimiento</th>
            <th class='clouds' style="text-align: center">Cantidad</th>
        </tr>

    <?php
    
    
    
    while ($row=mysqli_fetch_array($sql))

	{

    ?>

        <tr>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['fecha_loginv'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['codigo_producto'];?></td>
            <td class='gold' style="width: 40%; text-align: center"><?php echo $row['nombre_producto'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['tipo_loginv'];?></td>
            <td class='gold' style="width: 15%; text-align: center">
                <?php 
                    if($row['tipo_loginv'] == 'Descuento' || $row['tipo_loginv'] == 'Compra'){
                        echo "-".$row['cantidad_loginv'];
                    }else if($row['tipo_loginv'] == 'Incremento'){
                        echo "+".$row['cantidad_loginv'];
                    }
                ?>
            </td>
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