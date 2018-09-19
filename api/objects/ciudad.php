<?php
class Ciudad {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_codigo_ciudad;
    public $df_nombre_ciudad;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener ciudad de login
    function readAll(){
    
        // select all query
        $query = "SELECT `df_codigo_ciudad`, `df_nombre_ciudad` FROM `df_ciudad`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener ciudad
    function readByName(){
    
        // select all query
        $query = "SELECT `df_codigo_ciudad`, `df_nombre_ciudad` FROM `df_ciudad`
                    WHERE df_nombre_ciudad = '".$this->df_nombre_ciudad."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_codigo_ciudad`, `df_nombre_ciudad` FROM `df_ciudad`
                    WHERE df_codigo_ciudad = ".$this->df_codigo_ciudad;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un ciudad
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_ciudad`(`df_nombre_ciudad`) VALUES ('".$this->df_nombre_ciudad."')";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de ciudad
    function update(){
    
        // query 
        $query = "UPDATE `df_ciudad` SET 
                `df_nombre_ciudad`= '".$this->df_nombre_ciudad."'
                WHERE `df_codigo_ciudad`= ".$this->df_codigo_ciudad;

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function delete(){
    
        // query 
        $query = "DELETE FROM `df_ciudad` WHERE `df_codigo_ciudad`= ".$this->df_codigo_ciudad;
    
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