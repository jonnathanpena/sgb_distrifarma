var timer;
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var guias = [];
var current;
var datetime;

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
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 200);
}

function cargar() {
    guias = [];
    $('#resultados .table-responsive table tbody').html('Cargando...');
    //$('#resultados .table-responsive table tbody').empty();
    var q = $('#q').val();
    var urlCompleta = url + 'guiaEntrega/getAll.php';
    $.post(urlCompleta, JSON.stringify({ df_codigo_guia_ent: q }), function(response) {
        if (response.data.length > 0) {
            console.log('guias', response.data);
            guias = response.data;
            guias.sort(function(a, b) {
                return (b.df_num_guia_entrega - a.df_num_guia_entrega)
            });
            records = guias;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
    })
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
    $('#resultados .table-responsive table tbody').empty();
    var tr;
    for (var i = 0; i < displayRecords.length; i++) {
        tr = $('<tr/>');
        tr.append("<td>" + displayRecords[i].df_codigo_guia_ent + "</td>");
        tr.append("<td>" + displayRecords[i].df_fecha_ent + "</td>");
        tr.append("<td>" + displayRecords[i].df_nombre_per + "  " + displayRecords[i].df_apellido_per + "</td>");
        tr.append("<td class='text-center'>" + displayRecords[i].df_cant_total_producto_ent + "</td>");
        tr.append("<td class='text-center'>" + displayRecords[i].df_cant_total_cajas_ent + "</td>");
        tr.append("<td class='text-center'>" + displayRecords[i].df_cant_facturas_ent + "</td>");
        tr.append("<td><button class='btn btn-info pull-right' title='Detallar' onclick='detallar(" + displayRecords[i].df_num_guia_entrega + ")'><i class='glyphicon glyphicon-print'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    }
}

function consultarVendedor(guia) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: guia.df_repartidor_ent }), function(response) {
        if (response.data.length > 0) {
            guia.df_nombre_per = response.data[0].df_nombre_per;
            guia.df_apellido_per = response.data[0].df_apellido_per;
            guias.push(guia);
        }
    });
}

function detallar(id) {
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