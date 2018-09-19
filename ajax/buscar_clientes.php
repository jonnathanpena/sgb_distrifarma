<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	function getTarjetas($idcliente, $con){

		$sql="SELECT `monto_tarjetas` FROM `tarjetas` WHERE `cliente_id` = ".$idcliente;

		$query = mysqli_query($con, $sql);

		$count=mysqli_num_rows($query);

		if($count > 0) {
			$saldo = 0;
			while($row=mysqli_fetch_array($query)){
				$saldo = $saldo + $row['monto_tarjetas'];
			}
			return $saldo;
		}else{
			return 0.00;
		}
	}

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if (isset($_GET['id'])){

		$id_cliente=intval($_GET['id']);

		$query=mysqli_query($con, "select * from facturas where id_cliente='".$id_cliente."'");

		$count=mysqli_num_rows($query);

		if ($count==0){

			if ($delete1=mysqli_query($con,"DELETE FROM clientes WHERE id_cliente='".$id_cliente."'")){

			?>

			<div class="alert alert-success alert-dismissible" role="alert">

			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			  <strong>Aviso!</strong> Datos eliminados exitosamente.

			</div>

			<?php 

		}else {

			?>

			<div class="alert alert-danger alert-dismissible" role="alert">

			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.

			</div>

			<?php

			

		}

			

		} else {

			?>

			<div class="alert alert-danger alert-dismissible" role="alert">

			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

			  <strong>Error!</strong> No se pudo eliminar éste  cliente. Existen facturas vinculadas a éste producto. 

			</div>

			<?php

		}

		

		

		

	}

	if($action == 'ajax'){

		// escaping, additionally removing everything that could be (html/javascript-) code

         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));

		 $aColumns = array('codigo', 'nombre_cliente','documento_cliente');//Columnas de busqueda

		 $sTable = "clientes, empresas";

		 $sWhere = "";

		if ( $_GET['q'] != "" )

		{

			$sWhere = "WHERE (";

			for ( $i=0 ; $i<count($aColumns) ; $i++ )

			{

				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";

			}

			$sWhere = substr_replace( $sWhere, "", -3 );

			$sWhere .= ') and empresa_cliente = id_empresas ';

		}

		//valido Where para empresas
		if ($sWhere != ''){
			$sWhere.=" order by nombre_cliente";	
		} else {
			$sWhere.=" where empresa_cliente = id_empresas order by nombre_cliente";
		}
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

		$reload = './clientes.php';

		//main query to fetch the data

		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";

		$query = mysqli_query($con, $sql);

		//loop through fetched data

		if ($numrows>0){



			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="info">

					<th>Código</th>

					<th>Nombre</th>

					<th>Cedula</th>

					<th>Tipo</th>

					<th>Estado</th>

					<th>Saldo</th>

					<th>
						<span class='pull-right'>Descuento(%)</span>
					</th>

					<th class='text-right'>Acciones</th>

					

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

						$codigo=$row['codigo'];

						$id_cliente=$row['id_cliente'];

						$telefono_cliente=$row['telefono_cliente'];

						$nombre_cliente=$row['nombre_cliente'];

						$email_cliente=$row['email_cliente'];

						$documento_cliente=$row['documento_cliente'];

						$nombre_empresas=$row['nombre_empresas'];

						$id_empresas=$row['id_empresas'];

						$direccion_cliente=$row['direccion_cliente'];

						$status_cliente=$row['status_cliente'];

						$saldo_cliente= getTarjetas($id_cliente, $con);

						$descuento = $row['descuento'];

						if ($status_cliente==1){$estado="Activo";$label_class='label-success';}

						else {$estado="Inactivo";$label_class='label-danger';}

						

					?>

					

					<input type="hidden" value="<?php echo $codigo;?>" id="codigo<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $nombre_cliente;?>" id="nombre_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $telefono_cliente;?>" id="telefono_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $email_cliente;?>" id="email_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $documento_cliente;?>" id="documento_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $direccion_cliente;?>" id="direccion_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $nombre_empresas;?>" id="nombre_empresas<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $id_empresas;?>" id="id_empresas<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $status_cliente;?>" id="status_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $saldo_cliente;?>" id="saldo_cliente<?php echo $id_cliente;?>">

					<input type="hidden" value="<?php echo $descuento;?>" id="descuento<?php echo $id_cliente;?>">

					<?php 
						$descuento = $descuento * 100;
					?>

					<tr>

						

						<td><?php echo $codigo; ?></td>

						<td><?php echo $nombre_cliente; ?></td>

						<td><?php echo $documento_cliente;?></td>

						<td><?php echo $nombre_empresas;?></td>

						<td><span class="label <?php echo $label_class;?>"><?php echo $estado; ?></span></td>

						<td><?php echo "$";?><span class='pull-right'><?php echo number_format($saldo_cliente,2);?></span></td>

						<td><span class='pull-right'><?php echo number_format($descuento,2);;?></span></td>

					<td ><span class="pull-right">

					<a href="#" class='btn btn-default' title='Editar cliente' onclick="obtener_datos('<?php echo $id_cliente;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a> 

					<a href="#" class='btn btn-default' title='Borrar cliente' onclick="eliminar('<?php echo $id_cliente; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>

						

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=8><span class="pull-right">

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