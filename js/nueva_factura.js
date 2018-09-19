var timer;
var productos = [];
var current;
var datetime;
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
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '" selected>' + usuario.df_usuario_usuario + '</option>');
    $('#personal').empty();
    consultarPersonal();
    consultarSectores();
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

function consultarSectores() {
    var urlCompleta = url + 'sector/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#sector').append('<option value="' + row.df_codigo_sector + '">' + row.df_nombre_sector + '</option>');
        });
    });
}

function consultarCliente() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        $('#consultarClientes').modal('show');
        var urlCompleta = url + 'cliente/getAll.php';
        $('#resultados .table-responsive table tbody').empty();
        $.post(urlCompleta, JSON.stringify({ df_nombre_cli: $('#nombreDocumento').val() }), function(response) {
            var tr;
            if (response.data.length > 0) {
                $.each(response.data, function(index, row) {
                    tr = $('<tr style="cursor: pointer;" onclick="seleccionarCliente(' + row.df_id_cliente + ', ' + row.df_documento_cli + ', `' + row.df_nombre_cli + '` )"/>');
                    tr.append("<td>" + row.df_codigo_cliente + "</td>");
                    tr.append("<td>" + row.df_tipo_documento_cli + "</td>");
                    tr.append("<td>" + row.df_documento_cli + "</td>");
                    tr.append("<td>" + row.df_nombre_cli + "</td>");
                    tr.append("<td>" + row.df_razon_social_cli + "</td>");
                    $('#resultados .table-responsive table tbody').append(tr);
                });
            } else {
                $('#resultados .table-responsive table tbody').html('No existen usuarios registrados');
            }
        });
    }, 1000);
}

function seleccionarCliente(id_cliente, documento, nombre) {
    $('#consultarClientes').modal('hide');
    $('#documento_cliente').val(documento);
    $('#cliente_id').val(id_cliente);
    $('#nombre_cliente').val(nombre);
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
    $.post('./ajax/buscar_productos.php', { data: prod }, function(response) {
        $('#display_productos').html(response);
    });
}

function getCliente() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        var urlCompleta = url + 'cliente/getAll.php';
        $('#resultados .table-responsive table tbody').empty();
        $.post(urlCompleta, JSON.stringify({ df_nombre_cli: $('#nombreDocumento').val() }), function(response) {
            var tr;
            if (response.data.length > 0) {
                $.each(response.data, function(index, row) {
                    tr = $('<tr style="cursor: pointer;" onclick="seleccionarCliente(' + row.df_id_cliente + ', ' + row.df_documento_cli + ', `' + row.df_nombre_cli + '` )"/>');
                    tr.append("<td>" + row.df_codigo_cliente + "</td>");
                    tr.append("<td>" + row.df_tipo_documento_cli + "</td>");
                    tr.append("<td>" + row.df_documento_cli + "</td>");
                    tr.append("<td>" + row.df_nombre_cli + "</td>");
                    tr.append("<td>" + row.df_razon_social_cli + "</td>");
                    $('#resultados .table-responsive table tbody').append(tr);
                });
            } else {
                $('#resultados .table-responsive table tbody').html('No existen usuarios registrados');
            }
        });
    }, 1000);
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
}

$(document).on("click", "table#table_productos tbody td a.delete", function() {
    $(this).parents("tr").remove();
    calcular();
});

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

$('#form_nueva_factura').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var factura = {
        df_fecha_fac: datetime,
        df_cliente_cod_fac: $('#cliente_id').val(),
        df_personal_cod_fac: $('#personal').val(),
        df_sector_cod_fac: $('#sector').val(),
        df_forma_pago_fac: $('#forma_pago').val(),
        df_subtotal_fac: subtotal,
        df_iva_fac: total_iva,
        df_descuento_fac: descuento,
        df_valor_total_fac: total.toFixed(2),
        df_creadaBy: $('#usuario').val(),
        df_fecha_creacion: datetime,
        df_edo_factura_fac: 1,
        df_fecha_entrega_fac: $('#fecha_entrega').val()
    };
    validarInsercion(factura);
});

function validarInsercion(factura) {
    var seguir = true;
    if (factura.df_cliente_cod_fac == undefined) {
        alertar('warning', '¡Alerta!', 'Debe escoger un cliente');
        seguir = false;
    }
    if (factura.df_iva_fac == 0) {
        alertar('warning', '¡Alerta!', 'Los valores no pueden estar en cero');
        seguir = false;
    }
    if (factura.df_valor_total_fac == 0) {
        alertar('warning', '¡Alerta!', 'Los valores no pueden estar en cero');
        seguir = false;
    }
    if (factura.df_personal_cod_fac == 'null') {
        alertar('warning', '¡Alerta!', 'Debe escoger un personal');
        seguir = false;
    }
    if (factura.df_sector_cod_fac == 'null') {
        alertar('warning', '¡Alerta!', 'Debe escoger un sector');
        seguir = false;
    }
    if (seguir == true) {
        insertarFactura(factura);
    }
}

function insertarFactura(factura) {
    var urlCompleta = url + 'factura/insert.php';
    console.log('factura', factura);
    $.post(urlCompleta, JSON.stringify(factura), function(response) {
        var id_factura = response;
        var inserto = true;
        $('table#table_productos tbody tr').each(function(a, b) {
            var id_precio = $('.id_precio', b).text();
            var precio = $('.precio_unitario', b).text() * 1;
            var cantidad = $('.cantidad', b).text() * 1;
            var valor_sin_iva = $('.subtotal', b).text() * 1;
            var iva = $('.iva', b).text() * 1;
            var total_tupla = $('.total_tupla_producto', b).text() * 1;
            var nombre_unidad = $('.unidad', b).text();
            var unidad_caja = $('.unidad_caja', b).text();
            var cant_x_und = 0;
            if (nombre_unidad == 'UND') {
                cant_x_und = cantidad;
            } else {
                cant_x_und = unidad_caja * cantidad;
            }
            insertDetalle(id_factura, id_precio, precio, cantidad, valor_sin_iva, iva, total_tupla, nombre_unidad, cant_x_und);
        });
        insertarHistorialFactura(id_factura);
        alertar('success', '¡Éxito!', 'Factura # ' + id_factura + 'generada exitosamente');
        limpiar();
    });
}

function insertDetalle(id, id_precio, precio, cantidad, valor_sin_iva, iva, total, nombre_unidad, cant_x_und) {
    var urlCompleta = url + 'detalleFactura/insert.php';
    var detalle = {
        df_num_factura_detfac: id,
        df_prod_precio_detfac: id_precio,
        df_precio_prod_detfac: precio,
        df_cantidad_detfac: cantidad,
        df_valor_sin_iva_detfac: valor_sin_iva,
        df_iva_detfac: iva,
        df_valor_total_detfac: total,
        df_nombre_und_detfac: nombre_unidad,
        df_cant_x_und_detfac: cant_x_und,
        df_edo_entrega_prod_detfac: 1
    }
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {});
}

function insertarHistorialFactura(facturaId) {
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var historial = {
        df_num_factura: facturaId,
        df_edo_factura: 1,
        df_edo_impresion: 1,
        df_usuario_id: $('#usuario').val(),
        df_fecha_proceso: datetime
    };
    var urlCompleta = url + 'historiaEstadoFactura/insert.php';
    $.post(urlCompleta, JSON.stringify(historial), function(response) {})
}

function limpiar() {
    $('#personal').val('null');
    $('#documento_cliente').val('');
    $('#cliente_id').val(undefined);
    $('#nombre_cliente').val('');
    $('#sector').val('null');
    $('#forma_pago').val('EFECTIVO');
    $('#table_productos tbody').empty();
    $('#subtotal').html('$0.00');
    $('#descuento').html('$0.00');
    $('#total_iva').html('$0.00');
    $('#total').html('$0.00');
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