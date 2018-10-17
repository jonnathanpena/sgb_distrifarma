<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/cajaChicaIngreso.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$cajaChicaIngreso = new CajaChicaIngreso($db);
 
// query de lectura
$stmt = $cajaChicaIngreso->read();
$num = $stmt->rowCount();

//cajaChicaIngreso array
$cajaChicaIngreso_arr=array();
$cajaChicaIngreso_arr["data"]=array();
 
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
        $cajaChicaIngreso_item=array(
            "df_id_ingreso_cc"=>$df_id_ingreso_cc, 
            "df_fecha_ingreso"=>$df_fecha_ingreso,
            "df_usuario_id_ingreso"=>$df_usuario_id_ingreso,
            "df_num_cheque"=>$df_num_cheque,
            "df_valor_cheque"=>$df_valor_cheque,
            "df_saldo_cc"=>$df_saldo_cc
        ); 
 
        array_push($cajaChicaIngreso_arr["data"], $cajaChicaIngreso_item);
    }
 
    echo json_encode($cajaChicaIngreso_arr);
}
 
else{
    echo json_encode($cajaChicaIngreso_arr);
}
?>