<?php
class Franquicia {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_franquicia;
    public $nombre_franq;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener franquicias de TDD y TDC
    function readAll(){
    
        // select all query
        $query = "SELECT `id_franquicia`, `nombre_franq` FROM `franquicias` 
                    WHERE nombre_franq like '%".$this->nombre_franq."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_franquicia`, `nombre_franq` FROM `franquicias` 
                    WHERE id_franquicia = ".$this->id_franquicia;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>