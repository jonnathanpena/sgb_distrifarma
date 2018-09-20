var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var items = [];

$(document).ready(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    if (usuario.ingreso == true) {
        $('#pasaporte').hide();
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

var saldo = 0;
var ingresos = [];
var egresos = [];
var estatus = false;
var currentdate;
var datetime;

function load() {
    ingresos = [];
    egresos = [];
    saldo = 0;
    items = [];
    var urlCompleta = url + 'cajaChicaIngreso/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            ingresos = response.data;
            estatus = true;
        }
        getEgresos();
    });
}

function getEgresos() {
    var urlCompleta = url + 'cajaChicaGasto/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            egresos = response.data;
            if (ingresos.length > 0) {
                var fecha_ingreso = new Date(ingresos[0].df_fecha_ingreso);
                var fecha_egreso = new Date(egresos[0].df_fecha_gasto);
                if (fecha_ingreso > fecha_egreso) {
                    saldo = ingresos[0].df_saldo_cc * 1;
                } else {
                    saldo = egresos[0].df_saldo * 1;
                }
            } else {
                saldo = egresos[0].df_saldo;
            }
        } else {
            if (ingresos.length > 0) {
                saldo = ingresos[0].df_saldo_cc * 1;
            }
        }
        $('#saldo_caja').val(saldo.toFixed(2));
        llenarTabla();
    });
}

function llenarTabla() {
    $('#resultados .table-responsive table tbody').empty();
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    $.get(urlCompleta, function(response) {
        records = response.data;
        totalRecords = records.length;
        totalPages = Math.ceil(totalRecords / recPerPage);
        apply_pagination();
    });
}

function generate_table() {
    $('#resultados .table-responsive table tbody').empty();
    for (var i = 0; i < displayRecords.length; i++) {
        getUsuario(displayRecords[i]);
    }
}

function getUsuario(row) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: row.df_usuario_id }), function(response) {
        if (response.data[0].df_nombre_usuario == null) {
            row.df_personal_cod = response.data[0].df_personal_cod;
            getPersonal(row);
        } else {
            var tr;
            var f = row.df_fecha_gasto.split(' ')[0];
            var time = row.df_fecha_gasto.split(' ')[1];
            var dia = f.split('-')[2];
            var mes = f.split('-')[1];
            var ano = f.split('-')[0];
            var hora = time.split(':')[0];
            var min = time.split(':')[1];
            var seg = time.split(':')[2];
            var fecha = dia + '/' + mes + '/' + ano + ' ' + hora + ':' + min + ':' + seg;
            tr = $('<tr/>');
            tr.append("<td>" + fecha + "</td>");
            tr.append("<td>" + response.data[0].df_nombre_usuario + ' ' + response.data[0].df_apellido_usuario + "</td>");
            tr.append("<td>" + row.df_movimiento + "</td>");
            if (row.tipo == 'E') {
                tr.append("<td class='text-center'>0.00</td>");
                tr.append("<td class='text-center'>" + Number(row.df_gasto).toFixed(2) + "</td>");
            } else {
                tr.append("<td class='text-center'>" + Number(row.df_gasto).toFixed(2) + "</td>");
                tr.append("<td class='text-center'>0.00</td>");
            }
            tr.append("<td class='text-center'>" + Number(row.df_saldo).toFixed(2) + "</td>");
            //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

function getPersonal(row) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: row.df_personal_cod }), function(response) {
        var tr;
        var f = row.df_fecha_gasto.split(' ')[0];
        var time = row.df_fecha_gasto.split(' ')[1];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var hora = time.split(':')[0];
        var min = time.split(':')[1];
        var seg = time.split(':')[2];
        var fecha = dia + '/' + mes + '/' + ano + ' ' + hora + ':' + min + ':' + seg;
        tr = $('<tr/>');
        tr.append("<td>" + fecha + "</td>");
        tr.append("<td>" + response.data[0].df_nombre_per + ' ' + response.data[0].df_apellido_per + "</td>");
        tr.append("<td>" + row.df_movimiento + "</td>");
        if (row.tipo == 'E') {
            tr.append("<td class='text-center'>0.00</td>");
            tr.append("<td class='text-center'>" + Number(row.df_gasto).toFixed(2) + "</td>");
        } else {
            tr.append("<td class='text-center'>" + Number(row.df_gasto).toFixed(2) + "</td>");
            tr.append("<td class='text-center'>0.00</td>");
        }
        tr.append("<td class='text-center'>" + Number(row.df_saldo).toFixed(2) + "</td>");
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallarEgreso(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function apply_pagination() {
    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
    endRec = (displayRecordsIndex) + recPerPage;
    displayRecords = records.slice(displayRecordsIndex, endRec);
    //generate_table();
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

function insertarTablaEgreso(item) {
    var tr;
    var idUsuario = 0;
    if (item.df_usuario_id) {
        idUsuario = item.df_usuario_id;
    } else {
        idUsuario = item.df_usuario_id_ingreso;
    }
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: idUsuario }), function(response) {
        if (response.data[0].df_personal_cod != null) {
            insertarPersonalEnTablaEgreso(item, response.data[0].df_personal_cod);
        } else {
            tr = $('<tr/>');
            tr.append("<td>" + item.df_id_gasto + "</td>");
            tr.append("<td>" + item.df_fecha_gasto + "</td>");
            tr.append("<td>" + response.data[0].df_nombre_usuario + ' ' + response.data[0].df_apellido_usuario + "</td>");
            tr.append("<td>" + item.df_movimiento + "</td>");
            tr.append("<td class='text-center'>0.00</td>");
            tr.append("<td class='text-center'>" + Number(item.df_gasto).toFixed(2) + "</td>");
            tr.append("<td class='text-center'>" + Number(item.df_saldo).toFixed(2) + "</td>");
            tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_gasto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

function insertarTablaIngreso(item) {
    var tr;
    var idUsuario = 0;
    if (item.df_usuario_id_ingreso) {
        idUsuario = item.df_usuario_id_ingreso;
    }
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: idUsuario }), function(response) {
        if (response.data[0].df_personal_cod != null) {
            insertarPersonalEnTablaIngreso(item, response.data[0].df_personal_cod);
        } else {
            tr = $('<tr/>');
            tr.append("<td>" + item.df_id_ingreso_cc + "</td>");
            tr.append("<td>" + item.df_fecha_ingreso + "</td>");
            tr.append("<td>" + response.data[0].df_nombre_usuario + ' ' + response.data[0].df_apellido_usuario + "</td>");
            tr.append("<td>Ingreso</td>");
            tr.append("<td class='text-center'>" + Number(item.df_valor_cheque).toFixed(2) + "</td>");
            tr.append("<td class='text-center'>0.00</td>");
            tr.append("<td class='text-center'>" + Number(item.df_saldo_cc).toFixed(2) + "</td>");
            tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_gasto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

function insertarPersonalEnTablaEgreso(item, personalId) {
    var urlCompleta = url + 'personal/getById.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_id_personal: personalId }), function(response) {
        tr = $('<tr/>');
        tr.append("<td>" + item.df_id_gasto + "</td>");
        tr.append("<td>" + item.df_fecha_gasto + "</td>");
        tr.append("<td>" + response.data[0].df_nombre_per + ' ' + response.data[0].df_apellido_per + "</td>");
        tr.append("<td>" + item.df_movimiento + "</td>");
        tr.append("<td class='text-center'>0.00</td>");
        tr.append("<td class='text-center'>" + Number(item.df_gasto).toFixed(2) + "</td>");
        tr.append("<td class='text-center'>" + Number(item.df_saldo).toFixed(2) + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_gasto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function insertarPersonalEnTablaIngreso(item, personalId) {
    var urlCompleta = url + 'personal/getById.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_id_personal: personalId }), function(response) {
        tr = $('<tr/>');
        tr.append("<td>" + item.df_id_ingreso_cc + "</td>");
        tr.append("<td>" + item.df_fecha_ingreso + "</td>");
        tr.append("<td>" + response.data[0].df_nombre_per + ' ' + response.data[0].df_apellido_per + "</td>");
        tr.append("<td>Ingreso</td>");
        tr.append("<td class='text-center'>" + Number(item.df_valor_cheque).toFixed(2) + "</td>");
        tr.append("<td class='text-center'>0.00</td>");
        tr.append("<td class='text-center'>" + Number(item.df_saldo_cc).toFixed(2) + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_ingreso_cc + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function nuevoGasto() {
    document.getElementById("valor_egreso").max = saldo;
    $('#nuevoEgreso').modal('show');
    $('#saldo').val(saldo);
    $('#usuario_egreso').html('');
    $('#usuario_egreso').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');

}

function nuevoIngreso() {
    $('#nuevoIngreso').modal('show');
    $('#saldo_ingreso').val(saldo);
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
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

$('#guardar_ingreso').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var ingreso = {
        df_fecha_ingreso: datetime,
        df_usuario_id_ingreso: $('#usuario').val(),
        df_num_cheque: $('#documento').val(),
        df_valor_cheque: $('#valor').val(),
        df_saldo_cc: $('#saldo_ingreso').val()
    };
    insertIngreso(ingreso);
});

function insertIngreso(ingreso) {
    var urlCompleta = url + 'cajaChicaIngreso/insert.php';
    $.post(urlCompleta, JSON.stringify(ingreso), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Ingreso registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#documento').val('');
        $('#valor').val('');
        $('#nuevoIngreso').modal('hide');
        load();
    });
}

$('#guardar_egreso').submit(function(event) {
    event.preventDefault();
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var ingreso_id = 0;
    if (ingresos.length > 0) {
        ingreso_id = ingresos[0].df_id_ingreso_cc;
    }
    var egreso = {
        df_usuario_id: $('#usuario_egreso').val(),
        df_movimiento: $('#movimiento').val(),
        df_gasto: $('#valor_egreso').val(),
        df_saldo: $('#saldo').val(),
        df_fecha_gasto: datetime,
        df_num_documento: $('#documento_egreso').val(),
        df_ingreso_id: ingreso_id
    };
    insertEgreso(egreso);
});

function insertEgreso(egreso) {
    var urlCompleta = url + 'cajaChicaGasto/insert.php';
    $.post(urlCompleta, JSON.stringify(egreso), function(response) {
        if (response == true) {
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
}

$('#tipo').change(function() {
    load();
});

function detallar(id, tipo, detalle) {
    alert('id ' + id + ' tipo ' + tipo + ' detale ' + detalle);
    $('#editarCajaChica').modal('show');
    $('#editDetalle').val(detalle);
}

/*function detallarIngreso(id) {
    var urlCompleta = url + 'cajaChicaIngreso/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_ingreso_cc: id }), function(response) {
        if (response.data.length > 0) {
            $('#editarIngreso').modal('show');
            consultarUsuarioEditarIngreso(response.data[0]);
        } else {
            alertar('danger', '¡Error!', 'Por favor, verifique su conexión a internet, e intente nuevamente');
        }
    });
}

function consultarUsuarioEditarIngreso(ingreso) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: ingreso.df_usuario_id_ingreso }), function(response) {
        $('#editarIngresoUsuario').val(response.data[0].)
    });
}*/