

<?php
   include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
   
   $session_id= session_id();
   
   if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}
   
   if (isset($_POST['precio_unitario'])){$precio_unitario=$_POST['precio_unitario'];}

   if (isset($_POST['producto'])){$producto=$_POST['producto'];}
   
   
   
   	/* Connect To Database*/
   
   	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
   
   	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
   
   	//Archivo de funciones PHP
   
   	include("../funciones.php");

   if (!empty($cantidad) and !empty($precio_unitario) and !empty($producto))
   
   { 
        $insert_tmp=mysqli_query($con, "INSERT INTO `tmp_caja_chica`(`producto__tempcc`, `cantidad__tempcc`, 
            `precio_unitario__tempcc`, `sesion__tempcc`) VALUES (
                '$producto',
                $cantidad,
                $precio_unitario,
                '$session_id'
            )");
   }
   
   if (isset($_GET['id']))//codigo elimina un elemento del array
   {
   
		$id_tmp=intval($_GET['id']);	
		
        $delete=mysqli_query($con, "DELETE FROM `tmp_caja_chica` WHERE `id_tempcc` = ".$id_tmp);
        
   }
   
   $simbolo_moneda=get_row('perfil','moneda', 'id_perfil', 1);
   
   ?>
<table class="table">
   <tr>
      <th class='text-center'>PRODUCTO</th>
      <th class='text-center'>CANT.</th>
      <th class='text-right'>PRECIO UNIT.</th>
      <th class='text-right'>PRECIO TOTAL</th>
      <th></th>
   </tr>
   <?php
      $sumador_total=0;
      
      $sql=mysqli_query($con, "SELECT * FROM `tmp_caja_chica` WHERE `sesion__tempcc` = '".$session_id."'");
      
      $i = 0;
      
      while ($row=mysqli_fetch_array($sql))
      
      {
      
      $id_tmp=$row["id_tempcc"];
      
      $producto=$row['producto__tempcc'];
      
      $cantidad=$row['cantidad__tempcc'];
      
      $precio_venta=$row['precio_unitario__tempcc'];
      
      $precio_venta_f=number_format($precio_venta,2);//Formateo variables
      
      $precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
      
      $precio_total=$precio_venta_r*$cantidad;
      
      $precio_total_f=number_format($precio_total,2);//Precio total formateado
      
      $precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
      
      $sumador_total+=$precio_total_r;//Sumador
      
      $i++;
      
      	?>
   <tr>
      <td class='text-center'><?php echo $producto;?></td>
      <td class='text-center'><?php echo $cantidad;?></td>
      <td class='text-right'><?php echo $precio_venta_f;?></td>
      <td class='text-right'><?php echo $precio_total_f;?></td>
      <td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
   </tr>
   <?php
      }
      $total_compra = $sumador_total;
      $cantidad_productos = $i;
    ?>
   <tr>
      <td class='text-right' colspan=4>TOTAL <?php echo $simbolo_moneda;?></td>
      <td class='text-right'><?php echo number_format($total_compra,2);?></td>
      <input type="hidden" id="total_compra" value="<?php echo number_format($total_compra,2);?>">
      <input type="hidden" id="cantidad_productos" value="<?php echo $cantidad_productos;?>">
      <td></td>
   </tr>
</table>
<div class="col-md-2">
   <script>
      var total_compra = $("#total_compra").val();
      localStorage.setItem("total_compra", total_compra);
      var cantidad_productos =  $("#cantidad_productos").val();
      localStorage.setItem("cantidad_productos", cantidad_productos); 
   </script>
</div>

