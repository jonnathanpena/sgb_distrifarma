<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/login.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$login = new Login($db);
 
// query de lectura
$stmt = $login->readLogin();
$num = $stmt->rowCount();

//login de usuario array
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
        
        //Los nombres acá son iguales a los de la clase iguales a las columnas de la BD
        $login_item=array(
            "df_id_usuario"=>$df_id_usuario,
            "df_nombre_usuario"=>$df_nombre_usuario,
            "df_personal_cod"=>$df_personal_cod, 
            "df_clave_usuario"=>$df_clave_usuario,
            "df_activo"=>$df_activo
        );
 
        array_push($login_arr["data"], $login_item);
    }
 
    echo json_encode($login_arr);
}
 
else{
    echo json_encode($login_arr);
}
?>