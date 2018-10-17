<?php
class Kardex {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_kardex_id;
    public $df_kardex_codigo;
    public $df_fecha_kar;
    public $df_fecha_ini;
    public $df_fecha_fin;
    public $df_producto_cod_kar;
    public $df_producto;
    public $df_factura_kar;
    public $df_ingresa_kar;
    public $df_egresa_kar;
    public $df_existencia_kar;
    public $df_creadoBy_kar;
    public $df_edo_kardex;
    public $df_codigo_prod;
    public $df_nombre_edo_kardex;
    public $fecha;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener kardex
    function read(){
    
        // select all query
        $query = "SELECT ka.`df_kardex_id`, ka.`df_kardex_codigo`, ka.`df_fecha_kar`, ka.`df_producto_cod_kar`, 
                    pro.`df_codigo_prod`, ka.`df_producto`, ka.`df_factura_kar`, ka.`df_ingresa_kar`, 
                    ka.`df_egresa_kar`, ka.`df_existencia_kar`, ka.`df_creadoBy_kar`, ka.`df_edo_kardex`, 
                    edo.df_nombre_edo_kardex 
                FROM `df_kardex` ka
                LEFT JOIN `df_edo_kardex` edo ON (edo.df_id_edo_kardex = ka.`df_edo_kardex`) 
                LEFT JOIN `df_producto` pro ON (pro.df_id_producto = ka.df_producto_cod_kar)
                WHERE pro.`df_codigo_prod` LIKE '%".$this->df_codigo_prod."%' OR 
                    ka.`df_producto` like '%".$this->df_producto."%'
                ORDER BY ka.`df_kardex_id` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener maximo id kardex
    function readIdMax(){
    
        // select all query
        $query = "SELECT max(`df_kardex_id`) as df_kardex_id 
                    FROM `df_kardex`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_kardex_id`, `df_kardex_codigo`, `df_fecha_kar`, `df_producto_cod_kar`, `df_producto`,
                    `df_factura_kar`, `df_ingresa_kar`, `df_egresa_kar`, `df_existencia_kar`, `df_creadoBy_kar`, 
                    `df_edo_kardex` FROM `df_kardex` WHERE `df_kardex_id` =".$this->df_kardex_id;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByIdProd(){
    
        // select all query
        $query = "SELECT`df_kardex_id`, `df_kardex_codigo`, `df_fecha_kar`, 
                    `df_producto_cod_kar`, `df_producto`,`df_factura_kar`, `df_ingresa_kar`, `df_egresa_kar`, 
                    `df_existencia_kar`, `df_creadoBy_kar`, `df_edo_kardex` 
                    FROM `df_kardex` WHERE `df_producto_cod_kar` =".$this->df_producto_cod_kar." 
                        AND df_fecha_kar = (SELECT max(ka.df_fecha_kar) from `df_kardex` ka 
                        WHERE ka.`df_producto_cod_kar` = ".$this->df_producto_cod_kar." )";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByProducto(){
    
        // select all query
        $query = "SELECT `df_kardex_id`, `df_kardex_codigo`, `df_fecha_kar`, `df_producto_cod_kar`, `df_producto`,
                    `df_factura_kar`, `df_ingresa_kar`, `df_egresa_kar`, `df_existencia_kar`, `df_creadoBy_kar`, 
                    `df_edo_kardex` FROM `df_kardex` WHERE `df_producto` like '%".$this->df_kardex_id."%'
                    order by `df_fecha_kar` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByFecha(){
    
        // select all query
        $query = "SELECT `df_kardex_id`, `df_kardex_codigo`, `df_fecha_kar`, `df_producto_cod_kar`, `df_producto`,
                    `df_factura_kar`, `df_ingresa_kar`, `df_egresa_kar`, `df_existencia_kar`, `df_creadoBy_kar`, 
                    `df_edo_kardex` FROM `df_kardex` 
                    WHERE `df_fecha_kar` >='".$this->df_fecha_ini."' and df_fecha_kar <= '".$this->df_fecha_fin."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
    
    // insertar un kardex
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_kardex`(`df_kardex_codigo`, `df_fecha_kar`, `df_producto_cod_kar`, `df_producto`,
                    `df_factura_kar`, `df_ingresa_kar`, `df_egresa_kar`, `df_existencia_kar`, `df_creadoBy_kar`, 
                    `df_edo_kardex`) VALUES (
                        '".$this->df_kardex_codigo."',
                        '".$this->df_fecha_kar."',
                        ".$this->df_producto_cod_kar.",
                        '".$this->df_producto."',
                        ".$this->df_factura_kar.",
                        ".$this->df_ingresa_kar.",
                        ".$this->df_egresa_kar.",
                        ".$this->df_existencia_kar.",
                        ".$this->df_creadoBy_kar.",
                        ".$this->df_edo_kardex.")";
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