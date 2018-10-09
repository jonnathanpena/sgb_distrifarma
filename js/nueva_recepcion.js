var productos = [];
var timer;
var fecha_entrega = '';
var detalles = [];
var devueltas = [];
var facturas = [];
var guiasEntrega = [];
var guiasRemision = [];
var timer;
var currentdate, datetime;
var guiaRemision = {};
var guiaEntrega = {};
var detallesEntrega = [];
var detalleFactura = [];

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
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '" selected>' + usuario.df_usuario_usuario + '</option>');
    $('#personal').empty();
    consultarPersonal();
    detalles = [];
    devueltas = [];
    guiasEntrega = [];
    guiasRemision = [];
    $('#seleccionGuiaEntrega').hide();
    $('#seleccionGuiaRemision').hide();
    $('.num_guia_ent').hide();
    $('.num_guia_rem').hide();
}

function consultarPersonal() {
    detalles = [];
    devueltas = [];
    var urlCompleta = url + 'personal/getAll.php';
    $('#repartidor').append('<option value="null">Seleccione...</option>');
    $.get(urlCompleta, function(response) {
        if (response.data.length > 0) {
            $.each(response.data, function(index, row) {
                $('#repartidor').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
            })
        }
    });
}

function cambioTipoGuia() {
    var tipo = $('#tipo_guia').val();
    if (tipo == 'null') {
        alertar('warning', '¡Alerta!', 'Debe escoger un tipo de guía válido');
        $('#seleccionGuiaEntrega').hide('slow');
    } else if (tipo == 'Entrega') {
        getEntregaPendiente();
    } else if (tipo == 'Remision') {
        getRemisionPendiente();
    }
}

function getEntregaPendiente() {
    getSectores();
    getPersonal();
    var urlCompleta = url + 'guiaRecepcion/getPendienteEntrega.php';
    $('#num_guia_entrega').empty();
    $('#num_guia_entrega').append('<option value="null">Selecccione...</option>');
    $.get(urlCompleta, function(response) {
        console.log('entregapendiente', response);
        if (response.data.length > 0) {
            guiasEntrega = response.data;
            $('#seleccionGuiaEntrega').show('slow');
            $('#seleccionGuiaRemision').hide('slow');
            $('.num_guia_ent').show('slow');
            $('.num_guia_rem').hide('slow');
            guiasEntrega = response.data;
            $.each(guiasEntrega, function(index, row) {
                var option = '<option value="' + row.df_num_guia_entrega + '">' + row.df_codigo_guia_ent + '</option>';
                $('#num_guia_entrega').append(option);
            });
        } else {
            alertar('warning', '¡Alerta!', 'No existe ninguna guía pendiente');
        }
    });
}

function getRemisionPendiente() {
    var urlCompleta = url + 'guiaRecepcion/getPendienteRemision.php';
    $('#num_guia_remision').empty();
    $.get(urlCompleta, function(response) {
        guiasRemision = response.data;
        if (guiasRemision.length > 0) {
            $('#seleccionGuiaEntrega').hide('slow');
            $('#seleccionGuiaRemision').show('slow');
            $('.num_guia_ent').hide('slow');
            $('.num_guia_rem').show('slow');
            $('#num_guia_remision').append('<option value="null">Seleccione...</option>');
            $.each(guiasRemision, function(index, row) {
                var opcion = '<option value="' + row.df_guia_remision + '">' + row.df_codigo_rem + '</option>';
                $('#num_guia_remision').append(opcion);
            });
        } else {
            alertar('warning', '¡Alerta!', 'No existen guías de remisión pendientes');
            $('#tipo_guia').val('null');
            $('#seleccionGuiaEntrega').hide('slow');
            $('#seleccionGuiaRemision').hide('slow');
            $('.num_guia_ent').hide('slow');
            $('.num_guia_rem').hide('slow');
        }
    });
}

function getSectores() {
    $('#sector_remision').empty();
    $('#sector_entrega').empty();
    var urlCompleta = url + 'sector/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#sector_remision').append('<option value="' + row.df_codigo_sector + '">' + row.df_nombre_sector + '</option>');
            $('#sector_entrega').append('<option value="' + row.df_codigo_sector + '">' + row.df_nombre_sector + '</option>');
        });
    });
}

function getPersonal() {
    $('#vendedor_remision').empty();
    $('#repartidor_entrega').empty();
    var urlCompleta = url + 'personal/getAll.php';
    $.get(urlCompleta, function(response) {
        $.each(response.data, function(index, row) {
            $('#vendedor_remision').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
            $('#repartidor_entrega').append('<option value="' + row.df_id_personal + '">' + row.df_nombre_per + ' ' + row.df_apellido_per + '</option>');
        });
    });
    if ($('#tipo_guia').val() == 'Remision') {
        getRemision();
    }
}

function cambioNumGuiaRemision() {
    $('#valor_recaudado').val('0.00');
    $('#valor_efectivo').val('0.00');
    $('#valor_cheque').val('0.00');
    $('#valor_retenciones').val('0.00');
    $('#valor_descuento').val('0.00');
    $('#diferencia').val('0.00');
    getSectores();
    getPersonal();
    var urlCompleta = url + 'detalleRemision/getById.php';
    $('#table_productos tbody').empty();
    $.post(urlCompleta, JSON.stringify({ df_guia_remision_detrem: $('#num_guia_remision').val() }), function(response) {
        $.each(response.data, function(index, row) {
            var total = Number(row.df_valor_sin_iva_detrem) * Number(row.df_iva_detrem);
            total = Number(total + Number(row.df_valor_sin_iva_detrem)).toFixed(2);
            var tr = $('<tr/>');
            tr.append('<td width="100" class="codigo">' + row.df_codigo_prod + '</td>');
            tr.append('<td class="producto">' + row.df_nombre_producto + '</td>');
            tr.append('<td width="100" class="unidad">' + row.df_nombre_und_detrem + '</td>');
            tr.append('<td width="100" class="cantidad">' + row.df_cant_producto_detrem + '</td>');
            tr.append('<td width="100" class="precio_unitario">' + Number(row.df_valor_sin_iva_detrem / row.df_cant_producto_detrem).toFixed(2) + '</td>');
            tr.append('<td width="100" class="total">' + total + '</td>');
            tr.append('<td width="120" class="vendidos"><input type="number" class="form-control" id="vendidos-' + row.df_codigo_prod + '" value="' + row.df_cant_producto_detrem + '" onkeyup="cambioVendidos(`' + row.df_codigo_prod + '`, `' + row.df_cant_producto_detrem + '`)"/></td>');
            tr.append('<td width="120" class="devueltos"><input type="number" class="form-control" id="devueltos-' + row.df_codigo_prod + '" value="0" disabled/></td>');
            tr.append('<td class="iva" style="display: none;">' + row.df_iva_detrem + '</td>');
            tr.append('<td class="id_producto" style="display: none;">' + row.df_id_producto + '</td>');
            $('#table_productos tbody').append(tr);
        });
        calcularRemision();
    });
}

function getRemision() {
    var urlCompleta = url + 'guiaRemision/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_guia_remision: $('#num_guia_remision').val() * 1 }), function(response) {
        console.log('Remision -', response.data);
        $('#fecha_remision').val(response.data[0].df_fecha_remision.split(' ')[0]);
        $('#vendedor_remision').val(response.data[0].df_vendedor_rem);
        $('#vendedor_remisionT').val(response.data[0].df_nombre_per + ' ' + response.data[0].df_apellido_per);
        $('#sector_remision').val(response.data[0].df_sector_cod_rem);
        $('#sector_remisionT').val(response.data[0].df_nombre_sector);
        guiaRemision = response.data[0];
    });
}

function cambioVendidos(codigo, cantidad) {
    var vendido = $('#vendidos-' + codigo).val() * 1;
    var cantidad_anterior = cantidad * 1;
    if (vendido <= cantidad_anterior) {
        var devueltos = Number(cantidad_anterior - vendido);
        $('#devueltos-' + codigo).val(devueltos);
        calcularRemision();
    } else {
        vendido = $('#vendidos-' + codigo).val(cantidad_anterior);
        calcularRemision();
    }
}

function calcularRemision() {
    var valor_recaudado = 0;
    var valor_efectivo = Number($('#valor_efectivo').val()).toFixed(2);
    var valor_cheque = Number($('#valor_efectivo').val()).toFixed(2);
    var valor_retenciones = Number($('#valor_retenciones').val()).toFixed(2);
    var valor_descuento = Number($('#valor_descuento').val()).toFixed(2);
    var diferencia = 0;
    $('#table_productos tbody tr').each(function(a, b) {
        var iva = $('.iva', b).text() * 1;
        var precio_unitario = $('.precio_unitario', b).text() * 1;
        var codigo = $('.codigo', b).text();
        var cantidad = $('#vendidos-' + codigo).val() * 1;
        var subtotal = precio_unitario * cantidad;
        var total_iva = subtotal * iva * 1;
        var total_tupla = subtotal + total_iva;
        valor_recaudado += total_tupla;
    });
    $('#valor_recaudado').val(Number(valor_recaudado).toFixed(2));
    var resto = Number(valor_efectivo) + Number(valor_cheque) + Number(valor_retenciones) + Number(valor_descuento);
    diferencia = Number(valor_recaudado - resto).toFixed(2);
    $('#diferencia').val(diferencia);
}

function restarRemision() {
    var valor_recaudado = $('#valor_recaudado').val() * 1;
    var valor_efectivo = $('#valor_efectivo').val() * 1;
    var valor_cheque = $('#valor_cheque').val() * 1;
    var valor_retenciones = $('#valor_retenciones').val() * 1;
    var valor_descuento = $('#valor_descuento').val() * 1;
    var diferencia = valor_recaudado - valor_efectivo - valor_cheque - valor_retenciones - valor_descuento;
    if (diferencia == -0) {
        diferencia = 0;
    }
    $('#diferencia').val(Number(diferencia).toFixed(2));
}

$('#btn-guardar').click(function() {
    if ($('#tipo_guia').val() == 'null') {
        alertar('warning', '¡Alerta!', 'Debe seleccionar un tipo de guía');
        clearTimeout(timer);
        timer = setTimeout(function() {
            window.location.reload();
        }, 2000);
    } else if ($('#tipo_guia').val() == 'Entrega') {
        comenzarInsertarEntrega();
    } else if ($('#tipo_guia').val() == 'Remision') {
        comensarInsertarRemision();
    }
});

function comensarInsertarRemision() {
    if ($('#diferencia').val() * 1 == 0) {
        crearObjetoRemision();
    } else {
        alertar('warning', '¡Alerta!', 'La diferencia debe ser igual a cero');
    }
}

function crearObjetoRemision() {
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var recepcion = {
        df_codigo_guia_rec: $('#num_guia_remision option:selected').text(),
        df_fecha_recepcion: datetime,
        df_repartidor_rec: $('#vendedor_remision').val(),
        df_valor_recaudado: $('#valor_recaudado').val(),
        df_valor_efectivo: $('#valor_efectivo').val(),
        df_valor_cheque: $('#valor_cheque').val(),
        df_retenciones: $('#valor_retenciones').val(),
        df_descuento_rec: $('#valor_descuento').val(),
        df_diferencia_rec: $('#diferencia').val(),
        df_remision_rec: 1,
        df_entrega_rec: 0,
        df_num_guia: $('#num_guia_remision').val(),
        df_creadoBy_rec: $('#usuario').val()
    };
    insertRemision(recepcion);

}

function insertRemision(recepcion) {
    var urlCompleta = url + 'guiaRecepcion/insert.php';
    console.log('remision', recepcion);
    $.post(urlCompleta, JSON.stringify(recepcion), function(response) {
        console.log('response insert remision', response);
        if (response == false || response == false) {
            alertar('danger', '¡Error!', 'Verifique su conexión a internet, e intente nuevamente');
        } else {
            updateRemision();
            generarDetalleGuia(response);
        }
    });
}

function updateRemision() {
    var urlCompleta = url + 'guiaRemision/update.php';
    guiaRemision.df_modificadoBy_rem = $('#usuario').val();
    guiaRemision.df_guia_rem_recibido = 1;
    $.post(urlCompleta, JSON.stringify(guiaRemision), function(response) {
        console.log('update', response);
    });
}

function generarDetalleGuia(id) {
    var inserto = true;
    $('#table_productos tbody tr').each(function(a, b) {
        var codigo = $('.codigo', b).text();
        var detalle = {
            df_guia_recepcion_detrec: id,
            df_factura_rec: 0,
            df_cant_producto_detrec: $('.cantidad', b).text(),
            df_producto_cod_detrec: $('.id_producto', b).text(),
            df_nueva_fecha: '',
            df_detalleRemision_detrec: $('.unidad', b).text() + '-' + $('.precio_unitario', b).text() + '-' + $('.total', b).text() + '-' + $('#vendidos-' + codigo).val() + '-' + $('#devueltos-' + codigo).val(),
            df_edo_prod_fact_detrec: 0
        };
        var respuesta = insertDetelle(detalle);
        if (respuesta == false) {
            inserto = false;
        }
    });
    clearTimeout(timer);
    setTimeout(function() {
        if (inserto == true) {
            alertar('success', '¡Éxito!', 'Guía registrada exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Compruebe su conexión a internet e intente nuevamente');
        }
        $('#tipo_guia').val('null');
        $('#seleccionGuiaEntrega').hide('slow');
        $('#seleccionGuiaRemision').hide('slow');
        $('.num_guia_ent').hide('slow');
        $('.num_guia_rem').hide('slow');
    }, 3000);
}

function insertDetelle(detalle) {
    var urlCompleta = url + 'detalleRecepcion/insert.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        if (response == false || response == 'false') {
            return false;
        }
    });
}

function cambioNumGuiaEntrega() {
    detalleFactura = [];
    var id_entrega = $('#num_guia_entrega').val();
    $.each(guiasEntrega, function(index, row) {
        if (row.df_num_guia_entrega == id_entrega) {
            guiaEntrega = row;
        }
    });
    console.log('guia entrega', guiaEntrega);
    $('#sector_entrega').val(guiaEntrega.df_sector_ent);
    $('#repartidor_entrega').val(guiaEntrega.df_repartidor_ent);
    $('#repartidor_entregaT').val(guiaEntrega.df_nombre_per + ' ' + guiaEntrega.df_apellido_per);
    $('#fecha_entrega').val(guiaEntrega.df_fecha_ent.split(' ')[0]);
    getDetalleEntrega();
}

function getDetalleEntrega() {
    var urlCompleta = url + 'detalleEntrega/getById.php';
    $('#table_guias tbody').empty();
    $.post(urlCompleta, JSON.stringify({ df_guia_entrega: guiaEntrega.df_num_guia_entrega }), function(response) {
        detallesEntrega = response.data;
        console.log('detalles entrega', detallesEntrega);
        var factura = "";
        $.each(response.data, function(index, row) {
            var tr = $('<tr/>');
            if (factura != row.df_num_factura_detent) {
                factura = row.df_num_factura_detent;
                getDetalleFactura(factura);
                tr.append('<td width="120" class="factura">' + factura + '</td>');
                tr.append('<td class="estado"><select id="estado-' + factura + '" class="form-control" onchange="cambiaEstado(`' + factura + '`)"><option value="2">ENTREGADO</option><option value="4">MODIFICADA</option><option value="6">REASIGNADA</option><option value="5">ANULADA</option></select></td>');
                tr.append('<td width="180" class="nueva_fecha"><input type="date" class="form-control" id="nueva-fecha-' + factura + '"></td>');
                tr.append('<td class="forma-pago"><select id="forma-pago-' + factura + '" class="form-control" onchange="cambioFormaPago(`' + factura + '`)"><option value="EFECTIVO">EFECTIVO</option><option value="CHEQUE">CHEQUE</option><option value="TRANSFERENCIA">TRANSFERENCIA</option><option value="CREDITO">CRÉDITO</option></select></td>');
                $('#table_guias tbody').append(tr);
            }
        });
        clearTimeout(timer);
        timer = setTimeout(function() {
            calcularCostos();
        }, 3000);
    });
}

function getDetalleFactura(fact) {
    var urlCompleta = url + 'detalleFactura/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura_detfac: fact }), function(response) {
        console.log('response detalle', response);
        $.each(response.data, function(index, row) {
            detalleFactura.push(row);
        });
    });
    console.log('detalle factura', detalleFactura);
}

var modificados = [];

function cambiaEstado(fact) {
    modificados = [];
    $('#display_productos tbody').empty();
    if ($('#estado-' + fact).val() == 4) {
        $.each(detalleFactura, function(index, row) {
            if (row.df_num_factura_detfac == fact) {
                var tr = $('<tr/>');
                tr.append('<td class="id" style="display: none;"><input type="text" id="id-' + row.df_id_factura_detfac + '" value="' + row.df_id_factura_detfac + '"></td>');
                tr.append('<td style="display: none;"><input type="text" id="antes-' + row.df_id_factura_detfac + '" value="' + row.df_cantidad_detfac + '"></td>');
                tr.append('<td>' + row.df_codigo_prod + '</td>');
                tr.append('<td>' + row.df_nombre_producto + '</td>');
                tr.append('<td style="text-align: right;">' + row.df_nombre_und_detfac + '</td>');
                tr.append('<td><input type="number" class="form-control" id="vendidos-' + row.df_id_factura_detfac + '" value="' + row.df_cantidad_detfac + '" onkeyup="cambiaVendidos(`' + row.df_id_factura_detfac + '`)" ></td>');
                tr.append('<td><input type="number" class="form-control" id="devueltos-' + row.df_id_factura_detfac + '" value="0" disabled ></td>');
                $('#display_productos tbody').append(tr);
            }
        });
        $('#modificacionRecepcion').modal('show');
    }
}

function cambiaVendidos(id) {
    var antes = $('#antes-' + id).val() * 1;
    var ahora = $('#vendidos-' + id).val() * 1;
    if (ahora < antes) {
        var resta = antes - ahora;
        $('#devueltos-' + id).val(resta);
        for (var i = 0; i < detalleFactura.length; i++) {
            if (detalleFactura[i].df_id_factura_detfac == id) {
                modificados.push({
                    cantidad: ahora,
                    resta: resta,
                    id: detalleFactura[i].df_id_factura_detfac
                });
            };
        };
    } else {
        $('#vendidos-' + id).val(antes);
        $('#devueltos-' + id).val('0');
    }
    clearTimeout(timer);
    timer = setTimeout(function() {
        calcularCostos();
    }, 1000);
}

function gardaModificacion() {
    $.each(modificados, function(i, r) {
        for (var i = 0; i < detalleFactura.length; i++) {
            if (r.id == detalleFactura[i].df_id_factura_detfac) {
                detalleFactura[i].df_cant_x_und_detfac = r.cantidad;
                var tr = $('<tr/>');
                tr.append('<td>' + r.resta + '</td>');
                tr.append('<td>' + detalleFactura[i].df_nombre_und_detfac + '</td>');
                tr.append('<td>' + detalleFactura[i].df_nombre_producto + '</td>');
                $('#table_resumen_productos tbody').append(tr);
            }
        }
    });
    $('#modificacionRecepcion').modal('hide');

}

function calcularCostos() {
    var valor_recaudado = 0;
    var valor_efectivo = Number($('#valor_efectivo_entrega').val()).toFixed(2);
    var valor_cheque = Number($('#valor_cheque_entrega').val()).toFixed(2);
    var valor_retenciones = Number($('#valor_retenciones_entrega').val()).toFixed(2);
    var valor_descuento = Number($('#valor_descuento_entrega').val()).toFixed(2);
    var diferencia = 0;
    $.each(detalleFactura, function(index, row) {
        var iva = row.df_iva_detfac * 1;
        var precio_unitario = row.df_precio_prod_detfac * 1;
        var cantidad = row.df_cantidad_detfac * 1;
        var subtotal = precio_unitario * cantidad;
        var total_iva = subtotal * iva * 1;
        var total_tupla = subtotal + total_iva;
        valor_recaudado += total_tupla;
    });
    $('#valor_recaudado_entrega').val(Number(valor_recaudado).toFixed(2));
    var resto = Number(valor_efectivo) + Number(valor_cheque) + Number(valor_retenciones) + Number(valor_descuento);
    diferencia = Number(valor_recaudado - resto).toFixed(2);
    $('#diferencia_entrega').val(diferencia);
}

function restarEntrega() {
    var valor_recaudado = $('#valor_recaudado_entrega').val() * 1;
    var valor_efectivo = $('#valor_efectivo_entrega').val() * 1;
    var valor_cheque = $('#valor_cheque_entrega').val() * 1;
    var valor_retenciones = $('#valor_retenciones_entrega').val() * 1;
    var valor_descuento = $('#valor_descuento_entrega').val() * 1;
    var diferencia = valor_recaudado - valor_efectivo - valor_cheque - valor_retenciones - valor_descuento;
    console.log('diferencia', diferencia.toFixed(2));
    if (diferencia.toFixed(2) == -0) {
        diferencia = 0;
    }
    $('#diferencia_entrega').val(Number(diferencia).toFixed(2));
}

function comenzarInsertarEntrega() {
    var diferencia = $('#diferencia_entrega').val() * 1;
    if (diferencia != 0) {
        alertar('warning', '¡Alerta!', 'Diferencia debe estar en cero');
    } else {
        validarInsercionEntrega();
    }
}

function validarInsercionEntrega() {
    $.each(detalleFactura, function(index, row) {
        if ($('#estado-' + row.df_num_factura_detfac).val() == 6 && $('#nueva-fecha-' + row.df_num_factura_detfac).val() == '') {
            alertar('danger', '¡Error!', 'Las facturas reasignadas no tienen nueva fecha de entrega');
            return;
        }
        currentdate = new Date();
        datetime = currentdate.getFullYear() + "-" +
            (currentdate.getMonth() + 1) + "-" +
            currentdate.getDate() + " " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds();
        var recepcion = {
            df_codigo_guia_rec: $('#num_guia_entrega option:selected').text(),
            df_fecha_recepcion: datetime,
            df_repartidor_rec: $('#repartidor_entrega').val(),
            df_valor_recaudado: $('#valor_recaudado_entrega').val(),
            df_valor_efectivo: $('#valor_efectivo_entrega').val(),
            df_valor_cheque: $('#valor_cheque_entrega').val(),
            df_retenciones: $('#valor_retenciones_entrega').val(),
            df_descuento_rec: $('#valor_descuento_entrega').val(),
            df_diferencia_rec: $('#diferencia_entrega').val(),
            df_remision_rec: 0,
            df_entrega_rec: 1,
            df_num_guia: $('#num_guia_entrega').val(),
            df_creadoBy_rec: $('#usuario').val()
        };
        insertEntrega(recepcion);
    });
}

function insertEntrega(recepcion) {
    var urlCompleta = url + 'guiaRecepcion/insert.php';
    console.log('entrega', recepcion);
    $.post(urlCompleta, JSON.stringify(recepcion), function(response) {
        console.log('response insert entrega', response);
        if (response == false || response == false) {
            alertar('danger', '¡Error!', 'Verifique su conexión a internet, e intente nuevamente');
        } else {
            updateEntrega();
            generarDetalleGuiaEntrega(response);
        }
    });
}

function updateEntrega() {
    var urlCompleta = url + 'guiaEntrega/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_guia_entrega: guiaEntrega.df_num_guia_entrega }), function(response) {
        guiaEntrega = response.data[0];
        urlCompleta = url + 'guiaEntrega/update.php';
        guiaEntrega.df_modificadoBy_ent = $('#usuario').val();
        guiaEntrega.df_guia_ent_recibido = 1;
        $.post(urlCompleta, JSON.stringify(guiaEntrega), function(resp) {
            console.log('update', resp);
        });
    });
}

function generarDetalleGuiaEntrega(id) {
    var inserto = true;
    $.each(detalleFactura, function(index, row) {
        var factura = '';
        if (row.df_num_factura_detfac != factura) {
            factura = row.df_num_factura_detfac;
            var nueva_fecha;
            if ($('#estado-' + row.df_num_factura_detfac).val() == 6) {
                nueva_fecha = $('#nueva-fecha-' + row.df_num_factura_detfac).val();
            } else {
                nueva_fecha = '';
            }
            var detalle = {
                df_guia_recepcion_detrec: id,
                df_factura_rec: row.df_num_factura_detfac,
                df_cant_producto_detrec: 0,
                df_producto_cod_detrec: 0,
                df_nueva_fecha: nueva_fecha,
                df_detalleRemision_detrec: '',
                df_edo_prod_fact_detrec: $('#estado-' + row.df_num_factura_detfac).val()
            };
            var estado = $('#estado-' + row.df_num_factura_detfac).val() * 1;
            if (estado == 4) {
                estado = 2;
            }
            var forma_pago = $('#forma-pago-' + row.df_num_factura_detfac).val();
            buscarParaModificarFactura(factura, estado, forma_pago, nueva_fecha);
            var respuesta = insertDetelle(detalle);
            if (respuesta == false) {
                inserto = false;
            }
        }
    });
    clearTimeout(timer);
    setTimeout(function() {
        if (inserto == true) {
            alertar('success', '¡Éxito!', 'Guía registrada exitosamente');
        } else {
            alertar('danger', '¡Error!', 'Compruebe su conexión a internet e intente nuevamente');
        }
        recargar();
    }, 3000);
}

function recargar() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        window.location.reload();
    }, 2000);
}

function buscarParaModificarFactura(fact, estado, forma_pago, fecha_entrega) {
    var urlCompleta = url + 'factura/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura: fact }), function(response) {
        response.data[0].df_forma_pago_fac = forma_pago;
        response.data[0].df_edo_factura_fac = estado;
        if (fecha_entrega = !'') {
            response.data[0].df_fecha_entrega_fac = fecha_entrega;
        }
        updateFactura(response.data[0]);
    });
}

function updateFactura(factura) {
    var urlCompleta = url + 'factura/update.php';
    $.post(urlCompleta, JSON.stringify(factura), function(response) {
        console.log('factura modificada', response);
    });
}