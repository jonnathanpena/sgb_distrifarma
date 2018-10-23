<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/facturaRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$facturaRecepcion = new FacturaRecepcion($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$facturaRecepcion->df_id_guia_rec= $info[0]["df_id_guia_rec"];
// query de lectura
$stmt = $facturaRecepcion->readById();
$num = $stmt->rowCount();

//facturaRecepcion array
$facturaRecepcion_arr=array();
$facturaRecepcion_arr["data"]=array();
 
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
        $facturaRecepcion_item=array(
            "df_id_factura_rec"=>$df_id_factura_rec, 
			"df_id_guia_rec"=>$df_id_guia_rec,
			"df_num_factura_rec"=>$df_num_factura_rec, 
            "df_tipo_pago_rec"=>$df_tipo_pago_rec,
            "df_num_factura_rec"=>$df_num_factura_rec,
            "df_monto_rec"=>$df_monto_rec,
            "df_num_documento" =>$df_num_documento
        );
 
        array_push($facturaRecepcion_arr["data"], $facturaRecepcion_item);
    }
 
    echo json_encode($facturaRecepcion_arr);
}
 
else{
    echo json_encode($facturaRecepcion_arr);
}
?>