<?php
class GuiaEntrega {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_num_guia_entrega;
	public $df_codigo_guia_ent;
	public $df_repartidor_ent;
    public $df_cant_total_producto_ent;
	public $df_cant_facturas_ent;
	public $df_fecha_ent;
	public $df_creadoBy_ent;
    public $df_modificadoBy_ent;
	public $df_guia_ent_recibido;
    public $condicion;
    public $df_nombre_per;
    public $df_apellido_per;
    public $df_cant_total_cajas_ent;
    public $df_sector_ent;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia_entrega de login
    function read(){
    
        // select all query
        $query = "SELECT ent.`df_num_guia_entrega`, ent.`df_codigo_guia_ent`, ent.`df_repartidor_ent`, 
                    ent.`df_cant_total_producto_ent`, ent.`df_cant_facturas_ent`, ent.`df_fecha_ent`, ent.`df_creadoBy_ent`, 
                    ent.`df_modificadoBy_ent`, ent.`df_guia_ent_recibido`, per.`df_nombre_per`, per.`df_apellido_per`, ent.`df_cant_total_cajas_ent`
                    FROM `df_guia_entrega` as ent
                    INNER JOIN `df_personal` as per ON (per.df_id_personal =  ent.`df_repartidor_ent`)
                    WHERE `df_codigo_guia_ent` LIKE '%".$this->df_codigo_guia_ent."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener guia_entrega del usuario
    function readByRepartidor(){
    
        // select all query
        $query = "SELECT ent.`df_num_guia_entrega`, ent.`df_codigo_guia_ent`, ent.`df_repartidor_ent`, 
                    ent.`df_cant_total_producto_ent`, ent.`df_cant_facturas_ent`, ent.`df_fecha_ent`, ent.`df_creadoBy_ent`, 
                    ent.`df_modificadoBy_ent`, ent.`df_guia_ent_recibido`, per.`df_nombre_per`, per.`df_apellido_per`, ent.`df_cant_total_cajas_ent`
                    FROM `df_guia_entrega` as ent
                    INNER JOIN `df_personal` as per ON (per.df_id_personal =  ent.`df_repartidor_ent`)
                    WHERE `df_codigo_guia_ent` LIKE '%".$this->df_codigo_guia_ent."%'
                    AND ent.`df_repartidor_ent` =".$this->df_repartidor_ent;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener guia_entrega
    function readById(){
    
        // select all query
        $query = "SELECT ent.`df_num_guia_entrega`, ent.`df_codigo_guia_ent`, ent.`df_repartidor_ent`, 
                    ent.`df_cant_total_producto_ent`, ent.`df_cant_facturas_ent`, ent.`df_fecha_ent`, ent.`df_creadoBy_ent`, 
                    ent.`df_modificadoBy_ent`, ent.`df_guia_ent_recibido`, per.`df_nombre_per`, per.`df_apellido_per`, ent.`df_cant_total_cajas_ent`
                    FROM `df_guia_entrega` as ent
                    INNER JOIN `df_personal` as per ON (per.df_id_personal =  ent.`df_repartidor_ent`)
                    WHERE df_num_guia_entrega = ".$this->df_num_guia_entrega;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
	
	
    // obtener guia_entrega
    function readByRandom(){
    
        // select all query
        $query = "SELECT `df_num_guia_entrega`, `df_codigo_guia_ent`, `df_repartidor_ent`, 
                    `df_cant_total_producto_ent`, `df_cant_facturas_ent`, `df_fecha_ent`, `df_creadoBy_ent`, 
                    `df_modificadoBy_ent`, `df_guia_ent_recibido`, `df_cant_total_cajas_ent`
                    FROM `df_guia_entrega`".$this->condicion;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readIdMax(){
    
        // select all query
        $query = "SELECT MAX(`df_num_guia_entrega`) as df_num_guia_entrega FROM `df_guia_entrega`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un guia_entrega
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_guia_entrega`(`df_codigo_guia_ent`, `df_sector_ent`, `df_repartidor_ent`, `df_cant_total_producto_ent`, 
                    `df_cant_total_cajas_ent`, `df_cant_facturas_ent`, `df_fecha_ent`, `df_creadoBy_ent`, `df_modificadoBy_ent`, 
                    `df_guia_ent_recibido`) 
                    VALUES (
                        '".$this->df_codigo_guia_ent."',
                        0,
                        ".$this->df_repartidor_ent.",
                        ".$this->df_cant_total_producto_ent.",
                        ".$this->df_cant_total_cajas_ent.",
                        ".$this->df_cant_facturas_ent.",
                        '".$this->df_fecha_ent."',
                        ".$this->df_creadoBy_ent.",
                        0,
                        0
                    )";

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
        $query = "UPDATE `df_guia_entrega` SET 
                    `df_repartidor_ent`= ".$this->df_repartidor_ent.",
                    `df_cant_total_producto_ent`= ".$this->df_cant_total_producto_ent.",
                    `df_cant_total_cajas_ent`= ".$this->df_cant_total_cajas_ent.",
                    `df_cant_facturas_ent`= ".$this->df_cant_facturas_ent.",
                    `df_modificadoBy_ent`= ".$this->df_modificadoBy_ent.",
                    `df_guia_ent_recibido`= ".$this->df_guia_ent_recibido."
                    WHERE `df_num_guia_entrega` = ".$this->df_num_guia_entrega;
						
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