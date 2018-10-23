<?php
class HistoriaEdoCompra {

    //Esta tabla es la historia de los estado de entrega y si ya fue pagado, tanto compra como venta y retencion

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_hist_edo_com;
    public $compra_id_hist;
    public $venta_id_hist;
    public $retencion_id_hist;
    public $id_edo_entrega_hist;
    public $id_edo_pago_hist;
    public $fecha_hist;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener por id compra
    function readByCompra(){
    
        // select all query
        $query = "SELECT `id_hist_edo_com`, `compra_id_hist`, `venta_id_hist`, `retencion_id_hist`, 
                    `id_edo_entrega_hist`, `id_edo_pago_hist`, `fecha_hist` 
                    FROM `historia_edo_compra` 
                    WHERE compra_id_hist = ".$this->compra_id_hist."
                    ORDER BY `fecha_hist` desc";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    
    function readByVenta(){
    
        // select all query
        $query = "SELECT `id_hist_edo_com`, `compra_id_hist`, `venta_id_hist`, `retencion_id_hist`, 
                    `id_edo_entrega_hist`, `id_edo_pago_hist`, `fecha_hist` 
                    FROM `historia_edo_compra` 
                    WHERE venta_id_hist = ".$this->venta_id_hist."
                    ORDER BY `fecha_hist` desc";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByRetencion(){
    
        // select all query
        $query = "SELECT `id_hist_edo_com`, `compra_id_hist`, `venta_id_hist`, `retencion_id_hist`, 
                    `id_edo_entrega_hist`, `id_edo_pago_hist`, `fecha_hist` 
                    FROM `historia_edo_compra` 
                    WHERE retencion_id_hist = ".$this->retencion_id_hist."
                    ORDER BY `fecha_hist` desc";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // insertar un libro_diario
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `historia_edo_compra`(`compra_id_hist`, `venta_id_hist`, `retencion_id_hist`, 
                    `id_edo_entrega_hist`, `id_edo_pago_hist`, `fecha_hist`) VALUES (
                        ".$this->compra_id_hist.",
                        ".$this->venta_id_hist.",
                        '".$this->retencion_id_hist."',
                        ".$this->id_edo_entrega_hist.",
                        ".$this->id_edo_pago_hist.",
                        '".$this->fecha_hist."')";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

}
?>