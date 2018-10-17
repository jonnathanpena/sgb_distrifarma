<?php
class DetalleEntrega {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_detent;
	public $df_guia_entrega;
    public $df_cod_producto;
    public $df_unidad_detent;
	public $df_cant_producto_detent;
    public $df_factura_detent;
    public $df_nom_producto_detent;
    public $df_num_factura_detent;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener guia_entrega de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_detent`, `df_guia_entrega`, `df_cod_producto`, `df_unidad_detent`, `df_cant_producto_detent`, 
                    `df_factura_detent`, `df_nom_producto_detent`, `df_num_factura_detent` 
                    FROM `df_detalle_entrega`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

//FALTA USUARIO
    // obtener guia_entrega por ID de df_guia_remision_detrem
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_detent`, `df_guia_entrega`, `df_cod_producto`, `df_unidad_detent`, `df_cant_producto_detent`, 
                    `df_factura_detent`, `df_nom_producto_detent`, `df_num_factura_detent`
							FROM `df_detalle_entrega` 
                    WHERE df_guia_entrega = ".$this->df_guia_entrega."
                    ORDER BY `df_num_factura_detent` ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByIdPrint(){
    
        // select all query
        $query = "SELECT `df_id_detent`, `df_guia_entrega`, `df_cod_producto`, `df_unidad_detent`, `df_cant_producto_detent`, 
                    `df_factura_detent`, `df_nom_producto_detent`, `df_num_factura_detent`
							FROM `df_detalle_entrega` 
                    WHERE df_guia_entrega = ".$this->df_guia_entrega;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un guia_entrega
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_detalle_entrega`(`df_guia_entrega`, `df_cod_producto`, `df_unidad_detent`, `df_cant_producto_detent`, 
                    `df_factura_detent`, `df_nom_producto_detent`, `df_num_factura_detent`) 
                    VALUES (
                        ".$this->df_guia_entrega.",
                        '".$this->df_cod_producto."',
                        '".$this->df_unidad_detent."',
                        ".$this->df_cant_producto_detent.",
                        ".$this->df_factura_detent.",
                        '".$this->df_nom_producto_detent."',
                        ".$this->df_num_factura_detent."
                    )";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
    }

    // actualizar datos de guia_entrega
    function update(){
    
        // query 
        $query = "UPDATE `df_detalle_entrega` SET 
                    `df_guia_entrega`= ".$this->df_guia_entrega.",
                    `df_cod_producto`= '".$this->df_cod_producto."',
                    `df_unidad_detent`= '".$this->df_unidad_detent."',
                    `df_cant_producto_detent`= ".$this->df_cant_producto_detent.",
                    `df_factura_detent`= ".$this->df_factura_detent.",
                    `df_nom_producto_detent`= '".$this->df_nom_producto_detent."',
                    `df_num_factura_detent`= ".$this->df_num_factura_detent." 
                    WHERE `df_id_detent` = ".$this->df_id_detent;

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