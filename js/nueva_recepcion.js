var productos = [];


var timer;


var fecha_entrega = '';


var detalles = [];


var devueltas = [];

function getEntregaPendiente() {
    getSectores();
    getPersonal();
    var urlCompleta = url + 'guiaRecepcion/getPendienteEntrega.php';
    $.get(urlCompleta, function(response) {
        console.log('entregapendiente', response);
        if (response.data.length > 0) {
            $('#num_guia_entrega').empty();
            $('#num_guia_entrega').append('<option value="null">Selecccione...</option>');
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


var guiasEntrega = [];


var guiasRemision = [];


var timer;

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
            tr.append('<td class="unidad_x_caja" style="display: none;">' + row.df_cant_x_und_detrem + '</td>');
            $('#table_productos tbody').append(tr);
        });
        calcularRemision();
    });
}


var guiaRemision = {};


var guiaEntrega = {};

var cantidadUnidadRemision = 0;
var cantidadCajaRemision = 0;

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
        if ($('.unidad', b).text() == 'CAJA') {
            cantidadCajaRemision += cantidad;
        } else {
            cantidadUnidadRemision += cantidad;
        }
    });
    $('#valor_recaudado').val(Number(valor_recaudado).toFixed(2));
    var resto = Number(valor_efectivo) + Number(valor_cheque) + Number(valor_retenciones) + Number(valor_descuento);
    diferencia = Number(valor_recaudado - resto).toFixed(2);
    $('#diferencia').val(diferencia);
}


var detalleFactura = [];





$(document).ready(function() {

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
        df_creadoBy_rec: $('#usuario').val(),
        df_cant_und_rec: cantidadUnidadRemision,
        df_cant_caja_rec: cantidadCajaRemision
    };
    insertRemision(recepcion);


    if (usuario.ingreso == true) {


        $('#pasaporte').hide();


        if (usuario.df_tipo_usuario == 'Administrador') {

function generarDetalleGuia(id) {
    var inserto = true;
    $('#table_productos tbody tr').each(function(a, b) {
        var codigo = $('.codigo', b).text();
        var cantidadUnd = 0;
        var cantidadCaj = 0;
        if ($('.unidad', b).text() == 'CAJA') {
            cantidadCaj = $('.cantidad', b).text() * 1;
        } else {
            cantidadUnd = $('.cantidad', b).text() * 1;
        }
        var detalle = {
            df_guia_recepcion_detrec: id,
            df_factura_rec: 0,
            df_cant_producto_detrec: cantidadUnd,
            df_cant_caja_detrec: cantidadCaj,
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


            $('#Supervisor').hide('');


            $('#Ventas').hide('');


        } else if (usuario.df_tipo_usuario == 'Supervisor') {

function getDetalleFactura(fact) {
    var urlCompleta = url + 'detalleFactura/getByIdRecepcion.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura_detfac: fact }), function(response) {
        console.log('response detalle', response);
        $.each(response.data, function(index, row) {
            detalleFactura.push(row);
        });
    });
    console.log('detalle factura', detalleFactura);
}


            $('#Supervisor').show('');

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
    } else if ($('#estado-' + fact).val() == 5) {
        for (var i = 0; i < detalleFactura.length; i++) {
            if (detalleFactura[i].df_num_factura_detfac == fact) {
                var cantidad = detalleFactura[i].df_cantidad_detfac * 1;
                $('#table_resumen_productos tbody tr').each(function(a, b) {
                    if ($('.producto', b).text() == detalleFactura[i].df_nombre_producto) {
                        var resta_ant = $('.resta', b).text() * 1;
                        cantidad = cantidad + resta_ant;
                        $(this).remove();
                    }
                });
                var tr = $('<tr/>');
                tr.append('<td class="resta">' + cantidad + '</td>');
                tr.append('<td class="unidad">' + detalleFactura[i].df_nombre_und_detfac + '</td>');
                tr.append('<td class="producto">' + detalleFactura[i].df_nombre_producto + '</td>');
                $('#table_resumen_productos tbody').append(tr);
                detalleFactura[i].df_cantidad_detfac = 0;
            }
        };
        calcularCostos();
    } else if ($('#estado-' + fact).val() == 6) {
        for (var i = 0; i < detalleFactura.length; i++) {
            if (detalleFactura[i].df_num_factura_detfac == fact) {
                var cantidad = detalleFactura[i].df_cantidad_detfac * 1;
                $('#table_resumen_productos tbody tr').each(function(a, b) {
                    if ($('.producto', b).text() == detalleFactura[i].df_nombre_producto) {
                        var resta_ant = $('.resta', b).text() * 1;
                        cantidad = cantidad + resta_ant;
                        $(this).remove();
                    }
                });
                var tr = $('<tr/>');
                tr.append('<td class="resta">' + cantidad + '</td>');
                tr.append('<td class="unidad">' + detalleFactura[i].df_nombre_und_detfac + '</td>');
                tr.append('<td class="producto">' + detalleFactura[i].df_nombre_producto + '</td>');
                $('#table_resumen_productos tbody').append(tr);
                detalleFactura[i].df_cantidad_detfac = 0;
            }
        };
        calcularCostos();
    }
    console.log('modifica detalle', detalleFactura);
}

function cambiaVendidos(id) {
    clearTimeout(timer);
    timer = setTimeout(function() {
        for (var i = 0; i < modificados.length; i++) {
            if (modificados[i].id == id) {
                modificados.splice(i, 1);
            }
        }
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
                        id: detalleFactura[i].df_id_factura_detfac,
                        precio_uni: detalleFactura[i].df_precio_prod_detfac,
                        iva: detalleFactura[i].df_iva_detfac
                    });
                };
            };
        } else {
            $('#vendidos-' + id).val(antes);
            $('#devueltos-' + id).val('0');
        }
    }, 100);
}

function gardaModificacion() {
    $.each(modificados, function(i, r) {
        for (var i = 0; i < detalleFactura.length; i++) {
            if (r.id == detalleFactura[i].df_id_factura_detfac) {
                detalleFactura[i].df_cantidad_detfac = r.cantidad;
                var devuelve = r.resta;
                $('#table_resumen_productos tbody tr').each(function(a, b) {
                    if ($('.producto', b).text() == detalleFactura[i].df_nombre_producto) {
                        var resta_ant = $('.resta', b).text() * 1;
                        devuelve = devuelve + resta_ant;
                        $(this).remove();
                    }
                });
                var tr = $('<tr/>');
                tr.append('<td class="resta">' + devuelve + '</td>');
                tr.append('<td class="unidad">' + detalleFactura[i].df_nombre_und_detfac + '</td>');
                tr.append('<td class="producto">' + detalleFactura[i].df_nombre_producto + '</td>');
                tr.append('<td class="producto_id" style="display: none;">' + detalleFactura[i].df_id_producto + '</td>');
                tr.append('<td class="cant_x_caja" style="display: none;">' + detalleFactura[i].df_cant_x_und_detfac + '</td>');
                $('#table_resumen_productos tbody').append(tr);
            }
        }
    });
    $('#modificacionRecepcion').modal('hide');
    clearTimeout(timer);
    timer = setTimeout(function() {
        calcularCostos();
    }, 1000);
}

var totalUnidades = 0;
var totalCajas = 0;

function calcularCostos() {
    var valor_recaudado = 0;
    var valor_efectivo = Number($('#valor_efectivo_entrega').val());
    var valor_cheque = Number($('#valor_cheque_entrega').val());
    var valor_retenciones = Number($('#valor_retenciones_entrega').val());
    var valor_descuento = Number($('#valor_descuento_entrega').val());
    var diferencia = 0;
    totalUnidades = 0;
    totalCajas = 0;
    $.each(detalleFactura, function(index, row) {
        if (row.df_nombre_und_detfac == 'CAJA') {
            totalCajas += row.df_cantidad_detfac * 1;
        } else {
            totalUnidades += row.df_cantidad_detfac * 1;
        }
        var iva = row.df_iva_detfac * 1;
        var precio_unitario = row.df_precio_prod_detfac * 1;
        var cantidad = row.df_cantidad_detfac * 1;
        var subtotal = precio_unitario * cantidad;
        var total_iva = subtotal * iva;
        var total_tupla = subtotal + total_iva;
        valor_recaudado += total_tupla;
    });
    $('#valor_recaudado_entrega').val(Number(valor_recaudado).toFixed(2));
    var resto = Number(valor_efectivo) + Number(valor_cheque) + Number(valor_retenciones) + Number(valor_descuento);
    diferencia = Number(valor_recaudado - resto).toFixed(2);
    $('#diferencia_entrega').val(diferencia);
}


            $('#Ventas').show('');

function comenzarInsertarEntrega() {
    on();
    var diferencia = $('#diferencia_entrega').val() * 1;
    if (diferencia != 0) {
        off();
        alertar('warning', '¡Alerta!', 'Diferencia debe estar en cero');
    } else {
        validarInsercionEntrega();
    }
}

function validarInsercionEntrega() {
    $.each(detalleFactura, function(index, row) {
        if ($('#estado-' + row.df_num_factura_detfac).val() == 6 && $('#nueva-fecha-' + row.df_num_factura_detfac).val() == '') {
            off();
            alertar('danger', '¡Error!', 'Las facturas reasignadas no tienen nueva fecha de entrega');
            return;
        }
    });
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
        df_cant_und_rec: totalUnidades,
        df_cant_caja_rec: totalCajas,
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
    //alert('guardar entrega ', recepcion);
    console.log('entrega', recepcion);
    insertEntrega(recepcion);
}

function insertEntrega(recepcion) {
    var urlCompleta = url + 'guiaRecepcion/insert.php';
    //alert('Entrega ', recepcion);
    console.log('entrega', recepcion);
    $.post(urlCompleta, JSON.stringify(recepcion), function(response) {
        console.log('response insert entrega', response);
        if (response == false || response == false) {
            off();
            alertar('danger', '¡Error!', 'Verifique su conexión a internet, e intente nuevamente');
        } else {
            updateEntrega();
            generarDetalleGuiaEntrega(response);
        }
    });
}


    }

function generarDetalleGuiaEntrega(id) {
    //alert('Generar delatte guia entrega');
    var inserto = true;
    modificarInventario();
    $('#table_guias tbody tr').each(function(a, b) {
        var factura = $('.factura', b).text();
        var estado = $('#estado-' + factura).val() * 1;
        var nueva_fecha = $('#nueva-fecha-' + factura).val();
        var restarAFactura = 0;
        var subtotal = 0;
        var iva = 0;
        var total = 0;
        $.each(detalleFactura, function(index, row) {
            if (factura == row.df_num_factura_detfac) {
                var productosUnidad = 0;
                var productosCaja = 0;
                if (row.df_nombre_und_detfac == 'CAJA') {
                    productosCaja = row.df_cantidad_detfac * 1;
                } else {
                    productosUnidad = row.df_cantidad_detfac * 1;
                }
                var detalle = {
                    df_guia_recepcion_detrec: id,
                    df_factura_rec: row.df_num_factura_detfac,
                    df_cant_producto_detrec: productosUnidad, //cantidad segun factura puede ser caja o und
                    df_cant_caja_detrec: productosCaja,
                    df_producto_cod_detrec: row.df_id_producto,
                    df_nueva_fecha: nueva_fecha,
                    df_detalleRemision_detrec: '',
                    df_edo_prod_fact_detrec: $('#estado-' + factura).val()
                };
                if (estado == 4) {
                    var cantidadProducto = row.df_cantidad_detfac * 1;
                    if (cantidadProducto > 0) {
                        var valorRestar = row.df_valor_total_detfac * 1;
                        var valorSinIva = cantidadProducto * row.df_precio_prod_detfac * 1;
                        row.df_valor_sin_iva_detfac = valorSinIva;
                        var valorIva = row.df_valor_sin_iva_detfac * row.df_iva_detfac * 1;
                        row.df_valor_total_detfac = valorSinIva + valorIva;
                        valorRestar = valorRestar - row.df_valor_total_detfac;
                        restarAFactura += valorRestar * 1;
                        subtotal += valorSinIva;
                        iva += valorIva * 1;
                        total += row.df_valor_total_detfac * 1;
                        updateDetalleFactura(row);
                    } else {
                        deleteDetalleFactura(row);
                    }
                }
                var respuesta = insertDetelle(detalle);
                if (respuesta == false) {
                    inserto = false;
                }

            }
        });
        var forma_pago = $('#forma-pago-' + factura).val();
        restarAFactura = Number(restarAFactura).toFixed(2);
        subtotal = Number(subtotal).toExponential(2);
        iva = Number(iva).toFixed(2);
        total = Number(total).toFixed(2);
        buscarParaModificarFactura(factura, estado, forma_pago, nueva_fecha, restarAFactura, subtotal, iva, total);
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


});

function buscarParaModificarFactura(fact, estado, forma_pago, fecha_entrega, restarAFactura, subtotal, iva, total) {
    // alert('Buscar para modificar facturas');
    var urlCompleta = url + 'factura/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_factura: fact }), function(response) {
        var estadoFactura = estado * 1;
        if (estadoFactura == 2) {
            response.data[0].df_forma_pago_fac = forma_pago;
            response.data[0].df_edo_factura_fac = 2;
            updateFactura(response.data[0]);
        } else if (estadoFactura == 4) {
            response.data[0].df_forma_pago_fac = forma_pago;
            response.data[0].df_edo_factura_fac = 2;
            response.data[0].df_subtotal_fac = subtotal;
            response.data[0].df_iva_fac = iva;
            response.data[0].df_valor_total_fac = total;
            updateFactura(response.data[0]);
            buscarModificarBanco(restarAFactura, fact);
        } else if (estadoFactura == 6) {
            response.data[0].df_fecha_entrega_fac = fecha_entrega;
            response.data[0].df_edo_factura_fac = estado;
            updateFactura(response.data[0]);
        } else if (estadoFactura == 5) {
            response.data[0].df_edo_factura_fac = estado;
            updateFactura(response.data[0]);
            buscarModificarBanco(response.data[0].df_valor_total_fac, fact);
        }
    });
}

function updateFactura(factura) {
    //alert('Update Factura');
    var urlCompleta = url + 'factura/update.php';
    $.post(urlCompleta, JSON.stringify(factura), function(response) {
        console.log('factura modificada', response);
    });
}

function updateDetalleFactura(detalle) {
    var urlCompleta = url + 'detalleFactura/update.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        console.log('update detalle factura', response);
    });
}

function deleteDetalleFactura(detalle) {
    var urlCompleta = url + 'detalleFactura/delete.php';
    $.post(urlCompleta, JSON.stringify(detalle), function(response) {
        console.log('eliminar detalle', response);
    });
}

function buscarModificarBanco(restar, factura) {
    var urlCompleta = url + 'banco/getAll.php';
    $.get(urlCompleta, function(response) {
        var valorActual = response.data[0].df_saldo_banco * 1;
        restar = restar * 1;
        var nuevoMonto = valorActual - restar;
        insertarBanco(restar, nuevoMonto, factura, valorActual);
    });
}

function insertarBanco(monto, saldo, factura, saldoAnterior) {
    var urlCompleta = url + 'banco/insert.php';
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var banco = {
        df_fecha_banco: datetime,
        df_usuario_id_banco: $('#usuario').val(),
        df_tipo_movimiento: 'Egreso',
        df_monto_banco: monto,
        df_saldo_banco: saldo,
        df_num_documento_banco: 'Factura #' + factura,
        df_detalle_mov_banco: 'Devolución productos factura'
    };
    buscarModificarLibroDiario(saldoAnterior, monto);
    $.post(urlCompleta, JSON.stringify(banco), function(response) {
        console.log('inserción banco', response);
    });
}

function buscarModificarLibroDiario(saldoAnteriorBanco, monto) {
    var urlCompleta = url + 'cajaChicaGasto/getMes.php';
    $.get(urlCompleta, function(response) {
        var saldoCajaChica = response.data[0].df_saldo * 1;
        saldoAnteriorBanco = saldoAnteriorBanco * 1;
        var valorInicial = saldoCajaChica + saldoAnteriorBanco;
        insertarLibroDiario(valorInicial, monto);
    });
}

function insertarLibroDiario(valorInicial, monto) {
    var urlCompleta = url + 'libroDiario/insert.php';
    currentdate = new Date();
    datetime = currentdate.getFullYear() + "-" +
        (currentdate.getMonth() + 1) + "-" +
        currentdate.getDate() + " " +
        currentdate.getHours() + ":" +
        currentdate.getMinutes() + ":" +
        currentdate.getSeconds();
    var libroDiario = {
        df_fuente_ld: 'Banco',
        df_valor_inicial_ld: valorInicial,
        df_fecha_ld: datetime,
        df_descipcion_ld: 'Devolución productos',
        df_ingreso_ld: 0,
        df_egreso_ld: monto,
        df_usuario_id_ld: $('#usuario').val()
    };
    $.post(urlCompleta, JSON.stringify(libroDiario), function(response) {
        console.log('inserción libro diario', response);
    });
}

function modificarInventario() {
    $('#table_resumen_productos tbody tr').each(function(a, b) {
        var resta = $('.resta', b).text() * 1;
        var unidad = $('.unidad', b).text();
        var producto_id = $('.producto_id', b).text() * 1;
        var cantidadCaja = $('.cant_x_caja', b).text() * 1;
        var resta = $('.resta', b).text() * 1;
        consultarInventario(producto_id, resta, unidad, cantidadCaja);
    });
}

function consultarInventario(id, agregar, unidad, unidad_x_caja) {
    var urlCompleta = url + 'inventario/getByIdProd.php';
    if (unidad_x_caja == -1) {
        $.post(urlCompleta, JSON.stringify({ df_producto: id }), function(response) {
            var habia = response.data[0].df_cant_bodega * 1;
            var agrega = agregar * 1;
            if (unidad == 'CAJA') {
                agrega = agrega * response.data[0].df_und_caja;
                response.data[0].df_cant_bodega = habia + agrega;
            } else {
                response.data[0].df_cant_bodega = habia + agrega;
            }
            updateInventario(response.data[0]);
        });
    } else {
        $.post(urlCompleta, JSON.stringify({ df_producto: id }), function(response) {
            var habia = response.data[0].df_cant_bodega * 1;
            var agrega = agregar * 1;
            if (unidad == 'CAJA') {
                agrega = agrega * unidad_x_caja;
                response.data[0].df_cant_bodega = habia + agrega;
            } else {
                response.data[0].df_cant_bodega = habia + agrega;
            }
            updateInventario(response.data[0]);
        });
    }
}

function updateInventario(inventario) {
    var urlCompleta = url + 'inventario/update.php';
    $.post(urlCompleta, JSON.stringify(inventario), function(response) {
        console.log('update inventario', response);
    });
}