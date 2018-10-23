<?php
class DetalleCompraGasto {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_dcg;
    public $compra_id;
    public $cuenta_dcg;
    public $subtotal_civa_dcg;
    public $subtotal_siva_dcg;
    public $subtotal_iva_cero_dcg;
    public $total_dcg;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todos los detalles compra gasto por ID
    function readById(){
    
        // select one query
        $query = "SELECT `id_dcg`, `compra_id`, `cuenta_dcg`, `subtotal_civa_dcg`, `subtotal_siva_dcg`, 
                    `subtotal_iva_cero_dcg`, `total_dcg` FROM `detalle_compra_gasto` WHERE `id_dcg` = ".$this->id_dcg;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos los detalles compra gasto por id de la compra
    function readByCompra(){
    
        // select one query
        $query = "SELECT `id_dcg`, `compra_id`, `cuenta_dcg`, `subtotal_civa_dcg`, `subtotal_siva_dcg`, 
                    `subtotal_iva_cero_dcg`, `total_dcg` FROM `detalle_compra_gasto` WHERE `compra_id` = ".$this->compra_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un detalle compra gasto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `detalle_compra_gasto`(`compra_id`, `cuenta_dcg`, `subtotal_civa_dcg`, `subtotal_siva_dcg`, 
                    `subtotal_iva_cero_dcg`, `total_dcg`) VALUES (
                        ".$this->compra_id.",
                        '".$this->cuenta_dcg."',
                        ".$this->subtotal_civa_dcg.",
                        ".$this->subtotal_siva_dcg.",
                        ".$this->subtotal_iva_cero_dcg.",
                        ".$this->total_dcg."
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
        $query = "UPDATE `detalle_compra_gasto` SET 
                    `cuenta_dcg`= '".$this->cuenta_dcg."',
                    `subtotal_civa_dcg`= ".$this->subtotal_civa_dcg.",
                    `subtotal_siva_dcg`= ".$this->subtotal_siva_dcg.",
                    `subtotal_iva_cero_dcg`= ".$this->subtotal_iva_cero_dcg.",
                    `total_dcg`= ".$this->total_dcg."
                    WHERE `id_dcg` = ".$this->id_dcg;
    
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
        $query = "DELETE FROM `detalle_compra_gasto` WHERE `id_dcg` = ". $this->id_dcg;
    
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