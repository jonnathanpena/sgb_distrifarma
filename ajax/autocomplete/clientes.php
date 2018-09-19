<?php
if (isset($_GET['term'])){
include("../../config/db.php");
include("../../config/conexion.php");
$return_arr = array();
/* If connection to database, run sql statement. */
if ($con)
{
	
	$fetch = mysqli_query($con,"SELECT DISTINCT * FROM clientes, empresas where empresa_cliente = id_empresas 
				and (nombre_cliente 	like '%" . mysqli_real_escape_string($con,($_GET['term'])) . "%' 
				OR `documento_cliente` like '%" . mysqli_real_escape_string($con,($_GET['term'])) . "%')
				LIMIT 0 ,50"); 
	
	/* Retrieve and store in array the results of the query.*/
	while ($row = mysqli_fetch_array($fetch)) {
		$id_cliente=$row['id_cliente'];
		$row_array['value'] = $row['nombre_cliente'];
		$row_array['id_cliente']=$id_cliente;
		$row_array['nombre_cliente']=$row['nombre_cliente'];
		$row_array['telefono_cliente']=$row['telefono_cliente'];
		$row_array['email_cliente']=$row['email_cliente'];
		$row_array['saldo_cliente']=getCupo($row['id_cliente'], $con);
		$row_array['documento_cliente']=$row['documento_cliente'];
		$row_array['descuento']=$row['descuento'];
		$row_array['id_empresas']=$row['id_empresas'];
		$row_array['nombre_empresas']=$row['nombre_empresas'];
		array_push($return_arr,$row_array);
	}
	
}

/* Free connection resources. */
mysqli_close($con);

/* Toss back results as json encoded array. */
echo json_encode($return_arr);

}

function getCupo($idcliente, $conexion){
	$fetch = mysqli_query($conexion,"SELECT SUM(`monto_tarjetas`) as suma FROM `tarjetas` 
				WHERE `cliente_id` = ".$idcliente." AND `estatus_tarjetas` = 1");
	$row= mysqli_fetch_array($fetch);
	if($row['suma'] > 0){
		return $row['suma'];
	}else{
		return 0;
	}
}
?>