var $pagination = $('#pagination'),
    totalRecords = 0,
    records = [],
    displayRecords = [],
    recPerPage = 10,
    page = 1,
    totalPages = 0;
var items = [];
var timer;

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

var saldo = 0;
var ingresos = [];
var egresos = [];
var estatus = false;
var currentdate;
var datetime;

function load() {
    $('#guardar_ingreso').attr('disabled', false);
    $('#guardar_egreso').attr('disabled', false);
    ingresos = [];
    egresos = [];
    saldo = 0;
    items = [];
    caja = [];
    valorLibro = 0;
    banco = 0;
    selectMovimientos();
    var urlCompleta = url + 'banco/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $('#saldo_banco').val(response.data[0].df_saldo_banco * 1);
            banco = response.data[0].df_saldo_banco * 1;
        }
    });
    $('#resultados .table-responsive table tbody').html('Cargando...');
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    $.get(urlCompleta, function(response) {
        console.log('response ', response.data);
        if (response.data.length > 0) {
            $('#saldo_caja').val('$' + response.data[0].df_saldo * 1);
            saldo = response.data[0].df_saldo * 1;
            caja = response.data;
            valorLibro = ($('#saldo_banco').val() * 1) + saldo;
            $('#valor_libro').val(valorLibro);
            console.log('valor inicial', valorLibro);

            console.log(caja);
            clearTimeout(timer);
            timer = setTimeout(function() {
                /* caja.sort(function (a, b){
                    return (a.df_fecha_gasto - b.df_fecha_gasto)
                    });*/
                caja.sort(function(a, b) {
                    if (a.df_fecha_gasto > b.df_fecha_gasto) {
                        return -1;
                    }
                    if (a.df_fecha_gasto < b.df_fecha_gasto) {
                        return 1;
                    }
                    return 0;
                });
                records = caja;
                totalRecords = records.length;
                totalPages = Math.ceil(totalRecords / recPerPage);
                apply_pagination();
            }, 1000);
        } else {
            $('#resultados .table-responsive table tbody').html('No se encontró ningín resultado');
        }
    });
}

function getEgresos() {
    var urlCompleta = url + 'cajaChicaGasto/getAll.php';
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            egresos = response.data;
            console.log('getEgresos ', egresos);
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
        console.log('llenar tabla: ', response.data);
        /*  response.data.sort(function(a,b) {
              if (a.df_fecha_gasto > b.df_fecha_gasto){
                  return -1;
              }
              if (a.df_fecha_gasto < b.df_fecha_gasto){
                  return 1;
              }
              return 0;
          })*/
        records = response.data;
        totalRecords = records.length;
        totalPages = Math.ceil(totalRecords / recPerPage);
        apply_pagination();
    });
}

var cajasChicas = [];

function generate_table() {
    $('#resultados .table-responsive table tbody').empty();
    $.each(displayRecords, function(index, row) {
            var tr;
            var f = row.df_fecha_gasto.split(' ')[0];
            var time = row.df_fecha_gasto.split(' ')[1];
            var dia = f.split('-')[2];
            var mes = f.split('-')[1];
            var ano = f.split('-')[0];
            var fecha = dia + '/' + mes + '/' + ano;
            tr = $('<tr/>');
            tr.append("<td>" + fecha + "</td>");
            tr.append("<td>" + row.df_usuario_usuario + "</td>");
            tr.append("<td>" + row.df_movimiento + "</td>");
            if (row.tipo == 'E') {
                tr.append("<td class='text-center'>$ 0.00</td>");
                tr.append("<td class='text-center'>$ " + row.df_gasto + "</td>");
            } else {
                tr.append("<td class='text-center'>$ " + row.df_gasto + "</td>");
                tr.append("<td class='text-center'>$ 0.00</td>");
            }
            tr.append("<td>$ " + row.df_saldo + "</td>");
            $('#resultados .table-responsive table tbody').append(tr);
        })
        /*cajasChicas = [];
        $('#resultados .table-responsive table tbody').empty();
        for (var i = 0; i < displayRecords.length; i++) {
            getUsuario(displayRecords[i]);
        }
        clearTimeout(timer);
        timer = setTimeout(function(){
            console.log('generate table timer', cajasChicas);
            cajasChicas.sort(function (a, b){
                return (b.fecha - a.fecha)
              });
            poblarTabla();
        }, 1000);*/
}

function poblarTabla() {
    $.each(cajasChicas, function(index, row) {
        console.log('poblartbla');
        tr = $('<tr/>');
        tr.append("<td>" + row.fecha + "</td>");
        tr.append("<td>" + row.personal + "</td>");
        tr.append("<td>" + row.movimiento + "</td>");
        if (row.tipo == 'E') {
            tr.append("<td class='text-center'>$ 0.00</td>");
            tr.append("<td class='text-center'>$ " + row.gasto + "</td>");
        } else {
            tr.append("<td class='text-center'>$ " + row.gasto + "</td>");
            tr.append("<td class='text-center'>$ 0.00</td>");
        }
        tr.append("<td class='text-center'>$ " + row.saldo + "</td>");
        //tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallarEgreso(" + row.df_id_gasto + ",`" + row.tipo + "`, `"+ row.df_movimiento +"`)'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function getUsuario(row) {
    var urlCompleta = url + 'usuario/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_usuario: row.df_usuario_id }), function(response) {
        if (response.data[0].df_nombre_usuario == null) {
            row.df_personal_cod = response.data[0].df_personal_cod;
            getPersonal(row);
        } else {
            var f = row.df_fecha_gasto.split(' ')[0];
            var dia = f.split('-')[2];
            var mes = f.split('-')[1];
            var ano = f.split('-')[0];
            var fecha = dia + '/' + mes + '/' + ano;
            cajasChicas.push({
                fecha: fecha,
                personal: response.data[0].df_usuario_usuario, //response.data[0].df_nombre_usuario + ' ' + response.data[0].df_apellido_usuario,
                tipo: row.tipo,
                movimiento: row.df_movimiento,
                gasto: Number(row.df_gasto).toFixed(2),
                saldo: Number(row.df_saldo).toFixed(2)
            });
        }
    });
}

function getPersonal(row) {
    var urlCompleta = url + 'personal/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_personal: row.df_personal_cod }), function(response) {
        var tr;
        var f = row.df_fecha_gasto.split(' ')[0];
        var dia = f.split('-')[2];
        var mes = f.split('-')[1];
        var ano = f.split('-')[0];
        var fecha = dia + '/' + mes + '/' + ano;
        cajasChicas.push({
            fecha: fecha,
            personal: response.data[0].df_nombre_per + ' ' + response.data[0].df_apellido_per,
            tipo: row.tipo,
            movimiento: row.df_movimiento,
            gasto: Number(row.df_gasto).toFixed(2),
            saldo: Number(row.df_saldo).toFixed(2)
        });
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
            tr.append("<td>" + item.df_fecha_gasto.split(' ')[0] + "</td>");
            tr.append("<td>" + response.data[0].df_usuario_usuario + "</td>");
            tr.append("<td>" + item.df_movimiento + "</td>");
            tr.append("<td class='text-center'>$ 0.00</td>");
            tr.append("<td class='text-center'>$ " + Number(item.df_gasto).toFixed(2) + "</td>");
            tr.append("<td class='text-center'>$ " + Number(item.df_saldo).toFixed(2) + "</td>");
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
            tr.append("<td>" + item.df_fecha_ingreso.split(' ')[0] + "</td>");
            tr.append("<td>" + response.data[0].df_usuario_usuario + /* response.data[0].df_nombre_usuario + ' ' + response.data[0].df_apellido_usuario +*/ "</td>");
            tr.append("<td>Ingreso</td>");
            tr.append("<td class='text-center'> $ " + Number(item.df_valor_cheque).toFixed(2) + "</td>");
            tr.append("<td class='text-center'>$ 0.00</td>");
            tr.append("<td class='text-center'>$ " + Number(item.df_saldo_cc).toFixed(2) + "</td>");
            tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_gasto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
            $('#resultados .table-responsive table tbody').append(tr);
        }
    });
}

function insertarPersonalEnTablaEgreso(item, personalId) {
    var urlCompleta = url + 'usuario/getById.php'; // 'personal/getById.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_id_personal: personalId }), function(response) {
        tr = $('<tr/>');
        tr.append("<td>" + item.df_id_gasto + "</td>");
        tr.append("<td>" + item.df_fecha_gasto.split(' ')[0] + "</td>");
        tr.append("<td>" + response.data[0].df_usuario_usuario + "</td>");
        tr.append("<td>" + item.df_movimiento + "</td>");
        tr.append("<td class='text-center'>$ 0.00</td>");
        tr.append("<td class='text-center'> $ " + Number(item.df_gasto).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ " + Number(item.df_saldo).toFixed(2) + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_gasto + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function insertarPersonalEnTablaIngreso(item, personalId) {
    var urlCompleta = url + 'usuario/getById.php'; //'personal/getById.php';
    var tr;
    $.post(urlCompleta, JSON.stringify({ df_id_personal: personalId }), function(response) {
        tr = $('<tr/>');
        tr.append("<td>" + item.df_id_ingreso_cc + "</td>");
        tr.append("<td>" + item.df_fecha_ingreso.split(' ')[0] + "</td>");
        tr.append("<td>" + response.data[0].df_usuario_usuario + "</td>");
        tr.append("<td>Ingreso</td>");
        tr.append("<td class='text-center'> $ " + Number(item.df_valor_cheque).toFixed(2) + "</td>");
        tr.append("<td class='text-center'> $ 0.00</td>");
        tr.append("<td class='text-center'> $ " + Number(item.df_saldo_cc).toFixed(2) + "</td>");
        tr.append("<td><button class='btn btn-default pull-right' title='Detallar' onclick='detallar(" + item.df_id_ingreso_cc + ")'><i class='glyphicon glyphicon-edit'></i></button></td>");
        $('#resultados .table-responsive table tbody').append(tr);
    });
}

function nuevoGasto() {
    document.getElementById("valor_egreso").max = saldo;
    $('#nuevoEgreso').modal('show');
    $('#saldoCC').val(saldo);
    $('#usuario_egreso').html('');
    $('#usuario_egreso').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
    $('#movimiento').empty();
    $('#movimiento').append('<option value="null" selected>Seleccione...</option>');
    var urlCompleta = url + 'catMovimiento/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            if (row.df_tipo == 'E') {
                $('#movimiento').append('<option value="' + row.df_nombre_movimiento + '">' + row.df_nombre_movimiento + '</option>')
            }
        });
    });
}

function nuevoIngreso() {
    $('#nuevoIngreso').modal('show');
    $('#saldo_ingresoCC').val(saldo);
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '">' + usuario.df_usuario_usuario + '</option>');
}

function calcularIngreso() {
    var ingresa = $('#valor').val() * 1;
    var aFavor = saldo + ingresa;
    $('#saldo_ingresoCC').val(aFavor.toFixed(2));
}

function calcularEgreso() {
    var egreso = $('#valor_egreso').val() * 1;
    var aFavor = saldo - egreso;
    $('#saldoCC').val(aFavor.toFixed(2));
}

$('#guardar_ingreso').submit(function(event) {
    $('#guardar_ingreso').attr('disabled', true);
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
        df_saldo_cc: $('#saldo_ingresoCC').val()
    };
    var egresoBanco = {
        df_fecha_banco: datetime,
        df_usuario_id_banco: $('#usuario').val(),
        df_tipo_movimiento: "Egreso",
        df_monto_banco: $('#valor').val(),
        df_saldo_banco: ($('#saldo_banco').val() * 1) - ($('#valor').val() * 1),
        df_num_documento_banco: $('#documento').val(),
        df_detalle_mov_banco: "Ingreso a Caja Chica"
    };
    var egresoLibro = {
        df_fuente_ld: 'Banco',
        df_valor_inicial_ld: $('#valor_libro').val(),
        df_fecha_ld: datetime,
        df_descipcion_ld: "Fondos para ingreso de Caja Chica",
        df_ingreso_ld: 0,
        df_egreso_ld: $('#valor').val(),
        df_usuario_id_ld: $('#usuario').val()
    };
    var ingresoLibro = {
        df_fuente_ld: 'Caja Chica',
        df_valor_inicial_ld: $('#valor_libro').val(),
        df_fecha_ld: datetime,
        df_descipcion_ld: "Ingreso a Caja Chica",
        df_ingreso_ld: $('#valor').val(),
        df_egreso_ld: 0,
        df_usuario_id_ld: $('#usuario').val()
    };
    insertEgresoLibro(egresoLibro);
    insertIngresoLibro(ingresoLibro);
    insertEgresoBanco(egresoBanco);
    insertIngreso(ingreso);
});

function insertIngresoLibro(ingresoLibro) {
    var urlCompleta = url + 'libroDiario/insert.php';
    console.log('insert ingreso de CC en libro diario');
    $.post(urlCompleta, JSON.stringify(ingresoLibro), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Ingreso de Caja Chica en Libro Diario registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

function insertEgresoLibro(egresoLibro) {
    var urlCompleta = url + 'libroDiario/insert.php';
    console.log('insert egreso de BANCO en libro diario');
    $.post(urlCompleta, JSON.stringify(egresoLibro), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Egreso de Banco en Libro Diario registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

function insertEgresoBanco(egresoBanco) {
    var urlCompleta = url + 'banco/insert.php';
    console.log('egreso banco ', egresoBanco);
    $.post(urlCompleta, JSON.stringify(egresoBanco), function(response) {
        if (response != false) {
            alertar('success', '¡Éxito!', 'Egreso de Banco registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
        $('#movimiento').val('');
        $('#documento_egreso').val('');
        $('#valor_egreso').val('');
        $('#nuevoEgreso').modal('hide');
    });
}

function insertIngreso(ingreso) {
    var urlCompleta = url + 'cajaChicaIngreso/insert.php';
    $.post(urlCompleta, JSON.stringify(ingreso), function(response) {
        if (response == true) {
            alertar('success', '¡Éxito!', 'Ingreso Caja Chica registrado exitosamente');
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
    if ($('#movimiento').val() == 'null') {
        alertar('warning', '¡Advertencia!', 'Todos los campos son obligatorios');
    } else {
        $('#guardar_egreso').attr('disabled', true);
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
            df_saldo: $('#saldoCC').val(),
            df_fecha_gasto: datetime,
            df_num_documento: $('#documento_egreso').val(),
            df_ingreso_id: ingreso_id
        };
        var egresoLibro = {
            df_fuente_ld: 'Caja Chica',
            df_valor_inicial_ld: $('#valor_libro').val(),
            df_fecha_ld: datetime,
            df_descipcion_ld: $('#movimiento').val(),
            df_ingreso_ld: 0,
            df_egreso_ld: $('#valor_egreso').val(),
            df_usuario_id_ld: $('#usuario_egreso').val(),
        };
        insertEgresoLibroCC(egresoLibro);
        insertEgreso(egreso);
    }
});

function insertEgresoLibroCC(egresoLibro) {
    var urlCompleta = url + 'libroDiario/insert.php';
    console.log('insert egreso de CC en libro diario', egresoLibro);
    $.post(urlCompleta, JSON.stringify(egresoLibro), function(response) {
        if (response != false) {
            console.log('response', response);
            alertar('success', '¡Éxito!', 'Egreso de Caja Chica en Libro Diario registrado exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Error al insertar, verifique que todo está bien e intente de nuevo');
        }
    });
}

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
    selectMovimientos();
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

function selectMovimientos() {
    var urlCompleta = url + 'cajaChicaGasto/getAutocomplete.php';
    $.get(urlCompleta, function(response) {
        console.log('opciones', response);
        localStorage.setItem('distrifarma_autocomplete_caja', JSON.stringify(response.data));
    });
}

var opciones = {
    data: JSON.parse(localStorage.getItem('distrifarma_autocomplete_caja'))
};


$('#movimiento').easyAutocomplete(opciones);
$('div .easy-autocomplete').removeAttr("style");