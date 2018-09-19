var productos = [];
var timer;
var fecha_entrega = '';
var detalles = [];
var devueltas = [];
var facturas = [];
var guiasEntrega = [];
var guiasRemision = [];

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
    $('#usuario').html('');
    $('#usuario').append('<option value="' + usuario.df_id_usuario + '" selected>' + usuario.df_usuario_usuario + '</option>');
    $('#personal').empty();
    consultarPersonal();
    detalles = [];
    devueltas = [];
    guiasEntrega = [];
    guiasRemision = [];
    $('#seleccionGuiaEntrega').hide();
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
        $('#seleccionGuiaEntrega').show('slow');
        getEntregaPendiente();
    } else if (tipo == 'Remision') {
        $('#seleccionGuiaEntrega').hide('slow');
    }
}

function getEntregaPendiente() {
    var urlCompleta = url + 'guiaRecepcion/getPendienteEntrega.php';
    $('#num_gui_ent').empty();
    $('#num_gui_ent').append('<option value="null">Selecccione...</option>');
    $('#repartidor').empty();
    $('#repartidor').append('<option value="null">Selecccione...</option>');
    $.get(urlCompleta, function(response) {
        console.log('entregapendiente', response);
        if (response.data.length > 0) {
            guiasEntrega = response.data;
            $.each(guiasEntrega, function(index, row) {
                var option = '<option value="' + row.df_num_guia_entrega + '">' + row.df_codigo_guia_ent + '</option>';
                $('#num_gui_ent').append(option);
            });
        } else {
            alertar('warning', '¡Alerta!', 'No existe ninguna guía pendiente');
        }
    });
}

/*function getRemisionPendiente() {
    var urlCompleta = url + 'guiaRecepcion/getPendienteEntrega.php';
    $('#num_gui_ent').empty();
    $('#num_gui_ent').append('<option value="null">Selecccione...</option>');
    $('#repartidor').empty();
    $('#repartidor').append('<option value="null">Selecccione...</option>');
}*/












function cambioRepartidorVendedor() {
    $('#table_guias tbody').empty();
    $('#table_productos tbody').empty();
    $('#table_formas_pago tbody').empty();
    $('#valor_recaudado').val('$0.00');
    if ($('#personal').val() != 'null') {
        var urlCompleta = url + 'guiaRecepcion/getPendiente.php';
        $.post(urlCompleta, JSON.stringify({ df_repartidor_ent: $('#personal').val(), df_vendedor_rem: $('#personal').val() }), function(response) {
            console.log('guias', response.data);
            $.each(response.data, function(index, row) {
                if (row.df_codigo_guia_ent.split('-')[0] == 'GENT') {
                    consultarGuiasEntrega(row);
                } else if (row.df_codigo_guia_ent.split('-')[0] == 'GREM') {
                    consultarGuiasRemision(row);
                }
            });
            clearTimeout(timer);
            timer = setTimeout(function() {
                llenarTablaFacturas();
            }, 4000);
        });
    } else {
        alertar('warning', '¡Alerta!', 'El campo Repartidor/Vendedor, no puede quedar vacío');
    }
}

function consultarGuiasEntrega(row) {
    var urlCompleta = url + 'guiaEntrega/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_num_guia_entrega: row.df_num_guia_entrega }), function(response) {
        var detalle = response.data[0];
        urlCompleta = url + 'detalleEntrega/getById.php';
        $.post(urlCompleta, JSON.stringify({ df_guia_entrega: detalle.df_num_guia_entrega }), function(resp) {
            if (resp.data.length > 0) {
                detalle.facturas = resp.data;
                detalles.push(detalle);
                $.each(resp.data, function(index, fila) {
                    var tr = $('<tr/>');
                    tr.append('<td width="100" class="factura" id="factura">' + fila.df_num_factura_detent + '</td>');
                    tr.append('<td width="150" class="select" id="select"><select class="form-control" id="select-' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '" onchange="seleccionAccion(`' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '`)"> <option value="null">Seleccione...</option> <option value="1">Pendiente Entrega</option> <option value="2">Entregado</option> <option value="3">Abonado</option> <option value="4">Modificado</option> <option value="5">Anulado</option> <option value="6">Reasignado</option> </select></td>');
                    tr.append('<td width="100" class="input-cantidad" id="input-cantidad"><input type="number" class="form-control" value="' + fila.df_cant_producto_detent + '" id="cantidad-' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '" onkeyup="digitaTecla(`' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '`)"  disabled> </td>');
                    tr.append('<td width="200" class="producto" id="producto">' + fila.df_nom_producto_detent + '</td>');
                    tr.append('<td class="fecha" id="fecha"><input type="date" class="form-control" id="fecha-' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '" disabled></td>');
                    tr.append('<td class="cantidad-producto" id="cantidad-producto-' + row.df_codigo_guia_ent + '-' + fila.df_id_detent + '" style="display: none;">' + fila.df_cant_producto_detent + '</td>');
                    tr.append('<td class="producto-id" id="producto-id" style="display: none;">' + fila.df_cod_producto + '</td>');
                    $('#table_guias').append(tr);
                })
            }
        })
    })
}

function consultarGuiasRemision(row) {
    var urlCompleta = url + 'guiaRemision/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_guia_remision: row.df_num_guia_entrega }), function(response) {
        var detalle = {
            df_guia_remision: response.data[0].df_guia_remision,
            df_codigo_rem: response.data[0].df_codigo_rem,
            df_fecha_remision: response.data[0].df_fecha_remision,
            df_sector_cod_rem: response.data[0].df_sector_cod_rem,
            df_vendedor_rem: response.data[0].df_vendedor_rem,
            df_cant_total_producto_rem: response.data[0].df_cant_total_producto_rem,
            df_valor_efectivo_rem: response.data[0].df_valor_efectivo_rem,
            df_creadoBy_rem: response.data[0].df_creadoBy_rem,
            df_modificadoBy_rem: response.data[0].df_modificadoBy_rem,
            df_guia_rem_recibido: response.data[0].df_guia_rem_recibido,
            facturas: []
        };
        urlCompleta = url + 'detalleRemision/getById.php';
        $.post(urlCompleta, JSON.stringify({ df_guia_remision_detrem: row.df_num_guia_entrega }), function(resp) {
            if (resp.data.length > 0) {
                detalle.facturas = resp.data;
                detalles.push(detalle);
                $.each(resp.data, function(index, fila) {
                    consultarProductoPrecio(detalle, fila);
                });
            }
        });
    });
}

function consultarProductoPrecio(detalle, fila) {
    var urlCompleta = url + 'productoPrecio/getById.php';
    console.log('detalle', detalle);
    $.post(urlCompleta, JSON.stringify({ df_id_precio: fila.df_producto_precio_detrem }), function(response) {
        console.log('response producto precio', response.data);
        if (response.data.length > 0) {
            consultarProducto(response.data[0].df_producto_id, detalle, fila);
        }
    })
}

function consultarProducto(id, detalle, fila) {
    var urlCompleta = url + 'producto/getById.php';
    $.post(urlCompleta, JSON.stringify({ df_id_producto: id }), function(response) {
        if (response.data.length > 0) {
            var tr = $('<tr/>');
            tr.append('<td width="100" class="factura" id="factura">N/A</td>');
            tr.append('<td width="150" class="select" id="select"><select class="form-control" id="select-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '" onchange="seleccionAccion(`' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '`)"> <option value="null">Seleccione...</option> <option value="1">Pendiente Entrega</option> <option value="2">Entregado</option> <option value="3">Abonado</option> <option value="4">Modificado</option> <option value="5">Anulado</option> <option value="6">Reasignado</option> </select></td>');
            tr.append('<td width="100" class="input-cantidad" id="input-cantidad"><input type="number" class="form-control" value="' + fila.df_cant_producto_detrem + '" id="cantidad-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '" onkeyup="digitaTecla(`' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '`)"  disabled> </td>');
            tr.append('<td width="200" class="producto" id="producto-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '">' + response.data[0].df_nombre_producto + '</td>');
            tr.append('<td class="fecha" id="fecha"><input type="date" class="form-control" id="fecha-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '" disabled></td>');
            tr.append('<td class="cantidad-producto" id="cantidad-producto-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '" style="display: none;">' + fila.df_cant_producto_detrem + '</td>');
            tr.append('<td class="producto-id" id="producto-id-' + detalle.df_codigo_rem + '-' + fila.df_id_detrem + '" style="display: none;">' + id + '</td>');
            $('#table_guias').append(tr);
            console.log(detalles);
        }
    });
}

function seleccionAccion(action) {
    var accion = $('#select-' + action).val();
    if (accion == 4) {
        $('#cantidad-' + action).prop('disabled', false);
        reponerCantidad(action);
    } else if (accion == 5) {
        $('#cantidad-' + action).prop('disabled', true);
        $('#cantidad-' + action).val('0');
        llenarTablaDevueltos($('#producto-id-' + action).html(), $('#cantidad-producto-' + action).html(), $('#producto-' + action).html());
    } else if (accion == 6) {
        $('#cantidad-' + action).prop('disabled', true);
        $('#fecha-' + action).prop('disabled', false);
        reponerCantidad(action);
        llenarTablaDevueltos($('#producto-id-' + action).html(), $('#cantidad-producto-' + action).html(), $('#producto-' + action).html());
    } else {
        $('#cantidad-' + action).prop('disabled', true);
        $('#fecha-' + action).prop('disabled', true);
        reponerCantidad(action);
    }
}

function reponerCantidad(action) {
    var valor_anterior = $('#cantidad-producto-' + action).html();;
    $('#cantidad-' + action).val(valor_anterior);
}

function digitaTecla(action) {
    clearTimeout(timer);
    timer = setTimeout(function() {
        if ($('#select-' + action).val() == 4) {
            var producto_id = $('#producto-id-' + action).html();
            var cantidad_anterior = $('#cantidad-producto-' + action).html() * 1;
            var cantidad_modificada = $('#cantidad-' + action).val() * 1;
            var cantidad = cantidad_anterior - cantidad_modificada;
            var producto = $('#producto-' + action).html();
            llenarTablaDevueltos(producto_id, cantidad, producto);
        }
    }, 1000);
}

function llenarTablaDevueltos(id, cantidad, producto) {
    var igualdad = false;
    $('#table_productos tbody tr').each(function(index, row) {
        console.log('row', row);
        var id_producto = $('.id', row).text();
        if (id == id_producto) {
            igualdad = true;
            var cantidad_tupla = Number(cantidad) + ($('.cantidad', row).text() * 1);
            $('.cantidad', row).html(cantidad_tupla);
        }
    });
    if (igualdad == false) {
        var tr = $('<tr/>');
        tr.append('<td class="id" style="display: none;">' + id + '</td>');
        tr.append('<td class="cantidad" width="20">' + cantidad + '</td>');
        tr.append('<td class="producto">' + producto + '</td>');
        $('#table_productos tbody').append(tr);
    }
}

function llenarTablaFacturas() {
    $.each(detalles, function(index, row) {
        if (row.df_codigo_guia_ent) {
            $.each(row.facturas, function(ind, fila) {
                var iguales = false;
                $.each(facturas, function(i, r) {
                    if (r.factura == fila.df_num_factura_detent) {
                        iguales = true;
                    }
                })
                if (iguales == false) {
                    facturas.push({
                        factura: fila.df_num_factura_detent
                    });
                }
            })
        }
    });
    clearTimeout(timer);
    timer = setTimeout(function() {
        dibujarTablaFacturas();
    }, 100);
}

function dibujarTablaFacturas() {
    $.each(facturas, function(index, row) {
        var tr = $('<tr/>');
        tr.append('<td class="factura" width="200">' + row.factura + '</td>');
        tr.append('<td><select class="form-control" id="select-pago-' + row.factura + '"> <option value="EFECTIVO">Efectivo</option> <option value="CHEQUE">Cheque</option> <option value="TRANSFERENCIA">Transferencia</option> </select></td>');
        tr.append('<td width="200"><input type="number" class="form-control" min="0.00" step="0.01" id="valor-' + row.factura + '"></td>');
        $('#table_formas_pago tbody').append(tr);
    });
}

function cambiaValorCheque() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        var valor = Number($('#valor_cheque').val());
        var total = Number($('#valor_recaudado').html().split('$')[1]);
        total = total + valor;
        $('#valor_recaudado').html('$' + total);
    }, 1000);
}

function cambiaValorEfectivo() {
    clearTimeout(timer);
    timer = setTimeout(function() {
        var valor = Number($('#valor_efectivo').val());
        var total = Number($('#valor_recaudado').html().split('$')[1]);
        console.log('valor', valor);
        console.log('total', total);
        total = total + valor;
        $('#valor_recaudado').html('$' + total);
    }, 1000);
}

$('#form_nueva_guia').submit(function(e) {
    e.preventDefault();
    alertar('success', '¡Éxito!', 'Guía guardada exitosamente');
    limpiar();
});

function limpiar() {
    $('#table_guias tbody').empty();
    $('#table_productos tbody').empty();
    $('#table_formas_pago tbody').empty();
    $('#valor_recaudado').val('$0.00');
    $('#personal').val('null');
    $('#valor_recaudado').html('$0.00');
    $('#valor_efectivo').val('0.00');
    $('#valor_cheque').val('0.00');
    $('#retenciones').val('0.00');
    $('#descuentos').val('0.00');
    $('#diferencia').val('0.00');
}