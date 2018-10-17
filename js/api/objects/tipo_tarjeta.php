<?php
class TipoTarjeta {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_tipo_tarjeta;
    public $nombre_tipo_tarjeta;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener tipos de tarjetas
    function readAll(){
    
        // select all query
        $query = "SELECT `id_tipo_tarjeta`, `nombre_tipo_tarjeta` FROM `tipo_tarjeta` 
                    WHERE nombre_tipo_tarjeta like '%".$this->nombre_tipo_tarjeta."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_tipo_tarjeta`, `nombre_tipo_tarjeta` FROM `tipo_tarjeta` 
                    WHERE id_tipo_tarjeta = ".$this->id_tipo_tarjeta;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>