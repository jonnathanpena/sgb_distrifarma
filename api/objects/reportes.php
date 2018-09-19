<?php
class LibroDiario {

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

    //constructor con base de datos como conexión
    public function __construct($db){
        $this->conn = $db;
    }

    //reportes por fecha de libro diario
    function readByLibroDiario(){
    
        // select all query
        $query = "SELECT `df_id_libro_diario`, `df_valor_inicial_ld`, `df_fecha_ld`, `df_descipcion_ld`, 
                    `df_ingreso_ld`, `df_egreso_ld` FROM `df_libro_diario` 
                    WHERE `df_fecha_ld` >='".$this->df_fecha_ini."' and df_fecha_ld <= '".$this->df_fecha_fin."'
                    ORDER BY df_fecha_ld asc";
      // prepare query statement
      $stmt = $this->conn->prepare($query);
    
      // execute query
      $stmt->execute();
  
      return $stmt;
  }

  //reportes por fecha totales libro diario
  function readByTotalLDiario(){
    
    // select all query
    $query = "SELECT sum(`df_ingreso_ld`) as total_ingreso, sum(`df_egreso_ld`) as total_egreso, 
                (sum(`df_ingreso_ld`)-sum(`df_egreso_ld`)) as existencia
            FROM `df_libro_diario` 
            WHERE `df_fecha_ld` >='".$this->df_fecha_ini."' and df_fecha_ld <= '".$this->df_fecha_fin."'
            ORDER BY df_fecha_ld asc";
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
                INNER JOIN `df_personal` AS per on (fac.`df_personal_cod_fac` = per.`df_id_personal` 
                         AND per.`df_cargo_per` LIKE '%Vendedor%')
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
    }

}
?>