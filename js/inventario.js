var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var timer;
var items = [];
var inventario = [];

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
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    inventario = [];
    $('#resultados .table-responsive table tbody').empty();
    var urlCompleta = url + 'inventario/getAll.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_codigo_prod: q, df_nombre_producto: q }), function(response) {
        console.log(response.data);
        if (response.data.length > 0) {
            inventario = response.data;
        }
        clearTimeout(timer);
        timer = setTimeout(function() {
            inventario.sort(function(a, b) {
                return (b.df_id_inventario - a.df_id_inventario)
            });
            records = inventario;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
        }, 1000);
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
        tr = $('<tr/>');
        tr.append("<td>" + row.df_codigo_prod + "</td>");
        tr.append("<td class='text-center'>" + row.df_cant_bodega + "</td>");
        //tr.append("<td class='text-center'>" + row.df_cant_transito + "</td>");
        tr.append("<td class='text-center'>" + row.df_nombre_producto + "</td>");
        //tr.append("<td class='text-center'>" + row.df_ppp_ind + "</td>");
        //tr.append("<td class='text-center'>" + row.df_pvt_ind + "</td>");
        //tr.append("<td class='text-center'>" + row.df_ppp_total + "</td>");
        //tr.append("<td class='text-center'>" + row.df_pvt_total + "</td>");    
        //tr.append("<td class='text-center'>" + row.df_minimo_sug + "</td>");    
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })
}