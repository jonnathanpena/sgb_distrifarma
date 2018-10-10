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
        $personal = getPersonal($df_repartidor_rec, $db);
        $detalles = getDetalles($df_guia_recepcion, $db);
        //Los nombres acá son iguales a los de la clase iguales a las columnas de la BD
        $guiaRecepcion_item=array(
            "df_guia_recepcion"=>$df_guia_recepcion, 
            "df_codigo_guia_rec"=>$df_codigo_guia_rec,
            "df_fecha_recepcion"=>$df_fecha_recepcion,
            "df_repartidor_rec"=>$df_repartidor_rec,
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
            "personal"=>$personal,
            "detalles"=>$detalles
        );
 
        array_push($guiaRecepcion_arr["data"], $guiaRecepcion_item);
    }
}

echo json_encode($guiaRecepcion_arr);

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

function getDetalles($guia, $db) {
    include_once '../objects/detalleRecepcion.php';
    $detalleRecepcion = new DetalleRecepcion($db);
    $detalleRecepcion->df_guia_recepcion_detrec= $guia;
    $stmt = $detalleRecepcion->readById();
    $num = $stmt->rowCount();
    $detalleRecepcion_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $detalleRecepcion_item=array(
                "df_id_detrec"=>$df_id_detrec, 
                "df_guia_recepcion_detrec"=>$df_guia_recepcion_detrec,
                "df_factura_rec"=>$df_factura_rec, 
                "df_cant_producto_detrec"=>$df_cant_producto_detrec,
                "df_producto_cod_detrec"=>$df_producto_cod_detrec,
                "df_nueva_fecha"=>$df_nueva_fecha,
                "df_edo_prod_fact_detrec" =>$df_edo_prod_fact_detrec
            );
     
            array_push($detalleRecepcion_arr, $detalleRecepcion_item);
        }
    }
    return $detalleRecepcion_arr;
}
?>