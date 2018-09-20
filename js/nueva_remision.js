var productos = [];
var timer;
var saldo = 0;
var egresos = [];
var ingresos = [];
var subtotal = 0;
var total_iva = 0;
var descuento = 0;
var total = 0;
$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    if (usuario.ingreso == true) {
        if (usuario.df_tipo_usuario == 'Administrador') {
            $('#administrador').show('');
            $('#ventas').hide('');
        } else {
            $('#administrador').hide('');
            $('#ventas').show('');
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
    $('#cantidad').val('');
    $('#valor').val('');
    $('#sector').val('null');
    consultarPersonal();
    getSectores();
    getCajaChica();
}

function consultarPersonal() {
    var urlCompleta = url + 'personal/getAll.php';
    $('#personal').append('<option value="null">Seleccione...</option>')
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                if (row.df_cargo_per == 'Repartidor') {
                    $('#personal').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
                }
            })
        }
    });
}

function getCajaChica() {
    ingresos = [];
    egresos = [];
    saldo = 0;
    var urlCompleta = url + 'cajaChicaIngreso/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            ingresos = response.data;
        }
        getEgresos();
    });
}

function getEgresos() {
    var urlCompleta = url + 'cajaChicaGasto/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            egresos = response.data;
            if (ingresos.length > 0) {
                var fecha_ingreso = new Date(ingresos[0].df_fecha_ingreso);
                var fecha_egreso = new Date(egresos[0].df_fecha_gasto);
                if (fecha_ingreso > fecha_egreso) {
                    saldo = ingresos[0].df_saldo_cc * 1;
                } else {
                    saldo = egresos[0].df_saldo * 1;
                }
            } else {
                saldo = egresos[0].df_saldo;
            }
        } else {
            if (ingresos.length > 0) {
                saldo = ingresos[0].df_saldo_cc * 1;
            }
        }
        $('#valor').prop('max', saldo);
    });
}

function getSectores() {
    var urlCompleta = url + 'sector/getAll.php';
    $('#sector').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                $('#sector').append('<option value="' + row.df_codigo_sector + '">' + row.df_nombre_sector + '</option>');
            });
        } else {
            alertar('danger', '¡Error!', 'Por favor verifique que tiene conexión a internet, e intente nuevamente');
        }
    });
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
    var guia = {
        df_codigo_rem: '',
        df_fecha_remision: datetime,
        df_sector_cod_rem: $('#sector').val(),
        df_vendedor_rem: $('#personal').val(),
        df_cant_total_producto_rem: $('#cantidad').val(),
        df_valor_efectivo_rem: $('#valor').val(),
        df_creadoBy_rem: $('#usuario').val()
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
    if ($('#cantidad').val() == 0) {
        guardar = false;
    }
    if (guardar == false) {
        alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
    } else {
        getMaxId(guia);
    }
}

function getMaxId(guia) {
    var urlCompleta = url + 'guiaRemision/getIdMax.php';
    var codigo = '';
    $.get(urlCompleta, function(response) {
        $maximo = response.data[0].df_guia_remision * 1;
        $maximo = $maximo + 1;
        if (response.data[0].df_guia_remision == null) {
            codigo = 'GREM-001';
        } else if ($maximo > 0 && $maximo < 10) {
            codigo = 'GREM-00' + $maximo;
        } else if ($maximo > 9 && $maximo < 100) {
            codigo = 'GREM-0' + $maximo;
        } else if ($maximo > 99) {
            codigo = 'GREM-' + $maximo;
        }
        guia.df_codigo_rem = codigo;
        insertGuia(guia);
    });
}

function insertGuia(guia) {
    var urlCompleta = url + 'guiaRemision/insert.php';
    $.post(urlCompleta, JSON.stringify(guia), function(response) {
        if (response == false) {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
        } else {
            generarDetalle(response);
            guardarGasto(guia);
        }
        $('#nuevaRemision').modal('hide');
    });
}

function guardarGasto(guia) {
    var urlCompleta = url + 'cajaChicaGasto/insert.php';
    var value = $('#valor').val() * 1;
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var gasto = {
        df_usuario_id: $('#usuario').val(),
        df_movimiento: 'Guía Remision #' + guia.df_codigo_rem,
        df_gasto: value,
        df_saldo: saldo - value,
        df_fecha_gasto: datetime,
        df_num_documento: 'Guía Remision #' + guia.df_codigo_rem,
        df_ingreso_id: ingresos[0].df_id_ingreso_cc
    };
    insertGasto(gasto);
}

function insertGasto(gasto) {
    var urlCompleta = url + 'cajaChicaGasto/insert.php';
    $.post(urlCompleta, JSON.stringify(gasto), function(response) {
        console.log('response insert gasto', response);
    });
}

function buscarProductos() {
    productos = [];
    $('#consultarProductos').modal('show');
    var urlCompleta = url + 'producto/getAll.php';
    $('#resultados .table-responsive table tbody').empty();
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                consultarDetalleProducto(row);
            });
            clearTimeout(timer);
            timer = setTimeout(function() {
                llenarTablaProductos(productos);
            }, 1000);
        } else {
            $('#resultados .table-responsive table tbody').html('No existen usuarios registrados');
        }
    });
}

function getAllProductos() {
    productos = [];
    $('#resultados .table-responsive table tbody').empty();
    clearTimeout(timer);
    timer = setTimeout(function() {
        var q = $('#nombreCodigo').val();
        var urlCompleta = url + 'producto/getAll.php';
        $.post(urlCompleta, JSON.stringify({ df_nombre_producto: q }), function(response) {
            $('#resultados .table-responsive table tbody').empty();
            console.log('respuesta buscar producto', response);
            if (response.data.length > 0) {
                $.each(response.data, function(index, row) {
                    consultarDetalleProducto(row);
                });
                clearTimeout(timer);
                timer = setTimeout(function() {
                    llenarTablaProductos(productos);
                }, 1000);
            } else {
                $('#resultados .table-responsive table tbody').html('No existen usuarios registrados');
            }
        })
    }, 1000);
}

function consultarDetalleProducto(producto) {
    var urlCompleta = url + 'productoPrecio/getByProducto.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_producto_id: producto.df_id_producto }), function(data) {
        urlCompleta = url + 'productoPrecio/getById.php';
        $.post(urlCompleta, JSON.stringify({ df_id_precio: data.data[0].df_id_precio }), function(response) {
            producto.df_id_producto = producto.df_id_producto * 1;
            producto.df_id_precio = response.data[0].df_id_precio;
            producto.df_ppp = response.data[0].df_ppp;
            producto.df_pvt1 = response.data[0].df_pvt1;
            producto.df_pvt2 = response.data[0].df_pvt2;
            producto.df_pvp = response.data[0].df_pvp;
            producto.df_iva = response.data[0].df_iva;
            producto.df_min_sugerido = response.data[0].df_min_sugerido;
            producto.df_und_caja = response.data[0].df_und_caja;
            producto.df_utilidad = response.data[0].df_utilidad;
            producto.df_valor_impuesto = response.data[0].df_valor_impuesto;
            productos.push(producto);
        });
    });
}

function llenarTablaProductos(prod) {
    console.log('productos', prod);
    $('#consultarProductos').modal('show');
    $.post('./ajax/buscar_productos.php', { data: prod }, function(response) {
        $('#display_productos').html(response);
    });
}

var acciones = '<a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
var precio = 10;

function agregar(codigo, producto, id_producto, id_precio, iva) {
    var cantidad = $('#cantidad_' + codigo).val();
    var precio = $('#costo_' + codigo).val();
    var unidad = $('#unidad_' + codigo).val();
    var unidad_caja = $('#und_caja' + codigo).val();
    if (precio == 'null' || cantidad < 1) {
        alert('Debe escoger valores reales');
    } else {
        var subtotal_tabla = cantidad * precio;
        var total_iva_tabla = subtotal_tabla * iva;
        var total_tupla = subtotal_tabla + total_iva_tabla;
        total_tupla = total_tupla.toFixed(2);
        var row = '<tr>' +
            '<td class="id_producto" style="display: none;">' + id_producto + '</td>' +
            '<td class="id_precio" style="display: none;">' + id_precio + '</td>' +
            '<td class="iva" style="display: none;">' + iva + '</td>' +
            '<td class="subtotal" style="display: none;">' + subtotal_tabla + '</td>' +
            '<td class="total_iva" style="display: none;">' + total_iva_tabla + '</td>' +
            '<td class="unidad_caja" style="display: none;">' + unidad_caja + '</td>' +
            '<td width="100" class="codigo">' + codigo + '</td>' +
            '<td class="producto">' + producto + '</td>' +
            '<td width="100" class="unidad">' + unidad + '</td>' +
            '<td width="100" class="cantidad">' + cantidad + '</td>' +
            '<td width="100" class="precio_unitario">' + precio + '</td>' +
            '<td width="100" class="total_tupla_producto">' + total_tupla + '</td>' +
            '<td width="100">' + acciones + '</td>' +
            '</tr>';
        $('#table_productos tbody').append(row);
        $('[data-toggle="tooltip"]').tooltip();
    }
    calcular();
    calcularCantidad();
}

function calcular() {
    subtotal = 0;
    total_iva = 0;
    total = 0;
    $('table#table_productos tbody tr').each(function(a, b) {
        subtotal += $('.subtotal', b).text() * 1;
        total_iva += $('.total_iva', b).text() * 1;
        total += $('.total_tupla_producto', b).text() * 1;
    });
    $('#subtotal').html(subtotal.toFixed(2));
    $('#total_iva').html(total_iva.toFixed(2));
    $('#descuento').html(descuento.toFixed(2))
    $('#total').html(total.toFixed(2));
}

$(document).on("click", "table#table_productos tbody td a.delete", function() {
    $(this).parents("tr").remove();
    calcularCantidad()
});

function calcularCantidad() {
    var cantidad = 0;
    $('table#table_productos tbody tr').each(function(a, b) {
        cantidad += $('.cantidad', b).text() * 1;
    });
    $('#cantidad').val(cantidad);
}

function seleccionaUnidad(codigo) {
    var und_caja = $('#und_caja' + codigo).val();
    var precio_normal = $('#precio_normal' + codigo).val();
    var precio_descuento = $('#precio_descuento' + codigo).val();
    var normal = 0;
    var descuento = 0;
    if ($('#unidad_' + codigo).val() == 'null') {
        alert('Debe escoger una aunidad válida');
    } else if ($('#unidad_' + codigo).val() == 'CAJA') {
        normal = precio_normal;
        descuento = precio_descuento;
    } else if ($('#unidad_' + codigo).val() == 'UND') {
        normal = precio_normal / und_caja;
        normal = normal.toFixed(2);
        descuento = precio_descuento / und_caja;
        descuento = descuento.toFixed(2);
    }
    $('#costo_' + codigo).empty();
    $('#costo_' + codigo).append('<option value="null">Seleccione...</option>');
    $('#costo_' + codigo).append('<option value="' + normal + '">Normal $' + normal + '</option>');
    $('#costo_' + codigo).append('<option value="' + descuento + '">Descuento $' + descuento + '</option>');
}

function generarDetalle(id) {
    var id_remision = id;
    var insercion = true;
    $('table#table_productos tbody tr').each(function(a, b) {
        var cantidad = $('.cantidad', b).text() * 1;
        var unidad_caja = $('.unidad_caja', b).text();
        var cant_x_und = 0;
        var nombre_unidad = $('.unidad', b).text();
        if (nombre_unidad == 'UND') {
            cant_x_und = cantidad;
        } else {
            cant_x_und = unidad_caja * cantidad;
        }
        var detalle = {
            df_guia_remision_detrem: id_remision,
            df_producto_precio_detrem: $('.id_precio', b).text(),
            df_cant_producto_detrem: cantidad,
            df_nombre_und_detrem: $('.unidad', b).text(),
            df_cant_x_und_detrem: cant_x_und,
            df_valor_sin_iva_detrem: $('.subtotal', b).text() * 1,
            df_iva_detrem: $('.iva', b).text() * 1,
            df_valor_total_detrem: $('.total_tupla_producto', b).text() * 1
        };
        var ejec = insertarDetalle(detalle);
        if (ejec == false) {
            insercion = false;
        }
    });
    clearTimeout(timer);
    timer = setTimeout(function() {
        if (insercion == false) {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
        } else {
            alertar('success', '¡Éxito!', 'Guía registrada exitosamente');
            limpiar();
        }
    }, 1000);
}

function insertarDetalle(detalle) {
    var urlCompleta = url + 'detalleRemision/insert.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        return response;
    });
}

function limpiar() {
    $('#personal').val('null');
    $('#sector').val('null');
    $('#cantidad').val('0');
    $('#valor').val('0.00');
    $('#table_productos tbody').empty();
}