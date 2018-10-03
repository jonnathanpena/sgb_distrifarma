<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/login.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$login = new Login($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);
 
// configura los valores recibidos en post de la app
$login->df_usuario_usuario= $info[0]["df_usuario_usuario"];


// query de lectura
$stmt = $login->readLogin();
$num = $stmt->rowCount();



// login array
$login_arr=array();
$login_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $login_item=array(
            "df_id_usuario"=>$df_id_usuario, 
            "df_tipo_documento_usuario"=>$df_tipo_documento_usuario, 
            "df_documento_usuario"=>$df_documento_usuario, 
            "df_nombre_usuario"=>$df_nombre_usuario, 
            "df_apellido_usuario"=>$df_apellido_usuario, 
            "df_usuario_usuario"=>$df_usuario_usuario, 
            "df_personal_cod"=>$df_personal_cod, 
            "df_clave_usuario"=>$df_clave_usuario, 
            "df_activo"=>$df_activo, 
            "df_correo"=>$df_correo, 
            "df_tipo_usuario"=>$df_tipo_usuario
        );
 
        array_push($login_arr["data"], $login_item);
    }
 
    echo json_encode($login_arr);
}
 
else{
    echo json_encode($login_arr);
}
?>