<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleRecepcion = new DetalleRecepcion($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$detalleRecepcion->df_guia_recepcion_detrec= $info[0]["df_guia_recepcion_detrec"];
// query de lectura
$stmt = $detalleRecepcion->readById();
$num = $stmt->rowCount();

//detalleRecepcion array
$detalleRecepcion_arr=array();
$detalleRecepcion_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        
        //Los nombres acá son iguales a los de la clase iguales a las columnas de la BD
        $detalleRecepcion_item=array(
          	 "df_id_detrec"=>$df_id_detrec, 
			 "df_guia_recepcion_detrec"=>$df_guia_recepcion_detrec,
			 "df_factura_rec"=>$df_factura_rec, 
             "df_cant_producto_detrec"=>$df_cant_producto_detrec,
             "df_producto_cod_detrec"=>$df_producto_cod_detrec,
             "df_nueva_fecha"=>$df_nueva_fecha,
             "df_edo_prod_fact_detrec" =>$df_edo_prod_fact_detrec
        );
 
        array_push($detalleRecepcion_arr["data"], $detalleRecepcion_item);
    }
 
    echo json_encode($detalleRecepcion_arr);
}
 
else{
    echo json_encode($detalleRecepcion_arr);
}
?>