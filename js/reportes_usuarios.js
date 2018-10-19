var timer;
var usuario;
var guiasRemision = [];
var guiasEntrega = [];
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
$(document).ready(function() {
    $('#resultados_entrega').hide('slow');
    $('#resultados_remision').hide('slow');
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
});

function seleccionaTipoGuia() {
    var tipo = $('#selec-tipo-reporte').val();
    if (tipo == 'null') {
        $('#resultados_entrega').hide('slow');
        $('#resultados_remision').hide('slow');
        alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de reporte válido');
    } else if (tipo == 'entrega') {
        $('#resultados_entrega').show('slow');
        $('#resultados_remision').hide('slow');
    } else if (tipo == 'remision') {
        $('#resultados_entrega').hide('slow');
        $('#resultados_remision').show('slow');
    }
}

function procesar() {
    var tipo = $('#selec-tipo-reporte').val();
    if (tipo == 'null') {
        alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de reporte válido');
    } else {
        consultarReporte($('#selec-tipo-reporte').val());
    }
}

function consultarReporte(tipo) {
    if (tipo == 'entrega') {
        consultarGuiasEntrega();
    } else if (tipo == 'remision') {
        consultarGuiasRemision();
    }
}

function consultarGuiasEntrega() {
    guiasEntrega = [];
    var urlCompleta = url + 'guiaEntrega/getAllUsuario.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_codigo_guia_ent: q, df_repartidor_ent: usuario.df_personal_cod }), function(response) {
        console.log('response entrega', response.data);
        $('#table_entrega tbody').empty();
        if (response.data.length > 0) {
            guiasEntrega = response.data;
            records = guiasEntrega;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        } else {
            $('#table_entrega tbody').append('<tr><td colspan="6">No existe ningún resultado para esta búsqueda</td></tr>');
        }
    });
}

function consultarGuiasRemision() {
    guiasRemision = [];
    var urlCompleta = url + 'guiaRemision/getAllUsuario.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_codigo_rem: q, df_vendedor_rem: usuario.df_personal_cod }), function(response) {
        console.log('response remision', response);
        $('#table_remision tbody').empty();
        if (response.data.length > 0) {
            guiasRemision = response.data;
            records = guiasRemision;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        } else {
            $('#table_remision tbody').append('<tr><td colspan="6">No existe ningún resultado para esta búsqueda</td></tr>');
        }
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
    if ($('#selec-tipo-reporte').val() == 'remision') {
        $.each(displayRecords, function(index, row) {
            var tr = $('<tr/>');
            tr.append('<td>' + row.df_codigo_rem + '</td>');
            tr.append('<td>' + row.df_fecha_remision + '</td>');
            tr.append('<td class="text-center">' + row.df_cant_total_producto_rem + '</td>');
            tr.append('<td class="text-center">$' + Number(row.df_valor_efectivo_rem).toFixed(2) + '</td>');
            tr.append("<td><button class='btn btn-info pull-right' title='Detallar' onclick='detallarRemision(" + row.df_guia_remision + ")'><i class='glyphicon glyphicon-print'></i></button></td>");
            $('#table_remision tbody').append(tr);
        });
    } else {
        $('#table_entrega tbody').empty();
        $.each(displayRecords, function(index, row) {
            var tr = $('<tr/>');
            tr.append('<td>' + row.df_codigo_guia_ent + '</td>');
            tr.append('<td>' + row.df_fecha_ent + '</td>');
            tr.append('<td>' + row.df_cant_total_producto_ent + '</td>');
            tr.append('<td>' + row.df_cant_total_cajas_ent + '</td>');
            tr.append('<td>' + row.df_cant_facturas_ent + '</td>');
            tr.append("<td><button class='btn btn-info pull-right' title='Detallar' onclick='detallarEntrega(" + row.df_num_guia_entrega + ")'><i class='glyphicon glyphicon-print'></i></button></td>");
            $('#table_entrega tbody').append(tr);
        });
    }
}


function detallarEntrega(id) {
    var urlCompleta = url + 'guiaEntrega/print.php';
    $.post(urlCompleta, JSON.stringify({ df_num_guia_entrega: id }), function(response) {
        var form = $(document.createElement('form'));
        $(form).attr("action", "pdf/documentos/guia_entrega.php");
        $(form).attr("method", "POST");
        $(form).css("display", "none");
        $(form).attr("target", "_blank");

        var input_employee_name = $("<input>")
            .attr("type", "text")
            .attr("name", "data")
            .val(JSON.stringify(response.data[0]));
        $(form).append($(input_employee_name));

        form.appendTo(document.body);
        $(form).submit();
    });
}

function detallarRemision(id) {
    var urlCompleta = url + 'guiaRemision/print.php';
    $.post(urlCompleta, JSON.stringify({ df_guia_remision: id }), function(response) {
        var form = $(document.createElement('form'));
        $(form).attr("action", "pdf/documentos/guia_remision.php");
        $(form).attr("method", "POST");
        $(form).css("display", "none");
        $(form).attr("target", "_blank");
        var input_employee_name = $("<input>")
            .attr("type", "text")
            .attr("name", "data")
            .val(JSON.stringify(response.data[0]));
        $(form).append($(input_employee_name));
        form.appendTo(document.body);
        $(form).submit();
    });
}