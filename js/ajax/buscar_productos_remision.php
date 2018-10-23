
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
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Agregar</th>
      </tr>
<?php
      for ($i =0 ; $i < count($productos); $i++){
            $id_producto = $productos[$i]["df_id_producto"];
            $nombre = $productos[$i]["df_nombre_producto"];
            $codigo = $productos[$i]["df_codigo_prod"];
            $cantidad = 1;
         	?>

      <input type="hidden" value="<?php echo $id_producto;?>" id="id_producto<?php echo $codigo;?>">

      <tr>
            <td><?php echo $codigo; ?></td>
            <td ><?php echo $nombre; ?></td>
            <td ><input type="number" class="form-control" id="cantidad_<?php echo $codigo;?>" value="1" ></td>
            <td ><span class="pull-right">
                  <button class='btn btn-info' title='Agregar' onclick="agregar('<?php echo $codigo;?>', '<?php echo $nombre;?>', '<?php echo $id_producto;?>');">
                        <i class="fa fa-plus"></i>
                  </button>
            </td>
      </tr>

<?php

      }
?>

      <tr>
            <td colspan=4><span class="pull-right">
                  <?php
                  //echo paginate($reload, $page, $total_pages, $adjacents);
                  ?></span>
            </td>
      </tr>
</table>
</div>
<?php
      }
?>



