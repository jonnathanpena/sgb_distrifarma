<?php
class GuiaRemision {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_guia_remision;
    public $df_codigo_rem;
    public $df_fecha_remision;
    public $df_sector_cod_rem;
    public $df_vendedor_rem;
    public $df_cant_total_producto_rem;
    public $df_valor_efectivo_rem;
    public $df_creadoBy_rem;
    public $df_modificadoBy_rem;
    public $df_guia_rem_recibido;
    public $condicion;
    public $df_nombre_sector;
    public $df_nombre_per;
    public $df_apellido_per;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia_entrega de login
    function read(){
    
        // select all query
        $query = "SELECT `df_guia_remision`, `df_codigo_rem`, `df_fecha_remision`, `df_sector_cod_rem`, `df_vendedor_rem`, `df_cant_total_producto_rem`, 
                    `df_valor_efectivo_rem`, `df_creadoBy_rem`, `df_modificadoBy_rem`, `df_guia_rem_recibido` 
                    FROM `df_guia_remision` WHERE `df_codigo_rem` like '%".$this->df_codigo_rem."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

//FALTA USUARIO
    // obtener guia_entrega
    function readById(){
    
        // select all query
        $query = "SELECT rem.`df_guia_remision`, rem.`df_codigo_rem`, rem.`df_fecha_remision`, rem.`df_sector_cod_rem`, 
                    rem.`df_vendedor_rem`, rem.`df_cant_total_producto_rem`, rem.`df_valor_efectivo_rem`, rem.`df_creadoBy_rem`, 
                    rem.`df_modificadoBy_rem`, rem.`df_guia_rem_recibido`, sec.df_nombre_sector, per.`df_nombre_per`, per.`df_apellido_per` 
                    FROM `df_guia_remision` as rem
                    INNER JOIN `df_sector` as sec ON (sec.df_codigo_sector = rem.`df_sector_cod_rem`)
                    INNER JOIN `df_personal` as per ON (per.df_id_personal = rem.`df_vendedor_rem`)
                    WHERE rem.df_guia_remision =  ".$this->df_guia_remision;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener guia_entrega
    function readByRandom(){
    
        // select all query
        $query = "SELECT `df_guia_remision`, `df_codigo_rem`, `df_fecha_remision`, `df_sector_cod_rem`, 
                    `df_vendedor_rem`, `df_cant_total_producto_rem`, `df_valor_efectivo_rem`, `df_creadoBy_rem`, 
                    `df_modificadoBy_rem`, `df_guia_rem_recibido` 
                    FROM `df_guia_remision` ".$this->condicion;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readIdMax(){
    
        // select all query
        $query = "SELECT MAX(`df_guia_remision`) as df_guia_remision FROM `df_guia_remision`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un guia_entrega
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_guia_remision`(`df_codigo_rem`, `df_fecha_remision`, `df_sector_cod_rem`, 
                    `df_vendedor_rem`, `df_cant_total_producto_rem`, `df_valor_efectivo_rem`, `df_creadoBy_rem`, 
                    `df_modificadoBy_rem`, `df_guia_rem_recibido`) VALUES (
                        '".$this->df_codigo_rem."',
                        '".$this->df_fecha_remision."',
                        ".$this->df_sector_cod_rem.",
                        ".$this->df_vendedor_rem.",
                        ".$this->df_cant_total_producto_rem.",
                        ".$this->df_valor_efectivo_rem.",
                        ".$this->df_creadoBy_rem.",
                        0,
                        0)";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de guia_entrega
    function update(){
    
        // query 
        $query = "UPDATE `df_guia_remision` SET 
                `df_sector_cod_rem`= ".$this->df_sector_cod_rem.",
                 df_vendedor_rem = ".$this->df_vendedor_rem.",
                 df_cant_total_producto_rem = ".$this->df_cant_total_producto_rem.",
                 df_valor_efectivo_rem = ".$this->df_valor_efectivo_rem.",
                 df_modificadoBy_rem = ".$this->df_modificadoBy_rem.",
                 df_guia_rem_recibido = ".$this->df_guia_rem_recibido."
                WHERE `df_guia_remision`= ".$this->df_guia_remision;

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }
       

}
?>