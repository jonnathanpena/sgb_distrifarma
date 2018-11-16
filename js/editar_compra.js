var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0,
    timer,
    current,
    datetime,
    id,
    compra = {},
    veces = 0;

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
    $('#pago_transferencia').hide();
    $('#pago_tarjeta').hide();
    $('#pago_cheque').hide();
    $('#pago_electronico').hide();
    $('#pago_credito').hide();
    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    id = $_GET['id'];
    getCompraById();
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

$('#sustento_tributario').change(function() {
    if (veces == 0) {
        cargarTiposComprobantes(compra.sustento.sustento_id, compra.sustento.comprobante_id);
    } else {
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
    }
    veces++;
});

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
    cuotas = [];
    $('table#cuotas tbody tr').each(function(a, b) {
        var pago_minimo = $('.cuota', b).text() * 1;
        var fecha = $('.fecha', b).text();
        var descripcion = $('.descripcion', b).text();
        falta -= pago_minimo;
        cuotas.push({
            cuota: pago_minimo,
            fecha: fecha,
            descripcion: descripcion
        });
    });
    $(this).attr("disabled", "disabled");
    var index = $("table#cuotas tbody tr:last-child").index();
    var row = '<tr>' +
        '<td class="cuota"><input type="number" class="form-control" name="cuota" value="' + Number(falta).toFixed(3) + '" id="cuota_minima" autofocus></td>' +
        '<td class="fecha"><input type="date" class="form-control" name="fecha" id="fecha"></td>' +
        '<td class="descripcion"><input type="text" class="form-control" name="descripcion" id="descripcion"></td>' +
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
        if ((!$(this).val())) {
            var inputID = `${$(this).context.id}`;
            if (inputID != 'descripcion') {
                $(this).addClass("error");
                empty = true;
            }
        } else {
            $(this).removeClass("error");
        }
    });
    $(this).parents("tr").find(".error").first().focus();
    if (!empty) {
        input.each(function() {
            $(this).parent("td").html($(this).val());
        });
        $(this).parents("tr").find(".add, .edit").toggle();
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
        } else if (i == 2) {
            $(this).html('<input type="text" class="form-control" name="descripcion" id="descripcion" value="' + $(this).text() + '">');
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
/******* Fin Tabla cuotas  ********/

function agregarCuotas() {
    cuotas = [];
    $('table#cuotas tbody tr').each(function(a, b) {
        var pago_minimo = $('.cuota', b).text() * 1;
        var fecha = $('.fecha', b).text();
        var descripcion = $('.descripcion', b).text();
        cuotas.push({
            cuota: pago_minimo,
            fecha: fecha,
            descripcion: descripcion
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
            '<td class="descripcion">' + cuotas[i].descripcion + '</td>' +
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

$('#btn-guardar').click(function() {
    on();
    var currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    compra = {
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
        off();
        return;
    }
    if (compra.detalle_sustento_comprobante_id == 'null') {
        alertar('warning', '¡Alerta!', 'Debes escoger un tipo de comprobante');
        off();
        return;
    }
    if (compra.condiciones_compra == 'null') {
        alertar('warning', '¡Alerta!', 'La forma de pago es obligatoria');
        off();
        return;
    }
    var tipoPago = $('#condiciones').val() * 1;
    if (tipoPago == 2) {
        if ($('#banco_emisor').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            off();
            return;
        }
        if ($('#banco_receptor').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco receptor');
            off();
            return;
        }
        if ($('#monto').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el monto');
            off();
            return;
        }
        if ($('#codigo_transferencia').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el código de transferencia');
            off();
            return;
        }
        if ($('#fecha').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la fecha de transferencia');
            off();
            return;
        }
    } else if (tipoPago == 5) {
        if ($('#banco_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            off();
            return;
        }
        if ($('#tipo_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de tarjeta');
            off();
            return;
        }
        if ($('#marca_tarjeta').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe seleccionar una franquicia');
            off();
            return;
        }
        if ($('#numero_recibo').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el número de recibo');
            off();
            return;
        }
        if ($('#fecha').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la fecha');
            off();
            return;
        }
        if ($('#monto_tarjeta').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            off();
            return;
        }
        if ($('#titular_tarjeta').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el titular de la tarjeta');
            off();
            return;
        }
    } else if (tipoPago == 1) {
        if ($('#banco_cheque').val() == 'null') {
            alertar('warning', '¡Alerta!', 'Debe escoger un banco emisor');
            off();
            return;
        }
        if ($('#numero_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el número del cheque');
            off();
            return;
        }
        if ($('#monto_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            off();
            return;
        }
        if ($('#titular_cheque').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar el titular de la tarjeta');
            off();
            return;
        }
    } else if (tipoPago == 6) {
        if ($('#empresa').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe indicar la empresa');
            off();
            return;
        }
        if ($('#codigo').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el código');
            off();
            return;
        }
        if ($('#monto_electronico').val() == '') {
            alertar('warning', '¡Alerta!', 'Debe especificar el monto');
            off();
            return;
        }
    } else if (tipoPago == 4) {
        if (cuotas.length == 0) {
            alertar('warning', '¡Alerta!', 'Debe especificar las cuotas de pago');
            off();
            return;
        }
    }
    getKardexId();
    insert(compra);
}

function insert(compra) {
    console.log('a guardar:', compra);
    var urlCompleta = url + 'compra/insert.php';
    $.post(urlCompleta, JSON.stringify(compra), function(response) {
        console.log('guardado:', response);
        if (response == false) {
            console.log('compra insert', response);
            alertar('danger', '¡Error!', 'Algo malo ocurrió, por favor verifique e intente de nuevo');
            off();
        } else {
            if (compra.condiciones_compra != '4') {
                getBancos(compra.total_compra, response);
            }
            getDataProductoTable(response);
        }
    })
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
            off();
        } else {
            console.log('detalle pago compra', response);
            alertar('danger', '¡Error!', 'Algo malo ocurrió, por favor verifique e intente de nuevo');
            off();
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
            descripcion: row.descripcion,
            descuento: 0,
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

function getCompraById() {
    on();
    var urlCompleta = url + 'compra/print.php';
    $.post(urlCompleta, JSON.stringify({ id_compra: id }), function(response) {
        console.log('compra', response.data);
        compra = response.data[0];
        $('#fecha').val(compra.fecha_compra);
        $('#proveedor').val(compra.proveedor_id);
        $('#serie').val(compra.serie_compra);
        $('#documento').val(compra.documento_compra);
        $('#autorizacion').val(compra.autorizacion_compra);
        $('#fecha_comprobante').val(compra.fecha_comprobante_compra);
        $('#fecha_ingreso_bodega').val(compra.fecha_ingreso_bodega_compra);
        $('#fecha_caducidad_doc').val(compra.fecha_caducidad_compra);
        $('#vencimiento').val(compra.vencimiento_compra);
        $('#descripcion').val(compra.descripcion_compra);
        $('#condiciones').val(compra.condiciones_compra);
        $('#sustento_tributario').val(compra.sustento.sustento_id);
        cargarTiposComprobantes(compra.sustento.sustento_id, compra.detalle_sustento_comprobante_id);
        off();
    });
}

function cargarTiposComprobantes(id, comprobante_id) {
    alert('primera vez');
    var urlCompleta = url + 'sustento_tributario/getTiposComprobante.php';
    var sustento_id = id;
    var html = '<option value="null">Seleccione...</option>';
    data = {
        "sustento_id": sustento_id
    };
    $.ajax({
        type: "POST",
        url: urlCompleta,
        data: JSON.stringify(data),
        success: function(datos) {
            for (var i = 0; i < datos.data.length; i++) {
                if (datos.data[i].id_dsco == comprobante_id) {
                    html += '<option value="' + datos.data[i].id_dsco + '" selected>' + datos.data[i].nombre_tipocomprobante + '</option>';
                } else {
                    html += '<option value="' + datos.data[i].id_dsco + '">' + datos.data[i].nombre_tipocomprobante + '</option>';
                }
            }
            $("#tipo_comprobante").html(html);
        }
    });
}