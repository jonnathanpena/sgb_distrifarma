<?php
class RetencionCompraVenta {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_ret_com_ven;
    public $compra_id;
    public $venta_id;
    public $serie_retencion;
    public $num_retencion;
    public $autorizacion_ret;
    public $fecha_ingreso_ret;
    public $fecha_caduca_ret;
    public $retencion_iva_id;
    public $porcentaje_iva;
    public $base_imponible;
    public $retencion_ir_id;
    public $porcentaje_ir;
    public $base_imponible_c_iva;
    public $base_imponible_s_iva;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todos los retencion por ID
    function readById(){
    
        // select one query
        $query = "SELECT `id_ret_com_ven`, `compra_id`, `venta_id`, `serie_retencion`, `num_retencion`, 
                `autorizacion_ret`, `fecha_ingreso_ret`, `fecha_caduca_ret`, `retencion_iva_id`, `porcentaje_iva`, 
                `base_imponible`, `retencion_ir_id`, `porcentaje_ir`, `base_imponible_c_iva`, `base_imponible_s_iva`
                FROM `retencion_compra_venta`  WHERE `id_ret_com_ven` = ".$this->id_ret_com_ven;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos retención por id de la compra
    function readByCompra(){
    
        // select one query
        $query = "SELECT `id_ret_com_ven`, `compra_id`, `venta_id`, `serie_retencion`, `num_retencion`, 
                `autorizacion_ret`, `fecha_ingreso_ret`, `fecha_caduca_ret`, `retencion_iva_id`, `porcentaje_iva`, 
                `base_imponible`, `retencion_ir_id`, `porcentaje_ir`, `base_imponible_c_iva`, `base_imponible_s_iva`
                FROM `retencion_compra_venta`  WHERE`compra_id` = ".$this->compra_id;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos retención por id de la venta o factura
    function readByVenta(){
    
        // select one query
        $query = "SELECT `id_ret_com_ven`, `compra_id`, `venta_id`, `serie_retencion`, `num_retencion`, 
                `autorizacion_ret`, `fecha_ingreso_ret`, `fecha_caduca_ret`, `retencion_iva_id`, `porcentaje_iva`, 
                `base_imponible`, `retencion_ir_id`, `porcentaje_ir`, `base_imponible_c_iva`, `base_imponible_s_iva`
                FROM `retencion_compra_venta`  WHERE`venta_id` = ".$this->venta_id;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un retenciones de iva e ir
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `retencion_compra_venta`(`compra_id`, `venta_id`, `serie_retencion`, `num_retencion`, 
                `autorizacion_ret`, `fecha_ingreso_ret`, `fecha_caduca_ret`, `retencion_iva_id`, `porcentaje_iva`, 
                `base_imponible`, `retencion_ir_id`, `porcentaje_ir`, `base_imponible_c_iva`, `base_imponible_s_iva`)  
                VALUES (
                        ".$this->compra_id.",
                        ".$this->venta_id.",
                        '".$this->serie_retencion."',
                        '".$this->num_retencion."',
                        '".$this->autorizacion_ret."',
                        '".$this->fecha_ingreso_ret."',
                        '".$this->fecha_caduca_ret."',
                        ".$this->retencion_iva_id.",
                        ".$this->porcentaje_iva.",
                        ".$this->base_imponible.",
                        ".$this->retencion_ir_id.",
                        ".$this->porcentaje_ir.",
                        ".$this->base_imponible_c_iva.",
                        ".$this->base_imponible_s_iva."
                    )";
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
    }

    // actualizar datos de retencion
    function update(){
    
        // query 
        $query = "UPDATE `retencion_compra_venta` SET 
                    `compra_id`= ".$this->compra_id.",
                    `venta_id`= ".$this->venta_id.",
                    `serie_retencion`= '".$this->serie_retencion."',
                    `num_retencion`= '".$this->num_retencion."',
                    `autorizacion_ret`= '".$this->autorizacion_ret."',
                    `fecha_ingreso_ret`= '".$this->fecha_ingreso_ret."',
                    `fecha_caduca_ret`= '".$this->fecha_caduca_ret."',
                    `retencion_iva_id`= ".$this->retencion_iva_id.",
                    `porcentaje_iva`= ".$this->porcentaje_iva.",
                    `base_imponible`= ".$this->base_imponible.",
                    `retencion_ir_id`= ".$this->retencion_ir_id.",
                    `porcentaje_ir`= ".$this->porcentaje_ir.",
                    `base_imponible_c_iva`= ".$this->base_imponible_c_iva.",
                    `base_imponible_s_iva`= ".$this->base_imponible_s_iva." 
                    WHERE `id_ret_com_ven`=".$this->id_ret_com_ven;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function delete(){
    
        // query 
        $query = "DELETE FROM `detalle_pagos_compra` WHERE `id_dpc` = ". $this->id_dpc;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

}
?>