<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaEntrega.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaEntrega = new GuiaEntrega($db);
 
// query de lectura
$stmt = $guiaEntrega->readIdMax();
$num = $stmt->rowCount();

//guiaEntrega array
$guiaEntrega_arr=array();
$guiaEntrega_arr["data"]=array();
 
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
        $guiaEntrega_item=array(
            "df_num_guia_entrega"=>$df_num_guia_entrega
        );
 
        array_push($guiaEntrega_arr["data"], $guiaEntrega_item);
    }
 
    echo json_encode($guiaEntrega_arr);
}
 
else{
    echo json_encode($guiaEntrega_arr);
}
?>