<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaRemision.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaRemision = new GuiaRemision($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$guiaRemision->df_guia_remision= $info[0]["df_guia_remision"];
// query de lectura
$stmt = $guiaRemision->readById();
$num = $stmt->rowCount();

//guiaRemision array
$guiaRemision_arr=array();
$guiaRemision_arr["data"]=array();
 
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
        $guiaRemision_item=array(
            "df_guia_remision"=>$df_guia_remision * 1, 
            "df_codigo_rem"=>$df_codigo_rem,
            "df_fecha_remision"=>$df_fecha_remision,
            "df_sector_cod_rem"=>$df_sector_cod_rem * 1,
            "df_vendedor_rem"=>$df_vendedor_rem * 1,
            "df_cant_total_producto_rem"=>$df_cant_total_producto_rem * 1,
            "df_valor_efectivo_rem"=>$df_valor_efectivo_rem,
            "df_creadoBy_rem"=>$df_creadoBy_rem * 1,
            "df_modificadoBy_rem"=>$df_modificadoBy_rem * 1,
            "df_nombre_sector"=>$df_nombre_sector,
            "df_nombre_per"=>$df_nombre_per,
            "df_apellido_per"=>$df_apellido_per
        );
 
        array_push($guiaRemision_arr["data"], $guiaRemision_item);
    }
 
    echo json_encode($guiaRemision_arr);
}
 
else{
    echo json_encode($guiaRemision_arr);
}
?>