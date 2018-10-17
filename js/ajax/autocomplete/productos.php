<?php
if (isset($_GET['term'])){
include("../../config/db.php");
include("../../config/conexion.php");
$return_arr = array();
/* If connection to database, run sql statement. */
if ($con)
{
	
	$fetch = mysqli_query($con,"SELECT `id_producto`, `codigo_producto`, `nombre_producto` FROM `products` WHERE `nombre_producto` like '%" . mysqli_real_escape_string($con,($_GET['term'])) . "%' LIMIT 0 ,50"); 
	
	/* Retrieve and store in array the results of the query.*/
	while ($row = mysqli_fetch_array($fetch)) {
		$id_producto=$row['id_producto'];
		$row_array['value'] = $row['codigo_producto'].' - '.$row['nombre_producto'];		
		$row_array['id_producto']=$id_producto;
		$row_array['codigo_producto'] = $row['codigo_producto'];
		$row_array['nombre_producto'] = $row['nombre_producto'];
		array_push($return_arr,$row_array);
    }
	
}

/* Free connection resources. */
mysqli_close($con);

/* Toss back results as json encoded array. */
echo json_encode($return_arr);

}
?>