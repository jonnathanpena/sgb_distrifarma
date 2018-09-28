<?php
class DetallePagoCompra {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_dpc;
    public $compra_id;
    public $metodo_pago_id;
    public $egreso_id;
    public $banco_emisor;
    public $banco_receptor;
    public $codigo;
    public $fecha;
    public $tipo_tarjeta;
    public $franquicia;
    public $recibo;
    public $titular;
    public $cheque;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todos los detalles compra gasto por ID
    function readById(){
    
        // select one query
        $query = "SELECT `id_dpc`, `compra_id`, `metodo_pago_id`, `egreso_id`, `banco_emisor`, `banco_receptor`, 
                    `codigo`, `fecha`, `tipo_tarjeta`, `franquicia`, `recibo`, `titular`, `cheque` 
                    FROM `detalle_pagos_compra` WHERE `id_dpc` = ".$this->id_dpc;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos los detalles compra gasto por id de la compra
    function readByCompra(){
    
        // select one query
        $query = "SELECT `id_dpc`, `compra_id`, `metodo_pago_id`, `egreso_id`, `banco_emisor`, `banco_receptor`, 
                    `codigo`, `fecha`, `tipo_tarjeta`, `franquicia`, `recibo`, `titular`, `cheque` 
                    FROM `detalle_pagos_compra` WHERE `compra_id` = ".$this->compra_id;

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un detalle compra gasto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `detalle_pagos_compra`(`compra_id`, `metodo_pago_id`, `egreso_id`, `banco_emisor`, 
                `banco_receptor`, `codigo`, `fecha`, `tipo_tarjeta`, `franquicia`, `recibo`, `titular`, `cheque`) 
                VALUES (
                        ".$this->compra_id.",
                        '".$this->metodo_pago_id."',
                        ".$this->egreso_id.",
                        ".$this->banco_emisor.",
                        ".$this->banco_receptor.",
                        '".$this->codigo."',
                        '".$this->fecha."',
                        ".$this->tipo_tarjeta.",
                        ".$this->franquicia.",
                        '".$this->recibo."',
                        '".$this->titular."',
                        '".$this->cheque."',
                    )";
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de un detalle de compra por gasto
    function update(){
    
        // query 
        $query = "UPDATE `detalle_pagos_compra` SET 
                    `compra_id`= ".$this->compra_id.",
                    `metodo_pago_id`= ".$this->metodo_pago_id.",
                    `egreso_id`= ".$this->egreso_id.",
                    `banco_emisor`= ".$this->banco_emisor.",
                    `banco_receptor`= ".$this->banco_receptor.",
                    `codigo`= '".$this->codigo."',
                    `fecha`=  '".$this->fecha."',
                    `tipo_tarjeta`= ".$this->tipo_tarjeta.",
                    `franquicia`= ".$this->franquicia.",
                    `recibo`= '".$this->recibo."',
                    `titular`= '".$this->titular."',
                    `cheque`= '".$this->cheque."'
                    WHERE `id_dpc` = ".$this->id_dpc;
    
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