var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var timer;
var items = [];
var kardex = [];

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
    //load();
});

function load() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    kardex = [];
    $('#resultados .table-responsive table tbody').empty();
    var urlCompleta = url + 'kardex/getAll.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_codigo_prod: q, df_producto: q }), function(response) {
        console.log(response.data);
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                getUsuario(row);
            })
        }
        clearTimeout(timer);
        timer = setTimeout(function() {
            kardex.sort(function (a, b){
              return (b.df_kardex_id - a.df_kardex_id)
            });
            records = kardex;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        }, 1000);
    });
}

function getUsuario(row) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: row.df_creadoBy_kar }), function(response) {
        row.df_usuario_usuario = response.data[0].df_usuario_usuario;
        kardex.push(row);
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
    $.each(displayRecords, function(index, row) {
        var tr;
        var f = row.df_fecha_kar.split(' ')[0];
        var time = row.df_fecha_kar.split(' ')[1];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var fecha = dia + '/' + mes + '/' + ano; 
        tr = $('<tr/>');
        tr.append("<td>" + fecha + "</td>");
        tr.append("<td>" + row.df_codigo_prod + "</td>");
        tr.append("<td>" + row.df_producto + "</td>");
        tr.append("<td>" + row.df_factura_kar + "</td>");
        tr.append("<td>" + row.df_usuario_usuario + "</td>");
        tr.append("<td class='text-center'>" + row.df_ingresa_kar + "</td>");
        tr.append("<td class='text-center'>" + row.df_egresa_kar + "</td>");
        tr.append("<td class='text-center'>" + row.df_existencia_kar + "</td>");
        tr.append("<td class='text-center'>" + row.df_nombre_edo_kardex + "</td>");    
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })         
}