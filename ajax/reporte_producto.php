<?php

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){

		$aColumns = array('date', 'cod', 'name', 'cant', 'total');//Columnas de busqueda

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/

		if($_GET['tipo'] == 'all'){

			$count_query   = mysqli_query($con, "
				SELECT count(df.`numero_factura`) AS numrows, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
				fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
				prod.`nombre_producto` 
				FROM `detalle_factura` as df 
				JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
				JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
			");
			
			$sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                	fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
                	prod.`nombre_producto` 
                	FROM `detalle_factura` as df 
                	JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                	JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)";

		}else if($_GET['tipo'] == 'byid'){

			if($_GET['id_producto'] == 'nada') {

				$count_query   = mysqli_query($con, "
            		SELECT count(df.`numero_factura`) AS numrows, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
            		fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
            		prod.`nombre_producto` 
            		FROM `detalle_factura` as df 
            		JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
            		JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
            		WHERE fac.`fecha_factura` >= '".$_GET['inicio']."' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']."'
        		");
			
				$sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                	fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
                	prod.`nombre_producto` 
                	FROM `detalle_factura` as df 
                	JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                	JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                	WHERE fac.`fecha_factura` >= '".$_GET['inicio']."' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']."'";

			} else {

				$count_query   = mysqli_query($con, "
            		SELECT count(df.`numero_factura`) AS numrows, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
            		fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
            		prod.`nombre_producto` 
            		FROM `detalle_factura` as df 
            		JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
            		JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
            		WHERE df.`id_producto` = ".$_GET['id_producto']." 
            		AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']."'
        		");
			
				$sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                	fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
                	prod.`nombre_producto` 
                	FROM `detalle_factura` as df 
                	JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                	JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                	WHERE df.`id_producto` = ".$_GET['id_producto']." 
                	AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']."'";

			}

			
		}   

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './reportes.php';

		$query = mysqli_query($con, $sql);

		//loop through fetched data

?>
<input id="cantidad" type='hidden' value="<?php echo $numrows?>">	
<script>
	var cantidad= $("#cantidad").val();
	if(cantidad > 0){
		localStorage.setItem('exportar', 1);
	}else{
		localStorage.setItem('exportar', 0);
	}
</script>
<?php

		if ($numrows>0){

			

			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="info">

					<th>Fecha</th>

					<th>Código</th>

					<th>Nombre</th>

					<th class="text-center">Cantidad</th>

					<th class="text-center">Total Venta</th>

					

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

                        $date= date('d/m/Y', strtotime($row['fecha_factura']));    

                        $cod=$row['codigo_producto'];

						$nombre=$row['nombre_producto'];

						$cantidad=$row['cantidad'];

						$total=$row['total_venta'];
						

					?>
				

					<tr>

						<td><?php echo $date; ?></td>

						<td><?php echo $cod; ?></td>

						<td><?php echo $nombre; ?></td>

						<td class="text-center"><?php echo $cantidad; ?></td>

						<td class="text-center">$<?php echo $total;?></td>						

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=9><span class="pull-right">

					<?php

					 echo paginate($reload, $page, $total_pages, $adjacents);

					?></span></td>

				</tr>

			  </table>

			</div>

			<?php

		}

	}

?>