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
    $sub = (intval(($precio_unitario*$cantidad)*10000)) / 10000;
    $subtotal = $subtotal + $sub;
    $ti = (intval(($sub*$iva)*10000)) / 10000;
    $total_iva = $total_iva + $ti;
    $total = (intval(($subtotal + $total_iva)*10000)) / 10000;
}

echo $total;
?>