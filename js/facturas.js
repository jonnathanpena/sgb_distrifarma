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
                facts.sort(function(a, b) {
                    return (b.df_num_factura - a.df_num_factura)
                });
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
        var fecha_fact = factura.df_fecha_fac.split(' ')[0];
        tr = $('<tr/>');
        tr.append("<td>" + fecha_fact + "</td>");
        tr.append("<td>" + factura.df_num_factura + "</td>");
        tr.append("<td>" + factura.df_nombre_cli + "</td>");
        tr.append("<td>" + factura.df_forma_pago_fac + "</td>");
        tr.append("<td class='text-right'>$" + subtotal + "</td>");
        tr.append("<td class='text-right'>$" + descuentos + "</td>");
        tr.append("<td class='text-right'>$" + iva + "</td>");
        tr.append("<td class='text-right'>$" + total_factura + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Editar' onclick='editar(`" + factura.df_num_factura + "`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        tr.append("<td><button class='btn btn-info pull-right' title='Imprimir' onclick='imprimir(`" + factura.df_num_factura + "`)'><i class='glyphicon glyphicon-print'></i></button></td>");
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
        factura.df_nombre_cli = response.data[0].df_nombre_cli;
        facts.push(factura);
    });
}

function editar(facturaId) {
    window.location.href = "editar_factura.php?id=" + facturaId;
}

function imprimir(factura) {
    var urlCompleta = url + 'factura/print.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura: factura }), function(response) {
        var form = $(document.createElement('form'));
        $(form).attr("action", "print/factura.php");
        $(form).attr("method", "POST");
        $(form).css("display", "none");

        var input_employee_name = $("<input>")
            .attr("type", "text")
            .attr("name", "data")
            .val(JSON.stringify(response.data[0]));
        $(form).append($(input_employee_name));

        form.appendTo(document.body);
        $(form).submit();
        $.redirectPost('print/factura.php', { 'data': response.data[0] });
    });
}