<?php
class LibroDiario {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_libro_diario;
    public $df_valor_inicial_ld;
    public $df_fecha_ld;
    public $df_fecha_ini;
    public $df_fecha_fin;
    public $df_descipcion_ld;
    public $df_ingreso_ld;
    public $df_egreso_ld;
    public $df_fuente_ld;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener libro_diario
    function read(){
    
        // select all query
        $query = "SELECT `df_id_libro_diario`, `df_fuente_ld`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                    `df_ingreso_ld`, `df_egreso_ld`, df_usuario_id_ld 
                    FROM `df_libro_diario` 
                    where df_fecha_ld  BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                    ORDER BY `df_id_libro_diario` desc";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_libro_diario`, `df_fuente_ld`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                `df_ingreso_ld`, `df_egreso_ld`, df_usuario_id_ld FROM `df_libro_diario` 
                WHERE `df_id_libro_diario` =".$this->df_id_libro_diario."
                ORDER BY `df_fecha_ld` desc";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByFecha(){
    
        // select all query
        $query = "SELECT `df_id_libro_diario`, `df_fuente_ld`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                    `df_ingreso_ld`, `df_egreso_ld`, df_usuario_id_ld FROM `df_libro_diario` 
                    WHERE `df_fecha_ld` >='".$this->df_fecha_ini."' and df_fecha_ld <= '".$this->df_fecha_fin."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // insertar un libro_diario
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_libro_diario`(`df_fuente_ld`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                    `df_ingreso_ld`, `df_egreso_ld`, df_usuario_id_ld) VALUES (
                        '".$this->df_fuente_ld."',
                        ".$this->df_valor_inicial_ld.",
                        '".$this->df_fecha_ld."',
                        '".$this->df_descipcion_ld."',
                        ".$this->df_ingreso_ld.",
                        ".$this->df_egreso_ld.",
                        ".$this->df_usuario_id_ld.")";
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