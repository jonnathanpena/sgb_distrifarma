<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/kardex.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$kardex = new Kardex($db);

$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$kardex->df_fecha_ini= $info[0]["df_fecha_ini"];
$kardex->df_fecha_fin= $info[0]["df_fecha_fin"];
// query de lectura
$stmt = $kardex->readByFecha();
$num = $stmt->rowCount();

//kardex array
$kardex_arr=array();
$kardex_arr["data"]=array();
 
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
        $kardex_item=array(
            "df_kardex_id"=>$df_kardex_id, 
            "df_kardex_codigo"=>$df_kardex_codigo,
            "df_fecha_kar"=>$df_fecha_kar,
            "df_producto_cod_kar"=>$df_producto_cod_kar,
            "df_producto"=>$df_producto,
            "df_factura_kar"=>$df_factura_kar,
            "df_ingresa_kar"=>$df_ingresa_kar,
            "df_egresa_kar"=>$df_egresa_kar,
            "df_existencia_kar"=>$df_existencia_kar,
            "df_creadoBy_kar"=>$df_creadoBy_kar,
            "df_edo_kardex"=>$df_edo_kardex
        );
 
        array_push($kardex_arr["data"], $kardex_item);
    }
 
    echo json_encode($kardex_arr);
}
 
else{
    echo json_encode($kardex_arr);
}
?>