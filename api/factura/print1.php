<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// incluye la configuración de la base de datos y la conexión
include_once '../config/database.php';
include_once '../objects/factura.php';
 
// inicia la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
 
// inicia el objeto
$factura = new Factura($db);

// get posted data
$data = json_decode(file_get_contents('php://input'), true);

$info = array($data);

$factura->df_num_factura = $info[0]["df_num_factura"];
// query de lectura
$stmt = $factura->readById();
$num = $stmt->rowCount();

//factura array
$factura_arr=array();
$factura_arr["data"]=array();
 
// check if more than 0 record found
if($num>0){ 
    
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $cliente = getCliente($df_cliente_cod_fac, $db);
        $personal = getPersonal($df_personal_cod_fac, $db);
        $sector = getSector($df_sector_cod_fac, $db);
        $detalleFactura = getDetalle($df_num_factura, $db);
        //Los nombres acá son iguales a los de la clase iguales a las columnas de la BD
        $factura_item=array(
            "df_num_factura"=>$df_num_factura, 
            "df_fecha_fac"=>$df_fecha_fac, 
            "df_forma_pago_fac"=>$df_forma_pago_fac,
            "df_subtotal_fac"=>$df_subtotal_fac,
            "df_descuento_fac"=>$df_descuento_fac,
            "df_iva_fac"=>$df_iva_fac,
            "df_valor_total_fac"=>$df_valor_total_fac,
            "df_creadaBy"=>$df_creadaBy,
            "df_fecha_creacion"=>$df_fecha_creacion,
            "df_edo_factura_fac"=>$df_edo_factura_fac, 
            "df_fecha_entrega_fac"=>$df_fecha_entrega_fac,
            "cliente"=>$cliente,
            "personal"=>$personal,
            "sector"=>$sector,
            "detalles"=>$detalleFactura
        );
 
        array_push($factura_arr["data"], $factura_item);
    }
 
    echo json_encode($factura_arr);
}
 
else{
    echo json_encode($factura_arr);
}

function getCliente($id_cliente, $db) {
    include_once '../objects/cliente.php';
    $cliente = new Cliente($db);
    $cliente->df_id_cliente = $id_cliente;
    $cliente->df_codigo_cliente = $id_cliente;
    $stmt = $cliente->readById();
    $num = $stmt->rowCount();
    $cliente_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $cliente_item=array(
                "df_id_cliente"=>$df_id_cliente,
                "df_codigo_cliente"=>$df_codigo_cliente,
                "df_nombre_cli"=>$df_nombre_cli,
                "df_razon_social_cli"=>$df_razon_social_cli,
                "df_tipo_documento_cli"=>$df_tipo_documento_cli,
                "df_documento_cli"=>$df_documento_cli,
                "df_direccion_cli"=>$df_direccion_cli,
                "df_referencia_cli"=>$df_referencia_cli,
                "df_sector_cod"=>$df_sector_cod,
                "df_email_cli"=>$df_email_cli,
                "df_telefono_cli"=>$df_telefono_cli,
                "df_celular_cli"=>$df_celular_cli
            );
     
            array_push($cliente_arr, $cliente_item);
        }
    }
    return $cliente_arr[0];
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

function getSector($sector_id, $db) {
    include_once '../objects/sector.php';
    $sector = new Sector($db);
    $sector->df_codigo_sector = $sector_id;
    $stmt = $sector->readById();
    $num = $stmt->rowCount();
    $sector_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $sector_item=array(
                "df_codigo_sector"=>$df_codigo_sector, 
                "df_nombre_sector"=>$df_nombre_sector
            );
     
            array_push($sector_arr, $sector_item);
        }
    }
    return $sector_arr[0];
}

function getDetalle($factura, $db) {
    include_once '../objects/detalleFactura.php';
    $detalleFactura = new DetalleFactura($db);
    $detalleFactura->df_num_factura_detfac = $factura;
    $stmt = $detalleFactura->readById();
    $num = $stmt->rowCount();
    $detalleFactura_arr=array(); 
    if($num>0){ 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $detalleFactura_item=array(
                "df_id_factura_detfac"=>$df_id_factura_detfac, 
                "df_num_factura_detfac"=>$df_num_factura_detfac,
                "df_prod_precio_detfac"=>$df_prod_precio_detfac,
                "df_precio_prod_detfac"=>$df_precio_prod_detfac,
                "df_id_producto"=>$df_id_producto,
                "df_nombre_producto"=>$df_nombre_producto,
                "df_codigo_prod"=>$df_codigo_prod,
                "df_cantidad_detfac"=>$df_cantidad_detfac,
                "df_nombre_und_detfac"=>$df_nombre_und_detfac,
                "df_cant_x_und_detfac"=>$df_cant_x_und_detfac,
                "df_edo_entrega_prod_detfac"=>$df_edo_entrega_prod_detfac,
                "df_valor_sin_iva_detfac"=>$df_valor_sin_iva_detfac,
                "df_iva_detfac"=>$df_iva_detfac,
                "df_valor_total_detfac"=>$df_valor_total_detfac
            );
     
            array_push($detalleFactura_arr, $detalleFactura_item);
        }
    }
    return $detalleFactura_arr;
}
?>