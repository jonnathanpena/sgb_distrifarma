<?php
class RetencionIr {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_retencion_ir;
    public $codigo_ret_ir;
    public $nombre_ret_ir;
    public $porcentaje_ret_ir;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener tipos de tarjetas
    function readAll(){
    
        // select all query
        $query = "SELECT `id_retencion_ir`, `codigo_ret_ir`, `nombre_ret_ir`, `porcentaje_ret_ir` 
                    FROM `retencion_ir`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `id_retencion_ir`, `codigo_ret_ir`, `nombre_ret_ir`, `porcentaje_ret_ir` 
                    FROM `retencion_ir` WHERE id_retencion_ir = ".$this->id_retencion_ir;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }   

}
?>