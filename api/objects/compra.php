<?php
date_default_timezone_set('Australia/Melbourne');
$hoy = date('Y-m-d H:i:s');
class Compra {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_compra;
    public $usuario_id;
    public $fecha_compra;
    public $proveedor_id;
    public $detalle_sustento_comprobante_id;
    public $serie_compra;
    public $documento_compra;
    public $autorizacion_compra;
    public $fecha_comprobante_compra;
    public $fecha_ingreso_bodega_compra;
    public $fecha_caducidad_compra;
    public $vencimiento_compra;
    public $descripcion_compra;
    public $condiciones_compra;
    public $st_con_iva_compra;
    public $descuento_con_iva_compra;
    public $total_con_iva_compra;
    public $st_sin_iva_compra;
    public $descuento_sin_iva_compra;
    public $total_sin_iva_compra;
    public $st_iva_cero_compra;
    public $descuento_iva_cero_compra;
    public $total_iva_cero;
    public $ice_cc_compra;
    public $imp_verde_compra;
    public $iva_compra;
    public $otros_compra;
    public $interes_compra;
    public $bonificacion_compra;
    public $total_compra;
    public $df_nombre_empresa;
    public $df_codigo_proveedor;
    public $df_usuario_usuario;   

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener cliente de login
    function readAll(){
    
        // select all query
        $query = "SELECT comp.`id_compra`, comp.`usuario_id`, comp.`fecha_compra`, comp.`proveedor_id`, comp.`detalle_sustento_comprobante_id`, comp.`serie_compra`, 
                    comp.`documento_compra`, comp.`autorizacion_compra`, comp.`fecha_comprobante_compra`, comp.`fecha_ingreso_bodega_compra`, comp.`fecha_caducidad_compra`, 
                    comp.`vencimiento_compra`, comp.`descripcion_compra`, comp.`condiciones_compra`, comp.`st_con_iva_compra`, comp.`descuento_con_iva_compra`, 
                    comp.`total_con_iva_compra`, comp.`st_sin_iva_compra`, comp.`descuento_sin_iva_compra`, comp.`total_sin_iva_compra`, comp.`st_iva_cero_compra`, 
                    comp.`descuento_iva_cero_compra`, comp.`total_iva_cero`, comp.`ice_cc_compra`, comp.`imp_verde_compra`, comp.`iva_compra`, comp.`otros_compra`, 
                    comp.`interes_compra`, comp.`bonificacion_compra`, comp.`total_compra`, prov.`df_nombre_empresa`, prov.`df_codigo_proveedor`, usu.`df_usuario_usuario`
                    FROM `compra` as comp
                    JOIN `df_proveedor` as prov ON (comp.`proveedor_id` = prov.`df_id_proveedor`)
                    JOIN `df_usuario` as usu ON (comp.`usuario_id` = usu.`df_id_usuario`)
                    WHERE prov.`df_nombre_empresa` LIKE '%".$this->df_nombre_empresa."%'
                    OR usu.`df_usuario_usuario` LIKE '%".$this->df_nombre_empresa."%' 
                    ORDER BY comp.`id_compra` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un cliente
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `compra`(`usuario_id`, `fecha_compra`, `proveedor_id`, `detalle_sustento_comprobante_id`, `serie_compra`, 
                    `documento_compra`, `autorizacion_compra`, `fecha_comprobante_compra`, `fecha_ingreso_bodega_compra`, `fecha_caducidad_compra`, 
                    `vencimiento_compra`, `descripcion_compra`, `condiciones_compra`, `st_con_iva_compra`, `descuento_con_iva_compra`, `total_con_iva_compra`, 
                    `st_sin_iva_compra`, `descuento_sin_iva_compra`, `total_sin_iva_compra`, `st_iva_cero_compra`, `descuento_iva_cero_compra`, `total_iva_cero`, 
                    `ice_cc_compra`, `imp_verde_compra`, `iva_compra`, `otros_compra`, `interes_compra`, `bonificacion_compra`, `total_compra`) 
                    VALUES (
                        ".$this->usuario_id.",
                        '".$this->fecha_compra."',
                        ".$this->proveedor_id.",
                        ".$this->detalle_sustento_comprobante_id.",
                        '".$this->serie_compra."',
                        '".$this->documento_compra."',
                        '".$this->autorizacion_compra."',
                        '".$this->fecha_comprobante_compra."',
                        '".$this->fecha_ingreso_bodega_compra."',
                        '".$this->fecha_caducidad_compra."',
                        '".$this->vencimiento_compra."',
                        '".$this->descripcion_compra."',
                        ".$this->condiciones_compra.",
                        ".$this->st_con_iva_compra.",
                        ".$this->descuento_con_iva_compra.",
                        ".$this->total_con_iva_compra.",
                        ".$this->st_sin_iva_compra.",
                        ".$this->descuento_sin_iva_compra.",
                        ".$this->total_sin_iva_compra.",
                        ".$this->st_iva_cero_compra.",
                        ".$this->descuento_iva_cero_compra.",
                        ".$this->total_iva_cero.",
                        ".$this->ice_cc_compra.",
                        ".$this->imp_verde_compra.",
                        ".$this->iva_compra.",
                        ".$this->otros_compra.",
                        ".$this->interes_compra.",
                        ".$this->bonificacion_compra.",
                        ".$this->total_compra.")";        
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return $stmt;
        }
        
        
    }     

}
?>