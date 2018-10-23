<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/personal.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$personal = new Personal($db);
 
// query de lectura
$stmt = $personal->readIdMax();
$num = $stmt->rowCount();

//personal array
$personal_arr=array();
$personal_arr["data"]=array();
 
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
        $personal_item=array(
            "df_id_personal"=>$df_id_personal
        );
 
        array_push($personal_arr["data"], $personal_item);
    }
 
    echo json_encode($personal_arr);
}
 
else{
    echo json_encode($personal_arr);
}
?>