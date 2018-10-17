<?php
class DetalleCompraProducto {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_dcp; 
    public $compra_id;
    public $producto_id;
    public $bonificacion_dcp;
    public $cantidad_dcp;
    public $precio_unitario_dcp;
    public $descuento_dcp;
    public $iva_dcp;
    public $subtotal_dcp;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    function readByCompra(){
    
        // select all query
        $query = "SELECT `id_dcp`, `compra_id`, `producto_id`, `bonificacion_dcp`, `cantidad_dcp`, `precio_unitario_dcp`, `descuento_dcp`, `iva_dcp`, 
                    `subtotal_dcp` FROM `detalle_compra_producto` WHERE `compra_id` = ".$this->compra_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `detalle_compra_producto`(`compra_id`, `producto_id`, `bonificacion_dcp`, `cantidad_dcp`, `precio_unitario_dcp`, 
                    `descuento_dcp`, `iva_dcp`, `subtotal_dcp`) VALUES (
                        ".$this->compra_id.",
                        ".$this->producto_id.",
                        ".$this->bonificacion_dcp.",
                        ".$this->cantidad_dcp.",
                        ".$this->precio_unitario_dcp.",
                        ".$this->descuento_dcp.",
                        ".$this->iva_dcp.",
                        ".$this->subtotal_dcp."
                    )";        
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
    }     

}
?>