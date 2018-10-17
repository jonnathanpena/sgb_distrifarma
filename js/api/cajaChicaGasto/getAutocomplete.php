<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cajaChicaGasto.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cajaChicaGasto = new CajaChicaGasto($db);
 
// query de lectura
$stmt = $cajaChicaGasto->readAutocomplete();
$num = $stmt->rowCount();

//cajaChicaGasto array
$cajaChicaGasto_arr=array();
$cajaChicaGasto_arr["data"]=array();
 
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
        $valor = $df_movimiento;
 
        array_push($cajaChicaGasto_arr["data"], $valor);
    }
 
    echo json_encode($cajaChicaGasto_arr);
}
 
else{
    echo json_encode($cajaChicaGasto_arr);
}
?>