$(document).ready(function() {
    localStorage.setItem("total_compra", 0);
    localStorage.setItem("cantidad_productos", 0);
});

function agregar() {
    var precio_unitario = $('#precio_unitario').val();
    var cantidad = $('#cantidad').val();
    var producto = $('#producto').val();
    if (isNaN(cantidad)) {
        alert('Esto no es un numero');
        $('#cantidad').focus();
        return false;
    }
    if (isNaN(precio_unitario)) {
        alert('Esto no es un numero');
        $('#precio_unitario').focus();
        return false;
    }
    //Fin validacion

    $.ajax({
        type: "POST",
        url: "./ajax/agregar_producto.php",
        data: "precio_unitario=" + precio_unitario + "&cantidad=" + cantidad + "&producto=" + producto,
        beforeSend: function(objeto) {
            $("#resultados").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados").html(datos);
            $('#precio_unitario').val("");
            $('#cantidad').val("");
            $('#producto').val("");
            var total = localStorage.getItem("total_compra");
            $('#total').val(total);
        }
    });
}

function eliminar(id) {
    $.ajax({
        type: "GET",
        url: "./ajax/agregar_producto.php",
        data: "id=" + id,
        beforeSend: function(objeto) {
            $("#resultados").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados").html(datos);
            var total = localStorage.getItem("total_compra");
            $('#total').val(total);
        }
    });

}

$("#datos_factura").submit(function(event) {
    var cantidad_productos = localStorage.getItem('cantidad_productos');
    if (cantidad_productos < 1) {
        alert('Debe seleccionar productos para proceder la compra');
        event.preventDefault();
    }
});