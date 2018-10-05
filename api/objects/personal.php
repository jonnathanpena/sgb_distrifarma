<?php

date_default_timezone_set('America/Bogota');
$df_fecha_proceso = date("Y-m-d H:i:s");

class Personal {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    //date_default_timezone_set('America/Bogota');
    
    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_personal;
    public $df_nombre_per;
    public $df_cargo_per;
    public $df_fecha_ingreso;
    public $df_tipo_documento_per;
    public $df_documento_per;
    public $df_correo_per;
    public $df_codigo_personal;
    public $df_activo_per;
    public $df_id_usuario;
    public $df_tipo_documento_usuario;
    public $df_documento_usuario;
    public $df_nombre_usuario;
    public $df_apellido_usuario;
    public $df_usuario_usuario;
    public $df_personal_cod;
    public $df_clave_usuario;
    public $df_activo;
    public $df_correo;
    public $df_tipo_usuario;
    public $df_id_detper;
    public $df_sueldo_detper;
    public $df_bono_detper;
    public $df_anticipo_detper;
    public $df_descuento_detper;
    public $df_decimos_detper;
    public $df_vacaciones_detper;
    public $df_tabala_comision_detper;
    public $df_comisiones_detper;
    public $df_personal_cod_detper;
    public $df_usuario_detper;
    public $df_fecha_proceso;

    //public $fecha = date("Y-m-d H:i:s");

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id personal
    function readIdMax(){
    
        // select all query
        $query = "SELECT max(`df_id_personal`) as df_id_personal  FROM `df_personal`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener personal
    function readAll(){
    
        // select all query
        $query = "SELECT `df_id_personal`, `df_tipo_documento_per`, `df_nombre_per`, `df_apellido_per`, `df_cargo_per`, `df_fecha_ingreso`, 
                    `df_documento_per`, `df_correo_per`, `df_codigo_personal`, `df_activo_per` 
                    FROM `df_personal` 
                    WHERE `df_nombre_per` like '%".$this->df_nombre_per."%' 
                    OR `df_documento_per` like '%".$this->df_nombre_per."%'
                    order by df_id_personal desc";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener personal
    function readByDocumento(){
    
        // select all query
        $query = "SELECT per.`df_id_personal`, per.`df_tipo_documento_per`, per.`df_nombre_per`, per.`df_apellido_per`, per.`df_cargo_per`, 
                    per.`df_fecha_ingreso`, per.`df_documento_per`, per.`df_correo_per`, per.`df_codigo_personal`, 
                    per.`df_activo_per`, dp.`df_id_detper`, dp.`df_sueldo_detper`, dp.`df_bono_detper`, dp.`df_anticipo_detper`, 
                    dp.`df_descuento_detper`, dp.`df_decimos_detper`, dp.`df_vacaciones_detper`, dp.`df_tabala_comision_detper`, 
                    dp.`df_comisiones_detper`, dp.`df_personal_cod_detper`, dp.`df_usuario_detper`, dp.`df_fecha_proceso`
                    FROM `df_personal` as per
                    JOIN `df_detalle_personal` as dp ON (per.`df_id_personal` = dp.`df_personal_cod_detper`)
                    WHERE per.`df_documento_per` = '".$this->df_documento_per."' ORDER BY dp.`df_fecha_proceso` DESC";
                
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener personal
    function readById(){
    
        // select all query
        $query = "SELECT per.`df_id_personal`, per.`df_tipo_documento_per`, per.`df_nombre_per`, per.`df_apellido_per`, per.`df_cargo_per`, 
                    per.`df_fecha_ingreso`, per.`df_documento_per`, per.`df_correo_per`, per.`df_codigo_personal`, 
                    per.`df_activo_per`, dp.`df_id_detper`, dp.`df_sueldo_detper`, dp.`df_bono_detper`, dp.`df_anticipo_detper`, 
                    dp.`df_descuento_detper`, dp.`df_decimos_detper`, dp.`df_vacaciones_detper`, dp.`df_tabala_comision_detper`, 
                    dp.`df_comisiones_detper`, dp.`df_personal_cod_detper`, dp.`df_usuario_detper`, dp.`df_fecha_proceso`
                    FROM `df_personal` as per
                    JOIN `df_detalle_personal` as dp ON (per.`df_id_personal` = dp.`df_personal_cod_detper`)
                    WHERE per.`df_id_personal` = '".$this->df_id_personal."' ORDER BY dp.`df_fecha_proceso` DESC";
                
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener personal
    function readByNombre(){
    
        // select all query
        $query = "SELECT per.`df_id_personal`, per.`df_nombre_per`, per.`df_cargo_per`, per.`df_fecha_ingreso`,
        per.`df_documento_per`, per.`df_correo_per`, per.`df_codigo_personal`, per.df_activo_per,
        per.df_tipo_documento_per, usu.`df_id_usuario`, 
        usu.`df_tipo_documento_usuario`, usu.`df_documento_usuario`, usu.`df_nombre_usuario`, 
        usu.`df_apellido_usuario`, usu.`df_usuario_usuario`, usu.`df_personal_cod`, 
        usu.`df_clave_usuario`, usu.`df_activo`, usu.`df_correo`, usu.`df_tipo_usuario`, 
        detper.`df_id_detper`, detper.`df_sueldo_detper`, detper.`df_bono_detper`, 
        detper.`df_anticipo_detper`, detper.`df_descuento_detper`, detper.`df_decimos_detper`, 
        detper.`df_vacaciones_detper`, detper.`df_tabala_comision_detper`, 
        detper.`df_comisiones_detper`, detper.`df_personal_cod_detper`, detper.`df_usuario_detper`, 
        detper.`df_fecha_proceso`
        FROM `df_personal` as per
        INNER JOIN `df_usuario` as usu on (per.`df_id_personal` = usu.`df_personal_cod`)
        INNER JOIN `df_detalle_personal` as detper on (detper.`df_usuario_detper` = usu.`df_id_usuario` 
            and detper.`df_personal_cod_detper` = per.df_id_personal 
            and detper.`df_fecha_proceso` = (SELECT MAX(det.`df_fecha_proceso`) FROM `df_detalle_personal` as det
                                                    WHERE detper.`df_id_detper` = det.`df_id_detper`))
                WHERE per.`df_nombre_per` LIKE '".$this->df_nombre_per."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar un personal
    function insertPersonal(){
    
        // query to insert record
        $query = "INSERT INTO `df_personal`(`df_tipo_documento_per`, `df_nombre_per`, `df_apellido_per`, `df_cargo_per`, 
                    `df_fecha_ingreso`, `df_documento_per`, `df_correo_per`, `df_codigo_personal`, `df_activo_per`) VALUES (
                        '".$this->df_tipo_documento_per."',
                        '".$this->df_nombre_per."',
                        '".$this->df_apellido_per."',
                        '".$this->df_cargo_per."',
                        '".$this->df_fecha_ingreso."',
                        '".$this->df_documento_per."',
                        '".$this->df_correo_per."',
                        '".$this->df_codigo_personal."',
                        1
                    )";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    
    // insertar un usuario
    function insertUsuario(){
    
        // query to insert record
        $query = "INSERT INTO `df_usuario`(`df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, `df_apellido_usuario`, 
                        `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario`) VALUES (
                            '".$this->df_tipo_documento_usuario."',
                            ".$this->df_documento_usuario.",
                            '".$this->df_nombre_usuario."',
                            '".$this->df_apellido_usuario."',
                            '".$this->df_usuario_usuario."',
                            ".$this->df_personal_cod.",
                            '".$this->df_clave_usuario."',
                            1,
                            '".$this->df_correo."',
                            '".$this->df_tipo_usuario."'
                        )";
             
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query); 

        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    function insertDetPersonal(){
    
        // query to insert record
        if ($this->df_usuario_detper == null) {
            $query = "INSERT INTO `df_detalle_personal`(`df_sueldo_detper`, `df_bono_detper`, `df_anticipo_detper`, `df_descuento_detper`, 
                    `df_decimos_detper`, `df_vacaciones_detper`, `df_tabala_comision_detper`, `df_comisiones_detper`, `df_personal_cod_detper`, 
                    `df_usuario_detper`, `df_fecha_proceso`) VALUES (
                        ".$this->df_sueldo_detper.",
                        ".$this->df_bono_detper.",
                        ".$this->df_anticipo_detper.",
                        ".$this->df_descuento_detper.",
                        ".$this->df_decimos_detper.",
                        ".$this->df_vacaciones_detper.",
                        1.0,
                        ".$this->df_comisiones_detper.",
                        ".$this->df_personal_cod_detper.",
                        null,
                        now()
                    )";
        } else {
            $query = "INSERT INTO `df_detalle_personal`(`df_sueldo_detper`, `df_bono_detper`, `df_anticipo_detper`, `df_descuento_detper`, 
                    `df_decimos_detper`, `df_vacaciones_detper`, `df_tabala_comision_detper`, `df_comisiones_detper`, `df_personal_cod_detper`, 
                    `df_usuario_detper`, `df_fecha_proceso`) VALUES (
                        ".$this->df_sueldo_detper.",
                        ".$this->df_bono_detper.",
                        ".$this->df_anticipo_detper.",
                        ".$this->df_descuento_detper.",
                        ".$this->df_decimos_detper.",
                        ".$this->df_vacaciones_detper.",
                        1.0,
                        ".$this->df_comisiones_detper.",
                        ".$this->df_personal_cod_detper.",
                        ".$this->df_usuario_detper.",
                        now()
                    )";
        }
        
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);  

        if($stmt->execute()){
            return  true;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de cliente 
    function updatePersonal(){
    
        // query 
        $query = "UPDATE `df_personal` SET 
                    `df_tipo_documento_per`= '".$this->df_tipo_documento_per."',
                    `df_nombre_per`= '".$this->df_nombre_per."',
                    `df_apellido_per`= '".$this->df_apellido_per."',
                    `df_cargo_per`= '".$this->df_cargo_per."',
                    `df_fecha_ingreso`= '".$this->df_fecha_ingreso."',
                    `df_documento_per`= '".$this->df_documento_per."',
                    `df_correo_per`= '".$this->df_correo_per."',
                    `df_codigo_personal`= '".$this->df_codigo_personal."',
                    `df_activo_per`= ".$this->df_activo_per." 
                    WHERE `df_id_personal` = ".$this->df_id_personal;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function updateUsuario(){
    
        // query 
        $query = "UPDATE `df_usuario` SET                     
                df_tipo_documento_usuario = '".$this->df_tipo_documento_usuario."',
                df_documento_usuario = ".$this->df_documento_usuario.",
                df_nombre_usuario = '".$this->df_nombre_usuario."',
                df_apellido_usuario = '".$this->df_apellido_usuario."',
                df_usuario_usuario = '".$this->df_usuario_usuario."',
                df_personal_cod = ".$this->df_personal_cod."
                df_clave_usuario = '".$this->df_clave_usuario."',
                df_activo = ".$this->df_activo.",
                df_correo = '".$this->df_correo."',
                df_tipo_usuario = '".$this->df_tipo_usuario."'                    
                    WHERE `df_id_usuario`= ".$this->df_id_usuario;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function deleteUsuario(){
    
        // query 
        $query = "UPDATE `df_usuario` SET 
                    `activo`=0
                    WHERE  `df_id_usuario` = ".$this->df_id_usuario;
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }


    function deletePersonal(){
    
        // query 
        $query = "UPDATE `df_personal` SET 
                    `df_activo_per`=0
                    WHERE  `df_id_personal` = ".$this->df_id_personal;
    
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