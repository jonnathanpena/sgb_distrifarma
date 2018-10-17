<?php
class Bancos {
    /******* CATALOGO DE BANCOS *******/

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_bancos;
    public $nombre_bancos;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener tipos de tarjetas
    function readAll(){
    
        // select all query
        $query = "SELECT `id_bancos`, `nombre_bancos` FROM `bancos` 
                    WHERE nombre_bancos like '%".$this->nombre_bancos."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_bancos`, `nombre_bancos` FROM `bancos` 
                    WHERE id_bancos = ".$this->id_bancos;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>