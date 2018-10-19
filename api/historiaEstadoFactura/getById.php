<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/historiaEstadoFactura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$historiaEstadoFactura = new HistoriaEstadoFactura($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$historiaEstadoFactura->df_num_factura= $info[0]["df_num_factura"];
// query de lectura
$stmt = $historiaEstadoFactura->readById();
$num = $stmt->rowCount();

//historiaEstadoFactura array
$historiaEstadoFactura_arr=array();
$historiaEstadoFactura_arr["data"]=array();
 
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
        $historiaEstadoFactura_item=array(
            "df_id_hist_edo_factura"=>$df_id_hist_edo_factura, 
            "df_num_factura"=>$df_num_factura,
            "df_edo_factura"=>$df_edo_factura,
            "df_edo_impresion"=>$df_edo_impresion,
            "df_usuario_id"=>$df_usuario_id,
            "df_fecha_proceso"=>$df_fecha_proceso,
            "df_sector_factura"=>$df_sector_factura,
            "df_direccion_factura"=>$df_direccion_factura
        );
 
        array_push($historiaEstadoFactura_arr["data"], $historiaEstadoFactura_item);
    }
 
    echo json_encode($historiaEstadoFactura_arr);
}
 
else{
    echo json_encode($historiaEstadoFactura_arr);
}
?>