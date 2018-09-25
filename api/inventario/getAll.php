<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/inventario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$inventario = new Inventario($db);

$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$inventario->df_codigo_prod= $info[0]["df_codigo_prod"];
$inventario->df_nombre_producto= $info[0]["df_nombre_producto"];
 
// query de lectura
$stmt = $inventario->read();
$num = $stmt->rowCount();

//inventario array
$inventario_arr=array();
$inventario_arr["data"]=array();
 
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
        $inventario_item=array(
            "df_id_inventario"=>$df_id_inventario, 
            "df_cant_bodega"=>$df_cant_bodega,
            "df_cant_transito"=>$df_cant_transito,
            "df_producto"=>$df_producto,
            "df_ppp_ind"=>$df_ppp_ind,
            "df_pvt_ind"=>$df_pvt_ind,
            "df_ppp_total"=>$df_ppp_total,
            "df_pvt_total"=>$df_pvt_total,
            "df_minimo_sug"=>$df_minimo_sug,
            "df_und_caja"=>$df_und_caja,
            "df_bodega"=>$df_bodega,
            "df_codigo_prod"=>$df_codigo_prod,
            "df_nombre_producto"=>$df_nombre_producto
        );
 
        array_push($inventario_arr["data"], $inventario_item);
    }
 
    echo json_encode($inventario_arr);
}
 
else{
    echo json_encode($inventario_arr);
}
?>