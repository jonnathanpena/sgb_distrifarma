<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/guiaEntrega.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$guiaEntrega = new GuiaEntrega($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$guiaEntrega->df_codigo_guia_ent= $info[0]["df_codigo_guia_ent"];
 
// query de lectura
$stmt = $guiaEntrega->read();
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
            "df_num_guia_entrega"=>$df_num_guia_entrega, 
            "df_codigo_guia_ent"=>$df_codigo_guia_ent,
            "df_repartidor_ent"=>$df_repartidor_ent,
            "df_cant_total_producto_ent"=>$df_cant_total_producto_ent,
            "df_cant_facturas_ent"=>$df_cant_facturas_ent,
            "df_fecha_ent"=>$df_fecha_ent,
            "df_creadoBy_ent"=>$df_creadoBy_ent,
            "df_modificadoBy_ent"=>$df_modificadoBy_ent,
            "df_guia_ent_recibido"=>$df_guia_ent_recibido,
            "df_nombre_per"=>$df_nombre_per,
            "df_apellido_per"=>$df_apellido_per
        );
 
        array_push($guiaEntrega_arr["data"], $guiaEntrega_item);
    }
 
    echo json_encode($guiaEntrega_arr);
}
 
else{
    echo json_encode($guiaEntrega_arr);
}
?>