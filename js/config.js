var url = 'http://proconty.com/API/distrifar/';
var usuario = "";

function alertar(tipo, titulo, mensaje) {
    $.toaster({ priority: tipo, title: titulo, message: mensaje });
}

$('#logout').click(function() {
    usuario = JSON.parse(localStorage.getItem('distrifarma_test_user'));
    usuario.ingreso = false;
    console.log(usuario);
    localStorage.setItem('distrifarma_test_user', JSON.stringify(usuario));
    window.location.href = "login.php";
});

var personal = {};