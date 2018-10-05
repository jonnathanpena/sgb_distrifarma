var timer;
var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var usuarios = [];

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
    $('#guardar_usuario').attr('disabled', false);
    usuario = [];
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
}

function cargar() {
    var urlCompleta = url + 'usuario/getAll.php';
    var q = $('#q').val();
    $('#resultados .table-responsive table tbody').html('Cargando...');
    $.post(urlCompleta, JSON.stringify({ df_nombre_usuario: q }), function(data, status, xhr) {
        if (data.data.length > 0) {
            $('#resultados .table-responsive table tbody').html('');
            $.each(data.data, function(index, row) {
                console.log(row);
                if (row.df_personal_cod * 1 > 0) {
                    desplegarPersonal(row);
                } else {
                    $('#resultados .table-responsive table tbody').append('<tr><td>' + row.df_documento_usuario + '</td><td>' + row.df_nombre_usuario + '</td><td>' + row.df_usuario_usuario + '</td><td>' + row.df_correo + '</td><td>' + row.df_tipo_usuario + '</td><td>' + '<span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_id_usuario + '`)"><i class="glyphicon glyphicon-edit"></i> </a><a href="#" class="btn btn-default" title="Cambiar clave" onclick="modalCambioClave(`' + row.df_id_usuario + '`)"><i class="glyphicon glyphicon-cog"></i> </a></span></td></tr>');
                }
            })
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
    });
}

function desplegarPersonal(personal) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: personal.df_personal_cod }), function(response) {
        personal.df_documento_usuario = response.data[0].df_documento_per;
        personal.df_nombre_usuario = response.data[0].df_nombre_per;
        $('#resultados .table-responsive table tbody').append('<tr><td>' + personal.df_documento_usuario + '</td><td>' + personal.df_nombre_usuario + '</td><td>' + personal.df_usuario_usuario + '</td><td>' + personal.df_correo + '</td><td>' + personal.df_tipo_usuario + '</td><td>' + '<span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallarPersonal(`' + personal.df_documento_usuario + '`)"><i class="glyphicon glyphicon-edit"></i> </a><a href="#" class="btn btn-default" title="Cambiar clave" onclick="modalCambioClave(`' + personal.df_id_usuario + '`)"><i class="glyphicon glyphicon-cog"></i> </a></span></td></tr>');
    });
}

$('#guardar_usuario').submit(function(event) {
    $('#guardar_usuario').attr('disabled', true);
    event.preventDefault();
    var data = {
        df_tipo_documento_usuario: $('#tipo_documento').val(),
        df_nombre_usuario: $('#nombre').val(),
        df_apellido_usuario: $('#apellido').val(),
        df_usuario_usuario: $('#usuario').val(),
        df_personal_cod: "",
        df_clave_usuario: $('#clave').val(),
        df_correo: $('#email').val(),
        df_tipo_usuario: $('#perfil').val()
    };
    if (data.df_clave_usuario != $('#confirme').val()) {
        $('#clave').val("");
        $('#confirme').val("");
        $('#guardar_usuario').attr('disabled', false);
        alert('Las claves no coinciden');
    } else {
        if (data.df_tipo_documento_usuario != 'null') {
            if (data.df_tipo_usuario != 'null') {
                if (data.df_tipo_documento_usuario == 'Cedula') {
                    data.df_documento_usuario = $('#documento').val();
                } else {
                    data.df_documento_usuario = $('#pasaporte').val();
                }
                insertar(data);
            } else {
                alert('Todos los campos son obligatorios');
                $('#guardar_usuario').attr('disabled', false);
            }
        } else {
            alert('Todos los campos son obligatorios');
            $('#guardar_usuario').attr('disabled', false);
        }
    }
});

function insertar(usuario) {
    var urlCompleta = url + 'usuario/insertUsuario.php';
    $.post(urlCompleta, JSON.stringify(usuario), function(data, status, xhr) {
        if (data == false) {
            alertar('error', '¡Error!', 'Por favor intente de nuevo, si persiste, contacte al administrador del sistema');
        } else {
            alertar('success', '¡Éxito!', '¡Usuario insertado exitosamente!');
        }
        $('#tipo_documento').val("null");
        $('#documento').val("");
        $('#nombre').val("");
        $('#apellido').val("");
        $('#usuario').val("");
        $('#email').val("");
        $('#perfil').val("null");
        $('#clave').val("");
        $('#confirme').val("");
        $('#nuevoUsuario').modal('hide');
        load();
    });
}

function detallar(id) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: id }), function(data, status, hrx) {
        console.log(data);
        if (data.data[0].df_activo == 1) {
            $('#toggle-activo').bootstrapToggle('on');
        } else {
            $('#toggle-activo').bootstrapToggle('off');
        }
        $('#id').val(data.data[0].df_id_usuario);
        $('#editTipo_documento').val(data.data[0].df_tipo_documento_usuario);
        $('#editDocumento').val(data.data[0].df_documento_usuario);
        $('#editPasaporte').val(data.data[0].df_documento_usuario);
        $('#editNombre').val(data.data[0].df_nombre_usuario);
        $('#editApellido').val(data.data[0].df_apellido_usuario);
        $('#editUsuario').val(data.data[0].df_usuario_usuario);
        $('#editClave').val(data.data[0].df_clave_usuario);
        $('#editActivo').val(data.data[0].df_activo);
        $('#editMail').val(data.data[0].df_correo);
        $('#editPerfil').val(data.data[0].df_tipo_usuario);
        $('#editId').val(id);
        if (data.data[0].df_tipo_documento_usuario == 'Pasaporte') {
            $('#editPasaporte').show();
            $('#editDocumento').hide();
        } else {
            $('#editPasaporte').hide();
            $('#editDocumento').show();
        }
        $('#editarUsuario').modal('show');
    });
}

$('#toggle-activo').change(function() {
    var toggle = $(this).prop('checked');
    if (toggle == true) {
        $('#editActivo').val('1');
    } else {
        $('#editActivo').val('0');
    }
})

function editar() {
    $('#guardar_usuario').attr('disabled', true);
    var datos = {
        df_tipo_documento_usuario: $('#editTipo_documento').val(),
        df_nombre_usuario: $('#editNombre').val(),
        df_apellido_usuario: $('#editApellido').val(),
        df_usuario_usuario: $('#editUsuario').val(),
        df_clave_usuario: $('#editClave').val(),
        df_activo: $('#editActivo').val(),
        df_correo: $('#editMail').val(),
        df_tipo_usuario: $('#editPerfil').val(),
        df_id_usuario: $('#editId').val()
    };
    if (datos.df_tipo_documento_usuario == 'null' || datos.df_tipo_usuario == 'null' || datos.df_usuario_usuario == '') {
        alertar('warning', '¡Alerta!', 'Debe llenar todos los campos');
        $('#guardar_usuario').attr('disabled', false);
    } else {
        if ($('#editTipo_documento').val() == 'Cedula') {
            datos.df_documento_usuario = $('#editDocumento').val();
            update(datos);
        } else if ($('#editTipo_documento').val() == 'Pasaporte') {
            datos.df_documento_usuario = $('#editPasaporte').val();
            update(datos);
        }
    }
}

function update(datos) {
    var urlCompleta = url + 'usuario/updateUsuario.php';
    $.post(urlCompleta, JSON.stringify(datos), function(data, status, hrx) {
        console.log('update', data);
        if (data == true) {
            alertar('success', '¡Éxito!', 'Usuario modificado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al modifica, verifique que todo está bien e intente de nuevo');
        }
        $('#editarUsuario').modal('hide');
        load();
    });
}

$("#tipo_documento").change(function() {
    var valor = $('#tipo_documento').val();
    if (valor == 'null') {
        $('#documento').show();
        $('#pasaporte').hide();
    } else if (valor == 'Cedula') {
        $('#documento').show();
        $('#pasaporte').hide();
    } else if (valor == 'Pasaporte') {
        $('#documento').hide();
        $('#pasaporte').show();
    }
});

$("#editTipo_documento").change(function() {
    var valor = $('#editTipo_documento').val();
    if (valor == 'null') {
        $('#editDocumento').show();
        $('#editPasaporte').hide();
    } else if (valor == 'Cedula') {
        $('#editDocumento').show();
        $('#editPasaporte').hide();
    } else if (valor == 'Pasaporte') {
        $('#editDocumento').hide();
        $('#editPasaporte').show();
    }
});

var user = {};

function modalCambioClave(id) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: id }), function(data, status, hrx) {
        user = data.data[0];
        $('#editarClave').modal('show');
    });
}

$('#modificar_clave').submit(function(event) {
    $('#guardar_usuario').attr('disabled', true);
    event.preventDefault();
    user.df_clave_usuario = $('#editaClave').val();
    if ($('#editaClave').val() != $('#editarConfirme').val()) {
        alertar('warning', '¡Error!', 'Las claves no coinciden');
        $('#guardar_usuario').attr('disabled', false);
    } else {
        modificarClave(user);
    }
});

function modificarClave(datos) {
    var urlCompleta = url + 'usuario/cambioClave.php';
    $.post(urlCompleta, JSON.stringify(datos), function(data, status, hrx) {
        console.log('update', data);
        if (data == true) {
            alertar('success', '¡Éxito!', 'Calve modificada exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al modifica, verifique que todo está bien e intente de nuevo')
        }
        $('#editarClave').modal('hide');
        $('#editaClave').val('');
        $('#editarConfirme').val('');
        load();
    });
}

function nuevoUsuario() {
    $('#span_documento').hide('slow');
    $('#span_usuario').hide('slow');
}

function getByDocumento() {
    clearTimeout(timer);
    var urlCompleta = url + 'usuario/getByDocumento.php';
    timer = setTimeout(function() {
        if ($('#tipo_documento').val() == 'null') {
            alert('Selecciona un tipo de documento');
        } else if ($('#tipo_documento').val() == 'Cedula') {
            $.post(urlCompleta, JSON.stringify({ df_documento_usuario: $('#documento').val() }), function(response) {
                if (response.data.length > 0) {
                    $('#span_documento').show('slow');
                    $('#guardar').prop('disabled', true);
                } else {
                    $('#span_documento').hide('slow');
                    $('#guardar').prop('disabled', false);
                }
            });
        } else if ($('#tipo_documento').val() == 'Pasaporte') {
            $.post(urlCompleta, JSON.stringify({ df_documento_usuario: $('#pasaporte').val() }), function(response) {
                if (response.data.length > 0) {
                    $('#span_documento').show('slow');
                    $('#guardar').prop('disabled', true);
                } else {
                    $('#span_documento').hide('slow');
                    $('#guardar').prop('disabled', false);
                }
            });
        }
    }, 100);
}

function getByUsuario() {
    clearTimeout(timer);
    var urlCompleta = url + 'usuario/getByUser.php';
    timer = setTimeout(function() {
        $.post(urlCompleta, JSON.stringify({ df_usuario_usuario: $('#usuario').val() }), function(response) {
            if (response.data.length > 0) {
                $('#span_usuario').show('slow');
                $('#guardar').prop('disabled', true);
            } else {
                $('#span_usuario').hide('slow');
                $('#guardar').prop('disabled', false);
            }
        });
    }, 100);
}

function detallarPersonal(documento) {
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