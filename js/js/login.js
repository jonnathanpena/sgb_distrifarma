$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    usuario.ingreso = false;
    localStorage.setItem('distrifarma_test_user', JSON.stringify(usuario));
    bootstraper();
});

$('#form-login').submit(function(event) {
    event.preventDefault();
    $('#error-container').hide('');
    $('#alert-container').hide('');
    $('#alerta-container').hide('');
    var urlCompleta = url + 'login/getByName.php';
    var datos = {
        df_usuario_usuario: $('#user_name').val()
    };
    $.post(urlCompleta, JSON.stringify(datos), function(data, status, hrx) {
        console.log('Login API', data.data[0]);
        if (data.data.length > 0) {
            data.data[0].ingreso = true;
            localStorage.setItem('distrifarma_test_user', JSON.stringify(data.data[0]));
            usuario = data.data[0];
            if (usuario.df_activo == '1') {
                var clave = $('#user_password').val();
                if (usuario.df_clave_usuario != clave) {
                    alertar('warning', '¡Alerta!', 'Clave errada, intente nuevamente');
                    /*$('#alerta-container').show('slow');
                    $('#alerta-mensaje').html('Las claves no coinciden, intente nuevamente!');*/
                } else {
                    window.location.href = "nueva_factura.php";
                }
                $('#user_password').val("");
                $('#user_name').val();
            } else {
                alertar('danger', '¡Error!', 'Usuario sin permiso de acceso al sistema, por favor, comuníquese con el administrador del sistema!');
                /*$('#error-container').show('slow');
                $('#error-mensaje').html('Usuario sin permiso de acceso al sistema, por favor, comuníquese con el administrador del sistema!');*/
            }
        } else {
            alertar('danger', '¡Error!', 'Usuario no registrado, por favor, comuníquese con el administrador del sistema!');
            /*$('#error-container').show('slow');
            $('#error-mensaje').html('Usuario no registrado, por favor, comuníquese con el administrador del sistema!');*/
        }
    });
});

function bootstraper() {
    var urlCompleta = url + 'inventario/bootstraper.php';
    $.get(urlCompleta, function(response) {
        console.log('bootstraper', response);
    });
}