<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleRemision.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleRemision = new DetalleRemision($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$detalleRemision->df_guia_remision_detrem= $info[0]["df_guia_remision_detrem"];
// query de lectura
$stmt = $detalleRemision->readById();
$num = $stmt->rowCount();

//detalleRemision array
$detalleRemision_arr=array();
$detalleRemision_arr["data"]=array();
 
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
        $detalleRemision_item=array(
            "df_id_detrem"=>$df_id_detrem, 
			"df_guia_remision_detrem"=>$df_guia_remision_detrem,
			"df_producto_precio_detrem"=>$df_producto_precio_detrem, 
            "df_cant_producto_detrem"=>$df_cant_producto_detrem,
            "df_nombre_und_detrem"=>$df_nombre_und_detrem,
            "df_cant_x_und_detrem"=>$df_cant_x_und_detrem,
            "df_valor_sin_iva_detrem"=>$df_valor_sin_iva_detrem,
            "df_iva_detrem"=>$df_iva_detrem,
            "df_valor_total_detrem"=>$df_valor_total_detrem,
            "df_id_producto"=>$df_id_producto,
            "df_codigo_prod"=>$df_codigo_prod,
            "df_nombre_producto"=>$df_nombre_producto,
            "df_und_caja"=>$df_und_caja * 1
        );
 
        array_push($detalleRemision_arr["data"], $detalleRemision_item);
    }
 
    echo json_encode($detalleRemision_arr);
}
 
else{
    echo json_encode($detalleRemision_arr);
}
?>