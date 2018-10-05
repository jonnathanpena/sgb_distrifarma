<?php
class CuotasCompra {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_cc;
    public $compra_id;
    public $df_fecha_cc;
    public $df_monto_cc;
    public $df_estado_cc;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    function readByCompra() {
    
        // select all query
        $query = "SELECT `df_id_cc`, `compra_id`, `df_fecha_cc`, `df_monto_cc`, `df_estado_cc` 
                    FROM `df_cuotas_compra` 
                    WHERE `compra_id` = ".$this->compra_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_cuotas_compra`(`compra_id`, `df_fecha_cc`, `df_monto_cc`, `df_estado_cc`) 
                    VALUES (
                        ".$this->compra_id.",
                        '".$this->df_fecha_cc."',
                        ".$this->df_monto_cc.",
                        '".$this->df_estado_cc."'
                    )";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    function updateEstado() {
    
        // query 
        $query = "UPDATE `df_cuotas_compra` SET 
                    `df_estado_cc`= '".$this->df_estado_cc."'
                    WHERE `df_id_cc` = ".$this->df_id_cc;                      

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