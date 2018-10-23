<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/retencionIVA.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$retencionIva = new RetencionIva($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$retencionIva->nombre_ret_iva= $info[0]["nombre_ret_iva"];
// query de lectura
$stmt = $retencionIva->readAll();
$num = $stmt->rowCount();

// retencionIva array
$retencionIva_arr=array();
$retencionIva_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
    
        $retencionIva_item=array(
            "id_retencion_iva"=>$id_retencion_iva, 
            "nombre_ret_iva"=>$nombre_ret_iva,
            "porcentaje_ret_iva"=>$porcentaje_ret_iva
        );
 
        array_push($retencionIva_arr["data"], $retencionIva_item);
    }
 
    echo json_encode($retencionIva_arr);
}
 
else{
    echo json_encode($retencionIva_arr);
}
?>