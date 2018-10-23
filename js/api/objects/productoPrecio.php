<?php
class ProductoPrecio {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_precio;
    public $df_producto_id;
    public $df_ppp;
    public $df_pvt1;
    public $df_pvt2;
    public $df_pvp;
    public $df_iva;
    public $df_min_sugerido;
    public $df_und_caja;
    public $df_utilidad;
    public $df_id_impuesto;
    public $df_nombre_impuesto;
    public $df_valor_impuesto;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id producto
    function read(){
    
        // select all query
        $query = "SELECT `df_id_precio`, `df_producto_id`, `df_ppp`, `df_pvt1`, `df_pvt2`, `df_pvp`, `df_iva`, 
                    `df_min_sugerido`, `df_und_caja`, `df_utilidad` 
                    FROM `df_producto_precio`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener producto de login
    function readById(){
    
        // select all query
        $query = "SELECT pp.`df_id_precio`, pp.`df_producto_id`, pp.`df_ppp`, pp.`df_pvt1`, pp.`df_pvt2`, 
                    pp.`df_pvp`, pp.`df_iva`, pp.`df_min_sugerido`, pp.`df_und_caja`, pp.`df_utilidad`, 
                    imp.`df_id_impuesto`, imp.`df_nombre_impuesto`, imp.`df_valor_impuesto` 
                    FROM `df_producto_precio` as pp
                    INNER JOIN `df_impuesto` as imp ON (imp.df_id_impuesto = pp.df_iva) 
                    WHERE pp.`df_id_precio` = ".$this->df_id_precio;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByProducto(){
    
        // select all query
        $query = "SELECT `df_id_precio`, `df_producto_id`, `df_ppp`, `df_pvt1`, `df_pvt2`, `df_pvp`, `df_iva`, 
                    `df_min_sugerido`, `df_und_caja`, `df_utilidad` 
                    FROM `df_producto_precio` 
                    WHERE df_producto_id = ".$this->df_producto_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

        // obtener todos los impuestos
        function readImpuesto(){
    
            // select all query
            $query = "SELECT `df_id_impuesto`, `df_nombre_impuesto`, `df_valor_impuesto` FROM `df_impuesto`";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
        }
    
        // obtener producto de login
        function readByIdImpuesto(){
        
            // select all query
            $query = "SELECT `df_id_impuesto`, `df_nombre_impuesto`, `df_valor_impuesto` FROM `df_impuesto` 
                        WHERE df_id_impuesto = ".$this->df_id_impuesto;
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
        }
    
    // insertar un producto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_producto_precio`(`df_producto_id`, `df_ppp`, `df_pvt1`, `df_pvt2`, `df_pvp`, 
                `df_iva`, `df_min_sugerido`, `df_und_caja`, `df_utilidad`) VALUES (
                        ".$this->df_producto_id.",
                        ".$this->df_ppp.",
                        ".$this->df_pvt1.",
                        ".$this->df_pvt2.",
                        ".$this->df_pvp.",
                        ".$this->df_iva.",
                        ".$this->df_min_sugerido.",
                        ".$this->df_und_caja.",
                        ".$this->df_utilidad.")";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de producto
    function update(){
    
        // query 
        $query = "UPDATE `df_producto_precio` SET                     
                    `df_producto_id`= ".$this->df_producto_id.",
                    `df_ppp`= ".$this->df_ppp.",
                    `df_pvt1`= ".$this->df_pvt1.",
                    `df_pvt2`= ".$this->df_pvt2.",
                    `df_pvp`= ".$this->df_pvp.",
                    `df_iva`= ".$this->df_iva.",
                    `df_min_sugerido`= ".$this->df_min_sugerido.",
                    `df_und_caja`= ".$this->df_und_caja.",
                    `df_utilidad`= ".$this->df_utilidad."
                    WHERE `df_id_precio` = ".$this->df_id_precio;

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