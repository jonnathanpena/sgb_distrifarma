<?php
class Sector {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_codigo_sector;
    public $df_nombre_sector;
    public $fecha;
    public $df_sector_cod_fac;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener sector de login
    function readAll(){
    
        // select all query
        $query = "SELECT `df_codigo_sector`, `df_nombre_sector` 
                    FROM `df_sector`
                    where df_nombre_sector like '%".$this->df_nombre_sector."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    //sector por facturas pendiente de entrega
    function readSector(){
    
        // select all query
        $query = "SELECT DISTINCT sec.`df_codigo_sector`, sec.`df_nombre_sector` 
                    FROM `df_sector` as sec
                    INNER JOIN `df_factura` as fac on (fac.df_sector_cod_fac = sec.`df_codigo_sector` and 
                                fac.df_edo_factura_fac IN (1,3,4,6) and date(fac.df_fecha_entrega_fac) = '".$this->fecha."')
                    ORDER BY sec.`df_nombre_sector` ASC";
        /* $query = "SELECT DISTINCT sec.`df_codigo_sector`, sec.`df_nombre_sector` 
                    FROM `df_sector` as sec
                    INNER JOIN `df_cliente` as cli on (sec.`df_codigo_sector` = cli.`df_sector_cod`)
                    INNER JOIN `df_factura` as fac on (fac.df_cliente_cod_fac = cli.df_id_cliente and 
                                fac.df_edo_factura_fac IN (1,3,4,6) and date(fac.df_fecha_entrega_fac) = '".$this->fecha."')
                    ORDER BY sec.`df_nombre_sector` ASC"; */
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener sector
    function readByName(){
    
        // select all query
        $query = "SELECT `df_codigo_sector`, `df_nombre_sector` FROM `df_sector` 
                    WHERE df_nombre_sector = '".$this->df_nombre_sector."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT `df_codigo_sector`, `df_nombre_sector` FROM `df_sector` 
                    WHERE df_codigo_sector = ".$this->df_codigo_sector;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un sector
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_sector`(`df_nombre_sector`) VALUES ('".$this->df_nombre_sector."')";

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
        $query = "UPDATE `df_sector` SET 
                df_nombre_sector`= '".$this->df_nombre_sector."'
                WHERE `df_codigo_sector`= ".$this->df_codigo_sector;
    
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
        $query = "DELETE FROM `df_sector` WHERE `df_codigo_sector`= ".$this->df_codigo_sector;
    
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