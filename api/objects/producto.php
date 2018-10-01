<?php
class Producto {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_producto;
    public $df_nombre_producto;
    public $df_codigo_prod;
    public $df_prod_precio_detfac;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id producto
    function read(){
    
        // select all query
        $query = "SELECT `df_id_producto`, `df_nombre_producto`, `df_codigo_prod` FROM `df_producto` 
                    WHERE `df_codigo_prod` like '%".$this->df_nombre_producto."%' OR `df_nombre_producto` like '%".$this->df_nombre_producto."%'
                    ORDER BY df_id_producto DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener producto de login
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_producto`, `df_nombre_producto`, `df_codigo_prod` FROM `df_producto` 
                    WHERE df_id_producto = ".$this->df_id_producto;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener producto de login
    function readByName(){
    
        // select all query
        $query = "SELECT `df_id_producto`, `df_nombre_producto`, `df_codigo_prod` FROM `df_producto` 
                    WHERE df_nombre_producto like '%".$this->df_nombre_producto."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener producto 
    function readByFactura(){
    
        // select all query
        $query = "SELECT COUNT(`df_num_factura_detfac`) AS factura FROM `df_detalle_factura` 
                    WHERE df_prod_precio_detfac = ".$this->df_prod_precio_detfac;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener producto 
    function readIdMax(){
    
        // select all query
        $query = "SELECT MAX(`df_id_producto`) as df_id_producto FROM `df_producto`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un producto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_producto`(`df_nombre_producto`, `df_codigo_prod`) VALUES (
                        '".$this->df_nombre_producto."',
                        '".$this->df_codigo_prod."')";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de producto
    function update(){
    
        // query 
        $query = "UPDATE `df_producto` SET                     
                    `df_nombre_producto`= '".$this->df_nombre_producto."'
                    WHERE `df_id_producto`= ".$this->df_id_producto;                          

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }


    // actualizar datos de delete
    function delete(){
    
        // query 
        $query = "DELETE FROM `df_producto` WHERE `df_id_producto`= ".$this->df_id_producto;                        

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