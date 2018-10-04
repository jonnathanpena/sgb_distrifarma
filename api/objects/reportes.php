<?php
class Reportes {

    // conexión a la base de datos y nombre de la tabla
    private $conn;

    // Propiedades del objeto
    //Nombre igualitos a las columnas de la base de datos
    public $df_id_libro_diario;
    public $df_valor_inicial_ld;
    public $df_fecha_ld;
    public $df_fecha_ini;
    public $df_fecha_fin;
    public $df_descipcion_ld;
    public $df_ingreso_ld;
    public $df_egreso_ld;
    public $total_ingreso;
    public $total_egreso;
    public $existencia;
    public $df_id_banco;
    public $df_fecha_banco;
    public $df_usuario_id_banco;
    public $df_tipo_movimiento;
    public $df_monto_banco;
    public $df_saldo_banco;
    public $df_num_documento_banco;
    public $df_detalle_mov_banco;
    public $df_modificadoBy_banco;
    public $COUNT_FACTURA;
    public $df_personal_cod_fac;
    public $df_nombre_per;
    public $df_apellido_per;
    public $df_cargo_per;
    public $VALOR_VENDIDO;
    public $VALOR_ANULADO;
    public $valor_vendido;
    public $und_vendida;
    public $df_nombre_producto;
    public $VALOR_TOTAL;
    public $df_nombre_sector;
    public $df_id_gasto;
    public $df_usuario_id;
    public $df_usuario_usuario;
    public $df_movimiento;
    public $df_gasto;
    public $df_saldo;
    public $df_fecha_gasto;
    public $df_num_documento;
    public $tipo;

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    //reportes por fecha de libro diario
    function readByLibroDiario(){
    
        // select all query
        $query = "SELECT `df_id_libro_diario`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                    `df_ingreso_ld`, `df_egreso_ld` FROM `df_libro_diario` 
                    WHERE date(`df_fecha_ld`) >='".$this->df_fecha_ini."' and date(df_fecha_ld) <= '".$this->df_fecha_fin."'
                    ORDER BY df_fecha_ld asc";
      // prepare query statement
      $stmt = $this->conn->prepare($query);
    
      // execute query
      $stmt->execute();
  
      return $stmt;
  }

//reportes por fecha caja chica
function readByCajaChica(){
    
    // select all query
    $query = "(SELECT cg.`df_id_gasto`, cg.`df_usuario_id`, usu.`df_usuario_usuario`, cg.`df_movimiento`, 
                cg.`df_gasto`, cg.`df_saldo`, cg.`df_fecha_gasto`, cg.`df_num_documento`, 'E' as tipo 
                FROM `df_caja_chica_gasto` cg
                INNER JOIN `df_usuario` usu ON (usu.df_id_usuario = cg.`df_usuario_id`)
                where date(cg.df_fecha_gasto)  BETWEEN '".$this->df_fecha_ini."' AND '".$this->df_fecha_fin."'
                ORDER by cg.df_fecha_gasto ASC) 
            UNION (SELECT ci.`df_id_ingreso_cc`, ci.`df_usuario_id_ingreso`, us.`df_usuario_usuario`, 
                'Ingreso' as ingreso, ci.`df_valor_cheque`, ci.`df_saldo_cc`, ci.`df_fecha_ingreso`, 
                ci.`df_num_cheque`,  'I' as tipo 
                FROM `df_caja_chica_ingreso` ci
                INNER JOIN `df_usuario` us ON (us.df_id_usuario = ci.`df_usuario_id_ingreso`)
                WHERE date(ci.df_fecha_ingreso) BETWEEN '".$this->df_fecha_ini."' AND '".$this->df_fecha_fin."'
                ORDER BY ci.df_fecha_ingreso ASC) 
            ORDER BY `df_fecha_gasto` ASC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
}

//reportes por fecha Banco
  function readByBanco(){
    
    // select all query
    $query = "SELECT `df_id_banco`,date(`df_fecha_banco`) as df_fecha_banco, `df_usuario_id_banco`, 
                `df_tipo_movimiento`, `df_monto_banco`, `df_saldo_banco`, `df_num_documento_banco`, 
                `df_detalle_mov_banco`, `df_modificadoBy_banco`,
                (SELECT sum(`df_monto_banco`) FROM `df_banco` WHERE date(`df_fecha_banco`) >='".$this->df_fecha_ini."' 
                    AND  date(`df_fecha_banco`) <='".$this->df_fecha_fin."' AND df_tipo_movimiento = 'Ingreso') as total_ingreso,
                (SELECT sum(`df_monto_banco`) FROM `df_banco` WHERE date(`df_fecha_banco`) >='".$this->df_fecha_ini."' 
                    AND  date(`df_fecha_banco`) <='".$this->df_fecha_fin."' AND df_tipo_movimiento = 'Egreso') as total_egreso,
                ((SELECT sum(`df_monto_banco`) FROM `df_banco` WHERE date(`df_fecha_banco`) >='".$this->df_fecha_ini."'
                    AND  date(`df_fecha_banco`) <='".$this->df_fecha_fin."' AND df_tipo_movimiento = 'Ingreso')-(SELECT sum(`df_monto_banco`) 
                    FROM `df_banco` WHERE date(`df_fecha_banco`) >='".$this->df_fecha_ini."' AND  date(`df_fecha_banco`) <='".$this->df_fecha_fin."' 
                    AND df_tipo_movimiento = 'Egreso')) as existencia
            FROM `df_banco` 
            WHERE  date(`df_fecha_banco`) >='".$this->df_fecha_ini."' AND  date(`df_fecha_banco`) <='".$this->df_fecha_fin."' 	
            ORDER BY df_fecha_banco ASC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
    }

//reportes por fecha ventas por vendedor
function readByVentaVendedor(){
    
    // select all query
    $query = "SELECT COUNT(fac.`df_num_factura`) AS COUNT_FACTURA, fac.`df_personal_cod_fac`, 
                UPPER(per.`df_nombre_per`) AS df_nombre_per, UPPER(per.`df_apellido_per`) AS df_apellido_per, 
                per.`df_cargo_per`, SUM(fac.`df_subtotal_fac`) AS VALOR_VENDIDO,
                (SELECT SUM(fa.`df_subtotal_fac`) FROM `df_factura` as fa WHERE (
                    (date(fa.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fa.df_fecha_fac) <='".$this->df_fecha_fin."')  
                    or (date(fa.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' and 
                    date(fa.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."')) and  
                    fa.df_personal_cod_fac = fac.df_personal_cod_fac AND ((fa.`df_edo_factura_fac` in (1,5) ) 
                    or (date(fa.`df_fecha_entrega_fac`)  > '".$this->df_fecha_fin."' and (fa.`df_edo_factura_fac` IN (1,2,4,5) ))) ) 
                        AS VALOR_ANULADO
                FROM `df_factura` as fac
                INNER JOIN `df_personal` AS per on (fac.`df_personal_cod_fac` = per.`df_id_personal`)
                WHERE (date(fac.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fac.df_fecha_fac) <='".$this->df_fecha_fin."') 
                        or (date(fac.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' 
                        and date(fac.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."') 
                GROUP BY per.`df_nombre_per` ASC  ";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
    }

    function readByVentaProdVendedor(){
    
        // select all query
        $query = "SELECT SUM(dfac.`df_valor_sin_iva_detfac`) valor_vendido,
                    SUM(dfac.`df_cant_x_und_detfac`) und_vendida,
                    pro.df_nombre_producto,
                    UPPER(per.`df_nombre_per`) AS df_nombre_per, UPPER(per.`df_apellido_per`) AS df_apellido_per,
        
                    (SELECT SUM(dfa.`df_valor_sin_iva_detfac`)
                        FROM `df_detalle_factura` AS dfa 
                        INNER JOIN `df_factura` as fa ON (dfa.df_num_factura_detfac = fa.`df_num_factura`)
                        WHERE (date(fa.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fa.df_fecha_fac) <='".$this->df_fecha_fin."') 
                            and fa.`df_edo_factura_fac` NOT IN (5) and fac.`df_personal_cod_fac` = fa.`df_personal_cod_fac`
                        GROUP BY fa.`df_personal_cod_fac`) VALOR_TOTAL
                FROM `df_factura` as fac
                INNER JOIN `df_detalle_factura` AS dfac ON (dfac.`df_num_factura_detfac` = fac.`df_num_factura`)
                INNER JOIN `df_producto_precio` as pp ON (pp.df_id_precio = dfac.`df_prod_precio_detfac`)
                INNER JOIN `df_producto` AS pro ON (pro.df_id_producto = pp.df_producto_id)
                INNER JOIN df_personal AS per on (fac.`df_personal_cod_fac` = per.`df_id_personal`  
                    AND  per.`df_cargo_per` LIKE '%Vendedor%')
                WHERE (date(fac.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fac.df_fecha_fac) <='".$this->df_fecha_fin."') 
                    and fac.`df_edo_factura_fac` NOT IN (5)
                GROUP BY  pro.df_nombre_producto,  fac.`df_personal_cod_fac`
                ORDER BY per.`df_nombre_per` ASC";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

//reportes por fecha ventas por vendedor y sector
function readByVentaSector(){
    
    // select all query
    $query = "SELECT COUNT(fac.`df_num_factura`) AS COUNT_FACTURA, fac.`df_personal_cod_fac`,
                 UPPER(per.`df_nombre_per`) AS df_nombre_per, UPPER(per.`df_apellido_per`) AS df_apellido_per, 
                 per.`df_cargo_per`, SUM(fac.`df_subtotal_fac`) AS VALOR_VENDIDO,
                (SELECT SUM(fa.`df_subtotal_fac`) FROM `df_factura` as fa 
                WHERE ((date(fa.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fa.df_fecha_fac) <='".$this->df_fecha_fin."')  
                or (date(fa.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' 
                and date(fa.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."')) 
                and  fa.df_personal_cod_fac = fac.df_personal_cod_fac AND fa.`df_sector_cod_fac` = fac.`df_sector_cod_fac` 
                AND  ((fa.`df_edo_factura_fac` in (1,5) ) or (date(fa.`df_fecha_entrega_fac`)  > '".$this->df_fecha_fin."' 
                and (fa.`df_edo_factura_fac` IN (1,2,4,5) ))) 
                GROUP BY fa.`df_personal_cod_fac`, fa.`df_sector_cod_fac`) AS VALOR_ANULADO,
                sec.`df_nombre_sector`
            FROM `df_factura` as fac
            INNER JOIN `df_personal` AS per on (fac.`df_personal_cod_fac` = per.`df_id_personal`)
               -- AND per.`df_cargo_per` LIKE '%Vendedor%')
            INNER JOIN `df_sector` sec ON (sec.`df_codigo_sector` = fac.`df_sector_cod_fac`)
            WHERE (date(fac.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fac.df_fecha_fac) <='".$this->df_fecha_fin."') 
                or (date(fac.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' and 
                date(fac.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."') 
            GROUP BY per.`df_nombre_per`, fac.`df_sector_cod_fac`
            ORDER BY per.`df_nombre_per` ASC ";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
    }


    //reportes CLIENTE POR COMPRA ULTIMOS 30 DIAS
    function readByClienteCompra(){
    
    // select all query
    $query = "SELECT COUNT(fac.`df_num_factura`) PERIOCIDAD , fac.`df_cliente_cod_fac`, 
                UPPER(cli.`df_nombre_cli`), fac.`df_personal_cod_fac`, UPPER(per.`df_nombre_per`), 
                UPPER(per.`df_apellido_per`), fac.`df_sector_cod_fac`, sec.df_nombre_sector, 
                SUM(fac.`df_subtotal_fac`) VALOR_COMPRA_SIN_IVA, SUM(fac.`df_valor_total_fac`) VALOR_TOTAL_COMPRA
            FROM `df_factura` AS fac
            INNER JOIN `df_cliente` AS cli ON (cli.`df_id_cliente` = fac.`df_cliente_cod_fac`)
            INNER JOIN `df_sector` as sec ON (sec.df_codigo_sector = cli.df_sector_cod and 
                cli.`df_id_cliente` = fac.`df_cliente_cod_fac`)
            INNER JOIN `df_personal` AS per ON (per.df_id_personal = fac.`df_personal_cod_fac`)
            WHERE  ((df_fecha_fac BETWEEN NOW() - INTERVAL 30 DAY AND NOW()) OR 
                (`df_fecha_entrega_fac` BETWEEN NOW() - INTERVAL 30 DAY AND NOW())) AND 
                `df_edo_factura_fac` IN (2,4)
            GROUP BY df_cliente_cod_fac, df_sector_cod_fac, df_personal_cod_fac
            ORDER BY VALOR_COMPRA_SIN_IVA DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
    }

    //reportes CLIENTE POR COMPRA ULTIMOS 30 DIAS
   /* function readByClienteCompra(){
    
    // select all query
    $query = "SELECT DISTINCT  fac.`df_cliente_cod_fac`, fac.df_fecha_fac ,  UPPER(cli.`df_nombre_cli`), fac.`df_personal_cod_fac`, UPPER(per.`df_nombre_per`), UPPER(per.`df_apellido_per`), fac.`df_sector_cod_fac`, sec.df_nombre_sector, SUM(fac.`df_subtotal_fac`) VALOR_COMPRA_SIN_IVA, SUM(fac.`df_valor_total_fac`) VALOR_TOTAL_COMPRA
FROM `df_factura` AS fac
INNER JOIN `df_cliente` AS cli ON (cli.`df_id_cliente` = fac.`df_cliente_cod_fac`)
INNER JOIN `df_sector` as sec ON (sec.df_codigo_sector = cli.df_sector_cod and cli.`df_id_cliente` = fac.`df_cliente_cod_fac`)
INNER JOIN `df_personal` AS per ON (per.df_id_personal = fac.`df_personal_cod_fac`)
WHERE  ((fac.df_fecha_fac < NOW() - INTERVAL 30 DAY) OR 
	(fac.`df_fecha_entrega_fac` < NOW() - INTERVAL 30 DAY)) AND fac.`df_edo_factura_fac` IN (2,4)
    AND fac.`df_cliente_cod_fac` NOT IN (SELECT DISTINCT `df_cliente_cod_fac`
FROM `df_factura` 
WHERE  ((df_fecha_fac BETWEEN NOW() - INTERVAL 30 DAY AND NOW()) OR 
                (`df_fecha_entrega_fac` BETWEEN NOW() - INTERVAL 30 DAY AND NOW())) AND 
                `df_edo_factura_fac` IN (2,4))

GROUP BY df_cliente_cod_fac, df_sector_cod_fac, df_personal_cod_fac
ORDER BY fac.df_fecha_fac  DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
    }**

    /* 
    function readByEntregaSector(){
    
        // select all query
        $query = "SELECT COUNT(fac.`df_num_factura`) AS COUNT_FACTURA, fac.`df_personal_cod_fac`,
                     UPPER(per.`df_nombre_per`) AS df_nombre_per, UPPER(per.`df_apellido_per`) AS df_apellido_per, 
                     per.`df_cargo_per`, SUM(fac.`df_subtotal_fac`) AS VALOR_VENDIDO,
                    (SELECT SUM(fa.`df_subtotal_fac`) FROM `df_factura` as fa 
                    WHERE ((date(fa.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fa.df_fecha_fac) <='".$this->df_fecha_fin."')  
                    or (date(fa.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' 
                    and date(fa.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."')) 
                    and  fa.df_personal_cod_fac = fac.df_personal_cod_fac AND fa.`df_sector_cod_fac` = fac.`df_sector_cod_fac` 
                    AND  ((fa.`df_edo_factura_fac` in (1,5) ) or (date(fa.`df_fecha_entrega_fac`)  > '".$this->df_fecha_fin."' 
                    and (fa.`df_edo_factura_fac` IN (1,2,4,5) ))) 
                    GROUP BY fa.`df_personal_cod_fac`, fa.`df_sector_cod_fac`) AS VALOR_ANULADO,
                    sec.`df_nombre_sector`
                FROM `df_factura` as fac
                INNER JOIN `df_personal` AS per on (fac.`df_personal_cod_fac` = per.`df_id_personal`  
                    AND per.`df_cargo_per` LIKE '%Vendedor%')
                INNER JOIN `df_sector` sec ON (sec.`df_codigo_sector` = fac.`df_sector_cod_fac`)
                WHERE (date(fac.df_fecha_fac) >='".$this->df_fecha_ini."' and date(fac.df_fecha_fac) <='".$this->df_fecha_fin."') 
                    or (date(fac.`df_fecha_entrega_fac`) >='".$this->df_fecha_ini."' and 
                    date(fac.`df_fecha_entrega_fac`)  <='".$this->df_fecha_fin."') 
                GROUP BY per.`df_nombre_per`, fac.`df_sector_cod_fac`
                ORDER BY per.`df_nombre_per` ASC ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }*/

}
?>