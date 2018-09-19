<?php

class Factura {

    // conexión a la base de datos y nombre de la tabla

    private $conn;

    // Propiedades del objeto

    //Nombre igualitos a las columnas de la base de datos

    public $df_num_factura;
    public $df_fecha_fac;
    public $df_cliente_cod_fac;
    public $df_personal_cod_fac;
    public $df_sector_cod_fac;
    public $df_forma_pago_fac;
    public $df_subtotal_fac;
    public $df_descuento_fac;
    public $df_iva_fac;
    public $df_valor_total_fac;
    public $df_creadaBy;
    public $df_fecha_creacion;
    public $df_edo_factura_fac;
    public $df_fecha_entrega_fac;
    public $fecha;
    public $sector;

    //constructor con base de datos como conexión

    public function __construct($db){
        $this->conn = $db;
    }

    // obtener maximo id factura

    function read(){
        // select all query
        $query = "SELECT `df_num_factura`, `df_fecha_fac`, `df_cliente_cod_fac`, `df_personal_cod_fac`, `df_sector_cod_fac`, `df_forma_pago_fac`, `df_subtotal_fac`, 
                    `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, `df_creadaBy`, `df_fecha_creacion`,
                    `df_edo_factura_fac`, `df_fecha_entrega_fac` 
                    FROM `df_factura` WHERE `df_num_factura` like '%".$this->df_num_factura."%'
                    ORDER BY df_num_factura DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // obtener factura de login
    function readById(){    

        // select all query

        $query = "SELECT `df_num_factura`, `df_fecha_fac`, `df_cliente_cod_fac`, `df_personal_cod_fac`, `df_sector_cod_fac`, `df_forma_pago_fac`, 
                        `df_subtotal_fac`, `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, `df_creadaBy`, 
                        `df_fecha_creacion`, `df_edo_factura_fac`, `df_fecha_entrega_fac` FROM `df_factura` 
                        WHERE `df_num_factura` = ".$this->df_num_factura;

        // prepare query statement

        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();

        return $stmt;
    }

    //facturas para guia de entrega, condición el sector seleccionado
    function readFacturaGEnt(){    
        // select all query

        $query = "SELECT fac.`df_num_factura`, fac.`df_fecha_fac`, fac.`df_cliente_cod_fac`, 
                    fac.`df_personal_cod_fac`, fac.`df_sector_cod_fac`, fac.`df_forma_pago_fac`, 
                    fac.`df_subtotal_fac`, fac.`df_descuento_fac`, fac.`df_iva_fac`, fac.`df_valor_total_fac`, 
                    fac.`df_creadaBy`, fac.`df_fecha_creacion`, fac.`df_edo_factura_fac`, fac.`df_fecha_entrega_fac`
                FROM `df_sector` as sec
                INNER JOIN `df_cliente` as cli on (sec.`df_codigo_sector` = cli.`df_sector_cod`)
                INNER JOIN `df_factura` as fac on (fac.df_cliente_cod_fac = cli.df_id_cliente and 
                        fac.df_edo_factura_fac = 1 and fac.df_fecha_entrega_fac = '".$this->fecha."')
                WHERE fac.`df_sector_cod_fac` in (".$this->sector.")
                ORDER BY fac.`df_num_factura`ASC";

        // prepare query statement

        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();

        return $stmt;
    }


    // insertar un factura
    function insert(){
        // query to insert record
        $query = "INSERT INTO `df_factura`(`df_fecha_fac`, `df_cliente_cod_fac`, 
                    `df_personal_cod_fac`, `df_sector_cod_fac`, `df_forma_pago_fac`, `df_subtotal_fac`, 
                    `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, `df_creadaBy`, `df_fecha_creacion`,
                    `df_edo_factura_fac`, `df_fecha_entrega_fac`) VALUES (
                        '".$this->df_fecha_fac."',
                        ".$this->df_cliente_cod_fac.",
                        ".$this->df_personal_cod_fac.",
                        ".$this->df_sector_cod_fac.",
                        '".$this->df_forma_pago_fac."',
                        ".$this->df_subtotal_fac.",
                        ".$this->df_descuento_fac.",
                        ".$this->df_iva_fac.",
                        ".$this->df_valor_total_fac.",
                        ".$this->df_creadaBy.",
                        '".$this->df_fecha_creacion."',
                        ".$this->df_edo_factura_fac.",
                        '".$this->df_fecha_entrega_fac."')";

        // prepara la sentencia del query

        $stmt = $this->conn->prepare($query);    
        
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }else{
            return false;
        }   
    }

    // actualizar datos de factura
    function update(){
        // query 
        $query = "UPDATE `df_factura` SET                     
                    `df_cliente_cod_fac`= '".$this->df_cliente_cod_fac."',
                    `df_personal_cod_fac`= '".$this->df_personal_cod_fac."',
                    `df_sector_cod_fac`= '".$this->df_sector_cod_fac."',
                    `df_forma_pago_fac`= '".$this->df_forma_pago_fac."',
                    `df_subtotal_fac`= ".$this->df_subtotal_fac.",
                    `df_descuento_fac`= ".$this->df_descuento_fac.",
                    `df_iva_fac`= ".$this->df_iva_fac.",
                    `df_valor_total_fac`= ".$this->df_valor_total_fac.",
                    `df_edo_factura_fac` = ".$this->df_edo_factura_fac.",
                    df_fecha_entrega_fac = '".$this->df_fecha_entrega_fac."'
                    WHERE `df_num_factura`=".$this->df_num_factura;                        
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