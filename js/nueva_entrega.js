var productos = [];
var timer;
var fecha_entrega = '';
var facturas = [];
var seleccionadas = [];
$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    if (usuario.ingreso == true) {
        $('#pasaporte').hide();
        if (usuario.df_tipo_usuario == 'Administrador') {
            $('#Administrador').show('');
            $('#Supervisor').hide('');
            $('#Ventas').hide('');
        } else if (usuario.df_tipo_usuario == 'Supervisor') {
            $('#Administrador').hide('');
            $('#Supervisor').show('');
            $('#Ventas').hide('');
        } else if (usuario.df_tipo_usuario == 'Ventas') {
            $('#Administrador').hide('');
            $('#Supervisor').hide('');
            $('#Ventas').show('');
        }
    } else {
        window.location.href = "login.php";
    }
    load();
});

function load() {
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '" selected>' + usuario.df_usuario_usuario + '</option>');
    $('#personal').empty();
    consultarPersonal();
    facturas = [];
    seleccionadas = [];
}

function consultarPersonal() {
    var urlCompleta = url + 'personal/getAll.php';
    $('#personal').append('<option value="null">Seleccione...</option>')
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                $('#personal').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
            })
        }
    });
}

function cambioFechaEntrega() {
    consultarSectores();
}

function consultarSectores() {
    $('#table_sectores tbody').empty();
    $('#table_facturas tbody').empty();
    $('#table_productos tbody').empty();
    seleccionadas = [];
    var urlCompleta = url + 'sector/getSector.php';
    fecha_entrega = $('#fecha_entrega').val();
    $.post(urlCompleta, JSON.stringify({ fecha: fecha_entrega }), function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                var tr = '<tr onclick="selectSector(`' + row.df_codigo_sector + '`)" style="cursor: pointer;">' +
                    '<td width="20">' +
                    '<input type="checkbox" id="check_sector_' + row.df_codigo_sector + '">' +
                    '</td>' +
                    '<td>' +
                    row.df_nombre_sector +
                    '</td>' +
                    '</tr>';
                $('#table_sectores tbody').append(tr);
            });
        } else {
            alertar('warning', '¡Advertencia!', 'No existen registros para la fecha de entrega escogida');
        }
    });
}

function selectSector(sector) {
    var check = '#check_sector_' + sector;
    if ($(check).prop('checked') == false) {
        $(check).prop('checked', true);
        var urlCompleta = url + 'factura/getFacturaGEnt.php';
        $.post(urlCompleta, JSON.stringify({ fecha: fecha_entrega, sector: sector }), function(response) {
            if (response.data.length > 0) {
                for (var i = 0; i < response.data.length; i++) {
                    detalleFacturas(response.data[i], sector, i);
                }
                console.log('facturas', facturas);
            }
        });
    } else {
        $(check).prop('checked', false);
        eliminarSector(sector);
    }
}

function detalleFacturas(fact, sector, posicion) {
    var urlCompleta = url + 'detalleFactura/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura_detfac: fact.df_num_factura }), function(response) {
        if (response.data.length > 0) {
            facturas.push({
                detalles: response.data,
                df_cliente_cod_fac: fact.df_cliente_cod_fac,
                df_creadaBy: fact.df_creadaBy,
                df_descuento_fac: fact.df_descuento_fac,
                df_edo_factura_fac: fact.df_edo_factura_fac,
                df_fecha_creacion: fact.df_fecha_creacion,
                df_fecha_entrega_fac: fact.df_fecha_entrega_fac,
                df_fecha_fac: fact.df_fecha_fac,
                df_forma_pago_fac: fact.df_forma_pago_fac,
                df_iva_fac: fact.df_iva_fac,
                df_num_factura: fact.df_num_factura,
                df_personal_cod_fac: fact.df_personal_cod_fac,
                df_sector_cod_fac: fact.df_sector_cod_fac,
                df_subtotal_fac: fact.df_subtotal_fac,
                df_valor_total_fac: fact.df_valor_total_fac,
                sector: sector
            });
        } else {
            facturas.splice(facturas[posicion], 1);
        }
        llenarTablaFacturas();
        console.log('facturas', facturas);
    });
}

function eliminarSector(sector) {
    var iteracion = facturas.length - 1;
    for (var i = iteracion; i >= 0; i--) {
        if (facturas[i].sector * 1 == sector) {
            facturas.splice(i, 1);
        }
    }
    seleccionadas.length = 0;
    $('#table_productos tbody').empty();
    llenarTablaFacturas();
}

function llenarTablaFacturas() {
    $('#table_facturas tbody').empty();
    for (var i = 0; i < facturas.length; i++) {
        var tr = '<tr style="cursor: pointer;" onclick="seleccionarFactura(`' + facturas[i].df_num_factura + '`)">' +
            '<td width="40"><input type="checkbox" id="check_factura_' + facturas[i].df_num_factura + '"></td>' +
            '<td>' + facturas[i].df_num_factura + '</td>' +
            '<td width="80">' + facturas[i].df_subtotal_fac + '</td>' +
            '<td width="60">' + facturas[i].df_iva_fac + '</td>' +
            '<td width="60">' + facturas[i].df_valor_total_fac + '</td>' +
            '</tr>';
        $('#table_facturas tbody').append(tr);
    }
}

function seleccionarFactura(numFactura) {
    if ($('#check_factura_' + numFactura).prop('checked') == true) {
        $('#check_factura_' + numFactura).prop('checked', false);
        var contador = 0;
        var seguir = true;
        $.each(seleccionadas, function(index, row) {
            if (seguir) {
                if (row.df_num_factura == numFactura) {
                    seguir = false;
                    seleccionadas.splice(contador, 1);
                }
            }
            contador++;
        });
        $('#table_productos tbody').html('<p><img src="./img/ajax-loader.gif"> Cargando...</p>');
        clearTimeout(timer);
        timer = setTimeout(function() {
            poblarDetalles();
        }, 1000);
    } else if ($('#check_factura_' + numFactura).prop('checked') == false) {
        $('#check_factura_' + numFactura).prop('checked', true);
        $.each(facturas, function(index, row) {
            console.log('else row.df_num_factura: ', row.df_num_factura);
            console.log('else numFactura: ', numFactura);
            if (row.df_num_factura == numFactura) {
                seleccionadas.push(row);
            }
        });
        $('#table_productos tbody').html('<p><img src="./img/ajax-loader.gif"> Cargando...</p>');
        clearTimeout(timer);
        timer = setTimeout(function() {
            poblarDetalles();
        }, 1000);
    }
    $('#facturas').val(seleccionadas.length);
    console.log('seleccionadas', seleccionadas);
}

function poblarDetalles() {
    var temp = [];
    $('#table_productos tbody').empty();
    $.each(seleccionadas, function(index, row) {
        for (var i = 0; i < row.detalles.length; i++) {
            var cantidad = row.detalles[i].df_cantidad_detfac * 1;
            for (var j = 0; j < temp.length; j++) {
                if (row.detalles[i].df_codigo_prod == temp[j].codigo && row.detalles[i].df_nombre_und_detfac == temp[j].unidad) {
                    var nueva = temp[j].cantidad * 1;
                    cantidad = cantidad + nueva;
                    temp.splice(j, 1);
                }
            }
            temp.push({
                producto_id: row.detalles[i].df_id_producto,
                codigo: row.detalles[i].df_codigo_prod,
                producto: row.detalles[i].df_nombre_producto,
                cantidad: cantidad,
                factura: row.detalles[i].df_id_factura_detfac,
                sector: row.sector,
                numFactura: row.df_num_factura,
                unidad: row.detalles[i].df_nombre_und_detfac
            });
        }
        clearTimeout(timer);
        timer = setTimeout(function() {
            generateTablaDetalles(temp);
        }, 1000);
    });
}

function generateTablaDetalles(temp) {
    console.log('temp', temp);
    var totalProductos = 0;
    var totalCajas = 0;
    var posicion = 0;
    $('#table_productos tbody').empty();
    var j = 0;
    $.each(temp, function(index, row) {
        posicion++;
        if (row.unidad == 'CAJA') {
            totalCajas += row.cantidad * 1;
        } else {
            totalProductos += row.cantidad * 1;
        }
        var tr = $('<tr/>');
        tr.append("<td class='producto_id' style='display:none;'>" + row.producto_id + "</td>");
        tr.append("<td class='codigo'>" + row.codigo + "</td>");
        tr.append("<td class='producto'>" + row.producto + "</td>");
        tr.append("<td class='unidad'>" + row.unidad + "</td>");
        tr.append("<td class='cantidad'>" + row.cantidad + "</td>");
        tr.append("<td class='factura' style='display:none;'>" + row.factura + "</td>");
        tr.append("<td class='sector' style='display:none;'>" + row.sector + "</td>");
        tr.append("<td class='numFactura' style='display:none;'>" + row.numFactura + "</td>");
        $('#table_productos tbody').append(tr);
        $('#cantidad').val(totalProductos);
        $('#cantidad_cajas').val(totalCajas);
        j++;
    });
    if (temp.length == 0) {
        $('#cantidad').val('0');
        $('#cantidad_cajas').val('0');
    }
}

$('#form_nueva_guia').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    on();
    var guia = {
        df_codigo_guia_ent: '',
        df_sector_ent: $('#sector').val(),
        df_repartidor_ent: $('#personal').val(),
        df_cant_total_producto_ent: $('#cantidad').val(),
        df_cant_total_cajas_ent: $('#cantidad_cajas').val(),
        df_cant_facturas_ent: $('#facturas').val(),
        df_fecha_ent: datetime,
        df_creadoBy_ent: $('#usuario').val()
    };
    validarInsert(guia);
});

function validarInsert(guia) {
    var guardar = true;
    if (guia.df_sector_cod_rem == 'null') {
        guardar = false;
    }
    if (guia.df_vendedor_rem == 'null') {
        guardar = false;
    }
    if ($('#cantidad').val() == 0 && $('#cantidad_cajas').val() == 0) {
        guardar = false;
    }
    if ($('#facturas').val() < 1) {
        guardar = false;
    }
    if (guardar == false) {
        alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
        off();
    } else {
        getMaxId(guia);
    }
}

function getMaxId(guia) {
    var urlCompleta = url + 'guiaEntrega/getIdMax.php';
    var codigo = '';
    $.get(urlCompleta, function(response) {
        $maximo = response.data[0].df_num_guia_entrega * 1;
        $maximo = $maximo + 1;
        if (response.data[0].df_num_guia_entrega == null) {
            codigo = 'GENT-001';
        } else if ($maximo > 0 && $maximo < 10) {
            codigo = 'GENT-00' + $maximo;
        } else if ($maximo > 9 && $maximo < 100) {
            codigo = 'GENT-0' + $maximo;
        } else if ($maximo > 99) {
            codigo = 'GENT-' + $maximo;
        }
        guia.df_codigo_guia_ent = codigo;
        insertGuia(guia);
    });
}

function insertGuia(guia) {
    console.log('guia entrega', guia);
    var urlCompleta = url + 'guiaEntrega/insert.php';
    $.post(urlCompleta, JSON.stringify(guia), function(response) {
        if (response == false) {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
            off();
        } else {
            generarDetalle(response);
        }
    });
}

function generarDetalle(id) {
    var id_entrega = id;
    var insercion = true;
    $.each(seleccionadas, function(index, row) {
        $.each(row.detalles, function(index, r) {
            var detalle = {
                df_guia_entrega: id_entrega,
                df_cod_producto: r.df_id_producto,
                df_unidad_detent: r.df_nombre_und_detfac,
                df_cant_producto_detent: r.df_cantidad_detfac,
                df_factura_detent: r.df_id_factura_detfac,
                df_sector_id_detent: row.sector,
                df_nom_producto_detent: r.df_nombre_producto,
                df_num_factura_detent: row.df_num_factura
            };
            var ejec = insertarDetalle(detalle);
            if (ejec == false) {
                insercion = false;
            }
        });
    });
    clearTimeout(timer);
    timer = setTimeout(function() {
        if (insercion == false) {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
        } else {
            alertar('success', '¡Éxito!', 'Guía registrada exitosamente');
        }
        off();
        window.location.href = 'guia_entrega.php';
    }, 2000);
}

function insertarDetalle(detalle) {
    var urlCompleta = url + 'detalleEntrega/insert.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        return response;
    });
}

function limpiar() {
    $('#personal').val('null');
    $('#sector').val('null');
    $('#cantidad').val('0');
    $('#factura').val('1');
    $('#table_productos tbody').empty();
}

$('#personal').change(function() {
    $('#cantidad').val('0');
    $('#facturas').val('0');
    $('#table_sectores tbody').empty();
    $('#table_facturas tbody').empty();
    facturas = [];
    seleccionadas = [];
});