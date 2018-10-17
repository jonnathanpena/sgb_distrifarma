<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/factura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$factura = new Factura($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$factura->df_num_factura = $info[0]["df_num_factura"];
// query de lectura
$stmt = $factura->readById();
$num = $stmt->rowCount();

//factura array
$factura_arr=array();
$factura_arr["data"]=array();
 
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
        $factura_item=array(
            "df_num_factura"=>$df_num_factura, 
            "df_fecha_fac"=>$df_fecha_fac, 
            "df_cliente_cod_fac"=>$df_cliente_cod_fac,
            "df_personal_cod_fac"=>$df_personal_cod_fac,
            "df_sector_cod_fac"=>$df_sector_cod_fac,
            "df_forma_pago_fac"=>$df_forma_pago_fac,
            "df_subtotal_fac"=>$df_subtotal_fac,
            "df_descuento_fac"=>$df_descuento_fac,
            "df_iva_fac"=>$df_iva_fac,
            "df_valor_total_fac"=>$df_valor_total_fac,
            "df_creadaBy"=>$df_creadaBy,
            "df_fecha_creacion"=>$df_fecha_creacion,
            "df_edo_factura_fac"=>$df_edo_factura_fac, 
            "df_fecha_entrega_fac"=>$df_fecha_entrega_fac
        );
 
        array_push($factura_arr["data"], $factura_item);
    }
 
    echo json_encode($factura_arr);
}
 
else{
    echo json_encode($factura_arr);
}
?>