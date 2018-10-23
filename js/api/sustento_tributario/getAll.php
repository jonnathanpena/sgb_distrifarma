<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/sustento_tributario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$sustento_tributario = new SustentoTributario($db);
 
// query de lectura
$stmt = $sustento_tributario->readAll();
$num = $stmt->rowCount();

//sustento_tributario array
$sustento_tributario_arr=array();
$sustento_tributario_arr["data"]=array();
 
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
        $sustento_tributario_item=array(
            "id_sustento"=>$id_sustento,
            "nombre_sustento"=>$nombre_sustento
        );
 
        array_push($sustento_tributario_arr["data"], $sustento_tributario_item);
    }
 
    
}
 
echo json_encode($sustento_tributario_arr);
?>