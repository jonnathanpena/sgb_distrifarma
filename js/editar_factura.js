var timer;
var productos = [];
var current;
var datetime;
var subtotal = 0;
var total_iva = 0;
var descuento = 0;
var total = 0;
var id = '';
var factura = {};
var productosFactura = [];
var detallesProducto = [];
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
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }
        id = $_GET['id'];
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
    consultarFactura();
}

function consultarFactura() {
    var urlCompleta = url + 'factura/getAll.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura: id }), function(response) {
        if (response.data.length > 0) {
            console.log('resultados', response.data);
            factura = response.data[0];
            var f = factura.df_fecha_fac.split(' ')[0];
            var dia = f.split('-')[2];
            var mes = f.split('-')[1];
            var ano = f.split('-')[0];
            var fecha = dia + '/' + mes + '/' + ano;
            $('#fecha').val(fecha);
            consultarPersonal(factura.df_personal_cod_fac);
            consultarClienteComprador(factura.df_cliente_cod_fac);
            consultarSectores(factura.df_sector_cod_fac);
            consultarProductosFactura(factura.df_num_factura);
            var subtotal_factura = factura.df_subtotal_fac * 1;
            var descuento_factura = factura.df_descuento_fac * 1;
            var iva_factura = factura.df_iva_fac * 1;
            var total_factura = factura.df_valor_total_fac * 1;
            $('#forma_pago').val(factura.df_forma_pago_fac);
            $('#fecha_entrega').val(factura.df_fecha_entrega_fac);
            $('#subtotal').html(subtotal_factura.toFixed(2));
            $('#descuento').html(descuento_factura.toFixed(2));
            $('#total_iva').html(iva_factura.toFixed(2));
            $('#total').html(total_factura.toFixed(2));
        } else {
            alertar('danger', '¡Error!', 'Por favor certifique que está conectado a internet, e intente nuevamente');
            window.location.href = "facturas.php";
        }
    })
}

function consultarPersonal(personal) {
    var urlCompleta = url + 'personal/getAll.php';
    $('#personal').append('<option value="null">Seleccione...</option>')
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                $('#personal').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
            })
            $('#personal').val(personal);
        }
    });
}

function consultarSectores(sector) {
    var urlCompleta = url + 'sector/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#sector').append('<option value="' + row.df_codigo_sector + '">' + row.df_nombre_sector + '</option>');
        });
        $('#sector').val(sector);
    });
}

function consultarClienteComprador(cliente) {
    var urlCompleta = url + 'cliente/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_cliente: cliente }), function(response) {
        $('#documento_cliente').val(response.data[0].df_documento_cli);
        $('#cliente_id').val(response.data[0].df_id_cliente);
        $('#nombre_cliente').val(response.data[0].df_nombre_cli);
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

var acciones = '<a class="delete" title="Eliminar" data-toggle="tooltip" onclick="eliminarProducto()"><i class="material-icons">&#xE872;</i></a>';
var precio = 10;

/*function agregar(codigo, producto, id_producto, id_precio, iva) {
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
    });
    total = subtotal + total_iva;
    $('#subtotal').html(subtotal.toFixed(2));
    $('#total_iva').html(total_iva.toFixed(2));
    $('#descuento').html(descuento.toFixed(2))
    $('#total').html(total.toFixed(2));
}

function modificar() {
    factura.df_cliente_cod_fac = $('#cliente_id').val();
    factura.df_personal_cod_fac = $('#personal').val();
    factura.df_sector_cod_fac = $('#sector').val();
    factura.df_forma_pago_fac = $('#forma_pago').val();
    factura.df_subtotal_fac = subtotal;
    factura.df_descuento_fac = descuento;
    factura.df_iva_fac = total_iva;
    factura.df_valor_total_fac = total.toFixed(2);
    factura.df_fecha_entrega_fac = $('#fecha_entrega').val();
    factura.df_num_factura = id;
    validarInsercion(factura);
};

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
        modificarFactura(factura);
    }
}

function modificarFactura(factura) {
    var urlCompleta = url + 'factura/update.php';
    console.log('factura', factura);
    $.post(urlCompleta, JSON.stringify(factura), function(response) {
        if (response == true) {
            recorrerTablaProductos();
        } else {
            alertar('danger', '¡Error!', 'Certifica que tienes conectividad estable y vuelve a intentar');
            load();
        }
    });
}

function recorrerTablaProductos() {
    detallesProducto = [];
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
        var cant_bodega = $('.cant_bodega', b).text() * 1;
        var detalle = {
            df_num_factura_detfac: id,
            df_prod_precio_detfac: id_precio,
            df_precio_prod_detfac: precio,
            df_cantidad_detfac: cantidad,
            df_valor_sin_iva_detfac: valor_sin_iva,
            df_iva_detfac: iva,
            df_valor_total_detfac: total_tupla,
            df_nombre_und_detfac: nombre_unidad,
            df_cant_x_und_detfac: cant_x_und,
            df_edo_entrega_prod_detfac: 1,
            cant_bodega: cant_bodega,
            nombre_producto: $('.producto', b).text()
        }
        consultarExistencia(detalle);
        detallesProducto.push(detalle);
    });
    consultarElmimado();
    clearTimeout(timer);
    timer = setTimeout(function() {
        alertar('success', '¡Éxito!', 'Factura # ' + id + 'modificada exitosamente');
        load();
    }, 2000);
}

function consultarExistencia(detalle) {
    var parecido = false;
    for (var i = 0; i < productosFactura.length; i++) {
        if (detalle.df_prod_precio_detfac == productosFactura[i].df_prod_precio_detfac) {
            parecido = true;
        }
    }
    if (parecido == false) {
        insertDetalle(detalle);
    }
}

function insertDetalle(detalle) {
    var urlCompleta = url + 'detalleFactura/insert.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        getIdKardex(detalle, detalle.nombre_producto, detalle.cant_bodega);
        getInventario(detalle, detalle.cant_bodega);
    });
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
    var urlCompleta = url + 'kardex/insert.php';
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
        df_edo_kardex: 2
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

function consultarElmimado() {
    for (var i = 0; i < productosFactura.length; i++) {
        var existe = false;
        for (var j = 0; j < detallesProducto.length; j++) {
            if (productosFactura[i].df_prod_precio_detfac == detallesProducto[j].df_prod_precio_detfac) {
                existe = true;
            }
        }
        if (existe == false) {
            eliminarDetalle(productosFactura[i]);
        }
    }
}

function eliminarDetalle(detalle) {
    var urlCompleta = url + 'detalleFactura/delete.php';
    $.post(urlCompleta, JSON.stringify({ df_id_factura_detfac: detalle.df_id_factura_detfac }), function(response) {});
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

function consultarProductosFactura(facturaId) {
    $('#table_productos tbody').empty();
    var urlCompleta = url + 'detalleFactura/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura_detfac: facturaId }), function(response) {
        productosFactura = response.data;
        $.each(productosFactura, function(index, row) {
            var subtotal_tabla = row.df_cantidad_detfac * row.df_precio_prod_detfac;
            var total_iva_tabla = subtotal_tabla * row.df_iva_detfac;
            var total_tupla = subtotal_tabla + total_iva_tabla;
            total_tupla = total_tupla.toFixed(2);
            var unidad_caja = row.df_cant_x_und_detfac * 1;
            var unidad = row.df_nombre_und_detfac;
            var cantidad = row.df_cantidad_detfac * 1;
            if (unidad == 'CAJA') {
                cantidad = unidad_caja * cantidad;
            }
            var row = '<tr>' +
                '<td class="id_producto" style="display: none;">' + row.df_id_producto + '</td>' +
                '<td class="id_precio" style="display: none;">' + row.df_prod_precio_detfac + '</td>' +
                '<td class="iva" style="display: none;">' + Number(row.df_iva_detfac).toFixed(2) + '</td>' +
                '<td class="subtotal" style="display: none;">' + Number(subtotal_tabla).toFixed(2) + '</td>' +
                '<td class="total_iva" style="display: none;">' + Number(total_iva_tabla).toFixed(2) + '</td>' +
                '<td class="unidad_caja" style="display: none;">' + row.df_cant_x_und_detfac + '</td>' +
                '<td width="100" class="codigo">' + row.df_codigo_prod + '</td>' +
                '<td class="producto">' + row.df_nombre_producto + '</td>' +
                '<td width="100" class="unidad">' + row.df_nombre_und_detfac + '</td>' +
                '<td width="100" class="cantidad">' + row.df_cantidad_detfac + '</td>' +
                '<td width="100" class="precio_unitario">' + Number(row.df_precio_prod_detfac).toFixed(2) + '</td>' +
                '<td width="100" class="total_tupla_producto">' + Number(subtotal_tabla).toFixed(2) + '</td>' +
                '<td width="100"><a class="delete" title="Eliminar" data-toggle="tooltip" onclick="eliminarProducto(`'+ row.df_id_producto +'`, `'+ row.df_codigo_prod +'`, `'+ row.df_nombre_producto +'`, `'+ cantidad +'`)">'+
                    '<i class="material-icons">&#xE872;</i></a></td>' +
                '</tr>';
            $('#table_productos tbody').append(row);
            $('[data-toggle="tooltip"]').tooltip();
        });
        calcular();
    });
}

var keyPress = 0;
var producto_max = 0;

$('#codigo_producto').keyup(function(e) {
    if (e.which == 13 && keyPress == 0) {
        var urlCompleta = url + 'producto/getByCodigoFactura.php';
        var codigo = $('#codigo_producto').val();
        $.post(urlCompleta, JSON.stringify({ codigo: codigo }), function(response) {
            console.log('producto consultado', response);
            if (response.data.length > 0) {
                if (response.data[0].df_cant_bodega > 0) {
                    keyPress++;
                    producto = response.data[0];
                    producto_max = producto.df_cant_bodega;
                    poblarConProductoConsultado(response.data[0]);
                } else {
                    alertar('warning', '¡Alerta!', 'Cantidad insuficiente en bodega');
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
    } else if (e.which == 13 && keyPress > 0) {
        keyPress++;
        $("#cantidad_producto").val('');
        $("#cantidad_producto").focus();
    } else if (e.which != 13) {
        keyPress = 0;
    }
});

function limpiarLineaProducto() {
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
    if (cantidad > producto_max) {
        alertar('danger', '¡Alerta!', 'No puede vender más de ' + producto_max);
    } else {
        var precio = $('#precio_unitario_producto').val() * 1;
        var unidad = $('#unidad_producto').val();
        var unidad_caja = producto.df_und_caja * 1;
        var iva = producto.df_valor_impuesto / 100;
        if (precio == 'null' || cantidad < 1) {
            alert('Debe escoger valores reales');
        } else {
            var subtotal_tabla = cantidad * precio;
            var total_iva_tabla = subtotal_tabla * iva;
            var total_tupla = subtotal_tabla;
            var cant_bodega = producto.df_cant_bodega - cantidad;
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

function eliminarProducto(idProducto, codigo, nombre_producto, cantidad) {
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var cod_producto = codigo;
    var ingresa = cantidad * 1;
    var kardex = {
        df_kardex_codigo: '',
        df_fecha_kar: datetime,
        df_producto_cod_kar: cod_producto,
        df_producto: nombre_producto,
        df_factura_kar: id,
        df_ingresa_kar: ingresa,
        df_egresa_kar: 0,
        df_existencia_kar: 0,
        df_creadoBy_kar: $('#usuario').val(),
        df_edo_kardex: 3
    };    
    reponerInventario(idProducto, ingresa, kardex);
}

function reponerInventario(idProducto, cantidad, kardex) {
    var urlCompleta = url + 'inventario/getByIdProd.php';
    $.post(urlCompleta, JSON.stringify({ df_producto: idProducto }), function(response) {
        var inventario = response.data[0];
        var antes = inventario.df_cant_bodega * 1;
        inventario.df_cant_bodega = cantidad + antes;
        kardex.df_existencia_kar = inventario.df_cant_bodega;
        getIdMaxKardex(kardex);
        updateInventario(inventario);
    })
}

function getIdMaxKardex(kardex) {
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