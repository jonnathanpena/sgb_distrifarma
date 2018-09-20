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
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia_entrega de login
    function read(){
    
        // select all query
        $query = "SELECT `df_num_guia_entrega`, `df_codigo_guia_ent`, `df_repartidor_ent`, 
                    `df_cant_total_producto_ent`, `df_cant_facturas_ent`, `df_fecha_ent`, `df_creadoBy_ent`, 
                    `df_modificadoBy_ent`, `df_guia_ent_recibido` 
                    FROM `df_guia_entrega` 
                    WHERE `df_codigo_guia_ent` LIKE '%".$this->df_codigo_guia_ent."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener guia_entrega
    function readById(){
    
        // select all query
        $query = "SELECT `df_num_guia_entrega`, `df_codigo_guia_ent`, `df_repartidor_ent`, 
                    `df_cant_total_producto_ent`, `df_cant_facturas_ent`, `df_fecha_ent`, `df_creadoBy_ent`, 
                    `df_modificadoBy_ent`, `df_guia_ent_recibido` 
                    FROM `df_guia_entrega`
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
                    `df_modificadoBy_ent`, `df_guia_ent_recibido` 
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
        $query = "INSERT INTO `df_guia_entrega`(`df_codigo_guia_ent`, `df_repartidor_ent`,
					 `df_cant_total_producto_ent`, `df_cant_facturas_ent`, `df_fecha_ent`, `df_creadoBy_ent`, 
					 `df_modificadoBy_ent`, `df_guia_ent_recibido`) VALUES (
					 '".$this->df_codigo_guia_ent."',
					 ".$this->df_repartidor_ent.",
					 ".$this->df_cant_total_producto_ent.",
					 ".$this->df_cant_facturas_ent.",
					 '".$this->df_fecha_ent."',
					 ".$this->df_creadoBy_ent.",
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
        $query = "UPDATE `df_guia_entrega` SET 
						`df_repartidor_ent`= ".$this->df_repartidor_ent.",
						`df_cant_total_producto_ent`= ".$this->df_cant_total_producto_ent.",
						`df_cant_facturas_ent`= ".$this->df_cant_facturas_ent.",
						`df_modificadoBy_ent`= ".$this->df_modificadoBy_ent.",
						`df_guia_ent_recibido`= ".$this->df_guia_ent_recibido."
						WHERE  `df_num_guia_entrega`= ".$this->df_num_guia_entrega;
						
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