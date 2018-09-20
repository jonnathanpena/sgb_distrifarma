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
    guias = [];
    $('#resultados .table-responsive table tbody').empty();
    var q = $('#q').val();
    var urlCompleta = url + 'guiaRemision/getAll.php';
    $.post(urlCompleta, JSON.stringify({ df_codigo_rem: q }), function(response) {
        $.each(response.data, function(index, row) {
            consultarVendedor(row);
        });
        clearTimeout(timer);
        timer = setTimeout(function() {
            guias.sort(function (a, b){
                return (b.df_guia_remision - a.df_guia_remision)
              });
            records = guias;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        }, 1000);
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
        tr.append("<td>" + displayRecords[i].df_codigo_rem + "</td>");
        tr.append("<td>" + displayRecords[i].df_fecha_remision + "</td>");
        tr.append("<td>" + displayRecords[i].df_nombre_per + "  " + displayRecords[i].df_apellido_per + "</td>");
        tr.append("<td class='text-center'>" + displayRecords[i].df_cant_total_producto_rem + "</td>");
        tr.append("<td class='text-center'>" + displayRecords[i].df_valor_efectivo_rem + "</td>");
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + displayRecords[i].df_guia_remision + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    }
}

function consultarVendedor(guia) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: guia.df_vendedor_rem }), function(response) {
        guia.df_nombre_per = response.data[0].df_nombre_per;
        guia.df_apellido_per = response.data[0].df_apellido_per;
        guias.push(guia);
    });
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

function detallar(id) {
    console.log('id', id);
}