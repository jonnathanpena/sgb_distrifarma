<?php
class DetalleCompraProducto {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_dcp;
    public $compra_id;
    public $codigo_dcp;
    public $cantidad_dcp;
    public $precio_unitario_dcp;
    public $total_dcp;
    public $producto_id;
    public $promedio;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todos los detalles compra producto por ID
    function readById(){
    
        // select one query
        $query = "SELECT `id_dcp`, `compra_id`, `codigo_dcp`, `cantidad_dcp`, `precio_unitario_dcp`, `total_dcp` 
                    FROM `detalle_compra_producto` 
                    WHERE `id_dcp` = ".$this->id_dcp;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos los detalles compra producto por id de la compra
    function readByCompra(){
    
        // select one query
        $query = "SELECT `id_dcp`, `compra_id`, `codigo_dcp`, `cantidad_dcp`, `precio_unitario_dcp`, `total_dcp` 
                    FROM `detalle_compra_producto` 
                    WHERE `compra_id` = ".$this->compra_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un detalle compra producto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `detalle_compra_producto`(`compra_id`, `codigo_dcp`, `cantidad_dcp`, 
                    `precio_unitario_dcp`, `total_dcp`) VALUES (
                        ".$this->compra_id.",
                        '".$this->codigo_dcp."',
                        ".$this->cantidad_dcp.",
                        ".$this->precio_unitario_dcp.",
                        ".$this->total_dcp."
                    )";
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de un detalle de compra por producto
    function update(){
    
        // query 
        $query = "UPDATE `detalle_compra_producto` SET 
                    `codigo_dcp`= '".$this->codigo_dcp."',
                    `cantidad_dcp`= ".$this->cantidad_dcp.",
                    `precio_unitario_dcp`= ".$this->precio_unitario_dcp.",
                    `total_dcp`= ".$this->total_dcp." 
                    WHERE `id_dcp` = ".$this->id_dcp;
    
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
        $query = "DELETE FROM `detalle_compra_producto` WHERE `id_dcp` = ".$this->id_dcp;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function bootstraper() {
        $query = "SELECT AVG((det.`cantidad_dcp` * det.`precio_unitario_dcp`)/(det.`bonificacion_dcp` + det.`cantidad_dcp`)) as promedio
                    FROM `detalle_compra_producto` as det
                    JOIN `compra` as comp ON (det.`compra_id` = comp.`id_compra`)
                    WHERE det.`producto_id` = ".$this->producto_id." AND comp.`fecha_compra` >= DATE_ADD(NOW(), INTERVAL -3 MONTH)";
        
        $stmt = $this->conn->prepare($query);
    
        $stmt->execute();
    
        return $stmt;
    }

}
?>