var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0,
    timer,
    producto = {},
    current,
    datetime;

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
        window.location.href = 'login.php';
    }
    localStorage.setItem("total_compra", 0);
    localStorage.setItem("cantidad_productos", 0);
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '" selected>' + usuario.df_usuario_usuario + '</option>');
    $('#fecha_comprobante').val('');
    getAllProveedores();
    getAllSustentosTributarios();
    getAllBancos();
    getAllFranquicias();
    getAllRetenciones();
    getAllRetencionIva();
    $('#pago_transferencia').hide();
    $('#pago_tarjeta').hide();
    $('#pago_cheque').hide();
    $('#pago_electronico').hide();
    $('#pago_credito').hide();
});

var cuotas = [];

function getAllProveedores() {
    var urlCompleta = url + 'proveedor/getAll.php';
    $("#proveedor").html('');
    var html = '<option value="null">Seleccione...</option>';
    $.ajax({
        type: "GET",
        url: urlCompleta,
        success: function(datos) {
            for (var i = 0; i < datos.data.length; i++) {
                html += '<option value="' + datos.data[i].df_id_proveedor + '">' + datos.data[i].df_codigo_proveedor +
                    ' - ' + datos.data[i].df_nombre_empresa + '</option>';
            }
            $("#proveedor").html(html);
        }
    });
}

function getAllSustentosTributarios() {
    var urlCompleta = url + 'sustento_tributario/getAll.php';
    $("#sustento_tributario").html('');
    var html = '<option value="null">Seleccione...</option>';
    $.ajax({
        type: "GET",
        url: urlCompleta,
        success: function(datos) {
            for (var i = 0; i < datos.data.length; i++) {
                html += '<option value="' + datos.data[i].id_sustento + '">' + datos.data[i].nombre_sustento + '</option>';
            }
            $("#sustento_tributario").html(html);
        }
    });
}

function getAllRetencionIva() {
    var urlCompleta = url + 'retencionIva/getAll.php';
    $('#retencion_iva').empty();
    $('#retencion_iva').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#retencion_iva').append('<option value="' + row.id_retencion_iva + '-' + row.porcentaje_ret_iva + '">' + row.nombre_ret_iva + '</option>');
        });
    });
}

function seleccionoRetencionIva() {
    var porcentaje = $('#retencion_iva').val().split('-')[1] * 1;
    $('#porcentaje_ret_iva').val(porcentaje);
}

function getAllRetenciones() {
    var urlCompleta = url + 'retencionIr/getAll.php';
    $('#retencion_ir').empty();
    $('#retencion_ir').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#retencion_ir').append('<option value="' + row.id_retencion_ir + '-' + row.porcentaje_ret_ir + '">' + row.codigo_ret_ir + ' - ' + row.nombre_ret_ir + '</option>');
        });
    });
}

function seleccionoRetencionIr() {
    var porcentaje = $('#retencion_ir').val().split('-')[1] * 1;
    $('#porcentaje_retencion').val(porcentaje);
}

function getAllBancos() {
    $('#banco_emisor').empty();
    $('#banco_receptor').empty();
    $('#banco_tarjeta').empty();
    $('#banco_cheque').empty();
    $('#banco_emisor').append('<option value="null">Seleccione banco emisor...</option>');
    $('#banco_receptor').append('<option value="null">Seleccione banco emisor...</option>');
    $('#banco_tarjeta').append('<option value="null">Seleccione banco emisor...</option>');
    $('#banco_cheque').append('<option value="null">Seleccione banco emisor...</option>');
    var urlCompleta = url + 'bancos/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#banco_emisor').append('<option value="' + row.id_bancos + '">' + row.nombre_bancos + '</option>');
            $('#banco_receptor').append('<option value="' + row.id_bancos + '">' + row.nombre_bancos + '</option>');
            $('#banco_tarjeta').append('<option value="' + row.id_bancos + '">' + row.nombre_bancos + '</option>');
            $('#banco_cheque').append('<option value="' + row.id_bancos + '">' + row.nombre_bancos + '</option>');
        });
    });
}

function getAllFranquicias() {
    var urlCompleta = url + 'franquicia/getAll.php';
    $('#marca_tarjeta').empty();
    $('#marca_tarjeta').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#marca_tarjeta').append('<option value="' + row.id_franquicia + '">' + row.nombre_franq + '</option>');
        });
    });
}

$('[data-toggle="tooltip"]').tooltip();
var actions = '<a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
// Delete row on delete button click
$(document).on("click", ".delete", function() {
    $(this).parents("tr").remove();
    $(".add-new").removeAttr("disabled");
    calcularResultados();
});

$('#sustento_tributario').change(function() {
    var urlCompleta = url + 'sustento_tributario/getTiposComprobante.php';
    var sustento_id = $(this).val() * 1;
    $("#tipo_comprobante").html('');
    var html = '<option value="null">Seleccione...</option>';
    data = {
        "sustento_id": sustento_id
    }
    $.ajax({
        type: "POST",
        url: urlCompleta,
        data: JSON.stringify(data),
        success: function(datos) {
            for (var i = 0; i < datos.data.length; i++) {
                html += '<option value="' + datos.data[i].id_dsco + '">' + datos.data[i].nombre_tipocomprobante + '</option>';
            }
            $("#tipo_comprobante").html(html);
        }
    });
});

function consultarProductosAgregar() {
    $('#agregarProductoCompra').modal('show');
    getAllProductos();
}

function getAllProductos() {
    var urlCompleta = url + 'producto/getAll.php';
    records = [];
    $.post(urlCompleta, JSON.stringify({ df_nombre_producto: $('#q').val() }), function(response) {
        records = response.data;
        totalRecords = records.length;
        totalPages = Math.ceil(totalRecords / recPerPage);
        apply_pagination();
    });
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
    $('#productos_agregar_compra tbody').empty();
    var tr;
    $.each(displayRecords, function(index, row) {
        tr = $('<tr/>');
        tr.append('<td>' + row.df_codigo_prod + '</td>');
        tr.append('<td>' + row.df_nombre_producto + '</td>');
        tr.append('<td width="50"><input type="number" id="cant_' + row.df_codigo_prod + '" class="form-control"></td>');
        tr.append('<td width="50"><input type="number" id="precio_' + row.df_codigo_prod + '" class="form-control"></td>');
        tr.append('<td width="50"><select id="iva_' + row.df_codigo_prod + '" class="form-control"> <option value="0.12" selected>12%</option> <option value="0">0%</option> </select></td>');
        tr.append('<td> <button class="btn btn-info" onclick="agregarProducto(`' + row.df_codigo_prod + '`, `' + row.df_id_producto + '`, `' + row.df_nombre_producto + '`)"> <i class="glyphicon glyphicon-plus"></i> </button> </td>');
        $('#productos_agregar_compra tbody').append(tr);
    });
}

function agregarProducto(codigo, id_producto, producto) {
    var accion = '<a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
    var cantidad = $('#cant_' + codigo).val() * 1;
    var precio = Number($('#precio_' + codigo).val()).toFixed(3);
    var iva = $('#iva_' + codigo).val() * 1;
    iva = Number(iva * precio * cantidad).toFixed(3);
    var total_tupla = Number(precio * cantidad).toFixed(3);
    var tr = $('<tr/>');
    tr.append('<td class="id_producto" style="display:none;">' + id_producto + '</td>');
    tr.append('<td class="codigo" width="150">' + codigo + '</td>');
    tr.append('<td class="producto">' + producto + '</td>');
    tr.append('<td class="cantidad" width="100">' + cantidad + '</td>');
    tr.append('<td class="precio" width="100">' + precio + '</td>');
    tr.append('<td class="iva" width="100">' + iva + '</td>');
    tr.append('<td class="total_tupla" width="100">' + total_tupla + '</td>');
    tr.append('<td>' + accion + '</td>');
    $('#table_productos').append(tr);
    calcularResultados();
}

function calcularResultados() {
    var descuento_st_con_iva = $('#descuento_st_con_iva').val() * 1;
    var descuento_st_sin_iva = $('#descuento_st_sin_iva').val() * 1;
    var descuento_iva_cero = $('#descuento_iva_cero').val() * 1;
    var imp_verde = $('#imp_verde').val() * 1;
    var total_otros = $('#total_otros').val() * 1;
    $('#total_iva').val('0.12');
    var pre_st_con_iva = 0.00;
    var total_st_con_iva = 0.00;
    var pre_st_sin_iva = 0.00;
    var total_st_sin_iva = 0.00;
    var pre_st_iva_cero = 0.00;
    var total_st_iva_cero = 0.00;
    var total_iva = 0.00;
    $('#table_productos tbody tr').each(function(a, b) {
        var claculo_iva = $('.iva', b).text() * 1;
        total_iva += claculo_iva;
        console.log('calculo iva', claculo_iva);
        if (claculo_iva == 0) {
            pre_st_iva_cero += $('.total_tupla', b).text() * 1;
        }
        pre_st_sin_iva += $('.total_tupla', b).text() * 1;
    });
    pre_st_con_iva = pre_st_sin_iva + total_iva;
    $('#pre_st_con_iv').val(Number(pre_st_con_iva).toFixed(3));
    total_st_con_iva = pre_st_con_iva - descuento_st_con_iva;
    $('#total_st_con_iva').val(Number(total_st_con_iva).toFixed(3));
    $('#pre_st_sin_iva').val(Number(pre_st_sin_iva).toFixed(3));
    total_st_sin_iva = pre_st_sin_iva - descuento_st_sin_iva;
    $('#total_st_sin_iva').val(Number(total_st_sin_iva).toFixed(3));
    $('#pre_st_iva_cero').val(Number(pre_st_iva_cero).toFixed(3));
    total_st_iva_cero = pre_st_iva_cero - descuento_iva_cero;
    $('#total_st_iva_cero').val(Number(total_st_iva_cero).toFixed(3));
    var total_compra = total_st_con_iva + imp_verde + total_otros;
    $('#total_compra').val(Number(total_compra).toFixed(3));
}

function cambioDescuentoConIva() {
    var descuento = $('#descuento_st_con_iva').val() * 1;
    var total_antes = $('#pre_st_con_iv').val() * 1;
    if (descuento <= total_antes) {
        var total_despues = (total_antes - descuento).toFixed(3);
        $('#total_st_con_iva').val(total_despues);
        var total_total = $('#total_compra').val() * 1;
        total_total = Number(total_total - descuento).toFixed(3);
        $('#total_compra').val(total_total);
    } else {
        $('#descuento_st_con_iva').val('0.00');
        $('#total_st_con_iva').val(total_antes);
        alert('El descuento no puede ser mayor al total');
    }
    calcularResultados();
}

function cambioDescuentoSinIva() {
    var descuento = $('#descuento_st_sin_iva').val() * 1;
    var total_antes = $('#pre_st_sin_iva').val() * 1;
    if (descuento <= total_antes) {
        var total_despues = (total_antes - descuento).toFixed(2);
        $('#total_st_sin_iva').val(total_despues);
        var total_total = $('#total_compra').val() * 1;
        total_total = Number(total_total - descuento).toFixed(3);
        $('#total_compra').val(total_total);
    } else {
        $('#descuento_st_sin_iva').val('0.00');
        $('#total_st_sin_iva').val(total_antes);
        alert('El descuento no puede ser mayor al total');
    }
    calcularResultados();
}

function cambioDescuentoIvaCero() {
    var descuento = $('#descuento_iva_cero').val() * 1;
    var total_antes = $('#pre_st_iva_cero').val() * 1;
    if (descuento <= total_antes) {
        var total_despues = (total_antes - descuento).toFixed(2);
        $('#total_st_iva_cero').val(total_despues);
        var total_total = $('#total_compra').val() * 1;
        total_total = Number(total_total - descuento).toFixed(3);
        $('#total_compra').val(total_total);
    } else {
        $('#descuento_iva_cero').val('0.00');
        $('#total_st_iva_cero').val(total_antes);
        alert('El descuento no puede ser mayor al total');
    }
    calcularResultados();
}

function cambioICECC() {
    var pre_ice_cc = $('#pre_ice_cc').val() * 1;
    if (pre_ice_cc > 0) {
        var iva = 0.12;
        iva = (pre_ice_cc * iva) + iva;
        $('#total_iva').val(iva);
        var total_total = $('#total_st_sin_iva').val() * 1;
        total_total = (total_total * iva) + total_total;
        $('#total_compra').val(Number(total_total).toFixed(3));
    } else if (pre_ice_cc == 0) {
        $('#total_iva').val('0.12');
        $('#total_compra').val(Number($('#total_st_con_iva').val()).toFixed(3));
    }
}

function irRetencion() {
    var base_imponible = $('#total_iva').val();
    base_imponible = Number(base_imponible).toFixed(3);
    $('#base_imponible').val(base_imponible);
    var total_coniva = $('#total_st_con_iva').val();
    total_coniva = Number(total_coniva).toFixed(3);
    $('#base_imponible_civa').val(total_coniva);
    var total_siniva = $('#total_st_sin_iva').val();
    total_siniva = Number(total_siniva).toFixed(3);
    $('#base_imponible_siva').val(total_siniva);
}

$("#condiciones").change(function() {
    var valor = $(this).val() * 1;
    if (valor == 3) {
        $('#pago_transferencia').hide('slow');
        $('#pago_tarjeta').hide('slow');
        $('#pago_cheque').hide('slow');
        $('#pago_electronico').hide('slow');
        $('#pago_credito').hide('slow');
    } else if (valor == 2) {
        $('#pago_transferencia').show('slow');
        $('#pago_tarjeta').hide('slow');
        $('#pago_cheque').hide('slow');
        $('#pago_electronico').hide('slow');
        $('#pago_credito').hide('slow');
    } else if (valor == 5) {
        $('#pago_transferencia').hide('slow');
        $('#pago_tarjeta').show('slow');
        $('#pago_cheque').hide('slow');
        $('#pago_electronico').hide('slow');
        $('#pago_credito').hide('slow');
    } else if (valor == 1) {
        $('#pago_transferencia').hide('slow');
        $('#pago_tarjeta').hide('slow');
        $('#pago_cheque').show('slow');
        $('#pago_electronico').hide('slow');
        $('#pago_credito').hide('slow');
    } else if (valor == 6) {
        $('#pago_transferencia').hide('slow');
        $('#pago_tarjeta').hide('slow');
        $('#pago_cheque').hide('slow');
        $('#pago_electronico').show('slow');
        $('#pago_credito').hide('slow');
    } else if (valor == 4) {
        $('#pago_transferencia').hide('slow');
        $('#pago_tarjeta').hide('slow');
        $('#pago_cheque').hide('slow');
        $('#pago_electronico').hide('slow');
        var apagar = $('#total_compra').val() * 1;
        if (apagar > 0) {
            $('#nuevasCuotasCompra').modal({ backdrop: 'static', keyboard: false });
            initTablaCuotas();
            $('#pago_credito').show('slow');
        } else {
            alert('Debe llenar la tabla de gastos o productos antes de procesar el pago');
            $("#condiciones").val('3');
            $('#pago_credito').hide('slow');
        }
    }
});

$('#cancelarCuotas').click(function() {
    cuotas = [];
    $("#condiciones").val('3');
    $('#pago_credito').hide('slow');
});

$('#cerrarModal').click(function() {
    cuotas = [];
    $("#condiciones").val('3');
    $('#pago_credito').hide('slow');
});

/******* Tabla Cuotas  ********/
var acciones = '<a class="add" title="Agregar" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a><a class="edit" id="editar-cuota" title="Editar" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
// Append table with add row form on add new button click
$("#nueva_cuota").click(function() {
    var falta = $('#total_compra').val() * 1;
    for (var i = 0; i < cuotas.length; i++) {
        var cuota = cuotas[i].cuota * 1;
        falta = Number(falta - cuota).toFixed(3);
    }
    $(this).attr("disabled", "disabled");
    var index = $("table#cuotas tbody tr:last-child").index();
    var row = '<tr>' +
        '<td class="cuota"><input type="number" class="form-control" name="cuota" value="' + falta + '" id="cuota_minima" autofocus></td>' +
        '<td class="fecha"><input type="date" class="form-control" name="fecha" id="fecha"></td>' +
        '<td>' + acciones + '</td>' +
        '</tr>';
    $("table#cuotas").append(row);
    $("table#cuotas tbody tr").eq(index + 1).find(".add, .edit").toggle();
    $('[data-toggle="tooltip"]').tooltip();
});
// Add row on add button click
$(document).on("click", "table#cuotas tbody tr:last-child td a.add", function() {
    var empty = false;
    var input = $(this).parents("tr").find('input');
    var index = $("table#cuotas tbody tr:last-child").index();
    input.each(function() {
        if (!$(this).val()) {
            $(this).addClass("error");
            empty = true;
        } else {
            $(this).removeClass("error");
        }
    });
    $(this).parents("tr").find(".error").first().focus();
    if (!empty) {
        input.each(function() {
            $(this).parent("td").html($(this).val());
        });
        $("table#cuotas tbody tr").eq(index + 1).find(".edit").toggle();
        $("#nueva_cuota").removeAttr("disabled");
        agregarCuotas();
    }
});
// Edit row on edit button click
$(document).on("click", "#editar-cuota", function() {
    var i = 0;
    $(this).parents("tr").find("td:not(:last-child)").each(function() {
        if (i == 0) {
            $(this).html('<input type="number" class="form-control" name="cuota" id="cuota_minima" value="' + $(this).text() + '" autofocus>');
        } else if (i == 1) {
            $(this).html('<input type="date" class="form-control" name="fecha" id="fecha" value="' + $(this).text() + '">');
        }
        i++;
    });
    $(this).parents("tr").find(".add, .edit").toggle();
    $("#nueva_cuota").attr("disabled", "disabled");
});
// Delete row on delete button click
$(document).on("click", "table#cuotas tbody tr:last-child td a.delete", function() {
    $(this).parents("tr").remove();
    $("#nueva_cuota").removeAttr("disabled");
});
/******* Fin Tabla Productos  ********/

function agregarCuotas() {
    cuotas = [];
    $('table#cuotas tbody tr').each(function(a, b) {
        var pago_minimo = $('.cuota', b).text() * 1;
        var fecha = $('.fecha', b).text();
        cuotas.push({
            cuota: pago_minimo,
            fecha: fecha
        });
    });
    console.log('cuotas', cuotas);
}

function initTablaCuotas() {
    $("table#cuotas tbody").empty();
    var falta = $('#total_compra').val() * 1;
    for (var i = 0; i < cuotas.length; i++) {
        var c = cuotas[i].cuota * 1;
        falta = falta - c;
        var row = '<tr>' +
            '<td class="cuota">' + cuotas[i].cuota + '</td>' +
            '<td class="fecha">' + cuotas[i].fecha + '</td>' +
            '<td><a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' +
            '</tr>';
        $("table#cuotas").append(row);
    }
    if (falta <= 0) {
        $('#nueva_cuota').attr("disabled", "disabled");
    } else {
        $('#nueva_cuota').removeAttr("disabled");
    }
}

$('#btn-comprar').click(function() {
    var currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var compra = {
        usuario_id: $('#usuario').val(),
        fecha_compra: datetime,
        proveedor_id: $('#proveedor').val(),
        detalle_sustento_comprobante_id: $('#tipo_comprobante').val(),
        serie_compra: $('#serie').val(),
        documento_compra: $('#documento').val(),
        autorizacion_compra: $('#autorizacion').val(),
        fecha_comprobante_compra: $('#fecha_comprobante').val(),
        fecha_ingreso_bodega_compra: $('#fecha_ingreso_bodega').val(),
        fecha_caducidad_compra: $('#fecha_caducidad_doc').val(),
        vencimiento_compra: $('#vencimiento').val(),
        descripcion_compra: $('#descripcion').val(),
        condiciones_compra: $('#condiciones').val(),
        st_con_iva_compra: $('#pre_st_con_iv').val(),
        descuento_con_iva_compra: $('#descuento_st_con_iva').val(),
        total_con_iva_compra: $('#total_st_con_iva').val(),
        descuento_sin_iva_compra: $('#descuento_st_sin_iva').val(),
        st_sin_iva_compra: $('#pre_st_sin_iva').val(),
        total_sin_iva_compra: $('#total_st_sin_iva').val(),
        st_iva_cero_compra: $('#pre_st_iva_cero').val(),
        descuento_iva_cero_compra: $('#descuento_iva_cero').val(),
        total_iva_cero: $('#total_st_iva_cero').val(),
        ice_cc_compra: $('#pre_ice_cc').val(),
        imp_verde_compra: $('#imp_verde').val(),
        iva_compra: $('#total_iva').val(),
        otros_compra: $('#total_otros').val(),
        interes_compra: Number(Number($('#intereses').val()) / 100).toFixed(2),
        bonificacion_compra: Number(Number($('#bonificacion').val()) / 100).toFixed(2),
        total_compra: Number($('#total_compra').val()).toFixed(3)
    };
    validarCampos(compra);
});

function validarCampos(compra) {
    if (compra.proveedor_id == 'null') {
        alertar('warning', '¡Alerta!', 'Debes escoger un proveedor');
        return;
    }
    if (compra.detalle_sustento_comprobante_id == 'null') {
        alertar('warning', '¡Alerta!', 'Debes escoger un tipo de comprobante');
        return;
    }
    if (compra.condiciones_compra == 'null') {
        alertar('warning', '¡Alerta!', 'La forma de pago es obligatoria');
        return;
    }
    var tipoPago = $('#condiciones').val() * 1;
    if (tipoPago == 2) {
        if ($('#banco_emisor').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            return;
        }
        if ($('#banco_receptor').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco receptor');
            return;
        }
        if ($('#monto').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el monto');
            return;
        }
        if ($('#codigo_transferencia').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el código de transferencia');
            return;
        }
        if ($('#fecha').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la fecha de transferencia');
            return;
        }
    } else if (tipoPago == 5) {
        if ($('#banco_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            return;
        }
        if ($('#tipo_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de tarjeta');
            return;
        }
        if ($('#marca_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe seleccionar una franquicia');
            return;
        }
        if ($('#numero_recibo').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el número de recibo');
            return;
        }
        if ($('#fecha').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la fecha');
            return;
        }
        if ($('#monto_tarjeta').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            return;
        }
        if ($('#titular_tarjeta').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el titular de la tarjeta');
            return;
        }
    } else if (tipoPago == 1) {
        if ($('#banco_cheque').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            return;
        }
        if ($('#numero_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el número del cheque');
            return;
        }
        if ($('#monto_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            return;
        }
        if ($('#titular_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el titular de la tarjeta');
            return;
        }
    } else if (tipoPago == 6) {
        if ($('#empresa').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la empresa');
            return;
        }
        if ($('#codigo').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el código');
            return;
        }
        if ($('#monto_electronico').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            return;
        }
    } else if (tipoPago == 4) {
        if (cuotas.length == 0) {
            alertar('warning', '¡Alerta!', 'Debe especificar las cuotas de pago');
            return;
        }
    }
    getKardexId();
    insert(compra);
}

var kardex_max = 0;
var kardex_cod = '';

function getKardexId() {
    var urlCompleta = url + 'kardex/getIdMax.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            if (response.data[0].df_kardex_id != null || response.data[0].df_kardex_id != 'null') {
                kardex_max = response.data[0].df_kardex_id * 1;
            }
        }
    });
}

function insert(compra) {
    console.log('a guardar:', compra);
    var urlCompleta = url + 'compra/insert.php';
    $.post(urlCompleta, JSON.stringify(compra), function(response) {
        console.log('guardado:', response);
        if (response == false) {
            console.log('compra insert', response);
            alertar('danger', '¡Error!', 'Algo malo ocurrió, por favor verifique e intente de nuevo');
        } else {
            if (compra.condiciones_compra != '4') {
                getBancos(compra.total_compra, response);
            }
            getDataProductoTable(response);
        }
    })
}

function getDataProductoTable(id) {
    var exito = true;
    $('#table_productos tbody tr').each(function(a, b) {
        var compra_id = id;
        var producto_id = $('.id_producto', b).text() * 1;
        var cantidad_dcp = $('.cantidad', b).text() * 1;
        var precio_unitario_dcp = $('.precio', b).text() * 1;
        var iva_dcp = $('.iva', b).text() * 1;
        var subtotal_dcp = $('.total_tupla', b).text() * 1;
        var bonificacion = Number($('.bonificacion', b).text());
        var descuento = Number($('.total_descuento', b).text()).toFixed(3);
        var detalle = {
            compra_id: compra_id,
            producto_id: producto_id,
            cantidad_dcp: cantidad_dcp,
            precio_unitario_dcp: precio_unitario_dcp,
            iva_dcp: iva_dcp,
            subtotal_dcp: subtotal_dcp,
            bonificacion_dcp: bonificacion,
            descuento_dcp: descuento
        }
        consultarInventario(id, detalle, $('.producto', b).text());
        if (insertDetalleCompraProducto(detalle) == false) {
            exito = false;
        }
    });
    clearTimeout(timer);
    timer = setTimeout(function() {
        if (exito == true) {
            validarPago(id);
        } else {
            console.log('get data producto');
            alertar('danger', '¡Error!', 'Algo malo ocurrió, por favor verifique e intente de nuevo');
        }
    }, 2000)
}

function consultarInventario(id, detalle, nombre_producto) {
    var urlCompleta = url + 'inventario/getByIdProd.php';
    $.post(urlCompleta, JSON.stringify({ df_producto: detalle.producto_id }), function(response) {
        if (response.data.length > 0) {
            var inventario = response.data[0];
            var bodega_anterior = inventario.df_cant_bodega * 1;
            var nueva_cantidad = detalle.cantidad_dcp * inventario.df_und_caja;
            var nueva_bonificacion = detalle.bonificacion_dcp * inventario.df_und_caja;
            inventario.df_cant_bodega = Number(bodega_anterior + nueva_cantidad + nueva_bonificacion);
            updateInventario(id, inventario, nombre_producto, detalle.cantidad_dcp);
        } else {
            alertar('danger', '¡Error!', 'Compruebe su conexión a internet e intente nuevamente');
        }
    });
}

function updateInventario(id, inventario, nombre_producto, cantidad) {
    insertKardex(inventario, id, nombre_producto, cantidad);
    urlCompleta = url + 'inventario/update.php';
    $.post(urlCompleta, JSON.stringify(inventario), function(response) {});
}

function insertKardex(inventario, id, nombre_producto, cantidad) {
    var currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    kardex_max = kardex_max + 1;
    if (kardex_max > 0 && kardex_max < 10) {
        kardex_cod = 'KAR-00' + kardex_max;
    } else if (kardex_max > 9 && kardex_max < 100) {
        kardex_cod = 'KAR-0' + kardex_max;
    } else if (kardex_max > 99) {
        kardex_cod = 'KAR-' + kardex_max;
    }
    var kardex = {
        df_kardex_codigo: kardex_cod,
        df_fecha_kar: datetime,
        df_producto_cod_kar: inventario.df_producto,
        df_producto: nombre_producto,
        df_factura_kar: id,
        df_ingresa_kar: cantidad * 1,
        df_egresa_kar: 0,
        df_existencia_kar: inventario.df_cant_bodega,
        df_creadoBy_kar: $('#usuario').val(),
        df_edo_kardex: 2
    };
    var urlCompleta = url + 'kardex/insert.php';
    $.post(urlCompleta, JSON.stringify(kardex), function(response) {});
}

function insertDetalleCompraProducto(data) {
    var urlCompleta = url + 'detalleCompraProducto/insert.php';
    $.post(urlCompleta, JSON.stringify(data), function(response) {
        return response;
    });
}

function validarPago(id) {
    var tipo = $('#condiciones').val() * 1;
    var pago;
    if (tipo == 3) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: '',
            banco_receptor: '',
            codigo: '',
            fecha: '',
            tipo_tarjeta: '',
            franquicia: '',
            recibo: '',
            titular: '',
            cheque: ''
        };
    } else if (tipo == 2) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: $('#banco_emisor').val(),
            banco_receptor: $('#banco_receptor').val(),
            codigo: $('#codigo_transferencia').val(),
            fecha: $('#fecha').val(),
            tipo_tarjeta: '',
            franquicia: '',
            recibo: '',
            titular: '',
            cheque: ''
        };
    } else if (tipo == 5) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: $('#banco_emisor').val(),
            banco_receptor: '',
            codigo: '',
            fecha: $('#fecha').val(),
            tipo_tarjeta: $('#tipo_tarjeta').val(),
            franquicia: $('#marca_tarjeta').val(),
            recibo: $('#numero_recibo').val(),
            titular: $('#titular_tarjeta').val(),
            cheque: ''
        };
    } else if (tipo == 1) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: $('#banco_cheque').val(),
            banco_receptor: '',
            codigo: '',
            fecha: '',
            tipo_tarjeta: '',
            franquicia: '',
            recibo: '',
            titular: $('#titular_cheque').val(),
            cheque: $('#numero_cheque').val()
        };
    } else if (tipo == 6) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: '',
            banco_receptor: '',
            codigo: $('#codigo').val(),
            fecha: '',
            tipo_tarjeta: '',
            franquicia: '',
            recibo: $('#empresa').val(),
            titular: '',
            cheque: ''
        };
    } else if (tipo == 4) {
        pago = {
            compra_id: id,
            metodo_pago_id: tipo,
            banco_emisor: '',
            banco_receptor: '',
            codigo: '',
            fecha: '',
            tipo_tarjeta: '',
            franquicia: '',
            recibo: '',
            titular: '',
            cheque: ''
        };
    }
    insertPago(pago);
}

function insertPago(pago) {
    if (pago.metodo_pago_id == 4) {
        insertCuotas(pago.compra_id);
    }
    var urlCompleta = url + 'detalle_pago_compra/insert.php';
    $.post(urlCompleta, JSON.stringify(pago), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Compra registrada exitosamente');
        } else {
            console.log('detalle pago compra', response);
            alertar('danger', '¡Error!', 'Algo malo ocurrió, por favor verifique e intente de nuevo');
        }
        clearTimeout(timer);
        timer = setTimeout(function() {
            window.location.reload();
        }, 3000);
    });
}

function insertCuotas(compra_id) {
    $.each(cuotas, function(index, row) {
        var cuota = {
            compra_id: compra_id,
            df_fecha_cc: row.fecha,
            df_monto_cc: row.cuota,
            df_estado_cc: 'PENDIENTE'
        };
        insertarCuota(cuota);
    });
}

function insertarCuota(cuota) {
    var urlCompleta = url + 'cuotasCompra/insert.php';
    $.post(urlCompleta, JSON.stringify(cuota), function(response) {
        console.log('insert cuotas', response);
    });
}

var keyPress = 0;

$('#codigo_producto').keyup(function(e) {
    if (e.which == 13 && keyPress == 0) {
        var urlCompleta = url + 'producto/getByCodigoFactura.php';
        var codigo = $('#codigo_producto').val();
        $.post(urlCompleta, JSON.stringify({ codigo: codigo }), function(response) {
            if (response.data.length > 0) {
                keyPress++;
                producto = response.data[0];
                console.log('producto', producto);
                poblarConProductoConsultado(response.data[0]);
            } else {
                alertar('warning', '¡Alerta!', 'No existe producto asignado al código digitado');
                keyPress = 0;
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
    $('#cantidad_producto').val('1');
    $('#precio_unitario_producto').val('0.00');
    $('#iva_producto').val('0.12');
    $('#bonificacion_producto').val('0');
    $('#descuento_producto').val('');
    $("#codigo_producto").focus();
}

function poblarConProductoConsultado(prod) {
    $('#nombre_producto').val(prod.df_nombre_producto);
    $('#cantidad_producto').val('1');
    $('#precio_unitario_producto').empty();
    $('#precio_unitario_producto').append('<option value="' + prod.df_pvt1 + '" selected>Normal $' + prod.df_pvt1 + '</option>');
    $('#precio_unitario_producto').append('<option value="' + prod.df_pvt2 + '">Descuento $' + prod.df_pvt2 + '</option>');
}

$('#cantidad_producto').keyup(function(e) {
    if (e.which == 13) {
        $('#precio_unitario_producto').focus();
        $('#precio_unitario_producto').val('');
    }
});

$('#precio_unitario_producto').keyup(function(e) {
    if (e.which == 13) {
        $('#bonificacion_producto').focus();
        $('#bonificacion_producto').val('');
    }
});

$('#bonificacion_producto').keyup(function(e) {
    if (e.which == 13) {
        $('#descuento_producto').focus();
        $('#descuento_producto').val('');
    }
});

$('#descuento_producto').keyup(function(e) {
    if (e.which == 13) {
        agregar();
    }
});

function agregar() {
    var descuento = Number(Number($('#descuento_producto').val()) / 100);
    var accion = '<a class="delete" title="Eliminar" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
    var cantidad = $('#cantidad_producto').val() * 1;
    var precio = Number($('#precio_unitario_producto').val());
    var iva = $('#iva_producto').val() * 1;
    iva = Number(iva * precio * cantidad);
    var total_tupla = Number(precio * cantidad);
    var total_descuento = Number(descuento * total_tupla);
    total_tupla = Number(total_tupla - total_descuento);
    var tr = $('<tr/>');
    tr.append('<td class="id_producto" style="display:none;">' + producto.df_id_producto + '</td>');
    tr.append('<td class="codigo" width="150">' + producto.df_codigo_prod + '</td>');
    tr.append('<td class="producto">' + producto.df_nombre_producto + '</td>');
    tr.append('<td class="bonificacion" width="100">' + Number($('#bonificacion_producto').val()) + '</td>');
    tr.append('<td class="cantidad" width="100">' + cantidad + '</td>');
    tr.append('<td class="precio" width="100">' + Number(precio).toFixed(3) + '</td>');
    tr.append('<td class="iva" width="100">' + Number(iva).toFixed(3) + '</td>');
    tr.append('<td class="descuento" style="display: none;">' + Number(descuento).toFixed(3) + '</td>');
    tr.append('<td class="total_descuento" width="100">' + Number(total_descuento).toFixed(3) + '</td>');
    tr.append('<td class="total_tupla" width="100">' + Number(total_tupla).toFixed(3) + '</td>');
    tr.append('<td>' + accion + '</td>');
    $('#table_productos').append(tr);
    calcularResultados();
    limpiarLineaProducto();

}

$('#bonificacion').keyup(function(e) {
    var descuento = Number($('#bonificacion').val()).toFixed(3);
    if ($('#bonificacion').val() == '') {
        descuento = 0.00;
        $('#total_compra').val($('#total_st_con_iva').val());
        return;
    }
    descuento = Number(descuento / 100);
    var total_pagar = Number($('#total_compra').val()).toFixed(3);
    total_descuento = Number(total_pagar * descuento).toFixed(3);
    total_descuento = Number(total_pagar - total_descuento).toFixed(3);
    $('#total_compra').val(total_descuento);
});

function getBancos(monto, num_compra) {
    var urlCompleta = url + 'banco/getAll.php';
    var currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    $.get(urlCompleta, function(response) {
        saldo_anterior = response.data[0].df_saldo_banco * 1;
        nuevo_saldo = saldo_anterior - monto;
        var banco = {
            df_fecha_banco: datetime,
            df_usuario_id_banco: $('#usuario').val(),
            df_tipo_movimiento: 'Egreso',
            df_monto_banco: monto,
            df_saldo_banco: nuevo_saldo,
            df_num_documento_banco: num_compra,
            df_detalle_mov_banco: 'Compra'
        };
        insertBanco(banco);
        getCajaChica(saldo_anterior, monto, num_compra);
    });
}

function insertBanco(banco) {
    var urlCompleta = url + 'banco/insert.php';
    $.post(urlCompleta, JSON.stringify(banco), function(response) {
        console.log('insercion banco', response);
    });
}

function getCajaChica(saldo_banco, monto, compra_id) {
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    var saldo_inicial = saldo_banco * 1;
    var currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            var saldo_caja = response.data[0].df_saldo * 1;
            saldo_inicial = saldo_caja + saldo_inicial;
        }
        var libroDiario = {
            df_fuente_ld: 'Banco',
            df_valor_inicial_ld: saldo_inicial,
            df_fecha_ld: datetime,
            df_descipcion_ld: 'Compra #' + compra_id,
            df_ingreso_ld: 0,
            df_egreso_ld: monto,
            df_usuario_id_ld: $('#usuario').val()
        };
        insertLibroDiario(libroDiario);
    });
}

function insertLibroDiario(libroDiario) {
    var urlCompleta = url + 'libroDiario/insert.php';
    $.post(urlCompleta, JSON.stringify(libroDiario), function(response) {
        console.log('insert libro diario', response);
    });
}