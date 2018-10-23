<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/productoDevueltoRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$productoDevueltoRecepcion = new productoDevueltoRecepcion($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$productoDevueltoRecepcion->df_id_prod_dev_rec= $info[0]["df_id_prod_dev_rec"];
// query de lectura
$stmt = $productoDevueltoRecepcion->readById();
$num = $stmt->rowCount();

//productoDevueltoRecepcion array
$productoDevueltoRecepcion_arr=array();
$productoDevueltoRecepcion_arr["data"]=array();
 
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
        $productoDevueltoRecepcion_item=array(
            "df_id_prod_dev_rec"=>$df_id_prod_dev_rec,
            "df_guia_rec"=>$df_guia_rec,
            "df_cant_und_rec"=>$df_cant_und_rec,
            "df_producto_id_rec"=>$df_producto_id_rec
        );
 
        array_push($productoDevueltoRecepcion_arr["data"], $productoDevueltoRecepcion_item);
    }
 
    echo json_encode($productoDevueltoRecepcion_arr);
}
 
else{
    echo json_encode($productoDevueltoRecepcion_arr);
}
?>