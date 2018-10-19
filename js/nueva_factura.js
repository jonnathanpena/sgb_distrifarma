var timer;
var productos = [];
var current;
var datetime;
var subtotal = 0;
var total_iva = 0;
var descuento = 0;
var total = 0;

var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0,
    producto = {};
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
    $('#direccion_cliente').attr('readonly', 'readonly');
    $('#sector').attr('disabled', 'disabled');
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 0);
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
    $('#personal').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                $('#personal').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
            });
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
                    tr = $('<tr style="cursor: pointer;" onclick="seleccionarCliente(' + row.df_id_cliente + ', ' + row.df_documento_cli + ', ' + row.df_sector_cod + ', `' + row.df_nombre_cli + '`, `' + row.df_direccion_cli + '` )"/>');

                    tr.append("<td>" + row.df_codigo_cliente + "</td>");

                    tr.append("<td>" + row.df_tipo_documento_cli + "</td>");

                    tr.append("<td>" + row.df_documento_cli + "</td>");

                    tr.append("<td>" + row.df_nombre_cli + "</td>");

                    tr.append("<td>" + row.df_direccion_cli + "</td>");

                    $('#resultados .table-responsive table tbody').append(tr);

                });

            } else {

                $('#resultados .table-responsive table tbody').html('No existen usuarios registrados');

            }

        });

    }, 0);

}



function seleccionarCliente(id_cliente, documento, sector, nombre, direccion) {

    $('#consultarClientes').modal('hide');

    $('#documento_cliente').val(documento);

    $('#cliente_id').val(id_cliente);

    $('#nombre_cliente').val(nombre);

    $('#sector').val(sector);

    $('#direccion_cliente').val(direccion);



    if (documento == '9999999999') {

        $('#direccion_cliente').attr('readonly', false);

        $('#sector').attr('disabled', false);

    } else {

        $('#direccion_cliente').attr('readonly', 'readonly');

        $('#sector').attr('disabled', 'disabled');

    }

}



function buscarProductos() {

    productos = [];

    $('#consultarProductos').modal('show');

    var urlCompleta = url + 'producto/getAll.php';

    $('#tabla_productos tbody').empty();

    $.post(urlCompleta, JSON.stringify({ df_nombre_producto: $('#nombreCodigo').val() }), function(response) {

        if (response.data.length > 0) {

            $.each(response.data, function(index, row) {

                consultarDetalleProducto(row);

            });

            clearTimeout(timer);

            timer = setTimeout(function() {

                llenarTablaProductos(productos);

            }, 0);

        } else {

            $('#tabla_productos tbody').html('No existen usuarios registrados');

        }

    });

}



function getAllProductos() {

    productos = [];

    $('#tabla_productos tbody').empty();

    clearTimeout(timer);

    timer = setTimeout(function() {

        var q = $('#nombreCodigo').val();

        var urlCompleta = url + 'producto/getAll.php';

        $.post(urlCompleta, JSON.stringify({ df_nombre_producto: q }), function(response) {

            $('#tabla_productos tbody').empty();

            if (response.data.length > 0) {

                $.each(response.data, function(index, row) {

                    consultarDetalleProducto(row);

                });

                clearTimeout(timer);

                timer = setTimeout(function() {

                    llenarTablaProductos(productos);

                }, 0);

            } else {

                $('#tabla_productos tbody').html('No existen usuarios registrados');

            }

        })

    }, 200);

}



function consultarDetalleProducto(producto) {

    var urlCompleta = url + 'productoPrecio/getByProducto.php';

    var tr;

    $.post(urlCompleta, JSON.stringify({ df_producto_id: producto.df_id_producto }), function(data) {

        urlCompleta = url + 'productoPrecio/getById.php';

        $.post(urlCompleta, JSON.stringify({ df_id_precio: data.data[0].df_id_precio }), function(response) {

            console.log('detalles precio', response.data);

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

    records = prod;

    totalRecords = records.length;

    totalPages = Math.ceil(totalRecords / recPerPage);

    apply_pagination();

}



function apply_pagination() {

    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;

    endRec = (displayRecordsIndex) + recPerPage;

    displayRecords = records.slice(displayRecordsIndex, endRec);

    generate_table();

    $pagination.twbsPagination({

        totalPages: totalPages,

        visiblePages: 6,

        onPageClick: function(event, page) {

            displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;

            endRec = (displayRecordsIndex) + recPerPage;

            displayRecords = records.slice(displayRecordsIndex, endRec);

            generate_table();

        }

    });

}



function generate_table() {

    $('#tabla_productos tbody').empty();

    var tr;

    console.log('displayRecords', displayRecords);

    $.each(displayRecords, function(index, row) {

        tr = $('<tr/>');

        tr.append('<td width="80">' + row.df_codigo_prod + ' <input type="hidden" value="' + row.df_id_producto + '" id="id_producto' + row.df_codigo_prod + '"></td>');

        tr.append('<td>' + row.df_nombre_producto + ' <input type="hidden" value="' + row.df_id_precio + '" id="id_precio' + row.df_codigo_prod + '"> <input type="hidden" value="' + row.df_iva + '" id="id_iva' + row.df_codigo_prod + '"></td>');

        tr.append('<td> <input type="hidden" value="' + row.df_pvt2 + '" id="precio_descuento' + row.df_codigo_prod + '"> <input type="hidden" value="' + row.df_pvt1 + '" id="precio_normal' + row.df_codigo_prod + '"> <input type="hidden" value="' + row.df_und_caja + '" id="und_caja' + row.df_codigo_prod + '">  <select class="form-control" id="unidad_' + row.df_codigo_prod + '" onchange="seleccionaUnidad(`' + row.df_codigo_prod + '`)"><option value="null">Seleccione...</option><option value="CAJA">Caja</option><option value="UND">Unidad</option></select></td>');

        tr.append('<td width="80"><input type="number" class="form-control" id="cantidad_' + row.df_codigo_prod + '" value="1" ></td>');

        tr.append('<td><select class="form-control" id="costo_' + row.df_codigo_prod + '"><option value="null">Seleccione...</option></select></td>');

        tr.append('<td width="10"><span class="pull-right"><button class="btn btn-info" title="Agregar" onclick="agregar(`' + row.df_codigo_prod + '`, `' + row.df_nombre_producto + '`, `' + row.df_id_producto + '`, `' + row.df_id_precio + '`, `' + row.df_iva + '`);"><i class="fa fa-plus"></i></button></td>');

        $('#tabla_productos tbody').append(tr);

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

                    tr = $('<tr style="cursor: pointer;" onclick="seleccionarCliente(' + row.df_id_cliente + ', ' + row.df_documento_cli + ', ' + row.df_sector_cod + ', `' + row.df_nombre_cli + '` )"/>');

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

    }, 0);

}



var acciones = '<a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';

var precio = 10;



/*function agregar(codigo, producto, id_producto, id_precio, iva) {

    var cantidad = $('#cantidad_' + codigo).val() * 1;

    var precio = $('#costo_' + codigo).val() * 1;

    var unidad = $('#unidad_' + codigo).val();

    var unidad_caja = $('#und_caja' + codigo).val() * 1;

    if (precio == 'null' || cantidad < 1) {

        alert('Debe escoger valores reales');

    } else {

        var subtotal_tabla = cantidad * precio;

        var total_iva_tabla = subtotal_tabla * iva;

        var total_tupla = subtotal_tabla;

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

}*/



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

        total = subtotal + total_iva; //+= $('.total_tupla_producto', b).text() * 1;

    });

    $('#subtotal').html(subtotal.toFixed(2));

    $('#total_iva').html(total_iva.toFixed(2));

    $('#descuento').html(descuento.toFixed(2))

    $('#total').html(total.toFixed(2));

}



function guardar() {
    on();
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

};



function validarInsercion(factura) {

    var seguir = true;

    if (factura.df_fecha_entrega_fac == '') {
        off();

        alertar('warning', '¡Alerta!', 'Debe escoger una fecha de entrega');

        seguir = false;

        return;

    }

    if (factura.df_cliente_cod_fac == undefined) {
        off();
        alertar('warning', '¡Alerta!', 'Debe escoger un cliente');

        seguir = false;

        return;

    }

    /*  if (factura.df_valor_total_fac == 0) {

         alertar('warning', '¡Alerta!', 'Los valores no pueden estar en cero');

         seguir = false;

         return;

     } */

    if (factura.df_personal_cod_fac == 'null') {
        off();
        alertar('warning', '¡Alerta!', 'Debe escoger un personal');

        seguir = false;

        return;

    }

    if (factura.df_sector_cod_fac == 'null') {
        off();
        alertar('warning', '¡Alerta!', 'Debe escoger un sector');

        seguir = false;

        return;

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

            var id_precio = $('.id_producto', b).text(); //cambiado id_precio por id_producto

            var precio = $('.precio_unitario', b).text() * 1;

            var cantidad = $('.cantidad', b).text() * 1;

            var valor_sin_iva = $('.subtotal', b).text() * 1;

            var iva = $('.iva', b).text() * 1;

            var total_tupla = ($('.total_tupla_producto', b).text() * 1) + iva;

            var nombre_unidad = $('.unidad', b).text();

            var unidad_caja = $('.unidad_caja', b).text();

            var cant_x_und = 0;

            var nombre_producto = $('.producto', b).text();

            var cant_bodega = $('.cant_bodega', b).text() * 1;

            if (nombre_unidad == 'UND') {

                cant_x_und = cantidad;

            } else {

                cant_x_und = unidad_caja * cantidad;

            }

            if (nombre_unidad == 'UND') {

                cant_bodega = cant_bodega - cantidad;

            } else {

                cant_bodega = cant_bodega - (unidad_caja * cantidad);

            }            

            insertDetalle(id_factura, id_precio, precio, cantidad, valor_sin_iva, iva, total_tupla, nombre_unidad, cant_x_und, nombre_producto, cant_bodega);

        });

        clearTimeout(timer);

        timer = setTimeout(function() {
            off();
            insertarHistorialFactura(id_factura);

            alertar('success', '¡Éxito!', 'Factura # ' + id_factura + 'generada exitosamente');

            limpiar();

        }, 5000);

    });

}



function insertDetalle(id, id_precio, precio, cantidad, valor_sin_iva, iva, total, nombre_unidad, cant_x_und, nombre_producto, cant_bodega) {

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

    getIdKardex(detalle, nombre_producto, cant_bodega);

    getInventario(detalle, cant_bodega);

    $.post(urlCompleta, JSON.stringify(detalle), function(response) { });

}



function getInventario(detalle, cant_bodega) {

    var urlCompleta = url + 'inventario/getByIdProd.php';

    $.post(urlCompleta, JSON.stringify({ df_producto: detalle.df_prod_precio_detfac }), function(response) {

        var inventario = response.data[0];

        inventario.df_cant_bodega = cant_bodega;

        updateInventario(inventario);

    })

}



function updateInventario(inventario) {

    var urlCompleta = url + 'inventario/update.php';

    $.post(urlCompleta, JSON.stringify(inventario), function(response) {});

}



function getIdKardex(detalle, nombre_producto, cant_bodega) {

    currentdate = new Date();

    datetime = currentdate.getFullYear() + "-" +

        (currentdate.getMonth() + 1) + "-" +

        currentdate.getDate() + " " +

        currentdate.getHours() + ":" +

        currentdate.getMinutes() + ":" +

        currentdate.getSeconds();

    var kardex = {

        df_kardex_codigo: '',

        df_fecha_kar: datetime,

        df_producto_cod_kar: detalle.df_prod_precio_detfac,

        df_producto: nombre_producto,

        df_factura_kar: detalle.df_num_factura_detfac,

        df_ingresa_kar: 0,

        df_egresa_kar: detalle.df_cantidad_detfac,

        df_existencia_kar: cant_bodega,

        df_creadoBy_kar: $('#usuario').val(),

        df_edo_kardex: 1

    }

    var urlCompleta = url + 'kardex/getIdMax.php';

    $.get(urlCompleta, function(response) {

        console.log('kardex id', response.data);

        if (response.data.length > 0) {

            var codigo_kardex = response.data[0].df_kardex_id * 1;

            if (codigo_kardex > 0) {

                codigo_kardex = codigo_kardex + 1;

                if (codigo_kardex > 0 && codigo_kardex < 10) {

                    kardex.df_kardex_codigo = 'KAR-00' + codigo_kardex;

                } else if (codigo_kardex > 9 && codigo_kardex < 100) {

                    kardex.df_kardex_codigo = 'KAR-0' + codigo_kardex;

                } else if (codigo_kardex > 99) {

                    kardex.df_kardex_codigo = 'KAR-' + codigo_kardex;

                }

            } else {

                kardex.df_kardex_codigo = 'KAR-001';

            }

        } else {

            kardex.df_kardex_codigo = 'KAR-001';

        }

        insertKardex(kardex);

    })

}



function insertKardex(kardex) {

    var urlCompleta = url + 'kardex/insert.php';

    $.post(urlCompleta, JSON.stringify(kardex), function(response) {});

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

        df_fecha_proceso: datetime,

        df_sector_factura: $('#sector').val(),

        df_direccion_factura: $('#direccion_cliente').val()

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

    $('#direccion_cliente').val('');

    $('#fecha_entrega').val('');

    $('#direccion_cliente').attr('readonly', 'readonly');

    $('#sector').attr('disabled', 'disabled');

    location.reload();

}



/*function seleccionaUnidad(codigo) {

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

    $('#costo_' + codigo).append('<option value="' + normal + '" selected>Normal $' + normal + '</option>');

    $('#costo_' + codigo).append('<option value="' + descuento + '">Descuento $' + descuento + '</option>');

}*/



var keyPress = 0;

var producto_max = 0;



$('#codigo_producto').keyup(function(e) {

    if (e.which == 13 && keyPress == 0) {

        var urlCompleta = url + 'producto/getByCodigoFactura.php';

        var codigo = $('#codigo_producto').val();

        $.post(urlCompleta, JSON.stringify({ codigo: codigo }), function(response) {

            if (response.data.length > 0) {

                if (response.data[0].df_cant_bodega > 0) {

                    keyPress++;

                    producto = response.data[0];

                    producto_max = producto.df_cant_bodega;

                    poblarConProductoConsultado(response.data[0]);

                } else {

                    alertar('warning', '¡Alerta!', 'Producto sin stock');

                    keyPress = 0;

                    producto_max = 0;

                    limpiarLineaProducto();

                }

            } else {

                alertar('warning', '¡Alerta!', 'No existe producto asignado al código digitado');

                keyPress = 0;

                producto_max = 0;

                limpiarLineaProducto();

            }

        });

    } else if (e.which == 13 && keyPress == 1) {

        keyPress++;

        $("#cantidad_producto").val('');

        $("#cantidad_producto").focus();

    } else if (e.which != 13) {

        keyPress = 0;

        producto_max = 0;

    }

});



function limpiarLineaProducto() {

    producto_max = 0;

    $('#codigo_producto').val('');

    $('#nombre_producto').val('');

    $('#unidad_producto').val('CAJA');

    $('#cantidad_producto').val('1');

    $('#precio_unitario_producto').empty();

    $('#precio_unitario_producto').append('<option value="null">Seleccione...</option>');

    $("#codigo_producto").focus();

}



function poblarConProductoConsultado(prod) {

    $('#nombre_producto').val(prod.df_nombre_producto);

    $('#cantidad_producto').val('1');

    $('#precio_unitario_producto').empty();

    $('#precio_unitario_producto').append('<option value="' + prod.df_pvt1 + '" selected>Normal $' + prod.df_pvt1 + '</option>');

    $('#precio_unitario_producto').append('<option value="' + prod.df_pvt2 + '">Descuento $' + prod.df_pvt2 + '</option>');

}



function seleccionaUnidad() {

    var und_caja = producto.df_und_caja * 1;

    var precio_normal = producto.df_pvt1 * 1;

    var precio_descuento = producto.df_pvt2 * 1;

    var normal = 0;

    var descuento = 0;

    if ($('#unidad_producto').val() == 'CAJA') {

        normal = precio_normal;

        descuento = precio_descuento;

    } else if ($('#unidad_producto').val() == 'UND') {

        normal = precio_normal / und_caja;

        normal = normal.toFixed(2);

        descuento = precio_descuento / und_caja;

        descuento = descuento.toFixed(2);

    }

    $('#precio_unitario_producto').empty();

    $('#precio_unitario_producto').append('<option value="' + normal + '" selected>Normal $' + normal + '</option>');

    $('#precio_unitario_producto').append('<option value="' + descuento + '">Descuento $' + descuento + '</option>');

}



$('#cantidad_producto').keyup(function(e) {

    if (e.which == 13) {

        agregar();

    }

});



function agregar() {

    var cantidad = $('#cantidad_producto').val() * 1;

    var unidad_caja = producto.df_und_caja * 1;

    var stock = 0;

    if ($('#unidad_producto').val() == 'CAJA') {

        stock = cantidad * unidad_caja;

    } else {

        stock = cantidad;

    }

    if (stock > producto_max) {

        alertar('danger', '¡Alerta!', 'No puede vender más de ' + producto_max + 'UND');

    } else {

        var precio = $('#precio_unitario_producto').val() * 1;

        var unidad = $('#unidad_producto').val();



        var iva = producto.df_valor_impuesto / 100;

        if (precio == 'null' || cantidad < 1) {

            alert('Debe escoger valores reales');

        } else {

            var subtotal_tabla = cantidad * precio;

            var total_iva_tabla = subtotal_tabla * iva;

            var total_tupla = subtotal_tabla;

            var cant_bodega = producto.df_cant_bodega;

            total_tupla = total_tupla.toFixed(2);

            var row = '<tr>' +

                '<td class="id_producto" style="display: none;">' + producto.df_id_producto + '</td>' +

                '<td class="id_precio" style="display: none;">' + producto.df_id_precio + '</td>' +

                '<td class="cant_bodega" style="display: none;">' + cant_bodega + '</td>' +

                '<td class="iva" style="display: none;">' + iva + '</td>' +

                '<td class="subtotal" style="display: none;">' + subtotal_tabla + '</td>' +

                '<td class="total_iva" style="display: none;">' + Number(total_iva_tabla).toFixed(2) + '</td>' +

                '<td class="unidad_caja" style="display: none;">' + unidad_caja + '</td>' +

                '<td width="100" class="codigo">' + producto.df_codigo_prod + '</td>' +

                '<td class="producto">' + producto.df_nombre_producto + '</td>' +

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

        limpiarLineaProducto();

    }

}