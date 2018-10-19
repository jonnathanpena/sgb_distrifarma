<?php
class Inventario {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_inventario;
    public $df_cant_bodega;
    public $df_cant_transito;
    public $df_producto;
    public $df_ppp_ind;
    public $df_pvt_ind;
    public $df_ppp_total;
    public $df_pvt_total;
    public $df_minimo_sug;
    public $df_und_caja;
    public $df_bodega;
    public $df_codigo_prod;
    public $df_nombre_producto;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener inventario
    function read(){
    
        // select all query
        $query = "SELECT inv.`df_id_inventario`, inv.`df_cant_bodega`, inv.`df_cant_transito`, inv.`df_producto`,
                inv.`df_ppp_ind`, inv.`df_pvt_ind`, inv.`df_ppp_total`, inv.`df_pvt_total`, inv.`df_minimo_sug`, 
                inv.`df_und_caja`, inv.`df_bodega`, pro.`df_codigo_prod`, pro.`df_nombre_producto`,
                (inv.`df_cant_bodega` / inv.`df_und_caja`) as cantidad 
                FROM `df_inventario` inv 
                LEFT JOIN `df_producto` pro ON (pro.df_id_producto = inv.df_producto)
                WHERE pro.`df_codigo_prod` LIKE '%".$this->df_codigo_prod."%' OR 
                      pro.`df_nombre_producto` like '%".$this->df_nombre_producto."%'
                ORDER BY  inv.`df_id_inventario` DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readById(){
    
        // select all query
        $query = "SELECT inv.`df_id_inventario`, inv.`df_cant_bodega`, inv.`df_cant_transito`, inv.`df_producto`, inv.`df_ppp_ind`, 
                    inv.`df_pvt_ind`, inv.`df_ppp_total`, inv.`df_pvt_total`, inv.`df_minimo_sug`, pro.`df_und_caja`, inv.`df_bodega` 
                    FROM `df_inventario` as inv
                    INNER JOIN  `df_producto_precio` AS pro ON (pro.`df_producto_id` = inv.`df_producto`) 
                    WHERE `df_id_inventario` =".$this->df_id_inventario;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function readByIdProd(){
    
        // select all query
        $query = "SELECT inv.`df_id_inventario`, inv.`df_cant_bodega`, inv.`df_cant_transito`, inv.`df_producto`, inv.`df_ppp_ind`, 
        inv.`df_pvt_ind`, inv.`df_ppp_total`, inv.`df_pvt_total`, inv.`df_minimo_sug`, pro.`df_und_caja`, inv.`df_bodega` 
        FROM `df_inventario` as inv
        INNER JOIN  `df_producto_precio` AS pro ON (pro.`df_producto_id` = inv.`df_producto`) 
                WHERE `df_producto` = ".$this->df_producto." and `df_id_inventario` = (select max(inv.`df_id_inventario`) 
                        from  `df_inventario` inv where inv.`df_producto` = ".$this->df_producto." )";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar inventario
    function insert(){
    
        // query to insert record
        $query = "INSERT INTO `df_inventario`(`df_cant_bodega`, `df_cant_transito`, `df_producto`, `df_ppp_ind`, 
                        `df_pvt_ind`, `df_ppp_total`, `df_pvt_total`, `df_minimo_sug`, `df_und_caja`, `df_bodega`) 
                        VALUES  (
                        ".$this->df_cant_bodega.",
                        ".$this->df_cant_transito.",
                        ".$this->df_producto.",
                        ".$this->df_ppp_ind.",
                        ".$this->df_pvt_ind.",
                        ".$this->df_ppp_total.",
                        ".$this->df_pvt_total.",
                        ".$this->df_minimo_sug.",
                        ".$this->df_und_caja.",
                        ".$this->df_bodega.")";
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }           
    }

    // modificar inventario
    function update(){
    
        // query to update record
        $query = "UPDATE `df_inventario` SET 
                    `df_cant_bodega`= ".$this->df_cant_bodega.",
                    `df_cant_transito`= ".$this->df_cant_transito.",
                    `df_producto`= '".$this->df_producto."',
                    `df_ppp_ind`= ".$this->df_ppp_ind.",
                    `df_pvt_ind`= ".$this->df_pvt_ind.",
                    `df_ppp_total`= ".$this->df_ppp_total.",
                    `df_pvt_total`= ".$this->df_pvt_total.",
                    `df_minimo_sug`= ".$this->df_minimo_sug.",
                    `df_und_caja`= ".$this->df_und_caja.",
                    `df_bodega`= ".$this->df_bodega." 
                    WHERE `df_id_inventario` = ".$this->df_id_inventario;
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }           
    }

}
?>