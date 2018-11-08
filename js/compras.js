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
        response.data.sort(function(a, b) {
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
                tr.append('<td class="text-right">$ ' + (row.total_compra * 1).toFixed(3) + '</td>');
                tr.append('<td class="text-right"> <button class="btn btn-success"><i class="glyphicon glyphicon-eye-open" onclick="observarCuotas(`' + row.id_compra + '`)"></i></button> </td>');
                //tr.append('<td class="text-right"> <button class="btn btn-info pull-right" title="Imprimir" onclick="detallar(`' + row.id_compra + '`)"><i class="glyphicon glyphicon-print"></i></button> </td>');
                $('#resultados .table-responsive table tbody').append(tr);
            } else {
                tr.append('<td>' + row.id_compra + '</td>');
                tr.append('<td>' + row.df_usuario_usuario + '</td>');
                tr.append('<td>' + row.df_nombre_empresa + '</td>');
                tr.append('<td class="text-right">$ ' + (row.total_compra * 1).toFixed(3) + '</td>');
                tr.append('<td class="text-right"> <button class="btn btn-warning"><i class="glyphicon glyphicon-eye-open" onclick="observarCuotas(`' + row.id_compra + '`)"></i></button> </td>');
                //tr.append('<td class="text-right"> <button class="btn btn-info pull-right" title="Imprimir" onclick="detallar(`' + row.id_compra + '`)"><i class="glyphicon glyphicon-print"></i></button> </td>');
                $('#resultados .table-responsive table tbody').append(tr);
            }
        } else {
            tr.append('<td>' + row.id_compra + '</td>');
            tr.append('<td>' + row.df_usuario_usuario + '</td>');
            tr.append('<td>' + row.df_nombre_empresa + '</td>');
            tr.append('<td class="text-right">$ ' + (row.total_compra * 1).toFixed(3) + '</td>');
            tr.append('<td class="text-right"></td>');
            //tr.append('<td class="text-right"> <button class="btn btn-info pull-right" title="Imprimir" onclick="detallar(`' + row.id_compra + '`)"><i class="glyphicon glyphicon-print"></i></button> </td>');
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

var deudaTotal = 0;

function observarCuotas(compra_id) {
    var urlCompleta = url + 'cuotasCompra/getByCompra.php';
    $('#cuotas tbody').empty();
    $.post(urlCompleta, JSON.stringify({ compra_id: compra_id }), function(response) {
        console.log('observación cuotas', response.data);
        deudaTotal = 0;
        $.each(response.data, function(index, row) {
            var monto = row.df_monto_cc * 1;
            deudaTotal = deudaTotal + monto;
            var tr = $('<tr id="tr-' + row.df_id_cc + '"/>');
            tr.append('<td id="td-monto-' + row.df_id_cc + '" class="monto">' + row.df_monto_cc + '</td>');
            tr.append('<td id="td-fecha-' + row.df_id_cc + '">' + row.df_fecha_cc + '</td>');
            tr.append('<td class="text-center" id="td-descuento-' + row.df_id_cc + '">' + row.descuento + '</td>');
            tr.append('<td id="td-descripcion-' + row.df_id_cc + '">' + row.descripcion + '</td>');
            if (row.df_estado_cc == 'PENDIENTE') {
                tr.append('<td><button class="btn btn-warning" title="Editar" class="editarCuota" id="editarCuota-' + row.df_id_cc + '" onclick="editarcuota(`' + row.df_id_cc + '`, `' + row.compra_id + '`)"><i class="glyphicon glyphicon-pencil"></i></button> <button class="btn btn-info" id="cancelarCuota-' + row.df_id_cc + '" title="Pagar" onclick="pagarCuota(`' + row.df_id_cc + '`, `' + row.compra_id + '`, `'+ monto +'`)"><i class="glyphicon glyphicon-usd"></i></button></td>');
            } else {
                tr.append('<td><span class="label label-success">Cancelado</span></td>');
            }
            $('#cuotas tbody').append(tr);
        });
        console.log('deuda total', deudaTotal);
        $('#cuotasCompra').modal('show');
        calcularDeudaCredito();
    });
}

function editarcuota(id, idCompra) {
    var monto = $('#td-monto-' + id).html() * 1;
    var fecha = $('#td-fecha-' + id).html();
    var descuento = $('#td-descuento-' + id).html() * 1;
    var descripcion = $('#td-descripcion-' + id).html();
    $('#pago-min').val(monto);
    $('#fecha-pago').val(fecha);
    $('#descuento-pago').val(descuento);
    $('#descripcion-pago').val(descripcion);
    $('#id-cuota').val(id);
    $('#compra-id-cuota').val(idCompra);
}

$('#editar-cuota').submit(function(e) {
    e.preventDefault();
    modificarCuota();
    var idcuota = $("#id-cuota").val();
    var idCompra = $("#compra-id-cuota").val();
    var pagoMinimo = $('#pago-min').val();
    var tr = '<td class="monto">' + $('#pago-min').val() + '</td>';
    tr += '<td>' + $('#fecha-pago').val() + '</td>';
    tr += '<td style="text-align: center;">' + $('#descuento-pago').val() + '</td>';
    tr += '<td>' + $('#descripcion-pago').val() + '</td>';
    tr += '<td><button class="btn btn-info" id="cancelarCuota-' + idcuota + '" title="Pagar" onclick="pagarCuota(`' + idcuota + '`, `' + idCompra + '`, `' + pagoMinimo + '`)"><i class="glyphicon glyphicon-usd"></i></button></td>';
    $('#tr-' + $('#id-cuota').val()).html(tr);
    $('#pago-min').val('');
    $('#fecha-pago').val('');
    $('#descuento-pago').val('');
    $('#descripcion-pago').val('');
});

function modificarCuota() {
    var urlCompleta = url + 'cuotasCompra/edit.php';
    var cuota = {
        df_monto_cc: $('#pago-min').val(),
        df_fecha_cc: $('#fecha-pago').val(),
        descuento: $('#descuento-pago').val(),
        descripcion: $('#descripcion-pago').val(),
        df_id_cc: $("#id-cuota").val()
    };
    $.post(urlCompleta, JSON.stringify(cuota), function(response) {
        console.log('response', response);
        calcularDeudaCredito();
    });
}

function pagarCuota(id, compra_id, monto) {
    var urlCompleta = url + 'cuotasCompra/update.php';
    var cuota = {
        df_estado_cc: 'PAGADO',
        df_id_cc: id
    };
    $.post(urlCompleta, JSON.stringify(cuota), function(response) {
        console.log('pago cuota', response);
        descontarBancos(compra_id, monto);
    });
}

function detallar(id) {
    var urlCompleta = url + 'compra/print.php';
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

function calcularDeudaCredito() {
    var pagos = 0;
    $('#cuotas tbody tr').each(function(a, b) {
        pagos += $('.monto', b).text() * 1;
    });
    var resta = deudaTotal - pagos;
    $('#restan-cuotas').val(resta);
}

function cerrarPopUpCuotas() {
    if ($('#restan-cuotas').val() * 1 == 0) {
        $('#cuotasCompra').modal('hide');
    } else {
        alert('EL monto de las cuotas no coinciden');
    }
}

function descontarBancos(compra_id, monto) {
    var urlCompleta = url + 'banco/getAll.php';
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    $.get(urlCompleta, function(response) {
        saldo_anterior = response.data[0].df_saldo_banco * 1;
        nuevo_saldo = saldo_anterior - monto;
        var banco = {
            df_fecha_banco: datetime,
            df_usuario_id_banco: usuario.df_id_usuario,
            df_tipo_movimiento: 'Egreso',
            df_monto_banco: monto,
            df_saldo_banco: nuevo_saldo,
            df_num_documento_banco: 'Pago Compra #' + compra_id,
            df_detalle_mov_banco: 'Compra'
        };
        insertBanco(banco);
        getCajaChica(saldo_anterior, monto, compra_id);
    });
}

function insertBanco(banco) {
    var urlCompleta = url + 'banco/insert.php';
    $.post(urlCompleta, JSON.stringify(banco), function(response) {
        console.log('insercion banco', response);
    });
}

function getCajaChica(saldo_banco, monto, compra_id) {
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    var saldo_inicial = saldo_banco * 1;
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            var saldo_caja = response.data[0].df_saldo * 1;
            saldo_inicial = saldo_caja + saldo_inicial;
        }
        var libroDiario = {
            df_fuente_ld: 'Banco',
            df_valor_inicial_ld: saldo_inicial,
            df_fecha_ld: datetime,
            df_descipcion_ld: 'Pago Compra #' + compra_id,
            df_ingreso_ld: 0,
            df_egreso_ld: monto,
            df_usuario_id_ld: usuario.df_id_usuario
        };
        insertLibroDiario(libroDiario, compra_id);
    });
}

function insertLibroDiario(libroDiario, compra_id) {
    var urlCompleta = url + 'libroDiario/insert.php';
    $.post(urlCompleta, JSON.stringify(libroDiario), function(response) {
        console.log('insert libro diario', response);
        observarCuotas(compra_id);
    });
}