var timer;
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var productos = [];

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
    $('#guardar_producto').attr('disabled', false);
    $('#modificar_producto').attr('disabled', false);
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 0);
}

function cargar() {
    productos = [];
    $('#resultados .table-responsive table tbody').html('Cargando...');
    //$('#resultados .table-responsive table tbody').empty();
    //    var urlCompleta = url + 'producto/getAll.php';
    var urlCompleta = url + 'producto/getAlls.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_codigo_prod: q, df_nombre_producto: q }), function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                //getProductoPrecio(row);
                productos = response.data;
                console.log(productos);
            });
            clearTimeout(timer);
            timer = setTimeout(function() {
                productos.sort(function(a, b) {
                    return (b.df_id_producto - a.df_id_producto)
                });
                records = productos;
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }, 0);
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
    });
}

function generate_table() {
    $('#resultados .table-responsive table tbody').empty();
    var tr;
    for (var i = 0; i < displayRecords.length; i++) {
        tr = $('<tr/>');
        tr.append("<td>" + displayRecords[i].df_codigo_prod + "</td>");
        tr.append("<td>" + displayRecords[i].df_nombre_producto + "</td>");
        //tr.append("<td class='text-center'>" + displayRecords[i].df_ppp + "</td>");
        tr.append("<td class='text-right'> $ " + Number(displayRecords[i].df_pvt1).toFixed(2) + "</td>");
        tr.append("<td class='text-right'> $ " + Number(displayRecords[i].df_pvt2).toFixed(2) + "</td>");
        tr.append("<td class='text-right'> $ " + Number(displayRecords[i].df_pvp).toFixed(2) + "</td>");
        tr.append("<td class='text-right'>" + displayRecords[i].df_valor_impuesto + "%</td>");
        //tr.append("<td class='text-center'>" + displayRecords[i].df_min_sugerido + "</td>");
        tr.append("<td class='text-right'>" + displayRecords[i].df_und_caja + "</td>");
        //tr.append("<td class='text-center'>" + displayRecords[i].df_utilidad + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + displayRecords[i].df_id_producto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    }
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

function getProductoPrecio(producto) {
    var urlCompleta = url + 'productoPrecio/getByProducto.php';
    $.post(urlCompleta, JSON.stringify({ df_producto_id: producto.df_id_producto }), function(response) {
        producto.df_id_precio = response.data[0].df_id_precio;
        producto.df_ppp = response.data[0].df_ppp;
        producto.df_ppp = response.data[0].df_ppp;
        producto.df_pvt1 = response.data[0].df_pvt1;
        producto.df_pvt2 = response.data[0].df_pvt2;
        producto.df_pvp = response.data[0].df_pvp;
        producto.df_iva = response.data[0].df_iva;
        producto.df_min_sugerido = response.data[0].df_min_sugerido;
        producto.df_und_caja = response.data[0].df_und_caja;
        producto.df_utilidad = response.data[0].df_utilidad;
        getIva(producto);
    });
}

function getIva(producto) {
    var urlCompleta = url + 'productoPrecio/getByIdImpuesto.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_id_impuesto: producto.df_iva }), function(response) {
        producto.df_nombre_impuesto = response.data[0].df_nombre_impuesto;
        producto.df_valor_impuesto = response.data[0].df_valor_impuesto;
        productos.push(producto);
    });
}

function nuevoProducto() {
    $('#iva').empty();
    $('#nuevoProducto').modal('show');
    $('#codigop').append();
    var urlCompleta = url + 'producto/getIdMax.php';
    $.get(urlCompleta, function(response) {
        var codigo = '';
        if (response.data[0].df_id_producto == null) {
            codigo = 'PRO-001';
        } else if (response.data[0].df_id_producto > 0 && response.data[0].df_id_producto < 10) {
            codigo = 'PRO-00' + ((response.data[0].df_id_producto * 1) + 1);
        } else if (response.data[0].df_id_producto > 9 && response.data[0].df_id_producto < 100) {
            codigo = 'PRO-0' + ((response.data[0].df_id_producto * 1) + 1);
        } else if (response.data[0].df_id_producto > 99) {
            codigo = 'PRO-' + ((response.data[0].df_id_producto * 1) + 1);
        }
        console.log('MaxId ', codigo);
        $('#codigop').val(codigo);
    });
    var urlCompleta = url + 'productoPrecio/getAllImpuesto.php';
    $.get(urlCompleta, function(response) {
        var tr;
        var i = 0;
        $.each(response.data, function(index, row) {
            if (i == 0) {
                $('#iva').append("<option value='" + row.df_id_impuesto + "' selected>" + row.df_nombre_impuesto + ' - ' + row.df_valor_impuesto + "</option>");
            } else {
                $('#iva').append("<option value='" + row.df_id_impuesto + "'>" + row.df_nombre_impuesto + ' - ' + row.df_valor_impuesto + "</option>");
            }
            i++;
        });
    });
}

$('#guardar_producto').submit(function(event) {
    $('#guardar_producto').attr('disabled', true);
    event.preventDefault();
    var producto = {
        df_nombre_producto: $('#nombre').val(),
        df_codigo_prod: $('#codigop').val()
    };
    var productoPrecio = {
        df_producto_id: '',
        df_ppp: 0,
        df_pvt1: $('#pvt1').val(),
        df_pvt2: $('#pvt2').val(),
        df_pvp: $('#pvp').val(),
        df_iva: $('#iva').val(),
        df_min_sugerido: 0,
        df_und_caja: $('#unidad_caja').val(),
        df_utilidad: 0
    };
    if (producto.df_codigo_prod == '' || producto.df_codigo_prod == 'PRO-') {
        getMaxId(producto, productoPrecio);
    } else {
        insertProducto(producto, productoPrecio);
    }
});

function getMaxId(producto, productoPrecio) {
    var urlCompleta = url + 'producto/getIdMax.php';
    $.get(urlCompleta, function(response) {
        var codigo = '';
        if (response.data[0].df_id_producto == null) {
            codigo = 'PRO-001';
        } else if (response.data[0].df_id_producto > 0 && response.data[0].df_id_producto < 10) {
            codigo = 'PRO-00' + ((response.data[0].df_id_producto * 1) + 1);
        } else if (response.data[0].df_id_producto > 9 && response.data[0].df_id_producto < 100) {
            codigo = 'PRO-0' + ((response.data[0].df_id_producto * 1) + 1);
        } else if (response.data[0].df_id_producto > 99) {
            codigo = 'PRO-' + ((response.data[0].df_id_producto * 1) + 1);
        }
        producto.df_codigo_prod = codigo;
        insertProducto(producto, productoPrecio);
    });
}

function insertProducto(producto, productoPrecio) {
    var urlCompleta = url + 'producto/insert.php';
    $.post(urlCompleta, JSON.stringify(producto), function(response) {
        if (response != false) {
            productoPrecio.df_producto_id = response;
            insertPrecioProducto(productoPrecio);
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

function insertPrecioProducto(productoPrecio) {
    var urlCompleta = url + 'productoPrecio/insert.php';
    consultarInventario(productoPrecio);
    $.post(urlCompleta, JSON.stringify(productoPrecio), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Producto insertado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#codigop').val('');
        $('#nombre').val('');
        $('#ppp').val('');
        $('#pvt1').val('');
        $('#pvt2').val('');
        $('#pvp').val('');
        $('#iva').html('');
        $('#min').val('');
        $('#unidad_caja').val('');
        $('#utilidad').val('');
        $('#nuevoProducto').modal('hide');
        load();
    });
}

function detallar(id) {
    var urlCompleta = url + 'producto/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_producto: id }), function(response) {
        getProductoPrecioDetalle(response.data[0]);
    });
}

function getProductoPrecioDetalle(producto) {
    console.log('producto: ', producto);
    var urlCompleta = url + 'productoPrecio/getByProducto.php';
    $.post(urlCompleta, JSON.stringify({ df_producto_id: producto.df_id_producto }), function(response) {
        producto.df_id_precio = response.data[0].df_id_precio;
        producto.df_ppp = response.data[0].df_ppp;
        producto.df_pvt1 = response.data[0].df_pvt1;
        producto.df_pvt2 = response.data[0].df_pvt2;
        producto.df_pvp = response.data[0].df_pvp;
        producto.df_iva = response.data[0].df_iva;
        producto.df_min_sugerido = response.data[0].df_min_sugerido;
        producto.df_und_caja = response.data[0].df_und_caja;
        producto.df_utilidad = response.data[0].df_utilidad;
        getIvasDetalle(producto);
    });
}

function getIvasDetalle(producto) {
    var urlCompleta = url + 'productoPrecio/getAllImpuesto.php';
    $('#editIva').empty();
    $.get(urlCompleta, function(response) {
        console.log('detallar: ', response);
        $.each(response.data, function(index, row) {
            $('#editIva').append('<option value="' + row.df_id_impuesto + '">' + row.df_nombre_impuesto + ' - ' + row.df_valor_impuesto + '</option>')
        });
        $('#codigo').val(producto.df_codigo_prod);
        $('#editNombre').val(producto.df_nombre_producto);
        $('#id').val(producto.df_id_producto);
        $('#id_precio').val(producto.df_id_precio);
        $('#editPpp').val(producto.df_ppp);
        $('#editPvt1').val(producto.df_pvt1);
        $('#editPvt2').val(producto.df_pvt2);
        $('#editPvp').val(producto.df_pvp);
        $('#editIva').val(producto.df_iva);
        $('#editMin').val(producto.df_min_sugerido);
        $('#editUnidad_caja').val(producto.df_und_caja);
        $('#editUtilidad').val(producto.df_utilidad);
        $('#editarProducto').modal('show');
    });
}

$('#modificar_producto').submit(function(event) {
    $('#modificar_producto').attr('disabled', true);
    event.preventDefault();
    var producto = {
        df_nombre_producto: $('#editNombre').val(),
        df_id_producto: $('#id').val()
    };
    var urlCompleta = url + 'producto/update.php';
    $.post(urlCompleta, JSON.stringify(producto), function(response) {
        if (response == true) {
            updatePrecio();
        }
    });
});

function updatePrecio() {
    var precio = {
        df_producto_id: $('#id').val(),
        df_ppp: $('#editPpp').val(),
        df_pvt1: $('#editPvt1').val(),
        df_pvt2: $('#editPvt2').val(),
        df_pvp: $('#editPvp').val(),
        df_iva: $('#editIva').val(),
        df_min_sugerido: $('#editMin').val(),
        df_und_caja: $('#editUnidad_caja').val(),
        df_utilidad: $('#editUtilidad').val(),
        df_id_precio: $('#id_precio').val()
    };
    obtenerInventario(precio);
    var urlCompleta = url + 'productoPrecio/update.php';
    $.post(urlCompleta, JSON.stringify(precio), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Modificación exitosa');
        } else {
            alertar('danger', '¡Error!', 'Ocurrió un error, por favor, intenta nuevamente');
        }
        $('#editarProducto').modal('hide');
        load();
    });
}

function obtenerInventario(producto_precio) {
    var urlCompleta = url + 'inventario/getByIdProd.php';
    $.post(urlCompleta, JSON.stringify({ df_producto: producto_precio.df_producto_id }), function(response) {
        response.data[0].df_pvt_ind = producto_precio.df_pvt1;
        modificarInventario(response.data[0]);
    });
}

function modificarInventario(inventario) {
    var urlCompleta = url + 'inventario/update.php';
    $.post(urlCompleta, JSON.stringify(inventario), function(response) {
        console.log('update inventario', response);
    });
}

function consultarInventario(producto) {
    var urlCompleta = url + 'inventario/getByIdProd.php';
    $.post(urlCompleta, JSON.stringify({ df_producto: producto.df_producto_id }), function(response) {
        if (response.data.length == 0) {
            insertInventario(producto);
        }
    });
}

function insertInventario(producto) {
    var urlCompleta = url + 'inventario/insert.php';
    var inventario = {
        df_cant_bodega: 0,
        df_cant_transito: 0,
        df_producto: producto.df_producto_id,
        df_ppp_ind: 0,
        df_pvt_ind: producto.df_pvt1,
        df_ppp_total: 0,
        df_pvt_total: 0,
        df_minimo_sug: 0,
        df_und_caja: producto.df_und_caja,
        df_bodega: 1
    };
    $.post(urlCompleta, JSON.stringify(inventario), function(response) {});
}