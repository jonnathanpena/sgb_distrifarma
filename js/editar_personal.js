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
    var personal = JSON.parse(localStorage.getItem('distrifar_personal_editar'));
    $('#tipo_documento').val(personal.df_tipo_documento_per);
    $('#id').val(personal.df_id_personal);
    $('#documento').val(personal.df_documento_per);
    $('#nombre').val(personal.df_nombre_per);
    $('#apellido').val(personal.df_apellido_per);
    $('#email').val(personal.df_correo_per);
    $('#cargo').val(personal.df_cargo_per);
    $('#fecha_ingreso').val(personal.df_fecha_ingreso);
    $('#sueldo').val(personal.df_sueldo_detper);
    $('#bono').val(personal.df_bono_detper);
    $('#anticipo').val(personal.df_anticipo_detper);
    $('#descuento').val(personal.df_descuento_detper);
    $('#decimos').val(personal.df_descuento_detper);
    $('#vacaciones').val(personal.df_decimos_detper);
    $('#vacaciones').val(personal.df_vacaciones_detper);
    $('#comisiones').val(personal.df_comisiones_detper);
    $('#tabla_comisiones').val(personal.df_tabala_comision_detper);
    $('#codigo').val(personal.df_codigo_personal);
    $('#usuario_id').val(personal.df_usuario_detper);
    if (personal.df_usuario_detper != null) {
        $('#usuario').val(personal.df_usuario_usuario);
        $('#clave').val(personal.df_clave_usuario);
        $('#perfil').val(personal.df_tipo_usuario);
    } else {
        $('#usuario').hide();
        $('#label_usuario').hide();
        $('#clave').hide();
        $('#label_perfil').hide();
        $('#perfil').hide();
    }
}

$('#form_modificar_personal').submit(function(event) {
    $('#form_modificar_personal').attr('disabled', true);
    event.preventDefault();
    var personal = {
        df_tipo_documento_per: $('#tipo_documento').val(),
        df_documento_per: $('#documento').val(),
        df_nombre_per: $('#nombre').val(),
        df_apellido_per: $('#apellido').val(),
        df_cargo_per: $('#cargo').val(),
        df_fecha_ingreso: $('#fecha_ingreso').val(),
        df_correo_per: $('#email').val(),
        df_codigo_personal: $('#codigo').val(),
        df_activo_per: 1,
        df_id_personal: $('#id').val()
    };
    var detalle = {
        df_sueldo_detper: $('#sueldo').val(),
        df_bono_detper: $('#bono').val(),
        df_anticipo_detper: $('#anticipo').val(),
        df_descuento_detper: $('#descuento').val(),
        df_decimos_detper: $('#decimos').val(),
        df_vacaciones_detper: $('#vacaciones').val(),
        df_tabala_comision_detper: 1,
        df_comisiones_detper: $('#comisiones').val(),
        df_personal_cod_detper: personal.df_id_personal,
        df_usuario_detper: $('#usuario_id').val()
    };
    if (detalle.df_anticipo_detper == ''){
        detalle.df_anticipo_detper = 0;
    }
    if (detalle.df_bono_detper == ''){
        detalle.df_bono_detper = 0;
    }
    if (detalle.df_comisiones_detper == ''){
        detalle.df_comisiones_detper = 0;
    }
    if (detalle.df_decimos_detper == ''){
        detalle.df_decimos_detper = 0;
    }
    if (detalle.df_descuento_detper == ''){
        detalle.df_descuento_detper = 0;
    }
    if (detalle.df_tabala_comision_detper == ''){
        detalle.df_tabala_comision_detper = 0;
    }
    if (detalle.df_vacaciones_detper == ''){
        detalle.df_vacaciones_detper = 0;
    }
    updatePersonal(personal, detalle);
});

function updatePersonal(personal, detalle) {
    var urlCompleta = url + 'personal/updatePersonal.php';
    $.post(urlCompleta, JSON.stringify(personal), function(data, status, hrx) {
        if (data == true) {
            insertDetalle(detalle);
        } else {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
            $('#form_modificar_personal').attr('disabled', false);
        }
    });
}

function insertDetalle(detalle) {
    var urlCompleta = url + 'personal/insertDetPersonal.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(data, status, hrx) {
        if (data == false) {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
            $('#form_modificar_personal').attr('disabled', false);
        } else {
            var per = JSON.parse(localStorage.getItem('distrifar_personal_editar'));
            if (per.df_usuario_detper != null) {
                crearUsuario();
                alertar('success', '¡Éxito!', 'Personal modificado exitosamente');
            } else {
                alertar('success', '¡Éxito!', 'Personal modificado exitosamente');
                $('#form_modificar_personal').attr('disabled', false);
                window.location.href = "personal.php";
            }
        }
    });
}

function crearUsuario() {
    var user = {
        df_usuario_usuario: $('#usuario').val(),
        df_personal_cod: $('#id').val(),
        df_clave_usuario: $('#clave').val(),
        df_activo: 1,
        df_correo: $('#email').val(),
        df_tipo_usuario: $('#perfil').val(),
        df_id_usuario: $('#usuario_id').val()
    };
    updateUsuario(user);
}

function updateUsuario(user) {
    var urlCompleta = url + 'usuario/updateUsuario.php';
    console.log('usuario modificar', user);
    $.post(urlCompleta, JSON.stringify(user), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Personal modificado exitosamente');
            window.location.href = "personal.php";
        } else {
            alertar('danger', '¡Error!', 'Algo malo ocurrió, verifique la información e intente nuevamente');
        }
    })
}