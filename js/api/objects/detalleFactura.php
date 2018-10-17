<?php
class DetalleFactura {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_factura_detfac;
    public $df_num_factura_detfac;
    public $df_prod_precio_detfac;
    public $df_precio_prod_detfac;
    public $df_cantidad_detfac;
    public $df_valor_sin_iva_detfac;
    public $df_iva_detfac;
    public $df_valor_total_detfac;
    public $df_nombre_und_detfac;
    public $df_cant_x_und_detfac;
    public $df_edo_entrega_prod_detfac;
    public $df_id_producto;
    public $df_nombre_producto;
    public $df_codigo_prod;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener factura de login
    function readById(){
    
        // select all query
        //INNER JOIN df_producto_precio as pp on (pp.`df_id_precio` = det.`df_prod_precio_detfac`)        //

        $query = "SELECT det.`df_id_factura_detfac`, det.`df_num_factura_detfac`, det.`df_prod_precio_detfac`, 
                        det.df_precio_prod_detfac, prod.`df_id_producto`, prod.`df_nombre_producto`, 
                        prod.`df_codigo_prod`, det.`df_cantidad_detfac`,det.`df_nombre_und_detfac`, 
                        det.`df_cant_x_und_detfac`, det.`df_edo_entrega_prod_detfac`, det.`df_valor_sin_iva_detfac`,
                        det.`df_iva_detfac`, det.`df_valor_total_detfac` ,  pp.df_und_caja
                    FROM `df_detalle_factura` as det
                    INNER JOIN `df_producto` as prod on (det.`df_prod_precio_detfac` = prod.df_id_producto) 
                    INNER JOIN  `df_producto_precio`AS pp on (pp.`df_producto_id` = prod.df_id_producto )
                    WHERE det.df_num_factura_detfac = ".$this->df_num_factura_detfac;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un factura
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_detalle_factura`(`df_num_factura_detfac`, `df_prod_precio_detfac`, df_precio_prod_detfac, 
                    `df_cantidad_detfac`, `df_nombre_und_detfac`, `df_cant_x_und_detfac`, `df_edo_entrega_prod_detfac`,
                     `df_valor_sin_iva_detfac`, `df_iva_detfac`, `df_valor_total_detfac` ) 
                    VALUES (
                        ".$this->df_num_factura_detfac.",
                        ".$this->df_prod_precio_detfac.",
                        ".$this->df_precio_prod_detfac.",
                        ".$this->df_cantidad_detfac.",
                        '".$this->df_nombre_und_detfac."',
                        ".$this->df_cant_x_und_detfac.",
                        ".$this->df_edo_entrega_prod_detfac.",
                        ".$this->df_valor_sin_iva_detfac.",
                        ".$this->df_iva_detfac.",
                        ".$this->df_valor_total_detfac.")";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de factura
    function update(){
        // query 
        $query = "UPDATE `df_detalle_factura` SET 
                    `df_prod_precio_detfac`=".$this->df_prod_precio_detfac.",
                    df_precio_prod_detfac = ".$this->df_precio_prod_detfac.",
                    `df_cantidad_detfac`=".$this->df_cantidad_detfac.",
                    `df_valor_sin_iva_detfac`=".$this->df_valor_sin_iva_detfac.",
                    `df_iva_detfac`=".$this->df_iva_detfac.",
                    `df_valor_total_detfac`= ".$this->df_valor_total_detfac.",
                    `df_nombre_und_detfac` = '".$this->df_nombre_und_detfac."', 
                    `df_cant_x_und_detfac` = ".$this->df_cant_x_und_detfac.", 
                    `df_edo_entrega_prod_detfac` = ".$this->df_edo_entrega_prod_detfac."
                    WHERE `df_id_factura_detfac`=".$this->df_id_factura_detfac;                        

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    // delete
    function delete(){
    
        // query 
        $query = "DELETE FROM `df_detalle_factura` WHERE `df_id_factura_detfac`= ".$this->df_id_factura_detfac;                        

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