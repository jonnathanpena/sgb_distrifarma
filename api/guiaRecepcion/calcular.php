<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get posted data
$data = json_decode(file_get_contents('php://input'), true);
$subtotal = 0;
$total_iva = 0;
$total = 0;

for ($i = 0; $i < count($data); $i++) {
    $iva = $data[$i]['df_iva_detfac'] * 1;
    $precio_unitario = $data[$i]['df_precio_prod_detfac'] * 1;
    $cantidad = $data[$i]['df_cantidad_detfac'] * 1;
    $sub = $precio_unitario * $cantidad;
    $subtotal = $subtotal + $sub;
    $ti = $sub * $iva;
    $total_iva = $total_iva + $ti;
    $total = $subtotal + $total_iva;
}

echo $total;
?>