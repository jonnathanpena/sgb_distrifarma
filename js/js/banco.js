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
var libro = 0;

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
    $('#guardar_egreso').attr('disabled', false);
    $('#guardar_ingreso').attr('disabled', false);
    bancos = [];
    records = [];
    selectDetalles();
    selectDetallesIngreso();
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $('#valor_libro').val(response.data[0].df_saldo * 1);
        }
    });
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'banco/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $('#saldo_banco').val('$' + (response.data[0].df_saldo_banco * 1).toFixed(2));
            saldo = response.data[0].df_saldo_banco * 1;
            libro = ($('#valor_libro').val() * 1) + saldo;
            $('#valor_libro').val(libro);
            console.log('valor inicial', libro);
            $.each(response.data, function(index, row) {
                getUsuario(row);
            })
            clearTimeout(timer);
            timer = setTimeout(function() {
                bancos.sort(function(a, b) {
                    return (b.df_id_banco - a.df_id_banco)
                });
                records = bancos;
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }, 1000);
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
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
        tr.append("<td class='text-center'> $ " + Number(row.df_monto_banco).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_saldo_banco).toFixed(2) + "</td>");
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
    $('#movimiento').empty();
    $('#movimiento').append('<option value="null" selected>Seleccione...</option>');
    var urlCompleta = url + 'catMovimiento/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            if (row.df_tipo == 'E') {
                $('#movimiento').append('<option value="' + row.df_nombre_movimiento + '">' + row.df_nombre_movimiento + '</option>')
            }
        });
    });
}

function nuevoIngreso() {
    $('#nuevoIngresoBanco').modal('show');
    $('#saldo_ingreso').val(saldo);
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
    var urlCompleta = url + 'catMovimiento/getAll.php';
    $('#detalle').empty();
    $('#detalle').append('<option value="null" selected>Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            if (row.df_tipo == 'I') {
                $('#detalle').append('<option value="' + row.df_nombre_movimiento + '">' + row.df_nombre_movimiento + '</option>')
            }
        });
    });
}

function calcularIngreso() {
    var ingresa = $('#valor').val() * 1;
    var aFavor = saldo + ingresa;
    $('#saldo_ingreso').val(aFavor.toFixed(2));
}

function calcularEgreso() {
    var egreso = $('#valor_egreso').val() * 1;
    var aFavor = saldo - egreso;
    $('#saldo').val(aFavor.toFixed(2));
}

$('#guardar_egreso').submit(function(event) {
    $('#guardar_egreso').attr('disabled', true);
    event.preventDefault();
    if ($('#fecha_egreso').val() == '' || $('#movimiento').val() == 'null') {
        alertar('warning', '¡Advertencia!', 'Todos los campos son obligtorios');
    } else {
        var f = $('#fecha_egreso').val();
        var datetime = f + ' 00:00:00';
        currentdate = new Date();
        var datelibro = currentdate.getFullYear() + "-" +
            (currentdate.getMonth() + 1) + "-" +
            currentdate.getDate() + " " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds();

        var egreso = {
            df_fecha_banco: datetime,
            df_usuario_id_banco: $('#usuario_egreso').val(),
            df_tipo_movimiento: "Egreso",
            df_monto_banco: $('#valor_egreso').val(),
            df_saldo_banco: $('#saldo').val(),
            df_num_documento_banco: $('#documento_egreso').val(),
            df_detalle_mov_banco: $('#movimiento').val()
        };
        var egresoLibro = {
            df_fuente_ld: 'Banco',
            df_valor_inicial_ld: $('#valor_libro').val(),
            df_fecha_ld: datelibro,
            df_descipcion_ld: $('#movimiento').val(),
            df_ingreso_ld: 0,
            df_egreso_ld: $('#valor_egreso').val(),
            df_usuario_id_ld: $('#usuario_egreso').val(),
        };
        insertEgresoLibro(egresoLibro);
        insertEgreso(egreso);
    }
});

function insertEgresoLibro(egresoLibro) {
    var urlCompleta = url + 'libroDiario/insert.php';
    console.log('insert egreso de BANCO en libro diario');
    $.post(urlCompleta, JSON.stringify(egresoLibro), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Egreso de Banco en Libro Diario registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

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
    $('#guardar_ingreso').attr('disabled', true);
    event.preventDefault();
    if ($('#fecha').val() == '' || $('#detalle').val() == 'null') {
        alertar('warning', '¡Advertencia!', 'Todos los campos son obligtorios');
    } else {
        var f = $('#fecha').val();
        var datetime = f + ' 00:00:00';
        currentdate = new Date();
        var datelibro = currentdate.getFullYear() + "-" +
            (currentdate.getMonth() + 1) + "-" +
            currentdate.getDate() + " " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds();

        var ingreso = {
            df_fecha_banco: datetime,
            df_usuario_id_banco: $('#usuario').val(),
            df_tipo_movimiento: "Ingreso",
            df_monto_banco: $('#valor').val(),
            df_saldo_banco: $('#saldo_ingreso').val(),
            df_num_documento_banco: $('#documento').val(),
            df_detalle_mov_banco: $('#detalle').val()
        };
        var ingresoLibro = {
            df_fuente_ld: 'Banco',
            df_valor_inicial_ld: $('#valor_libro').val(),
            df_fecha_ld: datelibro,
            df_descipcion_ld: $('#detalle').val(),
            df_ingreso_ld: $('#valor').val(),
            df_egreso_ld: 0,
            df_usuario_id_ld: $('#usuario').val()
        };
        insertIngresoLibro(ingresoLibro);
        insertIngreso(ingreso);
    }
});

function insertIngresoLibro(ingresoLibro) {
    var urlCompleta = url + 'libroDiario/insert.php';
    console.log('insert ingreso de CC en libro diario');
    $.post(urlCompleta, JSON.stringify(ingresoLibro), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Ingreso de Caja Chica en Libro Diario registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

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

function selectDetalles() {
    var urlCompleta = url + 'banco/getAutocomplete.php';
    $.get(urlCompleta, function(response) {
        localStorage.setItem('distrifarma_autocomplete_banco', JSON.stringify(response.data));
    });
}

var opciones = {
    data: JSON.parse(localStorage.getItem('distrifarma_autocomplete_banco'))
};

$('#movimiento').easyAutocomplete(opciones);


function selectDetallesIngreso() {
    var urlCompleta = url + 'banco/getAutocompleteIng.php';
    $.get(urlCompleta, function(response) {
        localStorage.setItem('distrifarma_autocomplete_bancoingreso', JSON.stringify(response.data));
    });
}

var opcionesing = {
    data: JSON.parse(localStorage.getItem('distrifarma_autocomplete_bancoingreso'))
};

$('#detalle').easyAutocomplete(opcionesing);
$('div .easy-autocomplete').removeAttr("style");