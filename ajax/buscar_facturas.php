<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	$user_id = $_SESSION['user_id'];
    $query_usuario2=mysqli_query($con,"select * from users where user_id='".$user_id."'");
	$row_user=mysqli_fetch_array($query_usuario2);		

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if (isset($_GET['id'])){

		

		//Para devolver el saldo al cliente

		$numero_factura=intval($_GET['id']);

		

		$query_factura=mysqli_query($con,"select * from facturas where numero_factura='".$numero_factura."'");

		$rowfac=mysqli_fetch_array($query_factura);

		$id_clientefac = $rowfac['id_cliente'];

		$id_consumo = $rowfac['id_factura'];

		$valor_factura = number_format($rowfac['total_venta'],2);

		$fecha_factura=date("d/m/Y", strtotime($rowfac['fecha_factura']));

		

		$query_cliente=mysqli_query($con,"select * from clientes where id_cliente='".$id_clientefac."'");

		$rowcliente=mysqli_fetch_array($query_cliente);

		

		$valor_devuelto = $rowfac['total_venta'] + $rowcliente['saldo_cliente'];

		$email_cliente = $rowcliente['email_cliente'];

		$nombre_cliente = $rowcliente['nombre_cliente'];

		

		$del1="delete from facturas where numero_factura='".$numero_factura."'";

		$del2="delete from detalle_factura where numero_factura='".$numero_factura."'";

		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){

			

			//Devuelvo Saldo

			$sql="UPDATE clientes SET saldo_cliente='".$valor_devuelto."' WHERE id_cliente='".$id_clientefac."'";

			$query_update = mysqli_query($con,$sql);

			

			//Envio correo de devolución

			//require_once "correo_reverso.php";



			?>

			<div class="alert alert-success alert-dismissible" role="alert">

			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			  <strong>Aviso!</strong> Datos eliminados exitosamente

			</div>

			<?php 

		}else {

			?>

			<div class="alert alert-danger alert-dismissible" role="alert">

			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			  <strong>Error!</strong> No se puedo eliminar los datos

			</div>

			<?php

			

		}

	}

	if($action == 'ajax'){

		// escaping, additionally removing everything that could be (html/javascript-) code

         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));

		  $sTable = "facturas, clientes, users";

		 $sWhere = "";

		 $sWhere.=" WHERE facturas.id_cliente=clientes.id_cliente and facturas.id_vendedor=users.user_id";

		if ( $_GET['q'] != "" )

		{

		$sWhere.= " and  (clientes.nombre_cliente like '%$q%' or facturas.numero_factura like '%$q%')";

			

		}

		

		$sWhere.=" order by facturas.id_factura desc";

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/

		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './facturas.php';

		//main query to fetch the data

		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";

		$query = mysqli_query($con, $sql);

		//loop through fetched data

		if ($numrows>0){

			echo mysqli_error($con);

			?>

			

			<div class="table-responsive">

			  <table class="table">

				<tr  class="info">

					<th>#</th>

					<th>Fecha</th>

					<th>Cliente</th>

					<th>Vendedor</th>

					<th class='text-right'>Total</th>

					<th class='text-center'>Acciones</th>

					

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

						$id_factura=$row['id_factura'];

						$numero_factura=$row['numero_factura'];

						$fecha=date("d/m/Y", strtotime($row['fecha_factura']));

						$nombre_cliente=$row['nombre_cliente'];

						$telefono_cliente=$row['telefono_cliente'];

						$email_cliente=$row['email_cliente'];

						$nombre_vendedor=$row['firstname']." ".$row['lastname'];

						$total_venta=$row['total_venta'];

					?>

					<tr>

						<td><?php echo $numero_factura; ?></td>

						<td><?php echo $fecha; ?></td>

						<td><a href="#" data-toggle="tooltip" data-placement="top" title="<i class='glyphicon glyphicon-phone'></i> <?php echo $telefono_cliente;?><br><i class='glyphicon glyphicon-envelope'></i>  <?php echo $email_cliente;?>" ><?php echo $nombre_cliente;?></a></td>

						<td><?php echo $nombre_vendedor; ?></td>

						<td class='text-right'><?php echo number_format ($total_venta,2); ?></td>					

					<td class="text-right">

					

						<a href="#" class='btn btn-default' title='Descargar factura' onclick="imprimir_factura('<?php echo $id_factura;?>');"><i class="glyphicon glyphicon-download"></i></a> 
					
					<?php
						if ($row_user['perfil'] == "Administrador") {
					?>	
							<a href="#" class='btn btn-default' title='Borrar factura' onclick="eliminar('<?php echo $numero_factura; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					<?php		
						}
						
					?>
					</td>

						

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=7><span class="pull-right"><?php

					 echo paginate($reload, $page, $total_pages, $adjacents);

					?></span></td>

				</tr>

			  </table>

			</div>

			<?php

		}

	}

?>