<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleFactura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleFactura = new DetalleFactura($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$detalleFactura->df_num_factura_detfac= $info[0]["df_num_factura_detfac"];
// query de lectura
$stmt = $detalleFactura->readById();
$num = $stmt->rowCount();

//detalleFactura array
$detalleFactura_arr=array();
$detalleFactura_arr["data"]=array();
 
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
        $detalleFactura_item=array(
            "df_id_factura_detfac"=>$df_id_factura_detfac, 
            "df_num_factura_detfac"=>$df_num_factura_detfac,
            "df_prod_precio_detfac"=>$df_prod_precio_detfac,
            "df_precio_prod_detfac"=>$df_precio_prod_detfac,
            "df_id_producto"=>$df_id_producto,
            "df_nombre_producto"=>$df_nombre_producto,
            "df_codigo_prod"=>$df_codigo_prod,
            "df_cantidad_detfac"=>$df_cantidad_detfac,
            "df_nombre_und_detfac"=>$df_nombre_und_detfac,
            "df_cant_x_und_detfac"=>$df_cant_x_und_detfac,
            "df_edo_entrega_prod_detfac"=>$df_edo_entrega_prod_detfac,
            "df_valor_sin_iva_detfac"=>$df_valor_sin_iva_detfac,
            "df_iva_detfac"=>$df_iva_detfac,
            "df_valor_total_detfac"=>$df_valor_total_detfac
        );
 
        array_push($detalleFactura_arr["data"], $detalleFactura_item);
    }
 
    echo json_encode($detalleFactura_arr);
}
 
else{
    echo json_encode($detalleFactura_arr);
}
?>