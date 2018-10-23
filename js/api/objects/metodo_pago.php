<?php
class MetodoPago {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_metodo_pago;
    public $nombre_met_pago;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener metodos de pago
    function readAll(){
    
        // select all query
        $query = "SELECT `id_metodo_pago`, `nombre_met_pago` FROM `metodos_pago` 
                    WHERE nombre_met_pago LIKE '%".$this->nombre_met_pago."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_metodo_pago`, `nombre_met_pago` FROM `metodos_pago` 
                    WHERE id_metodo_pago = ".$this->id_metodo_pago;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>