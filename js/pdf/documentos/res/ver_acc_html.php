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
                DETALLE APERTURA/CIERRE CAJA Estado: <?php echo $tipo_detalle; ?>, Fecha: <?php echo $fecha_consulta; ?>
            </th>
        </tr>
    </table>   

<?php

    if($estado == 2){
?>

    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>            
            <th width="25%" style="text-align: left; padding: 3px 3px 3px;">Usuario</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">F. Cierre</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">$ Cierre</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">F. Depósito</th>
            <th width="25%" style="text-align: left; padding: 3px 3px 3px;">Banco</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">$</th>
            <th width="10%" style="text-align: center; padding: 3px 3px 3px;">Referencia</th>
        </tr>  

    <?php

    for ($i = 0; $i < count($acc); $i++) {

    ?>   
        <tr>            
            <td width="25%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $acc[$i]['usuario']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['fecha_cierre']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['monto_cierre']; ?></td>5
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['deposito']['fecha_dep']; ?></td>
            <td width="25%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $acc[$i]['deposito']['monto_dep']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['deposito']['banco_dep']; ?></td>
            <td width="10%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['deposito']['referencia_dep']; ?></td>
        </tr>  
    <?php
    }
    ?>
    </table>

<?php

    } else {

?>

    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>            
            <th width="25%" style="text-align: left; padding: 3px 3px 3px;">Usuario</th>
            <th width="15%" style="text-align: center; padding: 3px 3px 3px;">F. Apertura</th>
            <th width="15%" style="text-align: center; padding: 3px 3px 3px;">$ Apertura</th>
            <th width="15%" style="text-align: center; padding: 3px 3px 3px;">F. Cierre</th>
            <th width="15%" style="text-align: center; padding: 3px 3px 3px;">$ Cierre</th>
            <th width="15%" style="text-align: center; padding: 3px 3px 3px;">Estado</th>
        </tr>  

    <?php

    for ($i = 0; $i < count($acc); $i++) {

    ?>   
        <tr>            
            <td width="25%" style="text-align: left; padding: 3px 3px 3px;"><?php echo $acc[$i]['usuario']; ?></td>
            <td width="15%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['fecha_apertura']; ?></td>
            <td width="15%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['monto_apertura']; ?></td>5
            <td width="15%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['fecha_cierre']; ?></td>
            <td width="15%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['monto_cierre']; ?></td>
            <td width="15%" style="text-align: center; padding: 3px 3px 3px;"><?php echo $acc[$i]['estado']; ?></td>
        </tr>  
    <?php
    }
    ?>
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