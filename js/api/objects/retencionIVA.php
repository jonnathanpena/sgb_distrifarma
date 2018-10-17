<?php
class RetencionIva {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_retencion_iva;
    public $nombre_ret_iva;
    public $porcentaje_ret_iva;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener tipos de tarjetas
    function readAll(){
    
        // select all query
        $query = "SELECT `id_retencion_iva`, `nombre_ret_iva`, `porcentaje_ret_iva` FROM `retencion_iva` 
                    WHERE nombre_ret_iva like '%".$this->nombre_ret_iva."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_retencion_iva`, `nombre_ret_iva`, `porcentaje_ret_iva` FROM `retencion_iva`
                    WHERE id_retencion_iva = ".$this->id_retencion_iva;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>