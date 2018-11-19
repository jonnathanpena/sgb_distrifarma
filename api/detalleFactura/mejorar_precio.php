<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/detalleFactura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$detalleFactura = new DetalleFactura($db);
 
// query de lectura
$stmt = $detalleFactura->consultarNuevosCostos();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        modificarPrecio($db, $total_tupla, $df_id_factura_detfac);
    }
}

function modificarPrecio($db, $nuevo_precio, $id) {
    include_once '../objects/detalleFactura.php';
    $detalleFactura = new DetalleFactura($db);
    $detalleFactura->df_valor_total_detfac = $nuevo_precio;
    $detalleFactura->df_id_factura_detfac = $id;
    $detalleFactura->modificarPrecio();
}
?>