<?php
class CatMovimiento {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_cat_movimiento;
    public $df_nombre_movimiento;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener ciudad de login
    function read(){
    
        // select all query
        $query = "SELECT `df_id_cat_movimiento`, `df_nombre_movimiento`, `df_tipo` FROM `df_cat_movimiento`
                    ORDER BY df_nombre_movimiento ASC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
   

}
?>