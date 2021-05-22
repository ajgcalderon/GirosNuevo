
var base_url = 'fake_url';
var timeout;
document.onmousemove = function(){ 
    // confirm('La session está a punto de Expirar');
    clearTimeout(timeout); 
    contadorSesion(); //aqui cargamos la funcion de inactividad
} 


function contadorSesion() {
timeout = setTimeout(function () {
        $.confirm({
            title: 'Alerta de Inactividad!',
            content: 'La sesión esta a punto de expirar.',
            autoClose: 'expirar|10000',//cuanto tiempo necesitamos para cerrar la sess automaticamente
            type: 'red',
            icon: 'fa fa-spinner fa-spin',
            buttons: {
                expirar: {
                    text: 'Cerrar Sesión',
                    btnClass: 'btn-red',
                    action: function () {
                        salir();
                    }
                },
                permanecer: function () {
                    contadorSesion(); //reinicia el conteo
                    $.alert('La Sesión ha sido reiniciada!'); //mensaje
                    // window.location.href = base_url + "dashboard";
                }
            }
        });
    }, 180000);//3 segundos para no demorar tanto 
}

function salir() {
    window.location.href = BASE_URL + "Login/logout"; //esta función te saca
}