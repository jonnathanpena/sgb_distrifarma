<?php
class GuiaRecepcion {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_guia_recepcion;
	public $df_codigo_guia_rec;
	public $df_fecha_recepcion;
	public $df_repartidor_rec;
     public $df_valor_recaudado;
	 public $df_valor_efectivo;
	 public $df_valor_cheque;
	 public $df_retenciones;
     public $df_descuento_rec;
	 public $df_diferencia_rec;
	 public $df_remision_rec;
	 public $df_entrega_rec;
	 public $df_num_guia;
     public $df_creadoBy_rec;
	 public $df_modificadoBy_rec;
	 public $df_edo_factura_rec;
    public $condicion;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia_entrega de login
    function read(){
    
        // select all query
        $query = "SELECT `df_guia_recepcion`, `df_codigo_guia_rec`, `df_fecha_recepcion`, `df_repartidor_rec`, `df_valor_recaudado`, `df_valor_efectivo`, 
                    `df_valor_cheque`, `df_retenciones`, `df_descuento_rec`, `df_diferencia_rec`, `df_remision_rec`, `df_entrega_rec`, `df_num_guia`, 
                    `df_creadoBy_rec`, `df_modificadoBy_rec` FROM `df_guia_recepcion` WHERE `df_codigo_guia_rec` LIKE '%".$this->df_codigo_guia_rec."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener guia_entrega
    function readById(){
    
        // select all query
        $query = "SELECT `df_guia_recepcion`, `df_codigo_guia_rec`, `df_fecha_recepcion`, `df_repartidor_rec`, 
                    `df_valor_recaudado`, `df_valor_efectivo`, `df_valor_cheque`, `df_retenciones`, 
                    `df_descuento_rec`, `df_diferencia_rec`, `df_remision_rec`, `df_entrega_rec`, `df_num_guia`, 
                    `df_creadoBy_rec`, `df_modificadoBy_rec`, `df_edo_factura_rec` 
                    FROM `df_guia_recepcion`
                    WHERE df_guia_recepcion = ".$this->df_guia_recepcion;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readPendienteEnt(){
    
        // select all query
        $query = "SELECT `df_num_guia_entrega`, `df_codigo_guia_ent`, `df_sector_ent`, `df_repartidor_ent`, 
                    `df_cant_total_producto_ent`, `df_fecha_ent`, `df_creadoBy_ent`, `df_modificadoBy_ent`, 
                    `df_guia_ent_recibido` FROM `df_guia_entrega` 
                    WHERE `df_guia_ent_recibido` = 0 
                    order by df_fecha_ent asc";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readPendienteRem(){
    
        // select all query
        $query = "SELECT `df_guia_remision`, `df_codigo_rem`, `df_sector_cod_rem`, `df_vendedor_rem`, 
                    `df_cant_total_producto_rem`, `df_fecha_remision`, `df_creadoBy_rem`, `df_modificadoBy_rem`,
                    `df_guia_rem_recibido` FROM `df_guia_remision` 
                    WHERE `df_guia_rem_recibido` = 0
                    order by df_fecha_remision asc";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    function readPendiente(){
    
        // select all query
        $query = "(SELECT `df_num_guia_entrega`, `df_codigo_guia_ent`, `df_sector_ent`, `df_repartidor_ent`, 
                    `df_cant_total_producto_ent`, `df_fecha_ent`, `df_creadoBy_ent`, `df_modificadoBy_ent`, 
                    `df_guia_ent_recibido` FROM `df_guia_entrega` 
                    WHERE `df_guia_ent_recibido` = 0 AND `df_repartidor_ent` = ".$this->df_repartidor_ent.") 
                UNION 
                (SELECT `df_guia_remision`, `df_codigo_rem`, `df_sector_cod_rem`, `df_vendedor_rem`, 
                    `df_cant_total_producto_rem`, `df_fecha_remision`, `df_creadoBy_rem`, `df_modificadoBy_rem`,
                    `df_guia_rem_recibido` FROM `df_guia_remision` 
                    WHERE `df_guia_rem_recibido` = 0 AND  `df_vendedor_rem` = ".$this->df_vendedor_rem.")  ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

	// obtener guia_entrega
    function readByRandom(){
    
        // select all query
        $query = "SELECT `df_guia_recepcion`, `df_codigo_guia_rec`, `df_fecha_recepcion`, `df_repartidor_rec`, 
                    `df_valor_recaudado`, `df_valor_efectivo`, `df_valor_cheque`, `df_retenciones`, 
                    `df_descuento_rec`, `df_diferencia_rec`, `df_remision_rec`, `df_entrega_rec`, `df_num_guia`, 
                    `df_creadoBy_rec`, `df_modificadoBy_rec`, `df_edo_factura_rec` 
                    FROM `df_guia_recepcion` ".$this->condicion;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readIdMax(){
    
        // select all query
        $query = "SELECT MAX(`df_guia_recepcion`) as df_guia_recepcion FROM `df_guia_recepcion`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un guia_entrega
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_guia_recepcion`(`df_codigo_guia_rec`, `df_fecha_recepcion`, `df_repartidor_rec`, `df_valor_recaudado`, 
                    `df_valor_efectivo`, `df_valor_cheque`, `df_retenciones`, `df_descuento_rec`, `df_diferencia_rec`, `df_remision_rec`, 
                    `df_entrega_rec`, `df_num_guia`, `df_creadoBy_rec`, `df_modificadoBy_rec`) VALUES (
                        '".$this->df_codigo_guia_rec."',
                        '".$this->df_fecha_recepcion."',
                        ".$this->df_repartidor_rec.",
                        ".$this->df_valor_recaudado.",
                        ".$this->df_valor_efectivo.",
                        ".$this->df_valor_cheque.",
                        ".$this->df_retenciones.",
                        ".$this->df_descuento_rec.",
                        ".$this->df_diferencia_rec.",
                        ".$this->df_remision_rec.",
                        ".$this->df_entrega_rec."
                        ".$this->df_num_guia.",
                        ".$this->df_creadoBy_rec.",
                        null
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
        $query = "UPDATE `df_guia_recepcion` SET 	
							`df_repartidor_rec`= ".$this->df_repartidor_rec.",
							`df_valor_recaudado`= ".$this->df_valor_recaudado.",
							`df_valor_efectivo`= ".$this->df_valor_efectivo.",
							`df_valor_cheque`= ".$this->df_valor_cheque.",
							`df_retenciones`= ".$this->df_retenciones.",
							`df_descuento_rec`= ".$this->df_descuento_rec.",
							`df_diferencia_rec`= ".$this->df_diferencia_rec.",
							`df_remision_rec`= ".$this->df_remision_rec.",
							`df_entrega_rec`= ".$this->df_entrega_rec.",
							`df_num_guia`= ".$this->df_num_guia.",
							`df_modificadoBy_rec`= ".$this->df_modificadoBy_rec.",
							`df_edo_factura_rec`= ".$this->df_edo_factura_rec."
						WHERE `df_guia_recepcion`= ".$this->df_guia_recepcion;

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