<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/retencionIR.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$retencionIr = new RetencionIr($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$retencionIr->id_retencion_ir= $info[0]["id_retencion_ir"];

// query de lectura
$stmt = $retencionIr->readById();
$num = $stmt->rowCount();

//retencionIr array
$retencionIr_arr=array();
$retencionIr_arr["data"]=array();
 
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
        $retencionIr_item=array(
            "id_retencion_ir"=>$id_retencion_ir, 
            "codigo_ret_ir"=>$codigo_ret_ir,
            "nombre_ret_ir"=>$nombre_ret_ir,
            "porcentaje_ret_ir"=>$porcentaje_ret_ir
        );
 
        array_push($retencionIr_arr["data"], $retencionIr_item);
    }
 
    echo json_encode($retencionIr_arr);
}
 
else{
    echo json_encode($retencionIr_arr);
}
?>