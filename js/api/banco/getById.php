<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/banco.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$banco = new Banco($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$banco->df_id_banco= $info[0]["df_id_banco"];
// query de lectura
$stmt = $banco->readById();
$num = $stmt->rowCount();

//banco array
$banco_arr=array();
$banco_arr["data"]=array();
 
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
        $banco_item=array(
            "df_id_banco"=>$df_id_banco,
            "df_fecha_banco"=>$df_fecha_banco,
            "df_usuario_id_banco"=>$df_usuario_id_banco,
            "df_tipo_movimiento"=>$df_tipo_movimiento,
            "df_monto_banco"=>$df_monto_banco,
            "df_saldo_banco"=>$df_saldo_banco,
            "df_num_documento_banco"=>$df_num_documento_banco,
            "df_detalle_mov_banco"=>$df_detalle_mov_banco,
            "df_modificadoBy_banco"=>$df_modificadoBy_banco
        );
 
        array_push($banco_arr["data"], $banco_item);
    }
 
    echo json_encode($banco_arr);
}
 
else{
    echo json_encode($banco_arr);
}
?>