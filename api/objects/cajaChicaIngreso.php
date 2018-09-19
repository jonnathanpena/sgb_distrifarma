<?php
class CajaChicaIngreso {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_ingreso_cc;
    public $df_fecha_ingreso;
    public $df_usuario_id_ingreso;
    public $df_num_cheque;
    public $df_valor_cheque;
    public $df_saldo_cc;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id caja chica egreso
    function read(){
    
        // select all query
        $query = "SELECT `df_id_ingreso_cc`, `df_fecha_ingreso`, `df_usuario_id_ingreso`, `df_num_cheque`, 
                    `df_valor_cheque`, `df_saldo_cc` 
                    FROM `df_caja_chica_ingreso` 
                    ORDER BY  `df_fecha_ingreso` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener caja chica egreso de login
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_ingreso_cc`, `df_fecha_ingreso`, `df_usuario_id_ingreso`, `df_num_cheque`, 
                    `df_valor_cheque`, `df_saldo_cc` 
                    FROM `df_caja_chica_ingreso`
                    WHERE df_id_ingreso_cc = ".$this->df_id_ingreso_cc;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un caja chica egreso
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_caja_chica_ingreso`(`df_fecha_ingreso`, `df_usuario_id_ingreso`, `df_num_cheque`, 
                    `df_valor_cheque`, `df_saldo_cc`) VALUES (
                        '".$this->df_fecha_ingreso."',
                        ".$this->df_usuario_id_ingreso.",
                        '".$this->df_num_cheque."',
                        ".$this->df_valor_cheque.",
                        ".$this->df_saldo_cc.")";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de caja chica egreso
    function update(){
    
        // query 
        $query = "UPDATE `df_caja_chica_ingreso` SET                     
                    `df_fecha_ingreso`= '".$this->df_fecha_ingreso."',
                    `df_usuario_id_ingreso`= ".$this->df_usuario_id_ingreso.",
                    `df_num_cheque`= '".$this->df_num_cheque."',
                    `df_valor_cheque`= ".$this->df_valor_cheque.",
                    `df_saldo_cc`= ".$this->df_saldo_cc."
                    WHERE df_id_ingreso_cc = ".$this->df_id_ingreso_cc;                        

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