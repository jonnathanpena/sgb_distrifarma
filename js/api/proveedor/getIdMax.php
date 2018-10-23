<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/proveedor.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$proveedor = new Proveedor($db);
 
// query de lectura
$stmt = $proveedor->readIdMax();
$num = $stmt->rowCount();

//proveedor array
$proveedor_arr=array();
$proveedor_arr["data"]=array();
 
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
        $proveedor_item=array(
            "df_id_proveedor"=>$df_id_proveedor
        );
 
        array_push($proveedor_arr["data"], $proveedor_item);
    }
 
    echo json_encode($proveedor_arr);
}
 
else{
    echo json_encode($proveedor_arr);
}
?>