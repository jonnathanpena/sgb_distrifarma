<?php

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){

		$aColumns = array('date', 'num_fac', 'document', 'name', 'total');//Columnas de busqueda

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/

		if($_GET['tipo'] == 'all'){

			$count_query   = mysqli_query($con, "
            	SELECT count(fac.`id_factura`) as numrows, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
            	cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
            	FROM `facturas` as fac
            	JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
			");
			
			$sql="SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, 
                fac.`id_cliente`, cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
                FROM `facturas` as fac
                JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)";

		}else if($_GET['tipo'] == 'byid'){

			if($_GET['id_cliente'] == 'nada') {

				$count_query   = mysqli_query($con, "
            		SELECT count(fac.`id_factura`) as numrows, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
            		cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
            		FROM `facturas` as fac
            		JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
            		WHERE fac.`fecha_factura` >= '".$_GET['inicio']."' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']."'
				");
			
				$sql="SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, 
                	fac.`id_cliente`, cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
                	FROM `facturas` as fac
                	JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
                	WHERE fac.`fecha_factura` >= '".$_GET['inicio']."' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']."'";

			} else {
				
				$count_query   = mysqli_query($con, "
            		SELECT count(fac.`id_factura`) as numrows, fac.`numero_factura`, fac.`fecha_factura`, fac.`id_cliente`, 
            		cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
            		FROM `facturas` as fac
            		JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
            		WHERE fac.`id_cliente` = ".$_GET['id_cliente']."
            		AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']."'
				");
			
				$sql="SELECT fac.`id_factura`, fac.`numero_factura`, fac.`fecha_factura`, 
                	fac.`id_cliente`, cli.`nombre_cliente`, cli.`documento_cliente`, fac. `total_venta`
                	FROM `facturas` as fac
                	JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
                	WHERE fac.`id_cliente` = ".$_GET['id_cliente']."
                	AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']."'";
			}
		}       
        

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './reportes.php';

		//main query to fetch the data

		$query = mysqli_query($con, $sql);

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

		//loop through fetched data

		if ($numrows>0){

			

			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="info">

					<th>#</th>

					<th>Fecha</th>

					<th>Documento Cliente</th>

					<th>Nombre Cliente</th>

					<th class="text-center">Total Venta</th>

					

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

                        $numero= $row['numero_factura']; 

                        $fecha= date('d/m/Y', strtotime($row['fecha_factura']));    

                        $documento=$row['documento_cliente'];

						$nombre=$row['nombre_cliente'];

						$total=$row['total_venta'];
						

					?>
				

					<tr>

						<td><?php echo $numero; ?></td>

						<td><?php echo $fecha; ?></td>

						<td><?php echo $documento; ?></td>

						<td><?php echo $nombre; ?></td>

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