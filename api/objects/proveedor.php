<?php
class Proveedor {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_proveedor;
    public $df_codigo_proveedor;
    public $df_nombre_empresa;
    public $df_tlf_empresa;
    public $df_direccion_empresa;
    public $df_nombre_contacto;
    public $df_tlf_contacto;
    public $df_documento_prov;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id proveedor
    function readIdMax(){
    
        // select all query
        $query = "SELECT max(`df_id_proveedor`) as df_id_proveedor  FROM `df_proveedor`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener proveedor de login
    function readAll(){
    
        // select all query
        $query = "SELECT `df_id_proveedor`, `df_codigo_proveedor`, `df_nombre_empresa`, `df_tlf_empresa`, `df_direccion_empresa`, 
                    `df_nombre_contacto`, `df_tlf_contacto`, `df_documento_prov` 
                    FROM `df_proveedor` WHERE `df_nombre_empresa` like '%".$this->df_nombre_empresa."%' 
                    OR `df_documento_prov` like '%".$this->df_nombre_empresa."%' 
                    OR `df_codigo_proveedor` like '%".$this->df_nombre_empresa."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener proveedor
    function readByName(){
    
        // select all query
        $query = "SELECT `df_id_proveedor`, `df_codigo_proveedor`, `df_nombre_empresa`, `df_tlf_empresa`, 
                    `df_direccion_empresa`, `df_nombre_contacto`, `df_tlf_contacto`, `df_documento_prov` 
                    FROM `df_proveedor` 
                    WHERE df_nombre_empresa = '".$this->df_nombre_empresa."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener proveedor
    function readByRUC(){
    
        // select all query
        $query = "SELECT `df_id_proveedor`, `df_codigo_proveedor`, `df_nombre_empresa`, `df_tlf_empresa`, 
                    `df_direccion_empresa`, `df_nombre_contacto`, `df_tlf_contacto`, `df_documento_prov` 
                    FROM `df_proveedor` 
                    WHERE df_documento_prov = '".$this->df_documento_prov."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_id_proveedor`, `df_codigo_proveedor`, `df_nombre_empresa`, `df_tlf_empresa`, 
                    `df_direccion_empresa`, `df_nombre_contacto`, `df_tlf_contacto`, `df_documento_prov` 
                    FROM `df_proveedor` 
                    WHERE df_codigo_proveedor = '".$this->df_codigo_proveedor."'
                    OR df_documento_prov ='".$this->df_documento_prov."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un proveedor
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_proveedor`(`df_codigo_proveedor`, `df_nombre_empresa`, `df_tlf_empresa`, `df_direccion_empresa`, 
                    `df_nombre_contacto`, `df_tlf_contacto`, `df_documento_prov`) VALUES (
                        '".$this->df_codigo_proveedor."',
                        '".$this->df_nombre_empresa."',
                        '".$this->df_tlf_empresa."',
                        '".$this->df_direccion_empresa."',
                        '".$this->df_nombre_contacto."',
                        '".$this->df_tlf_contacto."',
                        '".$this->df_documento_prov."'
                    )";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de proveedor
    function update(){
    
        // query 
        $query = "UPDATE `df_proveedor` SET 
                    `df_codigo_proveedor`= '".$this->df_codigo_proveedor."',
                    `df_nombre_empresa`= '".$this->df_nombre_empresa."',
                    `df_tlf_empresa`= '".$this->df_tlf_empresa."',
                    `df_direccion_empresa`= '".$this->df_direccion_empresa."',
                    `df_nombre_contacto`= '".$this->df_nombre_contacto."',
                    `df_tlf_contacto`= '".$this->df_tlf_contacto."',
                    `df_documento_prov`= '".$this->df_documento_prov."'
                    WHERE df_id_proveedor = ".$this->df_id_proveedor;                           

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
        $query = "DELETE FROM `df_proveedor` WHERE df_id_proveedor = ".$this->df_id_proveedor;
    
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