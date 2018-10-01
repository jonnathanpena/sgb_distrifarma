var timer;
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;

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
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'personal/getAll.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_nombre_per: q }), function(data, status, hrx) {
        if (data.data.length > 0) {
            $('#resultados .table-responsive table tbody').html('');
            data.data.sort(function (a, b){
                return (b.df_id_personal - a.df_id_personal)
            });
            records = data.data;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
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
    var tr;
    $.each(displayRecords, function(index, row) {
        $('#resultados .table-responsive table tbody').append('<tr><td>' + row.df_documento_per + '</td><td>' + row.df_nombre_per + '</td><td>' + row.df_cargo_per + '</td><td>' + row.df_fecha_ingreso + '</td><td><span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_documento_per + '`)"><i class="glyphicon glyphicon-edit"></i> </a></span></td></tr>');
    });
}

function detallar(documento) {
    var urlCompleta = url + 'personal/getByDocumento.php';
    $.post(urlCompleta, JSON.stringify({ df_documento_per: documento }), function(data, status, hrx) {
        var detalle = data.data[0];
        if (detalle.df_usuario_detper == null) {
            localStorage.setItem('distrifar_personal_editar', JSON.stringify(detalle));
            window.location.href = "editar_personal.php";
        } else {
            getUsuarioById(detalle);
        }
    });
}

function getUsuarioById(personal) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: personal.df_usuario_detper }), function(data, status, hrx) {
        console.log('usuario', data);
        var user = data.data[0];
        personal.df_id_usuario = user.df_id_usuario;
        personal.df_usuario_usuario = user.df_usuario_usuario;
        personal.df_personal_cod = user.df_personal_cod;
        personal.df_clave_usuario = user.df_clave_usuario;
        personal.df_activo = user.df_activo;
        personal.df_correo = user.df_correo;
        personal.df_tipo_usuario = user.df_tipo_usuario;
        localStorage.setItem('distrifar_personal_editar', JSON.stringify(personal));
        window.location.href = "editar_personal.php";
    });
}