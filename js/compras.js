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
        response.data.sort(function (a, b){
            return (b.id_compra - a.id_compra)
          });
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
        if (row.condiciones_compra == 4) {
            if (row.pagado == 1) {
                tr.append('<td>' + row.id_compra + '</td>');
                tr.append('<td>' + row.df_usuario_usuario + '</td>');
                tr.append('<td>' + row.df_nombre_empresa + '</td>');
                tr.append('<td class="text-center">$ ' + (row.total_compra * 1).toFixed(3) + '</td>');
                tr.append('<td class="text-right"> <button class="btn btn-success"><i class="glyphicon glyphicon-eye-open" onclick="observarCuotas(`' + row.id_compra + '`)"></i></button> </td>');
                $('#resultados .table-responsive table tbody').append(tr);    
            } else {
                tr.append('<td>' + row.id_compra + '</td>');
                tr.append('<td>' + row.df_usuario_usuario + '</td>');
                tr.append('<td>' + row.df_nombre_empresa + '</td>');
                tr.append('<td class="text-center">$ ' + (row.total_compra * 1).toFixed(3) + '</td>');
                tr.append('<td class="text-right"> <button class="btn btn-warning"><i class="glyphicon glyphicon-eye-open" onclick="observarCuotas(`' + row.id_compra + '`)"></i></button> </td>');
                $('#resultados .table-responsive table tbody').append(tr);
            }            
        } else {
            tr.append('<td>' + row.id_compra + '</td>');
            tr.append('<td>' + row.df_usuario_usuario + '</td>');
            tr.append('<td>' + row.df_nombre_empresa + '</td>');
            tr.append('<td class="text-center">$ ' + (row.total_compra * 1).toFixed(3)+ '</td>');
            tr.append('<td class="text-right"></td>');
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

function observarCuotas(compra_id) {
    var urlCompleta = url + 'cuotasCompra/getByCompra.php';
    $('#cuotas tbody').empty();
    $.post(urlCompleta, JSON.stringify({ compra_id: compra_id }), function(response) {
        console.log('observación cuotas', response.data);
        $.each(response.data, function(index, row) {
            var tr = $('<tr/>');
            tr.append('<td>$ ' + row.df_monto_cc + '</td>');
            tr.append('<td>' + row.df_fecha_cc + '</td>');
            if (row.df_estado_cc == 'PENDIENTE') {
                tr.append('<td> <button class="btn btn-info" title="Pagar" onclick="pagarCuota(`' + row.df_id_cc + '`, `' + row.compra_id + '`)"><i class="glyphicon glyphicon-usd"></i></button> </td>');
            } else {
                tr.append('<td><span class="label label-success">Cancelado</span></td>');
            }
            $('#cuotas tbody').append(tr);
        });
        $('#cuotasCompra').modal('show');
    });
}

function pagarCuota(id, compra_id) {
    var urlCompleta = url + 'cuotasCompra/update.php';
    var cuota = {
        df_estado_cc: 'PAGADO',
        df_id_cc: id
    };
    $.post(urlCompleta, JSON.stringify(cuota), function(response) {
        console.log('pago cuota', response);
        observarCuotas(compra_id);
    });
}