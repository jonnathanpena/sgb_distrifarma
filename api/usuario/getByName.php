<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/usuario.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$usuario = new Usuario($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

// configura los valores recibidos en post de la app
$usuario->df_nombre_per= $info[0]["df_nombre_per"];

// query de lectura
$stmt = $usuario->readByNombre();
$num = $stmt->rowCount();

// usuario array
$usuario_arr=array();
$usuario_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $usuario_item=array(
            "df_codigo_per"=>$df_codigo_per, 
            "df_nombre_per"=>$df_nombre_per, 
            "df_cargo_per"=>$df_cargo_per, 
            "df_fecha_ingreso"=>$df_fecha_ingreso, 
            "df_documento_per"=>$df_documento_per, 
            "df_id_detper"=>$df_id_detper, 
            "df_sueldo_detper"=>$df_sueldo_detper, 
            "df_bono_detper"=>$df_bono_detper, 
            "df_anticipo_detper"=>$df_anticipo_detper, 
            "df_descuento_detper"=>$df_descuento_detper, 
            "df_decimos_detper"=>$df_decimos_detper, 
            "df_vacaciones_detper"=>$df_vacaciones_detper, 
            "df_tabala_comision_detper"=>$df_tabala_comision_detper,
            "df_comisiones_detper"=>$df_comisiones_detper, 
            "df_personal_cod"=>$df_personal_cod, 
            "df_usuario_detper"=>$df_usuario_detper, 
            "df_fecha_proceso"=>$df_fecha_proceso, 
            "df_id_usuario"=>$df_id_usuario, 
            "df_nombre_usuario"=>$df_nombre_usuario,
            "df_clave_usuario"=>$df_clave_usuario, 
            "df_activo"=>$df_activo
        );
 
        array_push($usuario_arr["data"], $usuario_item);
    }
 
    echo json_encode($usuario_arr);
}
 
else{
    echo json_encode($usuario_arr);
}
?>