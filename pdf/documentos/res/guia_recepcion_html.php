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
                <img style="width: 70%;" src="../../img/logo.jpg" alt="Logo"><br>
            </td>
            <td style="width: 50%; color: #34495e;font-size:12px;text-align:center;">
                <br>&nbsp;<br>
                <br>&nbsp;<br>
                <span style="color: #34495e;font-size:17px;font-weight:bold">DISTRIFARMA</span>
                <br style="font-size:10px;"><span style="font-size:14px;">Jipijapa No. 44 y Río Coca, Quito Pichincha</span><br>
                <span style="font-size:14px;">Teléfono: +(593) 99 059-6002</span><br>
                <span style="font-size:14px;">Email: distrifarma@hotmail.com</span>
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
        <?php
            $cod = substr($data['df_codigo_guia_rec'], 0,-4);
            if ($cod == 'GREM' ) {                          
        ?>
        <tr>
            <th style="width: 15%; text-align: left" class='midnight-blue'>CÓDIGO.</th>
            <th style="width: 45%; text-align: center" class='midnight-blue'>PRODUCTO</th>
            <th style="width: 10%; text-align: left" class='midnight-blue'>UNIDAD.</th>
            <th style="width: 15%; text-align: left" class='midnight-blue'>CANT VEND.</th>
            <th style="width: 15%; text-align: left" class='midnight-blue'>CANT DEV.</th>
        </tr>

        <?php
                for ($i = 0; $i < count($data['detalles']); $i++) {
                $detalle = $data['detalles'][$i];
                $detallesRemision = explode("-", $detalle['df_detalleRemision_detrec']); 
                $und = $detallesRemision[0];
                $ven = $detallesRemision[3];
                $dev = $detallesRemision[4];
            
            
        ?>

        <tr>
            <td style="width: 15%; text-align: left"><?php echo $detalle['df_codigo_prod']?></td>
            <td style="width: 45%; text-align: left"><?php echo $detalle['df_nombre_producto']?></td>
            <td style="width: 10%; text-align: left"><?php echo $und?></td>
            <td style="width: 15%; text-align: left"><?php echo $ven?></td>
            <td style="width: 15%; text-align: left"><?php echo $dev?></td>
        </tr>

        <?php
                }
            } else {                    
        ?>
        <tr>
            <th style="width: 15%;text-align: left" class='midnight-blue'>FACTURA</th>
            <th style="width: 15%;text-align: left" class='midnight-blue'>CODIGO</th>
            <th style="width: 40%;text-align: center" class='midnight-blue'>PRODUCTO</th>
            <th style="width: 15%;text-align: left" class='midnight-blue'>UND</th>
            <th style="width: 15%;text-align: left" class='midnight-blue'>CANT</th>
        </tr>
        <?php
                }   if ($data['detalles'][0][df_factura_rec] != '0') {
                    for ($i = 0; $i < count($data['detalles']); $i++) {
                    $detalle = $data['detalles'][$i];    
                        if ( $detalle['df_cant_producto_detrec'] == '0') {
                            $und = 'CAJA';
                            $cant = $detalle['df_cant_caja_detrec']; 
                        } else {
                            $und = 'UND';
                            $cant = $detalle['df_cant_producto_detrec'];
                        }
        ?>    
         <tr>
            <td style="width: 15%; text-align: left"><?php echo $detalle['df_factura_rec']?></td>
            <td style="width: 15%; text-align: left"><?php echo $detalle['df_codigo_prod']?></td>
            <td style="width: 40%; text-align: left"><?php echo $detalle['df_nombre_producto']?></td>
            <td style="width: 15%; text-align: left"><?php echo $und?></td>
            <td style="width: 15%; text-align: left"><?php echo $cant?></td>
        </tr>

        <?php
                    } 
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