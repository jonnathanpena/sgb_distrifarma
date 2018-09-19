var usuario = '';
var timer;

$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    if (usuario.ingreso == true) {
        if (usuario.df_tipo_usuario == 'Administrador') {
            $('#administrador').show('');
            $('#ventas').hide('');
        } else {
            $('#administrador').hide('');
            $('#ventas').show('');
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
    var q = $('#q').val();
    $('#resultados .table-responsive table tbody').html('Cargando...');
    $.post(url + 'proveedor/getAll.php', JSON.stringify({ df_nombre_empresa: q }), function(data, status, hrx) {
        if (data.data.length > 0) {
            $('#resultados .table-responsive table tbody').html('');
            $.each(data.data, function(index, row) {
                $('#resultados .table-responsive table tbody').append('<tr> <td>' + row.df_codigo_proveedor + '</td> <td>' + row.df_documento_prov + '</td> <td>' + row.df_nombre_empresa + '</td> <td>' + row.df_tlf_empresa + '</td> <td>' + row.df_nombre_contacto + '</td> <td>' + row.df_tlf_contacto + '</td> <td><span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_codigo_proveedor + '`, `' + row.df_documento_prov + '` )"><i class="glyphicon glyphicon-edit"></i></a></span></td> </tr>');
            })
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningún resultado');
        }
    });
}

$('#guardar_proveedor').submit(function(event) {
    event.preventDefault();
    var data = {
        df_codigo_proveedor: "",
        df_nombre_empresa: $('#nombre').val(),
        df_tlf_empresa: $('#telefono').val(),
        df_direccion_empresa: $('#direccion').val(),
        df_nombre_contacto: $('#nombre_contacto').val(),
        df_tlf_contacto: $('#telefono_contacto').val(),
        df_documento_prov: $('#ruc').val()
    };
    insertar(data);
});

function insertar(proveedor) {
    var codigo = "";
    $.get(url + 'proveedor/getIdMax.php', function(data) {
        if (data.data[0].df_id_proveedor == null) {
            codigo = 'PROV-' + '001';
        } else if (data.data[0].df_id_proveedor > 0 && data.data[0].df_id_proveedor < 10) {
            codigo = 'PROV-' + '00' + data.data[0].df_id_proveedor;
        } else if (data.data[0].df_id_proveedor > 9 && data.data[0].df_id_proveedor < 100) {
            codigo = 'PROV-' + '0' + data.data[0].df_id_proveedor;
        } else if (data.data[0].df_id_proveedor > 99 && data.data[0].df_id_proveedor < 1000) {
            codigo = 'PROV-' + data.data[0].df_id_proveedor;
        }
        proveedor.df_codigo_proveedor = codigo;
        var urlCompleta = url + 'proveedor/insert.php';
        console.log('proveedor', proveedor);
        $.post(urlCompleta, JSON.stringify(proveedor), function(datos, status, xhr) {
            if (datos == false) {
                alertar('danger', '¡Error!', 'Por favor intente de nuevo, si persiste, contacte al administrador del sistema');
            } else {
                alertar('success', '¡Éxito!', '¡Proveedor insertado exitosamente!');
            }
            $('#nombre').val("");
            $('#telefono').val("");
            $('#direccion').val('');
            $('#nombre_contacto').val('');
            $('#telefono_contacto').val('');
            $('#ruc').val('');
            $('#nuevoProveedor').modal('hide');
            load();
        });
    });
}

function detallar(codigo, documento) {
    var urlCompleta = url + 'proveedor/getById.php';
    $.post(urlCompleta, JSON.stringify({
        df_codigo_proveedor: codigo,
        df_documento_prov: documento
    }), function(data, status, xhr) {
        $('#editarProveedor').modal('show');
        $('#editNombre').val(data.data[0].df_nombre_empresa);
        $('#editTelefono').val(data.data[0].df_tlf_empresa);
        $('#editDireccion').val(data.data[0].df_direccion_empresa);
        $('#editNombre_contacto').val(data.data[0].df_nombre_contacto);
        $('#editTelefono_contacto').val(data.data[0].df_tlf_contacto);
        $('#editRuc').val(data.data[0].df_documento_prov);
        $('#codigo').val(data.data[0].df_codigo_proveedor);
        $('#id').val(data.data[0].df_id_proveedor);
    });
}

$('#modificar_proveedor').submit(function(event) {
    event.preventDefault();
    var data = {
        df_codigo_proveedor: $('#codigo').val(),
        df_nombre_empresa: $('#editNombre').val(),
        df_tlf_empresa: $('#editTelefono').val(),
        df_direccion_empresa: $('#editDireccion').val(),
        df_nombre_contacto: $('#editNombre_contacto').val(),
        df_tlf_contacto: $('#editTelefono_contacto').val(),
        df_documento_prov: $('#editRuc').val(),
        df_id_proveedor: $('#id').val()
    };
    modificar(data);
});

function modificar(proveedor) {
    var urlCompleta = url + 'proveedor/update.php';
    $.post(urlCompleta, JSON.stringify(proveedor), function(data, status, xhr) {
        if (data == true) {
            alertar('success', '¡Éxito!', '¡Proveedor insertado exitosamente!');
        } else {
            alertar('danger', '¡Error!', 'Por favor intente de nuevo, si persiste, contacte al administrador del sistema');
        }
        $('#editarProveedor').modal('hide');
        load();
    });
}

function consultarProveedor() {
    $('#resultados .table-responsive table tbody').html('');
    var urlCompleta = url + 'proveedor/getById.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({
        df_codigo_proveedor: q,
        df_documento_prov: q
    }), function(data, status, xhr) {
        $.each(data.data, function(index, row) {
            $('#resultados .table-responsive table tbody').append('<tr> <td>' + row.df_codigo_proveedor + '</td> <td>' + row.df_documento_prov + '</td> <td>' + row.df_nombre_empresa + '</td> <td>' + row.df_tlf_empresa + '</td> <td>' + row.df_nombre_contacto + '</td> <td>' + row.df_tlf_contacto + '</td> <td><span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_codigo_proveedor + '`, `' + row.df_documento_prov + '` )"><i class="glyphicon glyphicon-edit"></i></a></span></td> </tr>');
        })
    });
}

function nuevoProveedor() {
    $('#span_ruc').hide();
}

function consultarRUC() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        var urlCompleta = url + 'proveedor/getByRuc.php';
        $.post(urlCompleta, JSON.stringify({ df_documento_prov: $('#ruc').val() }), function(response) {
            if (response.data.length > 0) {
                $('#span_ruc').show('show');
                $('#guardar').prop('disabled', true);
            } else {
                $('#span_ruc').hide('slow');
                $('#guardar').prop('disabled', false);
            }
        });
    }, 1000);
}