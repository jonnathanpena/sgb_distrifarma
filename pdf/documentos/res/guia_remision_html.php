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
            <th style="width: 100%;text-align:center" class='midnight-blue' colspan="5">DETALLE GUÍA REMISIÓN <?php echo $data['df_codigo_rem']?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>FECHA: <?php echo $data['df_fecha_remision']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VENDEDOR: <?php echo $data['personal']['df_nombre_per']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>ZONA: <?php echo $data['sector']['df_nombre_zona']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>CANT PRODUCTOS: <?php echo $data['df_cant_total_producto_rem']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VALOR EFECTIVO: $<?php echo $data['df_valor_efectivo_rem']; ?></th>
        </tr>
    </table>    
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 15%;text-align:center" class='midnight-blue'>CÓDIGO.</th>
            <th style="width: 50%;text-align:center" class='midnight-blue'>PRODUCTO</th>
            <th style="width: 10%;text-align:center" class='midnight-blue'>UNIDAD.</th>
            <th style="width: 10%;text-align:center" class='midnight-blue'>CANTIDAD</th>
            <th style="width: 15%;text-align:right" class='midnight-blue'>TOTAL</th>
        </tr>

        <?php
            for ($i = 0; $i < count($data['detalles']); $i++) {
                $detalle = $data['detalles'][$i];
        ?>

        <tr>
            <td style="width: 15%; text-align: center"><?php echo $detalle['df_codigo_prod']?></td>
            <td style="width: 50%; text-align: center"><?php echo $detalle['df_nombre_producto']?></td>
            <td style="width: 10%; text-align: center"><?php echo $detalle['df_nombre_und_detrem']?></td>
            <td style="width: 10%; text-align: center"><?php echo $detalle['df_cant_producto_detrem']?></td>
            <td style="width: 15%; text-align: right">$<?php echo $detalle['df_valor_total_detrem']?></td>
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