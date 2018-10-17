<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/productoPrecio.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$productoPrecio = new ProductoPrecio($db);
 
// query de lectura
$stmt = $productoPrecio->read();
$num = $stmt->rowCount();

//productoPrecio array
$productoPrecio_arr=array();
$productoPrecio_arr["data"]=array();
 
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
        $productoPrecio_item=array(
            "df_id_precio"=>$df_id_precio, 
            "df_producto_id"=>$df_producto_id,
            "df_ppp"=>$df_ppp,
            "df_pvt1"=>$df_pvt1,
            "df_pvt2"=>$df_pvt2,
            "df_pvp"=>$df_pvp,
            "df_iva"=>$df_iva,
            "df_min_sugerido"=>$df_min_sugerido,
            "df_und_caja"=>$df_und_caja,
            "df_utilidad"=>$df_utilidad
        );
 
        array_push($productoPrecio_arr["data"], $productoPrecio_item);
    }
 
    echo json_encode($productoPrecio_arr);
}
 
else{
    echo json_encode($productoPrecio_arr);
}
?>