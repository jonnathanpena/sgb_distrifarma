<?php
class Usuario {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
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

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener usuario de login
    function readByUser(){
    
        // select all query
        $query = "SELECT `df_id_usuario`, `df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, `df_apellido_usuario`, 
                    `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario` 
                    FROM `df_usuario` WHERE `df_usuario_usuario` = '".$this->df_usuario_usuario."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByDocumento() {
        // select all query
        $query = "SELECT `df_id_usuario`, `df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, `df_apellido_usuario`, 
                    `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario` 
                    FROM `df_usuario` WHERE `df_documento_usuario` = '".$this->df_documento_usuario."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    // obtener todos los usuarios y/o por id y/o nombre de usuario
    function read(){
    
        // select all query
        $query = "SELECT `df_id_usuario`, `df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, 
                    `df_apellido_usuario`, `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario` 
                    FROM `df_usuario` WHERE `df_nombre_usuario` like '%".$this->df_nombre_usuario."%' 
                    OR `df_documento_usuario` like '%".$this->df_nombre_usuario."%' OR `df_usuario_usuario` like '%".$this->df_nombre_usuario."%'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener todos los usuarios por id
    function readById(){
    
        // select all query
        $query = "SELECT `df_id_usuario`, `df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, 
                    `df_apellido_usuario`, `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario` 
                    FROM `df_usuario` WHERE `df_id_usuario` = ".$this->df_id_usuario;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    
    // insertar un usuario
    function insert(){
    
        // query to insert record
        if ($this->df_personal_cod != "") {
            $query = "INSERT INTO `df_usuario`(`df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, `df_apellido_usuario`, 
                        `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario`) VALUES (
                            null,
                            null,
                            null,
                            null,
                            '".$this->df_usuario_usuario."',
                            ".$this->df_personal_cod.",
                            '".$this->df_clave_usuario."',
                            1,
                            '".$this->df_correo."',
                            '".$this->df_tipo_usuario."'
                        )";
        } else {
            $query = "INSERT INTO `df_usuario`(`df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, `df_apellido_usuario`, 
                        `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario`) VALUES (
                            '".$this->df_tipo_documento_usuario."',
                            ".$this->df_documento_usuario.",
                            '".$this->df_nombre_usuario."',
                            '".$this->df_apellido_usuario."',
                            '".$this->df_usuario_usuario."',
                            null,
                            '".$this->df_clave_usuario."',
                            1,
                            '".$this->df_correo."',
                            '".$this->df_tipo_usuario."'
                        )";
        }        
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query); 

        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de usuario
    function update(){
    
        // query 
        if ($this->df_personal_cod == "" || $this->df_personal_cod == null) {
            $query = "UPDATE `df_usuario` SET 
                    `df_tipo_documento_usuario`= '".$this->df_tipo_documento_usuario."',
                    `df_documento_usuario`= '".$this->df_documento_usuario."',
                    `df_nombre_usuario`= '".$this->df_nombre_usuario."',
                    `df_apellido_usuario`= '".$this->df_apellido_usuario."',
                    `df_usuario_usuario`= '".$this->df_usuario_usuario."',
                    `df_clave_usuario`= '".$this->df_clave_usuario."',
                    `df_activo`= ".$this->df_activo.",
                    `df_correo`= '".$this->df_correo."',
                    `df_tipo_usuario`= '".$this->df_tipo_usuario."'
                    WHERE `df_id_usuario` = ".$this->df_id_usuario;
        } else {
            $query = "UPDATE `df_usuario` SET 
                        `df_usuario_usuario`= '".$this->df_usuario_usuario."',
                        `df_personal_cod`= ".$this->df_personal_cod.",
                        `df_clave_usuario`= '".$this->df_clave_usuario."',
                        `df_activo`= ".$this->df_activo.",
                        `df_correo`= '".$this->df_correo."',
                        `df_tipo_usuario`= '".$this->df_tipo_usuario."' 
                        WHERE `df_id_usuario` = ".$this->df_id_usuario; 
        }        
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);
        
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }       
        
    }

    function cambioClave() {
        $query = "UPDATE `df_usuario` SET `df_clave_usuario`= '".$this->df_clave_usuario."' WHERE `df_id_usuario` = ".$this->df_id_usuario;
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function delete(){
    
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

     

}
?>