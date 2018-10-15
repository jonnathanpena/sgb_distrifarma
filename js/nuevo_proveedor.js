var usuario = '';

$(document).ready(function() {
    usuario = localStorage.getItem('distrifar_usuario');
    console.log('usuario', usuario);
    $('#administrador').show('slow');
    $('#ventas').hide('slow');
    $('.usuario').hide('slow');
    load();
});

$('#toggle-usuario').change(function() {
    var toggle = $(this).prop('checked');
    if (toggle == true) {
        $('#es_usuario').val('1');
        $('.usuario').show('slow');
    } else {
        $('#es_usuario').val('0');
        $('.usuario').hide('slow');
    }
})