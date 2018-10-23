<?php
class Zona {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_codigo_zona;
    public $df_nombre_zona;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener sector de login
    function readAll(){
    
        // select all query
        $query = "SELECT `df_codigo_zona`, `df_nombre_zona` FROM `df_zona` ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener sector
    function readByName(){
    
        // select all query
        $query = "SELECT `df_codigo_zona`, `df_nombre_zona` FROM `df_zona` 
                    WHERE df_nombre_zona like '%".$this->df_nombre_zona."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_codigo_zona`, `df_nombre_zona` FROM `df_zona` 
                    WHERE df_codigo_zona = ".$this->df_codigo_zona;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un sector
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_zona`(`df_nombre_zona`) VALUES ('".$this->df_nombre_zona."')";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de sector
    function update(){
    
        // query 
        $query = "UPDATE `df_zona` SET 
            df_nombre_zona`= '".$this->df_nombre_zona."'
            WHERE `df_codigo_zona`=".$this->df_codigo_zona;
    
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