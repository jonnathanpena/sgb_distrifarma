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
table.page_footer {
    width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;
}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial">
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
	<table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width: 25%; color: #444444;">
                <img style="width: 100%;" src="../../img/logo.jpg" alt="Logo"><br>
            </td>
            <td style="width: 50%; color: #34495e;font-size:12px;text-align:center">
                <span style="color: #34495e;font-size:14px;font-weight:bold">DISTRIFARMA</span>
                <br>Jipijapa No. 44 y Río Coca, Quito Pichinca<br>
                Teléfono: +(593) 99 059-6002<br>
                Email: distrifarma@hotmail.com
            </td>
            <td style="width: 25%;text-align:right">
            </td>
        </tr>
    </table>
    <br/>  
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 100%;text-align:center" class='midnight-blue' colspan="5">DETALLE GUÍA RECEPCIÓN <?php echo $data['df_codigo_guia_rec']?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>FECHA: <?php echo $data['df_fecha_recepcion']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>PERSONAL: <?php echo $data['personal']['df_nombre_per']."  ".$data['personal']['df_apellido_per']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VALOR RECAUDADO: $<?php echo $data['df_valor_recaudado']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VALOR EN EFECTIVO: $<?php echo $data['df_valor_efectivo']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VALOR CHEQUE: $<?php echo $data['df_valor_cheque']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>RETENCIONES: $<?php echo $data['df_retenciones']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>DESCUENTO: $<?php echo $data['df_descuento_rec']; ?></th>
        </tr>
    </table>    
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 10%;text-align:center" class='midnight-blue'>FACTURA</th>
            <th style="width: 45%;text-align:center" class='midnight-blue'>CATN PRODUCTOS</th>
            <th style="width: 10%;text-align:center" class='midnight-blue'>CODIGO</th>
            <th style="width: 10%;text-align:center" class='midnight-blue'>NUEVA FECHA</th>
            <th style="width: 25%;text-align:center" class='midnight-blue'>DET REM</th>
        </tr>

        <?php
            for ($i = 0; $i < count($data['detalles']); $i++) {
                $detalle = $data['detalles'][$i];
        ?>

        <tr>
            <td style="width: 10%; text-align: center"><?php echo $detalle['df_factura_rec']?></td>
            <td style="width: 45%; text-align: center"><?php echo $detalle['df_cant_producto_detrec']?></td>
            <td style="width: 10%; text-align: center"><?php echo $detalle['df_cant_producto_detrec']?></td>
            <td style="width: 10%; text-align: center"><?php echo $detalle['df_producto_cod_detrec']?></td>
            <td style="width: 25%; text-align: center"><?php echo $detalle['df_nueva_fecha']?></td>
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
            <th class='silver' style="width: 50%; text-align: left; font-size:11pt">Supervisor</th>
            <th class='silver' style="width: 40%; text-align: right; font-size:11pt">Recibe</th>
        </tr>
    </table>
</page>