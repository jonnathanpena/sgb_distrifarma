<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){

		//Trae tipo de cliente para seleccionar precio
	
		$nombre_empresas = $_GET['nombre_empresas'];

		// escaping, additionally removing everything that could be (html/javascript-) code

         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));

		 $aColumns = array('codigo_producto', 'nombre_producto', 'nombre_categoria');//Columnas de busqueda

		 $sTable = "products, categorias";

		 $sWhere = "";

		if ( $_GET['q'] != "" )

		{

			$sWhere = "WHERE `nombre_producto` LIKE '%".$_GET['q']."%' 
						OR `codigo_producto` LIKE '%".$_GET['q']."%'";
			
			$sWhere = "WHERE (";

			for ( $i=0 ; $i<count($aColumns) ; $i++ )

			{

				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";

			}

			$sWhere = substr_replace( $sWhere, "", -3 );

			$sWhere .= ') and categoria_id = id_categorias';

		}

		//valido Where para categorias
		if ($sWhere != ''){
			$sWhere.=" order by id_producto desc";
		} else {
			$sWhere.=" where categoria_id = id_categorias order by id_producto desc";
		
		}

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 5; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/

		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './index.php';

		//main query to fetch the data

		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";

		$query = mysqli_query($con, $sql);

		//loop through fetched data

		if ($numrows>0){

			

			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="warning">

					<th>Código</th>

					<th>Producto</th>
					<th>Categoría</th>

					<th><span class="pull-right">Precio</span></th>

					<th><span class="pull-right">I.V.A. 12%</span></th>

					<th><span class="pull-right">Precio Total</span></th>

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

					$id_producto=$row['id_producto'];

					$nombre_producto=$row['nombre_producto'];
					$nombre_categoria=$row['nombre_categoria'];

					if ($nombre_empresas == 'EXTERNO'){
						$precio_venta=$row["precio_publico"];
					} elseif ($nombre_empresas == 'FLACSO'){
						$precio_venta=$row["precio_flacso"];
					} else {
						$precio_venta=$row["precio_producto"];
					}

					$codigo_producto=$row['codigo_producto'];

					$precio_venta=number_format($precio_venta,2,'.','');

					$iva = $precio_venta * 0.12;

					$iva=number_format($iva,2,'.','');

					$total = $iva + $precio_venta;

					?>

					<tr>

						<td><?php echo $codigo_producto; ?></td>

						<td><?php echo $nombre_producto; ?></td>
						<td><?php echo $nombre_categoria; ?></td>

						<td>
						
							<div class="pull-right">
								<?php echo $precio_venta; ?>
							</div>
						
						</td>

						<td>
						
							<div class="pull-right">
								<?php echo $iva; ?>
							</div>
						
						</td>

						<td>
						
							<div class="pull-right">
								<?php echo $total; ?>
							</div>
						
						</td>

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=5><span class="pull-right">

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