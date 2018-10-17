<?php
class DetalleRemision {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_detrem;
    public $df_guia_remision_detrem;
    public $df_producto_cod_detrem;
    public $df_cant_producto_detrem;
    public $df_nombre_und_detrem;
    public $df_cant_x_und_detrem;
    public $df_valor_sin_iva_detrem;
    public $df_iva_detrem;
    public $df_valor_total_detrem;
    public $df_producto_precio_detrem;

    public $df_id_producto;
    public $df_codigo_prod;
    public $df_nombre_producto;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia remision de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_detrem`, `df_guia_remision_detrem`, `df_producto_precio_detrem`, 
                    `df_cant_producto_detrem`, `df_nombre_und_detrem`, `df_cant_x_und_detrem`, 
                    `df_valor_sin_iva_detrem`, `df_iva_detrem`, `df_valor_total_detrem` FROM `df_detalle_remision`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    //FALTA USUARIO
    // obtener guia remision por ID de df_guia_remision_detrem
    function readById(){
    
        // select all query
        $query = "SELECT drem.`df_id_detrem`, drem.`df_guia_remision_detrem`, drem.`df_producto_precio_detrem`, drem.`df_cant_producto_detrem`, 
                    drem.`df_nombre_und_detrem`, drem.`df_cant_x_und_detrem`, drem.`df_valor_sin_iva_detrem`, drem.`df_iva_detrem`, drem.`df_valor_total_detrem`, 
                    prod.`df_codigo_prod`, prod.`df_nombre_producto`, prod.`df_id_producto`, pp.df_und_caja
                    FROM `df_detalle_remision` as drem
                    JOIN `df_producto_precio` as pp ON (drem.`df_producto_precio_detrem` = pp.`df_id_precio`)
                    JOIN `df_producto` as prod ON (pp.`df_producto_id` = prod.`df_id_producto`)
                    WHERE drem.`df_guia_remision_detrem` = ".$this->df_guia_remision_detrem;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un guia remision
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_detalle_remision`(`df_guia_remision_detrem`, `df_producto_precio_detrem`, `df_cant_producto_detrem`, 
                    `df_nombre_und_detrem`, `df_cant_x_und_detrem`, `df_valor_sin_iva_detrem`, `df_iva_detrem`, `df_valor_total_detrem`) 
                    VALUES (
                        ".$this->df_guia_remision_detrem.",
                        ".$this->df_producto_precio_detrem.",
                        ".$this->df_cant_producto_detrem.",
                        '".$this->df_nombre_und_detrem."',
                        ".$this->df_cant_x_und_detrem.",
                        ".$this->df_valor_sin_iva_detrem.",
                        ".$this->df_iva_detrem.",
                        '".$this->df_valor_total_detrem."'
                    )";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de guia remision
    function update(){
    
        // query 
        $query = "UPDATE `df_detalle_remision` SET 
                `df_guia_remision_detrem`= ".$this->df_guia_remision_detrem.",
                 df_producto_precio_detrem = ".$this->df_producto_precio_detrem.",
                 df_cant_producto_detrem = ".$this->df_cant_producto_detrem.", 
                 df_nombre_und_detrem = '".$this->df_nombre_und_detrem."', 
                 `df_cant_x_und_detrem` = ".$this->df_cant_x_und_detrem.", 
                 `df_valor_sin_iva_detrem` = ".$this->df_valor_sin_iva_detrem.", 
                 `df_iva_detrem` = ".$this->df_iva_detrem.", 
                 `df_valor_total_detrem` = ".$this->df_valor_total_detrem."
                WHERE `df_id_detrem`= ".$this->df_id_detrem;

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