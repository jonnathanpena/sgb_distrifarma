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
        $query = "SELECT `df_num_factura`, `df_fecha_fac`, `df_cliente_cod_fac`, `df_personal_cod_fac`, `df_sector_cod_fac`, 
                            `df_forma_pago_fac`, `df_subtotal_fac`, `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, 
                            `df_creadaBy`, `df_fecha_creacion`, `df_edo_factura_fac`, edo.df_nombre_estado, 
                            `df_fecha_entrega_fac`, cli.df_codigo_cliente, cli.df_nombre_cli, cli.df_razon_social_cli, 
                            cli.`df_documento_cli`, (SELECT DISTINCT ent.df_codigo_guia_ent
                    FROM `df_detalle_entrega` detent
                    INNER JOIN df_guia_entrega ent ON (detent.`df_guia_entrega` = ent.df_num_guia_entrega)
                    WHERE ent.df_guia_ent_recibido = 0 AND detent.df_num_factura_detent = fac.df_num_factura) as guia
                        FROM `df_factura` as fac
                        INNER JOIN df_cliente as cli ON (fac.df_cliente_cod_fac = cli.df_id_cliente)
                        INNER JOIN df_estado_factura edo ON (fac.df_edo_factura_fac = edo.df_id_estado)
                        WHERE DATE(df_fecha_fac) >= DATE_ADD(NOW(), INTERVAL -3 DAY)
                        ORDER BY df_num_factura DESC";
        /*"SELECT `df_num_factura`, `df_fecha_fac`, `df_cliente_cod_fac`, `df_personal_cod_fac`, `df_sector_cod_fac`, 
                    `df_forma_pago_fac`, `df_subtotal_fac`, `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, 
                    `df_creadaBy`, `df_fecha_creacion`, `df_edo_factura_fac`, edo.df_nombre_estado, 
                    `df_fecha_entrega_fac`, cli.df_codigo_cliente, cli.df_nombre_cli, cli.df_razon_social_cli, 
                    cli.`df_documento_cli`
                FROM `df_factura` as fac
                INNER JOIN df_cliente as cli ON (fac.df_cliente_cod_fac = cli.df_id_cliente)
                INNER JOIN df_estado_factura edo ON (fac.df_edo_factura_fac = edo.df_id_estado)
                WHERE DATE(df_fecha_fac) >= DATE_ADD(NOW(), INTERVAL -3 DAY)
                ORDER BY df_num_factura DESC";*/

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function readId(){
        // select all query
        $query = "SELECT `df_num_factura`, `df_fecha_fac`, `df_cliente_cod_fac`, `df_personal_cod_fac`, `df_sector_cod_fac`, `df_forma_pago_fac`, `df_subtotal_fac`, 
                    `df_descuento_fac`, `df_iva_fac`, `df_valor_total_fac`, `df_creadaBy`, `df_fecha_creacion`,
                    `df_edo_factura_fac`,  edo.df_nombre_estado, `df_fecha_entrega_fac`, cli.df_codigo_cliente, 
                    cli.df_nombre_cli, cli.df_razon_social_cli, cli.`df_documento_cli`, (SELECT DISTINCT ent.df_codigo_guia_ent
                    FROM `df_detalle_entrega` detent
                    INNER JOIN df_guia_entrega ent ON (detent.`df_guia_entrega` = ent.df_num_guia_entrega)
                    WHERE ent.df_guia_ent_recibido = 0 AND detent.df_num_factura_detent = fac.df_num_factura) as guia
                FROM `df_factura` as fac
                INNER JOIN df_cliente as cli ON (fac.df_cliente_cod_fac = cli.df_id_cliente)
                INNER JOIN df_estado_factura edo ON (fac.df_edo_factura_fac = edo.df_id_estado)
                    WHERE df_num_factura like '%".$this->df_num_factura."%'
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
                        `df_fecha_creacion`, `df_edo_factura_fac`, `df_fecha_entrega_fac`, cli.df_codigo_cliente, cli.df_nombre_cli, cli.df_razon_social_cli, cli.`df_documento_cli`
                        FROM `df_factura` as fac
                        INNER JOIN df_cliente as cli ON (fac.df_cliente_cod_fac = cli.df_id_cliente)
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
                        fac.df_edo_factura_fac IN (1,3,4,6) and fac.df_fecha_entrega_fac = '".$this->fecha."'
                        and not exists (select * from df_detalle_entrega det, df_guia_entrega ent
                                    where concat('00',det.df_num_factura_detent) = fac.df_num_factura
                                    and ent.df_guia_ent_recibido = 0
                                    and det.df_guia_entrega = ent.df_num_guia_entrega))
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

     //Actualiza estado de factura
     function estado(){
        // query 
        $query = "UPDATE `df_factura` SET                                        
                    `df_edo_factura_fac` = ".$this->df_edo_factura_fac."
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