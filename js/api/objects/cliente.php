<?php
class Cliente {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_cliente;
    public $df_codigo_cliente;
    public $df_nombre_cli;
    public $df_razon_social_cli;
    public $df_tipo_documento_cli;
    public $df_documento_cli;
    public $df_direccion_cli;
    public $df_referencia_cli;
    public $df_sector_cod;
    public $df_email_cli;
    public $df_telefono_cli;
    public $df_celular_cli;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id cliente
    function readIdMax(){
    
        // select all query
        $query = "SELECT max(`df_id_cliente`) as df_id_cliente  FROM `df_cliente`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener cliente de login
    function readAll(){
    
        // select all query
        $query = "SELECT `df_id_cliente`, `df_codigo_cliente`, `df_nombre_cli`, `df_razon_social_cli`, `df_tipo_documento_cli`, 
                    `df_documento_cli`, `df_direccion_cli`, `df_referencia_cli`, `df_sector_cod`, `df_email_cli`, `df_telefono_cli`, 
                    `df_celular_cli` FROM `df_cliente` 
                    WHERE `df_nombre_cli` like '%".$this->df_nombre_cli."%' 
                    OR `df_razon_social_cli` like '%".$this->df_nombre_cli."%' 
                    OR `df_documento_cli` like '%".$this->df_nombre_cli."%'
                    ORDER BY df_id_cliente DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener cliente
    function readByName(){
    
        // select all query
        $query = "SELECT `df_id_cliente`, `df_codigo_cliente`, `df_nombre_cli`, `df_razon_social_cli`, 
                    `df_tipo_documento_cli`, `df_documento_cli`, `df_direccion_cli`, `df_referencia_cli`, 
                    `df_sector_cod`, `df_email_cli`, `df_telefono_cli`, `df_celular_cli` 
                    FROM `df_cliente` 
                    WHERE df_nombre_cli = '".$this->df_nombre_cli."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_id_cliente`, `df_codigo_cliente`, `df_nombre_cli`, `df_razon_social_cli`, `df_tipo_documento_cli`, 
                    `df_documento_cli`, `df_direccion_cli`, `df_referencia_cli`, `df_sector_cod`, `df_email_cli`, `df_telefono_cli`, 
                    `df_celular_cli` FROM `df_cliente` 
                    WHERE `df_id_cliente` = ".$this->df_id_cliente."  
                    OR `df_codigo_cliente` = '".$this->df_codigo_cliente."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByRUC(){
    
        // select all query
        $query = "SELECT `df_id_cliente`, `df_codigo_cliente`, `df_nombre_cli`, `df_razon_social_cli`, `df_tipo_documento_cli`, 
                    `df_documento_cli`, `df_direccion_cli`, `df_referencia_cli`, `df_sector_cod`, `df_email_cli`, `df_telefono_cli`, 
                    `df_celular_cli` FROM `df_cliente` 
                    WHERE `df_documento_cli` = '".$this->df_documento_cli."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un cliente
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_cliente`(`df_codigo_cliente`, `df_nombre_cli`, `df_razon_social_cli`, 
                    `df_tipo_documento_cli`, `df_documento_cli`, `df_direccion_cli`, `df_referencia_cli`, 
                    `df_sector_cod`, `df_email_cli`, `df_telefono_cli`, `df_celular_cli`) VALUES (
                        '".$this->df_codigo_cliente."',
                        '".$this->df_nombre_cli."',
                        '".$this->df_razon_social_cli."',
                        '".$this->df_tipo_documento_cli."',
                        '".$this->df_documento_cli."',
                        '".$this->df_direccion_cli."',
                        '".$this->df_referencia_cli."',
                        ".$this->df_sector_cod.",
                        '".$this->df_email_cli."',
                        '".$this->df_telefono_cli."',
                        '".$this->df_celular_cli."')";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de cliente
    function update(){
    
        // query 
        $query = "UPDATE `df_cliente` SET 
                `df_codigo_cliente`= '".$this->df_codigo_cliente."',
                `df_nombre_cli`= '".$this->df_nombre_cli."',
                `df_razon_social_cli`= '".$this->df_razon_social_cli."',
                `df_tipo_documento_cli`= '".$this->df_tipo_documento_cli."',
                `df_documento_cli`= '".$this->df_documento_cli."',
                `df_direccion_cli`= '".$this->df_direccion_cli."',
                `df_referencia_cli`= '".$this->df_referencia_cli."',
                `df_sector_cod`= ".$this->df_sector_cod.",
                `df_email_cli`= '".$this->df_email_cli."',
                `df_telefono_cli`= '".$this->df_telefono_cli."',
                `df_celular_cli`= '".$this->df_celular_cli."'
                WHERE df_id_cliente = ".$this->df_id_cliente;                        

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
        $query = "DELETE FROM `df_cliente` WHERE df_id_cliente = ".$this->df_id_cliente;
    
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