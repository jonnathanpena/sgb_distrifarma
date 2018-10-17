<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/ciudad.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$ciudad = new Ciudad($db);
 
// query de lectura
$stmt = $ciudad->readAll();
$num = $stmt->rowCount();

//ciudad array
$ciudad_arr=array();
$ciudad_arr["data"]=array();
 
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
        $ciudad_item=array(
            "df_codigo_ciudad"=>$df_codigo_ciudad, 
            "df_nombre_ciudad"=>$df_nombre_ciudad
        );
 
        array_push($ciudad_arr["data"], $ciudad_item);
    }
 
    echo json_encode($ciudad_arr);
}
 
else{
    echo json_encode($ciudad_arr);
}
?>