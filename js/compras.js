var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0,
    timer,
    items = [],
    compras = [];

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
    load();
});

function load() {
    var urlCompleta = url + 'compra/getAll.php';
    $.post(urlCompleta, JSON.stringify({ df_nombre_empresa: $('#q').val() }), function(response) {
        console.log('compra', response.data);
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
    $('#resultados .table-responsive table tbody').empty();
    var tr;
    $.each(displayRecords, function(index, row) {
        tr = $('<tr/>');
        tr.append('<td>' + row.id_compra + '</td>');
        tr.append('<td>' + row.df_usuario_usuario + '</td>');
        tr.append('<td>' + row.df_nombre_empresa + '</td>');
        tr.append('<td class="text-center">' + row.total_compra + '</td>');
        $('#resultados .table-responsive table tbody').append(tr);
    });
}