$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    $('.usuario').hide('slow');
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
    $('#span_documento').hide('slow');
}

$('#toggle-usuario').change(function() {
    var valor = $(this).prop('checked');
    var cargo = $('#cargo').val();
    if (valor == true) {
        $('.usuario').show('slow');
        $('#es_usuario').val('1');        
    } else {
        $('.usuario').hide('slow');
        $('#es_usuario').val('0');
    }
});

$('#form_nuevo_personal').submit(function(event) {
    $('#form_nuevo_personal').attr('disabled', true);
    event.preventDefault();
    var tipo_documento = $('#tipo_documento').val();
    var cargo = $('#cargo').val();
    if (cargo != 'null') {
        if (tipo_documento != 'null') {
            var es_usuario = $('#toggle-usuario').prop('checked');
            if (es_usuario == true) {
                var user = $('#usuario').val();
                var clave = $('#clave').val();
                var confirme = $('#confirme').val();
                var perfil = $('#perfil').val();
                if (user != '') {
                    if (clave.length > 0) {
                        if (confirme.length > 0) {
                            if (confirme == clave) {
                                if( cargo == 'Supervisor' || cargo == 'Secretaria'){
                                    $('#perfil').val('Supervisor');
                                    var perfil = $('#perfil').val();
                                    console.log('Cargo', $('#cargo').val());
                                    console.log('Perfil', $('#perfil').val());
                                } else if ( cargo == 'Vendedor' || cargo == 'Repartidor'){
                                    $('#perfil').val('Ventas');
                                    var perfil = $('#perfil').val();
                                    console.log('Cargo', $('#cargo').val());
                                    console.log('Perfil', $('#perfil').val());
                                }
                                if (perfil != 'null') {
                                    selectMaxID({
                                        df_tipo_documento_per: $('#tipo_documento').val(),
                                        df_nombre_per: $('#nombre').val(),
                                        df_apellido_per: $('#apellido').val(),
                                        df_cargo_per: $('#cargo').val(),
                                        df_fecha_ingreso: $('#fecha_ingreso').val(),
                                        df_documento_per: $('#documento').val(),
                                        df_correo_per: $('#email').val(),
                                        df_codigo_personal: ''
                                    });
                                } else {
                                    alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
                                    $('#form_nuevo_personal').attr('disabled', false);
                                }
                            } else {
                                alertar('warning', '¡Alerta!', 'Las claves no coinciden');
                                $('#form_nuevo_personal').attr('disabled', false);
                            }
                        } else {
                            alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
                            $('#form_nuevo_personal').attr('disabled', false);
                        }
                    } else {
                        alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
                        $('#form_nuevo_personal').attr('disabled', false);
                    }
                } else {
                    alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
                    $('#form_nuevo_personal').attr('disabled', false);
                }
            } else {
                selectMaxID({
                    df_tipo_documento_per: $('#tipo_documento').val(),
                    df_nombre_per: $('#nombre').val(),
                    df_apellido_per: $('#apellido').val(),
                    df_cargo_per: $('#cargo').val(),
                    df_fecha_ingreso: $('#fecha_ingreso').val(),
                    df_documento_per: $('#documento').val(),
                    df_correo_per: $('#email').val(),
                    df_codigo_personal: ''
                });
            }
        } else {
            alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
            $('#form_nuevo_personal').attr('disabled', false);
        }
    } else {
        alertar('warning', '¡Alerta!', 'Todos los campos son obligatorios');
        $('#form_nuevo_personal').attr('disabled', false);
    }
});

function selectMaxID(personal) {
    var urlCompleta = url + 'personal/getIdMax.php';
    $.get(urlCompleta, function(data) {
        if (data.data[0].df_id_personal == null) {
            personal.df_codigo_personal = 'PER-001';
            insert(personal);
        } else {
            if (data.data[0].df_id_personal > 0 && data.data[0].df_id_personal < 10) {
                personal.df_codigo_personal = 'PER-00' + ((data.data[0].df_id_personal * 1) + 1);
                insert(personal);
            } else if (data.data[0].df_id_personal > 9 && data.data[0].df_id_personal < 100) {
                personal.df_codigo_personal = 'PER-0' + ((data.data[0].df_id_personal * 1) + 1);
                insert(personal);
            } else if (data.data[0].df_id_personal > 99) {
                personal.df_codigo_personal = 'PER-' + ((data.data[0].df_id_personal * 1) + 1);
                insert(personal);
            }
        }
    })
}

function insert(personal) {
    var urlCompleta = url + 'personal/insertPersonal.php';
    console.log('insertar personal', personal);
    $.post(urlCompleta, JSON.stringify(personal), function(data, status, hrx) {
        if (data == false) {
            alertar('danger', '¡Error!', 'Algo ocurrió mal, verifique la información, y por favor, vuelva a intentar');
            $('#form_nuevo_personal').attr('disabled', false);
        } else {
            var es_usuario = $('#toggle-usuario').prop('checked');
            if (es_usuario == true) {
                crearUsuario(data);
            } else {
                crearObjetoDetalle(data);
            }
        }
    });
}

function crearObjetoDetalle(id, id_usuario) {
    var currentDate = new Date()
    var day = currentDate.getDate()
    var month = currentDate.getMonth() + 1
    var year = currentDate.getFullYear()
    var fecha = year + '-' + month + '-' + day;
    var detalle = {
        df_sueldo_detper: $('#sueldo').val(),
        df_bono_detper: $('#bono').val(),
        df_anticipo_detper: $('#anticipo').val(),
        df_descuento_detper: $('#descuento').val(),
        df_decimos_detper: $('#decimos').val(),
        df_vacaciones_detper: $('#vacaciones').val(),
        df_tabala_comision_detper: 1,
        df_comisiones_detper: $('#comisiones').val(),
        df_personal_cod_detper: id,
        df_usuario_detper: id_usuario,
        df_fecha_proceso: fecha
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
    insertDetalle(detalle);
}

function insertDetalle(detalle) {
    var urlCompleta = url + 'personal/insertDetPersonal.php';
    console.log('insert detalle', detalle);
    $.post(urlCompleta, JSON.stringify(detalle), function(data, status, hrx) {
        if (data == false) {
            alertar('danger', '¡Error!', 'Algo ocurrió mal, verifique la información e intente de nuevo');
        } else {
            alertar('success', '¡Éxito!', 'Personal agregado exitosamente');
            $('#form_nuevo_personal').attr('disabled', false);
            cancelar();
        }
    });
}

function crearUsuario(id) {
    var usuario = {
        df_usuario_usuario: $('#usuario').val(),
        df_personal_cod: id,
        df_clave_usuario: $('#clave').val(),
        df_correo: $('#email').val(),
        df_tipo_usuario: $('#perfil').val()
    };
    insertUsuario(usuario);
}

function insertUsuario(usuario) {
    var urlCompleta = url + 'usuario/insertUsuario.php';
    $.post(urlCompleta, JSON.stringify(usuario), function(data, status, xhr) {
        if (data == false) {
            alertar('error', '¡Error!', 'Por favor intente de nuevo, si persiste, contacte al administrador del sistema');
        } else {
            crearObjetoDetalle(usuario.df_personal_cod, data);
        }
    });
}

function cancelar() {
    $('#tipo_documento').val('null');
    $('#documento').val('');
    $('#nombre').val('');
    $('#apellido').val('');
    $('#email').val('');
    $('#fecha_ingreso').val('');
    $('#sueldo').val('');
    $('#bono').val('');
    $('#anticipo').val('');
    $('#descuento').val('');
    $('#decimos').val('');
    $('#vacaciones').val('');
    $('#comisiones').val('');
    $('#usuario').val('');
    $('#clave').val('');
    $('#confirme').val('');
    $('#perfil').val('null');
}

function getDocumento() {
    var urlCompleta = url + 'personal/getByDocumento.php';
    $.post(urlCompleta, JSON.stringify({ df_documento_per: $('#documento').val() }), function(response) {
        console.log('Respuesta getdocumento ',response);
        if (response.data.length > 0) {
            $('#span_documento').show('slow');
            $('#btn-guardar').prop('disabled', true);
        } else {
            $('#span_documento').hide('slow');
            $('#btn-guardar').prop('disabled', false);
        }
    });
}