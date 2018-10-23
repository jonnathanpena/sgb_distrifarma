<?php
class FacturaRecepcion {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_factura_rec;
    public $df_id_guia_rec;
    public $df_num_factura_rec;
    public $df_tipo_pago_rec;
    public $df_monto_rec;
    public $df_num_documento;
    
    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener facturaRecepcion de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_factura_rec`, `df_id_guia_rec`, `df_num_factura_rec`, `df_tipo_pago_rec`, 
                    `df_monto_rec`, `df_num_documento` 
                FROM `df_factura_recepcion` ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener facturaRecepcion por ID de df_guia_remision_detrem
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_factura_rec`, `df_id_guia_rec`, `df_num_factura_rec`, `df_tipo_pago_rec`, 
                        `df_monto_rec`, `df_num_documento` 
                    FROM `df_factura_recepcion`
                    WHERE df_id_guia_rec = ".$this->df_id_guia_rec;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un facturaRecepcion
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_factura_recepcion`(`df_id_guia_rec`, `df_num_factura_rec`, `df_tipo_pago_rec`, 
                    `df_monto_rec`, `df_num_documento`) VALUES (
							".$this->df_id_guia_rec.",
							".$this->df_num_factura_rec.",
							".$this->df_tipo_pago_rec.",
							".$this->df_monto_rec.",
                            '".$this->df_num_documento."')";

        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de facturaRecepcion
    function update(){
    
        // query 
        $query = "UPDATE `df_factura_recepcion` SET                 
                `df_id_guia_rec`= ".$this->df_id_guia_rec.",
                `df_num_factura_rec`= ".$this->df_num_factura_rec.",
                `df_tipo_pago_rec`= ".$this->df_tipo_pago_rec.",
                `df_monto_rec`= ".$this->df_monto_rec.",
                `df_num_documento`= '".$this->df_num_documento."'
                WHERE `df_id_factura_rec`= ".$this->df_id_factura_rec;

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