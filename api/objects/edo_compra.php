<?php
class EdoCompra {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_edo_compra;
    public $nombre_edo_com;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener estado de compra / venta/ retencion
    function readAll(){
    
        // select all query
        $query = "SELECT `id_edo_compra`, `nombre_edo_com` FROM `edo_compra` 
                    WHERE nombre_edo_com LIKE '%".$this->nombre_edo_com."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_edo_compra`, `nombre_edo_com` FROM `edo_compra` 
                    WHERE id_edo_compra = ".$this->id_edo_compra;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>