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
});

function seleccionaTipoReporte() {
    var tipo = $('#selec-tipo-reporte').val();
    if (tipo == 'null') {
        alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de reporte válido');
    } else if (tipo == 'banco') {
        $('#falores-banco').show('slow');
    }
}

function procesar() {
    var tipo = $('#selec-tipo-reporte').val();
    if (tipo == 'null') {
        alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de reporte válido');
    } else if (tipo == 'banco') {
        reporteBanco();
    }
}

function reporteBanco() {
    var inicio = $('#desde_banco').val();
    var fin = $('#hasta_banco').val();
    if (inicio == '' || fin == '') {
        alertar('warning', '¡Alerta!', 'Debe seleccionar un rango de fecha válido');
    } else {
        var urlCompleta = url + 'reportes/getBanco.php';
        $.post(urlCompleta, JSON.stringify({ df_fecha_ini: inicio, df_fecha_fin: fin }), function(response){
            console.log('bancos', response.data);
        });
    }
}