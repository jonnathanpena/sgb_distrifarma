<?php
class Compra {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $id_compra;
    public $usuario_id;
    public $fecha_compra;
    public $gasto_compra;
    public $iva_nocredito_compra; 
    public $proveedor_id;
    public $detalle_sustento_comprobante_id;
    public $serie_compra;
    public $documento_compra; 
    public $autorizacion_compra;
    public $fecha_comprobante_compra;
    public $fecha_ingreso_bodega_compra;
    public $fecha_caducidad_compra;
    public $vencimiento_compra;
    public $descripcion_compra;
    public $condiciones_compra;
    public $centro_costo_id;
    public $bodega_ingreso_id;
    public $usa_iva_cero_compra;
    public $st_con_iva_compra;
    public $descuento_con_iva_compra;
    public $total_con_iva_compra;
    public $st_sin_iva_compra;
    public $descuento_sin_iva_compra;
    public $total_sin_iva_compra;
    public $st_iva_cero_compra;
    public $descuento_iva_cero_compra;
    public $total_iva_cero;
    public $ice_cc_compra;
    public $imp_verde_compra;
    public $iva_compra;
    public $otros_compra;
    public $total_compra;
    public $tipo_compra_id;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    // obtener todas las compras
    function readAll(){
    
        // select all query
        $query = "SELECT `id_compra`, `usuario_id`, `fecha_compra`, `gasto_compra`, `iva_nocredito_compra`, 
                    `proveedor_id`, `detalle_sustento_comprobante_id`, `serie_compra`, `documento_compra`, 
                    `autorizacion_compra`, `fecha_comprobante_compra`, `fecha_ingreso_bodega_compra`, 
                    `fecha_caducidad_compra`, `vencimiento_compra`, `descripcion_compra`, `condiciones_compra`, 
                    `centro_costo_id`, `bodega_ingreso_id`, `usa_iva_cero_compra`, `tipo_compra_id` 
                    FROM `compra`";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // obtener una compra por ID
    function readById(){
    
        // select one query
        $query = "SELECT `id_compra`, `usuario_id`, `fecha_compra`, `gasto_compra`, `iva_nocredito_compra`, 
                    `proveedor_id`, `detalle_sustento_comprobante_id`, `serie_compra`, `documento_compra`, 
                    `autorizacion_compra`, `fecha_comprobante_compra`, `fecha_ingreso_bodega_compra`, 
                    `fecha_caducidad_compra`, `vencimiento_compra`, `descripcion_compra`, `condiciones_compra`, 
                    `centro_costo_id`, `bodega_ingreso_id`, `usa_iva_cero_compra`, `tipo_compra_id` 
                    FROM `compra` 
                    WHERE `id_compra` = ".$this->id_compra;
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // insertar una compra
    function insert(){
        
        date_default_timezone_set('America/Bogota');
        $hoy = date("Y-m-d H:i:s");
        // query to insert record
        $query = "INSERT INTO `compra`(`usuario_id`, `fecha_compra`, `gasto_compra`, `iva_nocredito_compra`, 
                    `proveedor_id`, `detalle_sustento_comprobante_id`, `serie_compra`, `documento_compra`, `autorizacion_compra`, 
                    `fecha_comprobante_compra`, `fecha_ingreso_bodega_compra`, `fecha_caducidad_compra`, `vencimiento_compra`, 
                    `descripcion_compra`, `condiciones_compra`, `centro_costo_id`, `bodega_ingreso_id`, `usa_iva_cero_compra`, 
                    `st_con_iva_compra`, `descuento_con_iva_compra`, `total_con_iva_compra`, `st_sin_iva_compra`, 
                    `descuento_sin_iva_compra`, `total_sin_iva_compra`, `st_iva_cero_compra`, `descuento_iva_cero_compra`, 
                    `total_iva_cero`, `ice_cc_compra`, `imp_verde_compra`, `iva_compra`, `otros_compra`, `total_compra`, 
                    `tipo_compra_id`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // prepara la sentencia del query
        $stmt = $this->conn->prepare($query);  
        
        $stmt->bindParam(1, $this->usuario_id);
        
        $stmt->bindParam(2, $hoy);
        $stmt->bindParam(3, $this->gasto_compra);
        if ($this->iva_nocredito_compra == 'null') {
            $this->iva_nocredito_compra = null;
        } 
        $stmt->bindParam(4, $this->iva_nocredito_compra);
        $stmt->bindParam(5, $this->proveedor_id);
        $stmt->bindParam(6, $this->detalle_sustento_comprobante_id);
        if ($this->serie_compra == 'null') {
            $this->serie_compra = null;
        } 
        $stmt->bindParam(7, $this->serie_compra); 
        if ($this->documento_compra == 'null') {
            $this->documento_compra = null;
        } 
        $stmt->bindParam(8, $this->documento_compra);
        if ($this->autorizacion_compra == 'null') {
            $this->autorizacion_compra = null;
        } 
        $stmt->bindParam(9, $this->autorizacion_compra);
        $stmt->bindParam(10, $this->fecha_comprobante_compra);  
        $stmt->bindParam(11, $this->fecha_ingreso_bodega_compra);  
        $stmt->bindParam(12, $this->fecha_caducidad_compra); 
        if ($this->vencimiento_compra == 'null') {
            $this->vencimiento_compra = null;
        } 
        $stmt->bindParam(13, $this->vencimiento_compra);
        if ($this->descripcion_compra == 'null') {
            $this->descripcion_compra = null;
        } 
        $stmt->bindParam(14, $this->descripcion_compra);  
        $stmt->bindParam(15, $this->condiciones_compra);  
        if ($this->centro_costo_id == 'null') {
            $this->centro_costo_id = null;
        } 
        $stmt->bindParam(16, $this->centro_costo_id);
        if ($this->bodega_ingreso_id == 'null') {
            $this->bodega_ingreso_id = null;
        } 
        $stmt->bindParam(17, $this->bodega_ingreso_id);  
        if ($this->usa_iva_cero_compra == 'null') {
            $this->usa_iva_cero_compra = null;
        } 
        $stmt->bindParam(18, $this->usa_iva_cero_compra);
        $stmt->bindParam(19, $this->st_con_iva_compra);
        $stmt->bindParam(20, $this->descuento_con_iva_compra);
        $stmt->bindParam(21, $this->total_con_iva_compra);
        $stmt->bindParam(22, $this->st_sin_iva_compra);
        $stmt->bindParam(23, $this->descuento_sin_iva_compra);
        $stmt->bindParam(24, $this->total_sin_iva_compra);
        $stmt->bindParam(25, $this->st_iva_cero_compra);
        $stmt->bindParam(26, $this->descuento_iva_cero_compra);
        $stmt->bindParam(27, $this->total_iva_cero);
        $stmt->bindParam(28, $this->ice_cc_compra);
        $stmt->bindParam(29, $this->imp_verde_compra);
        $stmt->bindParam(30, $this->iva_compra);
        $stmt->bindParam(31, $this->otros_compra);
        $stmt->bindParam(32, $this->total_compra);
        $stmt->bindParam(33, $this->tipo_compra_id);
        
        if($stmt->execute()){
            return $this->conn->lastInsertId() * 1;
        }else{
            return false;
        }   
        
        
    }

    // actualizar datos de una compra
    function update(){
    
        // query 
        $query = "UPDATE `compra` SET 
                    `gasto_compra`= ".$this->gasto_compra.",
                    `iva_nocredito_compra`= ".$this->iva_nocredito_compra.",
                    `proveedor_id`= ".$this->proveedor_id.",
                    `detalle_sustento_comprobante_id`= ".$this->detalle_sustento_comprobante_id.",
                    `serie_compra`= '".$this->serie_compra."',
                    `documento_compra`= '".$this->documento_compra."',
                    `autorizacion_compra`= '".$this->autorizacion_compra."',
                    `fecha_comprobante_compra`= '".$this->fecha_comprobante_compra."',
                    `fecha_ingreso_bodega_compra`= '".$this->fecha_ingreso_bodega_compra."',
                    `fecha_caducidad_compra`= '".$this->fecha_caducidad_compra."',
                    `vencimiento_compra`= '".$this->vencimiento_compra."',
                    `descripcion_compra`= '".$this->descripcion_compra."',
                    `condiciones_compra`= ".$this->condiciones_compra.",
                    `centro_costo_id`= ".$this->centro_costo_id.",
                    `bodega_ingreso_id`= ".$this->bodega_ingreso_id.",
                    `usa_iva_cero_compra`= ".$this->usa_iva_cero_compra.",
                    WHERE `id_compra` = ".$this->id_compra;
    
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