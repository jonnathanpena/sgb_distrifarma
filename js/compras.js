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
        if (usuario.df_tipo_usuario == 'Administrador') {
            $('#administrador').show('');
            $('#ventas').hide('');
        } else {
            $('#administrador').hide('');
            $('#ventas').show('');
        }
    } else {
        window.location.href = 'login.php';
    }
    load();
});

function load() {
    /*bancos = [];
    records = [];
    var urlCompleta = url + 'banco/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $('#saldo_banco').val('$' + response.data[0].df_saldo_banco);
            saldo = response.data[0].df_saldo_banco * 1;
            $.each(response.data, function(index, row) {
                getUsuario(row);
            })
        }
        clearTimeout(timer);
        timer = setTimeout(function() {
            records = bancos;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        }, 1000);
    });*/
}

/*function apply_pagination() {
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
    $.each(displayRecords, function(index, row) {
        var tr;
        var f = row.df_fecha_banco.split(' ')[0];
        var time = row.df_fecha_banco.split(' ')[1];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var hora = time.split(':')[0];
        var min = time.split(':')[1];
        var seg = time.split(':')[2];
        var fecha = dia + '/' + mes + '/' + ano + ' ' + hora + ':' + min + ':' + seg;
        tr = $('<tr/>');
        tr.append("<td>" + fecha + "</td>");
        tr.append("<td>" + row.df_usuario_usuario + "</td>");
        tr.append("<td>" + row.df_tipo_movimiento + "</td>");
        tr.append("<td>" + row.df_detalle_mov_banco + "</td>");
        tr.append("<td>" + row.df_num_documento_banco + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_monto_banco).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_saldo_banco).toFixed(2) + "</td>");
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })
}*/