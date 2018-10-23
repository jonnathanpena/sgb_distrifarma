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
        $sector = getSector($df_sector_cod_rem, $db);
        $personal = getPersonal($df_vendedor_rem, $db);
        $detalles = getDetalle($df_guia_remision, $db);
        //Los nombres acá son iguales a los de la clase iguales a las columnas de la BD
        $guiaRemision_item=array(
            "df_guia_remision"=>$df_guia_remision, 
            "df_codigo_rem"=>$df_codigo_rem,
            "df_fecha_remision"=>$df_fecha_remision,
            "df_sector_cod_rem"=>$df_sector_cod_rem,
            "df_vendedor_rem"=>$df_vendedor_rem,
            "df_cant_total_producto_rem"=>$df_cant_total_producto_rem,
            "df_valor_efectivo_rem"=>$df_valor_efectivo_rem,
            "df_creadoBy_rem"=>$df_creadoBy_rem,
            "df_modificadoBy_rem"=>$df_modificadoBy_rem,
            "df_guia_rem_recibido"=>$df_guia_rem_recibido,
            "sector"=>$sector,
            "personal"=>$personal,
            "detalles"=>$detalles
        );
 
        array_push($guiaRemision_arr["data"], $guiaRemision_item);
    }
 
    echo json_encode($guiaRemision_arr);
}
 
else{
    echo json_encode($guiaRemision_arr);
}

function getSector($sector_id, $db) {
    include_once '../objects/zona.php';
    $zona = new Zona($db);
    $zona->df_codigo_zona= $sector_id;
    $stmt = $zona->readById();
    $num = $stmt->rowCount();
    $zona_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $zona_item=array(
                "df_codigo_zona"=>$df_codigo_zona, 
                "df_nombre_zona"=>$df_nombre_zona
            );     
            array_push($zona_arr, $zona_item);
        }
    }
    return $zona_arr[0];
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
    include_once '../objects/detalleRemision.php';
    $detalleRemision = new DetalleRemision($db);
    $detalleRemision->df_guia_remision_detrem = $guia;
    $stmt = $detalleRemision->readById();
    $num = $stmt->rowCount();
    $detalleRemision_arr=array();
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $detalleRemision_item=array(
                "df_id_detrem"=>$df_id_detrem, 
                "df_guia_remision_detrem"=>$df_guia_remision_detrem,
                "df_producto_precio_detrem"=>$df_producto_precio_detrem, 
                "df_cant_producto_detrem"=>$df_cant_producto_detrem,
                "df_nombre_und_detrem"=>$df_nombre_und_detrem,
                "df_cant_x_und_detrem"=>$df_cant_x_und_detrem,
                "df_valor_sin_iva_detrem"=>$df_valor_sin_iva_detrem,
                "df_iva_detrem"=>$df_iva_detrem,
                "df_valor_total_detrem"=>$df_valor_total_detrem,
                "df_id_producto"=>$df_id_producto,
                "df_codigo_prod"=>$df_codigo_prod,
                "df_nombre_producto"=>$df_nombre_producto
            );
            array_push($detalleRemision_arr, $detalleRemision_item);
        }        
    }
    return $detalleRemision_arr;
}
?>