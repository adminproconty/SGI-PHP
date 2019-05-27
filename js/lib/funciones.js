function alertar(tipo, titulo, mensaje) {
    // $.toaster({ priority: tipo, title: titulo, message: mensaje, timeout: 7000, autohide: false });
    var opciones = {
        'bgColor': '#5cb85c',
        'ftColor': 'white',
        'vPosition': 'top',
        'hPosition': 'right',
        'fadeIn': 400,
        'fadeOut': 400,
        'clickable': true,
        'autohide': true,
        'duration': 3000
    };
    if (tipo == 'warning') {
        opciones.bgColor = '#f0ad4e';
    } else if (tipo == 'danger') {
        opciones.bgColor = '#d9534f';
    }
    flash(titulo + ' - ' + mensaje, opciones);
}