<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaRecepcion.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaRecepcion = new GuiaRecepcion($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$guiaRecepcion->df_guia_recepcion= $info[0]["df_guia_recepcion"];
// query de lectura
$stmt = $guiaRecepcion->readById();
$num = $stmt->rowCount();

//guiaRecepcion array
$guiaRecepcion_arr=array();
$guiaRecepcion_arr["data"]=array();
 
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
        $guiaRecepcion_item=array(
            "df_guia_recepcion"=>$df_guia_recepcion, 
            "df_codigo_guia_rec"=>$df_codigo_guia_rec,
            "df_fecha_recepcion"=>$df_fecha_recepcion,
            "df_repartidor_rec"=>$df_repartidor_rec,
            "df_cant_und_rec"=>$df_cant_und_rec,
            "df_cant_caja_rec"=>$df_cant_caja_rec,
            "df_valor_recaudado"=>$df_valor_recaudado,
            "df_valor_efectivo"=>$df_valor_efectivo,
            "df_valor_cheque"=>$df_valor_cheque,
            "df_retenciones"=>$df_retenciones,
            "df_descuento_rec"=>$df_descuento_rec,
            "df_diferencia_rec"=>$df_diferencia_rec,
			"df_remision_rec"=>$df_remision_rec,
			"df_entrega_rec"=>$df_entrega_rec,
			"df_num_guia"=>$df_num_guia,
			"df_creadoBy_rec"=>$df_creadoBy_rec,
			"df_modificadoBy_rec"=>$df_modificadoBy_rec,
			"df_edo_factura_rec"=>$df_edo_factura_rec
        );
 
        array_push($guiaRecepcion_arr["data"], $guiaRecepcion_item);
    }
 
    echo json_encode($guiaRecepcion_arr);
}
 
else{
    echo json_encode($guiaRecepcion_arr);
}
?>