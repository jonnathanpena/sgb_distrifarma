<?php
class DetalleRecepcion {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_detrec;
	public $df_guia_recepcion_detrec;
	public $df_factura_rec;
	public $df_cant_producto_detrec;
	public $df_producto_cod_detrec;
    public $df_nueva_fecha;
    public $df_edo_prod_fact_detrec;
    public $df_detalleRemision_detrec;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtenerdetalleRecepcion de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_detrec`, `df_guia_recepcion_detrec`, `df_factura_rec`, 
							`df_cant_producto_detrec`, `df_producto_cod_detrec`, `df_nueva_fecha`, `df_edo_prod_fact_detrec`
							FROM `df_detalle_recepcion`  ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

//FALTA USUARIO
    // obtenerdetalleRecepcion por ID de df_guia_remision_detrem
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_detrec`, `df_guia_recepcion_detrec`, `df_factura_rec`, 
							`df_cant_producto_detrec`, `df_producto_cod_detrec`, `df_nueva_fecha` , `df_edo_prod_fact_detrec`
							FROM `df_detalle_recepcion` 
                    WHERE df_guia_recepcion_detrec = ".$this->df_guia_recepcion_detrec;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar undetalleRecepcion
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_detalle_recepcion`(`df_guia_recepcion_detrec`, `df_factura_rec`, `df_cant_producto_detrec`, `df_producto_cod_detrec`, 
                    `df_nueva_fecha`, `df_detalleRemision_detrec`, `df_edo_prod_fact_detrec`) 
                    VALUES (
                        ".$this->df_guia_recepcion_detrec.",
                        ".$this->df_factura_rec.",
                        ".$this->df_cant_producto_detrec.",
                        ".$this->df_producto_cod_detrec.",
                        '".$this->df_nueva_fecha."',
                        '".$this->df_detalleRemision_detrec."',
                        ".$this->df_edo_prod_fact_detrec."
                    )";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos dedetalleRecepcion
    function update(){
    
        // query 
        $query = "UPDATE `df_detalle_recepcion` SET 				
				`df_guia_recepcion_detrec`= ".$this->df_guia_recepcion_detrec.",
				`df_factura_rec`= ".$this->df_factura_rec.",
				`df_cant_producto_detrec`= ".$this->df_cant_producto_detrec.",
				`df_producto_cod_detrec`= ".$this->df_producto_cod_detrec.",
                `df_nueva_fecha`= '".$this->df_nueva_fecha."',
                `df_edo_prod_fact_detrec` = ".$this->df_edo_prod_fact_detrec."
                WHERE `df_id_detrec`= ".$this->df_id_detrec;

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