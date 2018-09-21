<?php
class SustentoTributario {

    // conexión a la base de datos 
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_sustento;
    public $nombre_sustento;
    public $id_dsc;
    public $sustento_id;
    public $comprobante_id;
    public $nombre_tipocomprobante;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todos los sustentos tributarios
    function readAll(){
    
        // select all query
        $query = "SELECT `id_sustento`, `nombre_sustento` FROM `sustento_tributario`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos los tipos de comprobante por sustento
    function readTiposComprobantesBySustento(){
    
        // select one query
        $query = "SELECT dsc.`id_dsc`, dsc.`sustento_id`, st.`id_sustento`, st.`nombre_sustento`, dsc.`comprobante_id`, 
                    tc.`nombre_tipocomprobante`
                    FROM `detalle_sustento_comprobante` as dsc
                    JOIN `sustento_tributario` as st ON (dsc.`sustento_id` = st.`id_sustento`)
                    JOIN `tipo_comprobante` as tc ON (dsc.`comprobante_id`  = tc.`id_tipocomprobante`)
                    WHERE dsc.`sustento_id` = ".$this->sustento_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

}
?>