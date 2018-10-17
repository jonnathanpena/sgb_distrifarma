<?php
class ProductoDevueltoRecepcion {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_prod_dev_rec;
    public $df_guia_rec;
    public $df_cant_und_rec;
    public $df_producto_id_rec;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener productoDevuelto de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_prod_dev_rec`, `df_guia_rec`, `df_cant_und_rec`, `df_producto_id_rec` 
                    FROM `df_producto_devuelto_recepcion`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener productoDevuelto por ID de df_guia_remision_detrem
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_prod_dev_rec`, `df_guia_rec`, `df_cant_und_rec`, `df_producto_id_rec` 
                    FROM `df_producto_devuelto_recepcion`
                    WHERE df_id_prod_dev_rec = ".$this->df_id_prod_dev_rec;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un productoDevuelto
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_producto_devuelto_recepcion`(`df_guia_rec`, `df_cant_und_rec`, `df_producto_id_rec`)
                             VALUES (
							".$this->df_guia_rec.",
							".$this->df_cant_und_rec.",
							".$this->df_producto_id_rec.")";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de productoDevuelto
    function update(){
    
        // query 
        $query = "UPDATE `df_producto_devuelto_recepcion` SET                 
                `df_guia_rec`= ".$this->df_guia_rec.",
                `df_cant_und_rec`= ".$this->df_cant_und_rec.",
                `df_producto_id_rec`= ".$this->df_producto_id_rec."
                WHERE `df_id_prod_dev_rec`=".$this->df_id_prod_dev_rec;

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