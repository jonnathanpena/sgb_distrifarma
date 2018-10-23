
<?php
   include 'pagination.php'; //include pagination file
   $page = 1; 
   $per_page = 10; //how much records you want to show   
   $adjacents  = 4; //gap between pages after number of adjacents
   $offset = ($page - 1) * $per_page;  
   $productos = $_POST["data"];
   $numrows = count($productos); 
   $total_pages = ceil($numrows/$per_page);
   $reload = './productos.php';
   if ($numrows>0){
   	$simbolo_moneda="$";  
?>

<div class="table-responsive">
   <table class="table">
      <tr  class="info">
            <th width="80">Código</th>
            <th>Producto</th>
            <th>Unidad</th>
            <th width="80">Cantidad</th>
            <th>Precio Unitario</th>
            <th width="10">Agregar</th>
      </tr>
<?php
      for ($i =0 ; $i < count($productos); $i++){
            $id_producto = $productos[$i]["df_id_producto"];
            $nombre = $productos[$i]["df_nombre_producto"];
            $codigo = $productos[$i]["df_codigo_prod"];
            $id_precio = $productos[$i]["df_id_precio"];
            $ppp = $productos[$i]["df_ppp"] * 1;
            $pvt1 = $productos[$i]["df_pvt1"] * 1;
            $pvt2 = $productos[$i]["df_pvt2"] * 1;
            $pvp = $productos[$i]["df_pvp"] * 1;
            $id_iva = $productos[$i]["df_iva"];
            $iva = $productos[$i]["df_valor_impuesto"]/100;
            $und_caja = $productos[$i]["df_und_caja"];
            $cantidad = 1;
         	?>

      <input type="hidden" value="<?php echo $id_producto;?>" id="id_producto<?php echo $codigo;?>">
      <input type="hidden" value="<?php echo $id_precio;?>" id="id_precio<?php echo $codigo;?>">
      <input type="hidden" value="<?php echo $id_iva;?>" id="id_iva<?php echo $codigo;?>">
      <input type="hidden" value="<?php echo $und_caja;?>" id="und_caja<?php echo $codigo;?>">
      <input type="hidden" value="<?php echo $pvt1;?>" id="precio_normal<?php echo $codigo;?>">
      <input type="hidden" value="<?php echo $pvt2;?>" id="precio_descuento<?php echo $codigo;?>">

      <tr>
            <td width="80"><?php echo $codigo; ?></td>
            <td ><?php echo $nombre; ?></td>
            <td >
                  <select class="form-control" id="unidad_<?php echo $codigo;?>" onchange="seleccionaUnidad('<?php echo $codigo;?>')">
                        <option value="null">Seleccione...</option>
                        <option value="CAJA">Caja</option>
                        <option value="UND">Unidad</option>
                  </select>
            </td>
            <td width="80"><input type="number" class="form-control" id="cantidad_<?php echo $codigo;?>" value="1" ></td>            
            <td >
                  <select class="form-control" id="costo_<?php echo $codigo;?>">
                        <option value="null">Seleccione...</option>
                  </select>
            </td>
            <td width="10"><span class="pull-right">
                  <button class='btn btn-info' title='Agregar' onclick="agregar('<?php echo $codigo;?>', '<?php echo $nombre;?>', '<?php echo $id_producto;?>', '<?php echo $id_precio;?>', '<?php echo $iva;?>');">
                        <i class="fa fa-plus"></i>
                  </button>
            </td>
      </tr>

<?php

      }
?>

      <tr>
            <td colspan=6><span class="pull-right">
                  <?php
                  echo paginate($reload, $page, $total_pages, $adjacents);
                  ?></span>
            </td>
      </tr>
</table>
</div>
<?php
      }
?>



