var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var timer;
var items = [];
var bancos = [];
var saldo = 0;
var detalles = [];

$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    if (usuario.ingreso == true) {
        $('#pasaporte').hide();
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
    bancos = [];
    records = [];
    selectDetalles();
    selectDetallesIngreso();
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
            bancos.sort(function (a, b){
              return (b.df_id_banco - a.df_id_banco)
            });
            records = bancos;
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
        var f = row.df_fecha_banco.split(' ')[0];
        var time = row.df_fecha_banco.split(' ')[1];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var fecha = dia + '/' + mes + '/' + ano; 
        tr = $('<tr/>');
        tr.append("<td>" + row.df_id_banco + "</td>");
        tr.append("<td>" + fecha + "</td>");
        tr.append("<td>" + row.df_usuario_usuario + "</td>");
        tr.append("<td>" + row.df_tipo_movimiento + "</td>");
        tr.append("<td>" + row.df_detalle_mov_banco + "</td>");
        tr.append("<td>" + row.df_num_documento_banco + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_monto_banco).toFixed(3) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_saldo_banco).toFixed(3) + "</td>");        
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })         
}

function getUsuario(row) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: row.df_usuario_id_banco }), function(response) {
        row.df_usuario_usuario = response.data[0].df_usuario_usuario;
        bancos.push(row);
    });
}

function nuevoEgreso() {
    document.getElementById("valor_egreso").max = saldo;
    $('#nuevoEgresoBanco').modal('show');
    $('#saldo').val(saldo);
    $('#usuario_egreso').html('');
    $('#usuario_egreso').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
}

function nuevoIngreso() {
    $('#nuevoIngresoBanco').modal('show');
    $('#saldo_ingreso').val(saldo);
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
}

function calcularIngreso() {
    var ingresa = $('#valor').val() * 1;
    var aFavor = saldo + ingresa;
    $('#saldo_ingreso').val(aFavor.toFixed(3));
}

function calcularEgreso() {
    var egreso = $('#valor_egreso').val() * 1;
    var aFavor = saldo - egreso;
    $('#saldo').val(aFavor.toFixed(3));
}

$('#guardar_egreso').submit(function(event) {
    event.preventDefault();
    if ($('#fecha_egreso').val() == '') {
        alertar('warning','¡Advertencia!','Todos los campos son obligtorios');
    } else {
    var f = $('#fecha_egreso').val();
    var datetime = f + ' 00:00:00';

    var egreso = {
        df_fecha_banco: datetime,
        df_usuario_id_banco: $('#usuario_egreso').val(),
        df_tipo_movimiento: "Egreso",
        df_monto_banco: $('#valor_egreso').val(),
        df_saldo_banco: $('#saldo').val(),
        df_num_documento_banco: $('#documento_egreso').val(),
        df_detalle_mov_banco: $('#movimiento').val()
    };
    insertEgreso(egreso);
    }
});

function insertEgreso(egreso) {
    var urlCompleta = url + 'banco/insert.php';
    $.post(urlCompleta, JSON.stringify(egreso), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Egreso registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#movimiento').val('');
        $('#documento_egreso').val('');
        $('#valor_egreso').val('');
        $('#nuevoEgreso').modal('hide');
        load();
    });
    selectDetalles();
}

$('#guardar_ingreso').submit(function(event) {
    event.preventDefault();
    if ($('#fecha').val() == '') {
        alertar('warning','¡Advertencia!','Todos los campos son obligtorios');
    } else {
    var f = $('#fecha').val();
    var datetime = f + ' 00:00:00';

    var ingreso = {
        df_fecha_banco: datetime,
        df_usuario_id_banco: $('#usuario').val(),
        df_tipo_movimiento: "Ingreso",
        df_monto_banco: $('#valor').val(),
        df_saldo_banco: $('#saldo_ingreso').val(),
        df_num_documento_banco: $('#documento').val(),
        df_detalle_mov_banco: $('#detalle').val()
    };
    insertIngreso(ingreso);
    }
});

function insertIngreso(ingreso) {
    var urlCompleta = url + 'banco/insert.php';
    $.post(urlCompleta, JSON.stringify(ingreso), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Ingreso registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#detalle').val('');
        $('#documento').val('');
        $('#valor').val('');
        $('#nuevoIngreso').modal('hide');
        load();
    });
    selectDetallesIngreso();
}

function selectDetalles(){
    var urlCompleta = url + 'banco/getAutocomplete.php';
    $.get(urlCompleta, function(response){
        localStorage.setItem('distrifarma_autocomplete_banco', JSON.stringify(response.data));
    });
}

var opciones = {
    data: JSON.parse(localStorage.getItem('distrifarma_autocomplete_banco'))
};

$('#movimiento').easyAutocomplete(opciones);


function selectDetallesIngreso(){
    var urlCompleta = url + 'banco/getAutocompleteIng.php';
    $.get(urlCompleta, function(response){
        localStorage.setItem('distrifarma_autocomplete_bancoingreso', JSON.stringify(response.data));
    });
}

var opcionesing = {
    data: JSON.parse(localStorage.getItem('distrifarma_autocomplete_bancoingreso'))
};

$('#detalle').easyAutocomplete(opcionesing);
$('div .easy-autocomplete').removeAttr("style");