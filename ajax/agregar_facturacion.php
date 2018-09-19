

<?php
   include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
   
   $session_id= session_id();
   
   if (isset($_POST['id'])){$id=$_POST['id'];}
   
   if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}
   
   if (isset($_POST['precio_venta'])){$precio_venta=$_POST['precio_venta'];}

   if (isset($_POST['aplica_descuento'])){$descuento_cliente=$_POST['aplica_descuento'];}

   if (isset($_POST['aplica_iva'])){$aplica_iva=$_POST['aplica_iva'];}
   
   
   
   	/* Connect To Database*/
   
   	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
   
   	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
   
       
    //obtiene id de producto para tarjeta
    function getidTarjetas($con){

		$sql="SELECT `id_producto` FROM `products` WHERE `nombre_producto` = 'Tarjeta Prepago'";

		$query = mysqli_query($con, $sql);

		$count=mysqli_num_rows($query);

		if($count > 0) {
			$saldo = 0;
			while($row=mysqli_fetch_array($query)){
				$id_prod_tarjeta = $row['id_producto'];
			}
			return $id_prod_tarjeta;
		}else{
			return 0;
		}
	}
    
       //Archivo de funciones PHP
   
   	include("../funciones.php");

   if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
   
   {
   	if(isset($_POST['tarjeta'])){
        $id_prod_tarjeta2=getidTarjetas($con);
        $insert_tmp=mysqli_query($con, "INSERT INTO `tmp`(`id_producto`, `cantidad_tmp`, `precio_tmp`, `tarjeta_temp`, `session_id`)  
   			VALUES ($id_prod_tarjeta2,'$cantidad','$precio_venta', 1, '$session_id')");
   	}else{
   		$insert_tmp=mysqli_query($con, "INSERT INTO `tmp`(`id_producto`, `cantidad_tmp`, `precio_tmp`, `tarjeta_temp`, `session_id`)  
   			VALUES ('$id','$cantidad','$precio_venta', 0, '$session_id')");
   	}
   
   
   }
   
   if (isset($_GET['id']))//codigo elimina un elemento del array
   {
   
		$id_tmp=intval($_GET['id']);	
		
		$delete=mysqli_query($con, "DELETE FROM tmp WHERE id_tmp='".$id_tmp."'");
   
        $descuento_cliente = $_GET['aplica_descuento'];
        
        $aplica_iva = $_GET['aplica_iva'];
   }
   
   $simbolo_moneda=get_row('perfil','moneda', 'id_perfil', 1);
   
   ?>
<table class="table">
   <tr>
      <th class='text-center'>CODIGO</th>
      <th class='text-center'>CANT.</th>
      <th>DESCRIPCION</th>
      <th class='text-right'>PRECIO UNIT.</th>
      <th class='text-right'>PRECIO TOTAL</th>
      <th></th>
   </tr>
   <?php
      $sumador_total=0;
      
      $sql=mysqli_query($con, "select * from products, tmp where products.id_producto=tmp.id_producto and tmp.session_id= '".$session_id."' and tmp.tarjeta_temp = 0");
      
      $i = 0;
      
      while ($row=mysqli_fetch_array($sql))
      
      {
      
      $id_tmp=$row["id_tmp"];
      
      $codigo_producto=$row['codigo_producto'];
      
      $cantidad=$row['cantidad_tmp'];
      
      $nombre_producto=$row['nombre_producto'];
      
      $precio_venta=$row['precio_tmp'];
      
      $precio_venta_f=number_format($precio_venta,2);//Formateo variables
      
      $precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
      
      $precio_total=$precio_venta_r*$cantidad;
      
      $precio_total_f=number_format($precio_total,2);//Precio total formateado
      
      $precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
      
      $sumador_total+=$precio_total_r;//Sumador
      
      $i++;
      
      	?>
   <tr>
      <td class='text-center'><?php echo $codigo_producto;?></td>
      <td class='text-center'><?php echo $cantidad;?></td>
      <td><?php echo $nombre_producto;?></td>
      <td class='text-right'><?php echo $precio_venta_f;?></td>
      <td class='text-right'><?php echo $precio_total_f;?></td>
      <td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
   </tr>
   <?php
      }
      
      $st = $sumador_total;
      $iv = $st * $aplica_iva;
      $tot = $st + $iv;
      
      $sql=mysqli_query($con, "SELECT `id_tmp`, `cantidad_tmp`, `precio_tmp` FROM `tmp` 
      		WHERE `session_id` = '".$session_id."' AND `tarjeta_temp` = 1");
      
      $gastos_tarjeta = 0;
      
      while ($row=mysqli_fetch_array($sql))
      
      {
      
      $id_tmp=$row["id_tmp"];
      
      $codigo_producto=0001;
      
      $cantidad=$row['cantidad_tmp'];
      
      $nombre_producto='Tarjeta prepagada';
      
      
      $precio_venta=$row['precio_tmp'];
      
      $precio_venta_f=number_format($precio_venta,2);//Formateo variables
      
      $precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
      
      $precio_total=$precio_venta_r*$cantidad;
      
      $precio_total_f=number_format($precio_total,2);//Precio total formateado
      
      $precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
      
      $sumador_total+=$precio_total_r;//Sumador
      
      $gastos_tarjeta += $precio_total_r;
      
      $i++;
      
      	?>
   <tr>
      <td class='text-center'><?php echo $codigo_producto;?></td>
      <td class='text-center'><?php echo $cantidad;?></td>
      <td><?php echo $nombre_producto;?></td>
      <td class='text-right'><?php echo $precio_venta_f;?></td>
      <td class='text-right'><?php echo $precio_total_f;?></td>
      <td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
   </tr>
   <?php
      }
      
      $impr_impuesto=get_row('perfil','impuesto', 'id_perfil', 1);
      if ($aplica_iva == 0){
        $impuesto = 0;
      } else {
          
          $impuesto=get_row('perfil','impuesto', 'id_perfil', 1);
      }
      
      
	  $subtotal=number_format($sumador_total,2,'.','');
	  
	  //DESCUENTO
	  $descuento = $subtotal * ($descuento_cliente/100);
	  $descuento=number_format($descuento,2,'.','');

	  
      $total_iva=(($subtotal-$descuento) * $impuesto )/100;
      
      $total_iva=number_format($total_iva,2,'.','');
      
      $total_factura=$subtotal-$descuento+$total_iva;
      
      $cantidad_productos = $i;
      ?>
   <tr>
      <td class='text-right' colspan=4>SUBTOTAL <?php echo $simbolo_moneda;?></td>
      <td class='text-right'><?php echo number_format($subtotal,2);?></td>
      <td></td>
   </tr>
   <tr>
      <td class='text-right' colspan=4>Descuento <?php echo $simbolo_moneda;?></td>
      <td class='text-right' id="column_descuento"><?php echo number_format($descuento,2);?></td>
      <td></td>
   </tr>
   <tr>
      <td class='text-right' colspan=4>IVA (<?php echo $impr_impuesto;?>)% <?php echo $simbolo_moneda;?></td>
      <td class='text-right'><?php echo number_format($total_iva,2);?></td>
      <td></td>
   </tr>
  
   <tr>
      <td class='text-right' colspan=4>TOTAL <?php echo $simbolo_moneda;?></td>
      <td class='text-right'><?php echo number_format($total_factura,2);?></td>
      <input id="total_factura" type='hidden' value="<?php echo $total_factura; ?>" >
      <input id="cantidad_productos" type='hidden' value="<?php echo $cantidad_productos  ?>" >
      <input id="gastos_tarjeta" type='hidden' value="<?php echo $gastos_tarjeta  ?>" >
      <td></td>
   </tr>
</table>
<div class="col-md-2">
   <script>
      var gastos_tarjeta = $("#gastos_tarjeta").val();	
      localStorage.setItem("gastos_tarjeta", gastos_tarjeta);
      var total_factura = $("#total_factura").val();
      localStorage.setItem("total_factura", total_factura);
      var porcentaje_descuento = localStorage.getItem('descuento');
      //var descuento = $("#descuento").val();
      //localStorage.setItem("descuento", descuento);
      var cantidad_productos =  $("#cantidad_productos").val();
      localStorage.setItem("cantidad_productos", cantidad_productos); 
   </script>
</div>

