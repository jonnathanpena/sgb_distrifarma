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
            <th style="width: 100%;text-align:center" class='midnight-blue' colspan="5">DETALLE GUÍA ENTREGA <?php echo $data['df_codigo_guia_ent']?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>FECHA: <?php echo $data['df_fecha_ent']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>VENDEDOR: <?php echo $data['personal']['df_nombre_per']."  ".$data['personal']['df_apellido_per']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>CANT PRODUCTOS UND: <?php echo $data['df_cant_total_producto_ent']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>CANT PRODUCTOS CAJA: <?php echo $data['df_cant_total_cajas_ent']; ?></th>
        </tr>
        <tr>
            <th style="width: 100%;text-align:left" class='silver'>CANT FACTURAS: <?php echo $data['df_cant_facturas_ent']; ?></th>
        </tr>
    </table>    
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 70%;text-align:left" class='midnight-blue'>PRODUCTO</th>
            <th style="width: 15%;text-align:left" class='midnight-blue'>UNIDAD</th>
            <th style="width: 15%;text-align:left" class='midnight-blue'>CANTIDAD</th>
        </tr>

        <?php
            $contador = count($data['detalles']);
            $tabla = [];
            for ($i = 0; $i < $contador ; $i++) {
                $detalle = $data['detalles'][$i];
                $conseguido = false;
                for ($j = 0; $j < count($tabla); $j++) {
                    if ($tabla[$j]['df_nom_producto_detent'] == $detalle['df_nom_producto_detent'] && $tabla[$j]['df_unidad_detent'] == $detalle['df_unidad_detent']) {
                        $conseguido = true;
                        $cantidad = $tabla[$j]['df_cant_producto_detent'] * 1;
                        $cantidad_nueva = $detalle['df_cant_producto_detent'] * 1;
                        $cantidad = $cantidad + $cantidad_nueva;
                        $tabla[$j]['df_cant_producto_detent'] = $cantidad;
                    } 
                }
                if ($conseguido == false) {
                    array_push($tabla, $detalle);
                }
            }

            for ($i = 0; $i < count($tabla); $i++) {
                $detalle = $tabla[$i];
            
        ?>

        <tr>
            <td style="width: 70%; text-align: left"><?php echo $detalle['df_nom_producto_detent']?></td>
            <td style="width: 15%; text-align: left"><?php echo $detalle['df_unidad_detent']?></td>
            <td style="width: 15%; text-align: left"><?php echo $detalle['df_cant_producto_detent']?></td>
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