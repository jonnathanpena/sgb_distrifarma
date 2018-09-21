<?php
class CajaChicaGasto {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_gasto;
    public $df_usuario_id;
    public $df_movimiento;
    public $df_gasto;
    public $df_saldo;
    public $df_fecha_gasto;
    public $df_num_documento;
    public $df_ingreso_id;
    public $tipo;
    public $df_usuario_usuario;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id caja chica egreso
    function read(){
    
        // select all query
        $query = "SELECT `df_id_gasto`, `df_usuario_id`, `df_movimiento`, `df_gasto`, `df_saldo`, 
                    `df_fecha_gasto`, `df_num_documento`, df_ingreso_id 
                    FROM `df_caja_chica_gasto` 
                    ORDER BY  `df_fecha_gasto` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
	
	// obtener egresos e ingresos ordenados por fecha desc en un rango de los últimos 30 días
    function readMes(){
    
        // select all query
        $query = "(SELECT `df_id_gasto`, `df_usuario_id`, US.df_usuario_usuario, `df_movimiento`, `df_gasto`, 
                        `df_saldo`, `df_fecha_gasto`, `df_num_documento`, 'E' as tipo 
                    FROM `df_caja_chica_gasto` AS  CC
                    INNER JOIN `df_usuario` AS  US ON (CC.df_usuario_id = US.df_id_usuario)
                    where df_fecha_gasto  BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                    ORDER by df_fecha_gasto DESC) 
                UNION (SELECT `df_id_ingreso_cc`, `df_usuario_id_ingreso`, USU.df_usuario_usuario, 
                        'Ingreso' as ingreso, `df_valor_cheque`, `df_saldo_cc`, `df_fecha_ingreso`, `df_num_cheque`,  'I' as tipo 
                    FROM `df_caja_chica_ingreso` AS  CI
                    INNER JOIN `df_usuario` AS USU ON (CI.df_usuario_id_ingreso = USU.df_id_usuario)
                    WHERE df_fecha_ingreso BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                    ORDER BY df_fecha_ingreso desc) ORDER BY `df_fecha_gasto` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener caja chica egreso de login
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_gasto`, `df_usuario_id`, `df_movimiento`, `df_gasto`, `df_saldo`, 
                    `df_fecha_gasto`, `df_num_documento`, df_ingreso_id 
                    FROM `df_caja_chica_gasto` 
                    WHERE df_id_gasto = ".$this->df_id_gasto;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readAutocomplete(){
    
        // select all query
        $query = "SELECT DISTINCT `df_movimiento`
                    FROM `df_caja_chica_gasto` ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un caja chica egreso
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_caja_chica_gasto`(`df_usuario_id`, `df_movimiento`, `df_gasto`, `df_saldo`, 
                    `df_fecha_gasto`, `df_num_documento`, `df_ingreso_id`) VALUES (
                        ".$this->df_usuario_id.",
                        '".$this->df_movimiento."',
                        ".$this->df_gasto.",
                        ".$this->df_saldo.",
                        '".$this->df_fecha_gasto."',
                        '".$this->df_num_documento."',
                        ".$this->df_ingreso_id.")";
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
        $query = "UPDATE `df_caja_chica_gasto` SET                     
                    `df_usuario_id`= ".$this->df_usuario_id.",
                    `df_movimiento`= '".$this->df_movimiento."',
                    `df_gasto`= ".$this->df_gasto.",
                    `df_saldo`= ".$this->df_saldo.",
                    `df_fecha_gasto`= '".$this->df_fecha_gasto."',
                    `df_num_documento`= '".$this->df_num_documento."',
                    `df_ingreso_id`= ".$this->df_ingreso_id."
                    WHERE `df_id_gasto`=".$this->df_id_gasto;                        

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