<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if (isset($_GET['id'])){

		$id_cc=intval($_GET['id']);

		$query_cc=mysqli_query($con,"select * from caja_chica where id_cc='".$id_cc."'");

		$rowfac=mysqli_fetch_array($query_cc);


		$del1="delete from detalle_caja_chica where caja_chica_id='".$id_cc."'";

		$del2="delete from caja_chica where id_cc='".$id_cc."'";

		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){

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

         $inicio = mysqli_real_escape_string($con,(strip_tags($_REQUEST['inicio'], ENT_QUOTES)));

         $fin = mysqli_real_escape_string($con,(strip_tags($_REQUEST['fin'], ENT_QUOTES)));

		 $aColumns = array('id_cc', 'fecha_cc','user_cc','firstname','gasto_total_cc');//Columnas de busqueda
        
         include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/

		$count_query   = mysqli_query($con, "SELECT count(cc.`id_cc`) as numrows
                            FROM `caja_chica` as cc, detalle_caja_chica dc 
                            WHERE dc.caja_chica_id = cc.id_cc 
							AND cc.`fecha_cc` >= '".$inicio." 00:00:00'
                            AND cc.`fecha_cc` <= '".$fin." 23:59:59'");

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './caja_chica.php';

		//main query to fetch the data

		$sql="SELECT cc.`id_cc`, cc.`fecha_cc`, cc.`user_cc`, us.`firstname`, us.`lastname`, cc.`gasto_total_cc`, dc.producto_dcc, dc.precio_total_dcc
                FROM `caja_chica` as cc
                JOIN `users` as us ON (cc.`user_cc` = us.`user_id`) 
				JOIN `detalle_caja_chica` as dc ON (cc.`id_cc` = dc.`caja_chica_id`) 
                WHERE cc.`fecha_cc` >= '".$inicio." 00:00:00'
				AND cc.`fecha_cc` <= '".$fin." 23:59:59' 
				ORDER BY cc.`id_cc` DESC
                LIMIT $offset,$per_page";

		$query = mysqli_query($con, $sql);

		//loop through fetched data

		if ($numrows>0){



			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="info">

					<th>ID</th>

					<th>Fecha</th>

					<th>Usuario</th>

					<th>Producto</th>

					<th>
						<span class='pull-right'>Total Gasto($)</span>
					</th>

					<th class='text-right'>Acciones</th>

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

						$id_cc=$row['id_cc'];

						$fecha=date("d/m/Y", strtotime($row['fecha_cc']));

						$usuario_id=$row['user_cc'];

						$usuario_nombre=$row['firstname'];

						$usuario_apellido=$row['lastname'];

						$gasto_total=$row['gasto_total_cc'];

						$producto_dcc=$row['producto_dcc'];

						$precio_total_dcc=$row['precio_total_dcc'];

						

						

					?>

					

					<input type="hidden" value="<?php echo $id_cc;?>" id="id_cc<?php echo $id_cc;?>">

					<input type="hidden" value="<?php echo $fecha;?>" id="fecha<?php echo $id_cc;?>">

					<input type="hidden" value="<?php echo $usuario_id;?>" id="usuario_id<?php echo $id_cc;?>">

					<input type="hidden" value="<?php echo $usuario_nombre;?>" id="usuario_nombre<?php echo $id_cc;?>">

					<input type="hidden" value="<?php echo $usuario_apellido;?>" id="usuario_apellido<?php echo $id_cc;?>">

					<input type="hidden" value="<?php echo $gasto_total;?>" id="gasto_total<?php echo $id_cc;?>">

					<tr>

						

						<td><?php echo $id_cc; ?></td>

						<td><?php echo $fecha; ?></td>

						<td><?php echo $usuario_nombre." ".$usuario_apellido;?> </td>

						<td><?php echo $producto_dcc; ?></td>

						<td><span class='pull-right'><?php echo "$";?><?php echo number_format($precio_total_dcc,2);?></span></td>

					<td ><span class="pull-right">

					 <!-- <a href="#" class='btn btn-default' title='Descargar factura' onclick="imprimir_factura('<?php echo $id_factura;?>');"><i class="glyphicon glyphicon-download"></i></a>  -->

                        <a href="#" class='btn btn-default' title='Borrar Compra' onclick="eliminar('<?php echo $id_cc; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>

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