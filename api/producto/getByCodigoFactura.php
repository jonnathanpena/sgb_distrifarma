<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/producto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$producto = new Producto($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$producto->codigo= $info[0]["codigo"];
// query de lectura
$stmt = $producto->readByCodigoFactura();
$num = $stmt->rowCount();

//producto array
$producto_arr=array();
$producto_arr["data"]=array();
 
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
        $producto_item=array(
            "df_id_producto"=>$df_id_producto, 
            "df_nombre_producto"=>$df_nombre_producto,
            "df_codigo_prod"=>$df_codigo_prod,
            "df_id_precio"=>$df_id_precio,
            "df_producto_id"=>$df_producto_id,
            "df_ppp"=>$df_ppp,
            "df_pvt1"=>$df_pvt1,
            "df_pvt2"=>$df_pvt2,
            "df_pvp"=>$df_pvp,
            "df_iva"=>$df_iva,
            "df_min_sugerido"=>$df_min_sugerido,
            "df_und_caja"=>$df_und_caja,
            "df_utilidad"=>$df_utilidad,
            "df_valor_impuesto"=>$df_valor_impuesto, 
            "df_cant_bodega"=>$df_cant_bodega * 1
        );
 
        array_push($producto_arr["data"], $producto_item);
    }
 
    echo json_encode($producto_arr);
}
 
else{
    echo json_encode($producto_arr);
}
?>