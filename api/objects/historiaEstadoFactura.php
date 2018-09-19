<?php
class HistoriaEstadoFactura {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_hist_edo_factura;
    public $df_num_factura;
    public $df_edo_factura;
    public $df_edo_impresion;
    public $df_usuario_id;
    public $df_fecha_proceso;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id factura
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_hist_edo_factura`, `df_num_factura`, `df_edo_factura`, `df_edo_impresion`, 
                    `df_usuario_id`, `df_fecha_proceso` 
                    FROM `df_historia_edo_factura`
                    WHERE df_num_factura = ".$this->df_num_factura."
                    ORDER BY df_fecha_proceso DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // insertar un factura
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_historia_edo_factura`(`df_num_factura`, `df_edo_factura`, `df_edo_impresion`, 
                    `df_usuario_id`, `df_fecha_proceso`) VALUES (
                        ".$this->df_num_factura.",
                        ".$this->df_edo_factura.",
                        ".$this->df_edo_impresion.",
                        ".$this->df_usuario_id.",
                        '".$this->df_fecha_proceso."')";
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