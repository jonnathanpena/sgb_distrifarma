<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/reportes.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$reportes = new Reportes($db);

$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
$reportes->df_fecha_ini= $info[0]["df_fecha_ini"];
$reportes->df_fecha_fin= $info[0]["df_fecha_fin"];
// query de lectura
$stmt = $reportes->readByVentaVendedor();
$num = $stmt->rowCount();

//reportes array
$reportes_arr=array();
$reportes_arr["data"]=array();
 
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
        $reportes_item=array(
            "COUNT_FACTURA"=>$COUNT_FACTURA, 
            "df_personal_cod_fac"=>$df_personal_cod_fac,
            "df_nombre_per"=>$df_nombre_per,
            "df_apellido_per"=>$df_apellido_per,
            "df_cargo_per"=>$df_cargo_per,
            "VALOR_VENDIDO"=>$VALOR_VENDIDO,
            "VALOR_ANULADO"=>$VALOR_ANULADO
        );
 
        array_push($reportes_arr["data"], $reportes_item);
    }
 
    echo json_encode($reportes_arr);
}
 
else{
    echo json_encode($reportes_arr);
}
?>