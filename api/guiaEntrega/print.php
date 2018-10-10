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

$guiaEntrega->df_num_guia_entrega= $info[0]["df_num_guia_entrega"];
// query de lectura
$stmt = $guiaEntrega->readById();
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
        $personal = getPersonal($df_repartidor_ent, $db);
        $detalles = getDetalle($df_num_guia_entrega, $db);
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
            "personal"=>$personal,
            "detalles"=>$detalles
        );
 
        array_push($guiaEntrega_arr["data"], $guiaEntrega_item);
    }
 
    echo json_encode($guiaEntrega_arr);
}
 
else{
    echo json_encode($guiaEntrega_arr);
}

function getPersonal($personal_id, $db) {
    include_once '../objects/personal.php';
    $personal = new Personal($db);
    $personal->df_id_personal = $personal_id;
    $stmt = $personal->readById();
    $num = $stmt->rowCount();
    $personal_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $personal_item=array(
                "df_id_personal"=>$df_id_personal,
                "df_tipo_documento_per"=>$df_tipo_documento_per,
                "df_nombre_per"=>$df_nombre_per,
                "df_apellido_per"=>$df_apellido_per,
                "df_cargo_per"=>$df_cargo_per,
                "df_fecha_ingreso"=>$df_fecha_ingreso,
                "df_documento_per"=>$df_documento_per,
                "df_correo_per"=>$df_correo_per,
                "df_codigo_personal"=>$df_codigo_personal,
                "df_activo_per"=>$df_activo_per,
                "df_sueldo_detper"=>$df_sueldo_detper,
                "df_bono_detper"=>$df_bono_detper,
                "df_anticipo_detper"=>$df_anticipo_detper,
                "df_descuento_detper"=>$df_descuento_detper,
                "df_decimos_detper"=>$df_decimos_detper,
                "df_vacaciones_detper"=>$df_vacaciones_detper,
                "df_tabala_comision_detper"=>$df_tabala_comision_detper,
                "df_comisiones_detper"=>$df_comisiones_detper,
                "df_personal_cod_detper"=>$df_personal_cod_detper,
                "df_fecha_proceso"=>$df_fecha_proceso,
                "df_usuario_detper"=> $df_usuario_detper    
            );
     
            array_push($personal_arr, $personal_item);
        }
    }
    return $personal_arr[0];
}

function getDetalle($guia, $db) {
    include_once '../objects/detalleEntrega.php';
    $detalleEntrega = new DetalleEntrega($db);
    $detalleEntrega->df_guia_entrega = $guia;
    $stmt = $detalleEntrega->readById();
    $num = $stmt->rowCount();
    $detalleEntrega_arr=array();
    if($num>0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $detalleEntrega_item=array(
                "df_id_detent"=>$df_id_detent, 
                "df_guia_entrega"=>$df_guia_entrega,
                "df_cod_producto"=>$df_cod_producto, 
                 "df_cant_producto_detent"=>$df_cant_producto_detent,
                 "df_factura_detent"=>$df_factura_detent,
                 "df_nom_producto_detent"=>$df_nom_producto_detent,
                 "df_num_factura_detent"=>$df_num_factura_detent
            );
     
            array_push($detalleEntrega_arr, $detalleEntrega_item);
        }
     
        return $detalleEntrega_arr;
    }
}

?>