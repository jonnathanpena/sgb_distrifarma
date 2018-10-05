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
    $('#ruc').hide();
    $('#pasaporte').hide();
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
    $('#guardar_cliente').attr('disabled', false);
    $('#editarCliente').attr('disabled', false);
    clearTimeout(timer);
    timer = setTimeout(function() {
        cargar();
    }, 1000);
    consultarSectores();
}

function cargar() {
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'cliente/getAll.php';
    var q = $('#q').val();
    $.post(urlCompleta, JSON.stringify({ df_nombre_cli: q }), function(data, status, xhr) {
        if (data.data.length > 0) {
            $('#resultados .table-responsive table tbody').html('');
            data.data.sort(function (a, b){
                return (b.df_id_cliente - a.df_id_cliente)
              });
            records = data.data;
            totalRecords = records.length;
            totalPages = Math.ceil(totalRecords / recPerPage);
            apply_pagination();
            /*$.each(data.data, function(index, row) {
                $('#resultados .table-responsive table tbody').append('<tr><td>' + row.df_codigo_cliente + '</td><td>' + row.df_tipo_documento_cli + '</td><td>' + row.df_documento_cli + '</td><td>' + row.df_nombre_cli + '</td><td>' + row.df_razon_social_cli + '</td><td>' + '<span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_id_cliente + '`)"><i class="glyphicon glyphicon-edit"></i> </a></span></td></tr>');
            })*/
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
        $('#resultados .table-responsive table tbody').append('<tr><td>' + row.df_codigo_cliente + '</td><td>' + row.df_tipo_documento_cli + '</td><td>' + row.df_documento_cli + '</td><td>' + row.df_nombre_cli + '</td><td>' + row.df_razon_social_cli + '</td><td>'  + row.df_direccion_cli + '</td><td>' + '<span class="pull-right"><a href="#" class="btn btn-default" title="Detallar" onclick="detallar(`' + row.df_id_cliente + '`)"><i class="glyphicon glyphicon-edit"></i> </a></span></td></tr>');
    });
}

$('#guardar_cliente').submit(function(event) {
    $('#guardar_cliente').attr('disabled', true);
    event.preventDefault();
    var documento = "";
    if ($('#tipo_documento').val() == 'null' || $('#sector').val() == 'null') {
        alertar('warning', '¡Alerta!', 'Tipo de documento y sector son campos obligatorios');
        $('#guardar_cliente').attr('disabled', false);
    } else {
        switch ($('#tipo_documento').val()) {
            case 'Cedula':
                documento = $('#documento').val();
                break;

            case 'RUC':
                documento = $('#ruc').val();
                break;

            case 'Pasaporte':
                documento = $('#pasaporte').val();
                break;
        }
        if (documento == '') {
            alertar('warning', '¡Alerta!', 'No debe quedar ningún campo vacío');
            $('#guardar_cliente').attr('disabled', false);
        } else {
            getCodigo(documento);
        }
    }
});

function insertar(documento, codigo) {
    var cliente = {
        df_codigo_cliente: codigo,
        df_nombre_cli: $('#nombre').val(),
        df_razon_social_cli: $('#razon_social').val(),
        df_tipo_documento_cli: $('#tipo_documento').val(),
        df_documento_cli: documento,
        df_direccion_cli: $('#direccion').val(),
        df_referencia_cli: $('#referencia').val(),
        df_sector_cod: $('#sector').val(),
        df_email_cli: $('#email').val(),
        df_telefono_cli: $('#telefono').val(),
        df_celular_cli: $('#celular').val()
    };
    var urlCompleta = url + 'cliente/insert.php';
    $.post(urlCompleta, JSON.stringify(cliente), function(data, status, hrx) {
        if (data == true) {
            alertar('success', '¡Éxito!', 'Cliente registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Ocurrió un problema, por favor, intente de nuevo');
        }
        $('#nombre').val('');
        $('#razon_social').val('');
        $('#tipo_documento').val('');
        $('#direccion').val('');
        $('#referencia').val('');
        $('#sector').val('null');
        $('#email').val('');
        $('#telefono').val('');
        $('#celular').val('');
        $('#tipo_documento').val('null');
        $('#documento').val('');
        $('#ruc').val('');
        $('#pasaporte').val('');
        $('#nuevoCliente');
        $('#nuevoCliente').modal('hide');
        load();
    });
}

function getCodigo(documento) {
    var urlCompleta = url + 'cliente/getIdMax.php';
    $.get(urlCompleta, function(data) {
        if (data.data[0].df_id_cliente == null) {
            insertar(documento, 'CLI-001');
        } else {
            var codigo = "";
            if (data.data[0].df_id_cliente >= 0 && data.data[0].df_id_cliente < 10) {
                codigo = 'CLI-00' + ((data.data[0].df_id_cliente * 1) + 1);
            } else if (data.data[0].df_id_cliente > 9 && data.data[0].df_id_cliente < 100) {
                codigo = 'CLI-0' + ((data.data[0].df_id_cliente * 1 ) + 1);
            } else if (data.data[0].df_id_cliente > 99) {
                codigo = 'CLI-' + ((data.data[0].df_id_cliente * 1) + 1);
            }
            insertar(documento, codigo);
        }
    });
}

$('#tipo_documento').change(function() {
    switch ($('#tipo_documento').val()) {
        case 'null':
            $('#documento').show();
            $('#ruc').hide();
            $('#pasaporte').hide();
            break;

        case 'Cedula':
            $('#documento').show();
            $('#ruc').hide();
            $('#pasaporte').hide();
            break;

        case 'RUC':
            $('#documento').hide();
            $('#ruc').show();
            $('#pasaporte').hide();
            break;

        case 'Pasaporte':
            $('#documento').hide();
            $('#ruc').hide();
            $('#pasaporte').show();
            break;
    }
});

function detallar(id) {
    var urlCompleta = url + 'cliente/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_cliente: id }), function(data, status, hrx) {
        console.log('Detalle de Cliente para editar: ',data);
        $('#editTipo_documento').val(data.data[0].df_tipo_documento_cli);
        $('#editDocumento').val(data.data[0].df_documento_cli);
        $('#editRuc').val(data.data[0].df_documento_cli);
        $('#editPasaporte').val(data.data[0].df_documento_cli);
        $('#editNombre').val(data.data[0].df_nombre_cli);
        $('#editRazon_social').val(data.data[0].df_razon_social_cli);
        $('#editDireccion').val(data.data[0].df_direccion_cli);
        $('#editReferencia').val(data.data[0].df_referencia_cli);
        $('#editSector').val(data.data[0].df_sector_cod);
        $('#editEmail').val(data.data[0].df_email_cli);
        $('#editTelefono').val(data.data[0].df_telefono_cli);
        $('#editCelular').val(data.data[0].df_celular_cli);
        $('#editCodigo').val(data.data[0].df_codigo_cliente);
        $('#id').val(id);
        $('#editarCliente').modal('show');
        if (data.data[0].df_tipo_documento_cli == 'Pasaporte') {
            $('#editDocumento').hide();
            $('#editRuc').hide();
            $('#editPasaporte').show();
        } else if (data.data[0].df_tipo_documento_cli == 'Cedula') {
            $('#editDocumento').show();
            $('#editRuc').hide();
            $('#editPasaporte').hide();
        } else if (data.data[0].df_tipo_documento_cli == 'RUC') {
            $('#editDocumento').hide();
            $('#editRuc').show();
            $('#editPasaporte').hide();
        }
    });
}

$('#editTipo_documento').change(function() {
    var valor = $('#editTipo_documento').val();
    if (valor == 'null') {
        $('#editDocumento').show();
        $('#editRuc').hide();
        $('#editPasaporte').hide();
    } else if (valor = 'Documento') {
        $('#editDocumento').show();
        $('#editRuc').hide();
        $('#editPasaporte').hide();
    } else if (valor == 'Pasaporte') {
        $('#editDocumento').hide();
        $('#editRuc').hide();
        $('#editPasaporte').show();
    } else if (valor == 'RUC') {
        $('#editDocumento').hide();
        $('#editRuc').show();
        $('#editPasaporte').hide();
    }
});

$('#editarCliente').submit(function(event) {
    $('#editarCliente').attr('disabled', true);
    event.preventDefault();
    var documento = '';
    if ($('#editTipo_documento').val() == 'null' || $('#editSector').val() == 'null') {
        alertar('warning', '¡Alerta!', 'Ningún campo debe quedar vacío');
        $('#editarCliente').attr('disabled', false);
    } else {
        switch ($('#editTipo_documento').val()) {
            case 'Cedula':
                documento = $('#editDocumento').val();
                break;

            case 'RUC':
                documento = $('#editRuc').val();
                break;

            case 'Pasaporte':
                documento = $('#editPasaporte').val();
                break;
        }
    }
    if (documento == '') {
        alertar('warning', '¡Alerta!', 'No debe quedar ningún campo vacío');
        $('#editarCliente').attr('disabled', false);
    } else {
        var datos = {
            df_codigo_cliente: $('#editCodigo').val(),
            df_nombre_cli: $('#editNombre').val(),
            df_razon_social_cli: $('#editRazon_social').val(),
            df_tipo_documento_cli: $('#editTipo_documento').val(),
            df_documento_cli: documento,
            df_direccion_cli: $('#editDireccion').val(),
            df_referencia_cli: $('#editReferencia').val(),
            df_sector_cod: $('#editSector').val(),
            df_email_cli: $('#editEmail').val(),
            df_telefono_cli: $('#editTelefono').val(),
            df_celular_cli: $('#editCelular').val(),
            df_id_cliente: $('#id').val()
        };
        update(datos);
    }
});

function update(cliente) {
    var urlCompleta = url + 'cliente/update.php';
    $.post(urlCompleta, JSON.stringify(cliente), function(data, status, hrx) {
        if (data == true) {
            alertar('success', '¡Éxito!', 'Cliente modificado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Problema al modificar, por favor, verifica la información e intenta nuevamente');
        }
        $('#editarCliente').modal('hide');
        load();
    });
}

function nuevoCliente() {
    $('#span_documento').hide('slow');
    consultarSectores();
}

function getByRUC() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        var urlCompleta = url + 'cliente/getByRUC.php';
        if ($('#tipo_documento').val() == 'Cedula') {
            $.post(urlCompleta, JSON.stringify({ df_documento_cli: $('#documento').val() }), function(response) {
                console.log('cedula', response);
                if (response.data.length > 0) {
                    $('#span_documento').show('slow');
                    $('#guardar').prop('disabled', true);
                } else {
                    $('#span_documento').hide('slow');
                    $('#guardar').prop('disabled', false);
                }
            });
        } else if ($('#tipo_documento').val() == 'RUC') {
            $.post(urlCompleta, JSON.stringify({ df_documento_cli: $('#ruc').val() }), function(response) {
                console.log('ruc', response);
                if (response.data.length > 0) {
                    $('#span_documento').show('slow');
                    $('#guardar').prop('disabled', true);
                } else {
                    $('#span_documento').hide('slow');
                    $('#guardar').prop('disabled', false);
                }
            });
        } else if ($('#tipo_documento').val() == 'Pasaporte') {
            $.post(urlCompleta, JSON.stringify({ df_documento_cli: $('#pasaporte').val() }), function(response) {
                console.log('pasaporte', response);
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

function consultarSectores() {
    var urlCompleta = url + 'sector/getAll.php';
    $('#sector').empty();
    $('#sector').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row){
            $('#sector').append('<option value="'+ row.df_codigo_sector +'">'+ row.df_nombre_sector +'</option>');
        });
    });
    $('#editSector').empty();
    $('#editSector').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row){
            $('#editSector').append('<option value="'+ row.df_codigo_sector +'">'+ row.df_nombre_sector +'</option>');
        });
    });
}