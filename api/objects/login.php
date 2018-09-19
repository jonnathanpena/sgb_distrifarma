<?php
class Login {

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
    // obtener usuario de login


    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    function readLogin(){
    
        // select all query
        $query = "SELECT `df_id_usuario`, `df_tipo_documento_usuario`, `df_documento_usuario`, `df_nombre_usuario`, 
                    `df_apellido_usuario`, `df_usuario_usuario`, `df_personal_cod`, `df_clave_usuario`, `df_activo`, `df_correo`, `df_tipo_usuario` 
                    FROM `df_usuario` WHERE `df_usuario_usuario` = '".$this->df_usuario_usuario."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

     

}
?>