var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var timer;
var items = [];
var libro = [];
var banco = 0;
var caja = 0;
var valorInicial = 0;

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
    libro = [];
    records = [];
    saldoInicial();
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'libroDiario/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                getUsuario(row);
            })
            clearTimeout(timer);
            timer = setTimeout(function() {
                libro.sort(function (a, b){
                  return (b.df_id_libro_diario - a.df_id_libro_diario)
                });
                records = libro;
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }, 1000);
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
    });
}

function saldoInicial(){
    caja = 0;
    banco = 0;
    valorInicial = 0;
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            caja = response.data[0].df_saldo * 1;
        }
    });
    var urlCompleta = url + 'banco/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            banco = response.data[0].df_saldo_banco * 1;
            valorInicial = caja + banco;
            $('#valor_libro').val(valorInicial);
            $('#valorInicial').val(valorInicial);
            $('#valorInicialE').val(valorInicial),
            console.log('valor inicial',valorInicial);
        }
    });
}

function getUsuario(row) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: row.df_usuario_id_ld }), function(response) {
        row.df_usuario_usuario = response.data[0].df_usuario_usuario;
        libro.push(row);
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
        var f = row.df_fecha_ld.split(' ')[0];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var fecha = dia + '/' + mes + '/' + ano; 
        tr = $('<tr/>');
        tr.append("<td>" + fecha + "</td>");
        tr.append("<td>" + row.df_usuario_usuario + "</td>");
        tr.append("<td>" + row.df_fuente_ld + "</td>");
        tr.append("<td>" + row.df_descipcion_ld + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_valor_inicial_ld).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_ingreso_ld).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(row.df_egreso_ld).toFixed(2) + "</td>");        
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    })         
}

function nuevoIngreso() {
    $('#nuevoIngresoLD').modal('show');
    $('#usuarioLD').html('');
    $('#usuarioLD').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
}

function nuevoEgreso() {
    $('#nuevoEgresoLD').modal('show');
    $('#usuarioELD').html('');
    $('#usuarioELD').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
}

$('#guardar_ingresoLD').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate();
    var ingreso = {
        df_fuente_ld: 'Libro Diario',
        df_valor_inicial_ld: $('#valor_libro').val(),
        df_fecha_ld: datetime,
        df_descipcion_ld: $('#detalleLD').val(),
        df_ingreso_ld: $('#saldo_ingreso').val(),
        df_egreso_ld: 0,
        df_usuario_id_ld: $('#usuarioLD').val()
    };
    console.log('ingreso ',ingreso);
    insertIngreso(ingreso);
});

function insertIngreso(ingreso) {
    var urlCompleta = url + 'libroDiario/insert.php';
    $.post(urlCompleta, JSON.stringify(ingreso), function(response) {
        console.log('BD ',response);
        if (response != false) {
            alertar('success', '¡Éxito!', 'Ingreso registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#detalleLD').val('');
        $('#valorInicial').val('');
        $('#nuevoIngresoLD').modal('hide');
        load();
    });
}

$('#guardar_egresoLD').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate();
    var egreso = {
        df_fuente_ld: 'Libro Diario',
        df_valor_inicial_ld: $('#valor_libro').val(),
        df_fecha_ld: datetime,
        df_descipcion_ld: $('#detalleELD').val(),
        df_ingreso_ld: 0,
        df_egreso_ld: $('#saldo_egreso').val(),
        df_usuario_id_ld: $('#usuarioELD').val()
    };
    console.log('egreso ',egreso);
    insertEgreso(egreso);
});

function insertEgreso(egreso) {
    var urlCompleta = url + 'libroDiario/insert.php';
    $.post(urlCompleta, JSON.stringify(egreso), function(response) {
        console.log('BD ',response);
        if (response != false) {
            alertar('success', '¡Éxito!', 'Egreso registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#detalleELD').val('');
        $('#valorInicialE').val('');
        $('#nuevoEgresoLD').modal('hide');
        load();
    });
}