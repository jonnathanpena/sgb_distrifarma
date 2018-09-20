var timer;
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var facts = [];

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
    facts = [];
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'factura/getAll.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura: $('#q').val() }), function(response) {
        if (response.data.length > 0) {
            $('#resultados .table-responsive table tbody').html('');
            var facturas = response.data;
            $.each(facturas, function(index, row) {
                getCliente(row);
            });
            clearTimeout(timer);
            timer = setTimeout(function() {
                facts.sort(function(a,b) {
                    if (a.df_fecha_fac > b.df_fecha_fac){
                        return -1;
                    }
                    if (a.df_fecha_fac < b.df_fecha_fac){
                        return 1;
                    }
                    return 0;
                })
                records = facts;
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }, 1000);
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontraron facturas');
        }
    })
}

function generate_table() {
    $('#resultados .table-responsive table tbody').empty();
    var tr;
    $.each(displayRecords, function(index, factura) {
        var subtotal = Number(factura.df_subtotal_fac).toFixed(2);
        var descuentos = Number(factura.df_descuento_fac).toFixed(2);
        var iva = Number(factura.df_iva_fac).toFixed(2);
        var total_factura = Number(factura.df_valor_total_fac).toFixed(2);
        var tr;
        tr = $('<tr/>');
        tr.append("<td>" + factura.df_fecha_fac + "</td>");
        tr.append("<td>" + factura.df_num_factura + "</td>");
        tr.append("<td>" + factura.df_razon_social_cli + "</td>");
        tr.append("<td>" + factura.df_forma_pago_fac + "</td>");
        tr.append("<td>$" + subtotal + "</td>");
        tr.append("<td>$" + descuentos + "</td>");
        tr.append("<td>$" + iva + "</td>");
        tr.append("<td>$" + total_factura + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Editar' onclick='editar(`" + factura.df_num_factura + "`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
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

function getCliente(factura) {
    var urlCompleta = url + 'cliente/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_cliente: factura.df_cliente_cod_fac }), function(response) {
        factura.df_razon_social_cli = response.data[0].df_razon_social_cli;
        facts.push(factura);
    });
}

function editar(facturaId) {
    window.location.href = "editar_factura.php?id=" + facturaId;
}