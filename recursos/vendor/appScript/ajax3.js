$(function () {
    function mostrarCuentas(){
        var resultado = document.getElementById('cuentas');
        var girador = document.getElementById('girador');
        var xmlhttp;
        // alert('el nombre ingresado es: ' + persona.value);
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200){
                resultado.innerHTML = this.responseText; 
            }
        }
        xmlhttp.open('GET','Usuarios/getCuentas?girador='+girador.value,true);
        xmlhttp.send();
    }
    $('#busqueda').click(function(){mostrarCuentas()});

    function consulta(tagReferencia,tagResultado,method,url){
        var referencia = document.getElementById(tagReferencia);
        var transaccion = document.getElementById(tagResultado);
        var xmlhttp;
        
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200){
                transaccion.innerHTML = this.responseText; 
            }
        }
        xmlhttp.open(method,url+referencia.value,true);
        xmlhttp.send();
    }
    function consultaBeneficiario(tagCedula,tagNacionalidad,tagResultado,method,url){
        var cedula = document.getElementById(tagCedula);
        var nacionalidad = document.getElementById(tagNacionalidad);
        var cedulaBeneficiario = nacionalidad.value + '-' + cedula.value;
        var resultado = document.getElementById(tagResultado);
        var xmlhttp;
        
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200){
                resultado.innerHTML = this.responseText; 
            }
        }
        xmlhttp.open(method,url+cedulaBeneficiario,true);
        xmlhttp.send();
    }

    function calcularMonto(pesos,tasa){
        var monto = pesos / tasa;
        return monto.toFixed(2);
    } 
    function readImage (input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#vistaPrevia').attr('src', e.target.result); // Renderizamos la imagen
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#capture").change(function () {
        // C贸digo a ejecutar cuando se detecta un cambio de archivO
        readImage(this);
        
    });
    $('#vistaPrevia').hover(function() {
        $(this).addClass('transition');
    }, function() {
        $(this).removeClass('transition');
    });
    $('#pesos').on('change keyup',function(){
        var pesos = $('#pesos');
        var tasa = $('#tasa');
        var monto = $('#monto');
        var saldo = $('#saldoDisponible');
        var saldoText = $('#saldo');
        var montoNuevo = calcularMonto(pesos.val(),tasa.val());

        // Deja todos los numeros multiplos de 1000
        if(montoNuevo > 999){
            montoNuevo = Math.round(montoNuevo/1000)*1000;
        }
        monto.val(montoNuevo);
        // if(pesos.val() > 100){
        //     var resultado = parseInt(saldo.val())+parseInt(pesos.val());
        //     console.log(resultado);
        // }
        if(parseInt(saldo.val()) < parseInt(pesos.val())){
            // boton.attr('disabled',true);
            saldoText.attr('class','text-danger');
            $('#group-pesos').attr('class','form-group has-error');
            alert('El monto en pesos no debe ser mayor al saldo Disponible, usted cuenta con un saldo de: $COP '+saldo.val().toLocaleString());
        }else{
            // boton.attr('disabled',false);
            saldoText.attr('class','text-success');
            $('#group-pesos').attr('class','form-group has-success');
        }
    });
    $('#group-pesos').focusout(function(){
        var pesos = $('#pesos');
        var saldo = $('#saldoDisponible');
        if(parseInt(saldo.val()) > parseInt(pesos.val())){
            $('#group-pesos').attr('class','form-group');
        }
    });
    $('#referenciaTransaccion').on('change keyup',function(){consulta('referenciaTransaccion','transaccion','GET','Reclamos/buscarTransaccion?transaccion=')});
    $('#buscarTransaccion').click(function(){consulta('transaccionClientes','transaccion','GET','Reclamos/buscarTransaccion?transaccion=')});
    $('#referenciaDevolucion').on('change keyup',function(){consulta('referenciaDevolucion','devolucion','GET','Reclamos/buscarTransaccion?transaccion=')});
    // $('#referenciaDevolucion').on('change keyup',function(){consulta('referenciaDevolucion','devolucion','GET','Transferencias/busqueda?transferencia=')});
    $('#tipoUsuario').change(function(){consulta('tipoUsuario','formulario','GET','Usuarios/cargarFormulario?tipoUsuario=')});
    $('#tiendaDiferir').change(function(){consulta('tiendaDiferir','saldoDisponible','GET','Recargas/saldoDisponible?tienda=')});
    
    // $('#distribuidor').change(function(){
    //     console.log('cambio');
    //     // consulta('distribuidor','subdist-options','GET','Usuarios/cargarSubdistribuidores?distribuidor=')
    // });
    $('#fecha').change(function(){
        if($(this).val() == 'fecha'){
            $('#divDia').removeAttr('style');
        }else{
            $('#divDia').attr('style', 'display: none');
        }
    });
    $("#pesosHome").on('change keyup',function(){
        var pesos = $(this);
        var tasa = $('#tasa');
        var monto = $('#bsHome');
        var bolivares = calcularMonto(pesos.val(),tasa.val());
        monto.val(bolivares);
    });
    $("#selectGirador").change(function(){
        if($(this).val() != ''){
            $('#botonAsignar').attr('disabled',false);
        }else{
            $('#botonAsignar').attr('disabled',true);
        }
    });
    $("#numeroCedula").focusout(function(){
        consultaBeneficiario('numeroCedula','nacionalidad','infoBeneficiario','GET','Usuarios/getInfoBeneficiario?cedulaBeneficiario=');
    });
    $("#numeroCedulaCliente").focusout(function(){
        consultaBeneficiario('numeroCedulaCliente','nacionalidadCliente','infoCliente','GET','Usuarios/getInfoCliente?cedulaCliente=');
    });
    // $('#nombre').focusout(function(){
    //     $(this).val($(this).val().toUpperCase());
    // }); 
    for (let index = 1; index < 5; index++) {
        $('#miModal'+index).modal('show');
    }
    $('#registrarTransferencias').submit(function(){
        $('#botonRegistrar').attr('disabled',true);
    });
    $('#btnprueba').click(function () {
        $('#btnprueba').attr('disabled', true);
    });
    $('#MensajeRegistro').modal('show');
    $('#grupo').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Por Recarga'){
            let campo = `<label>Saldo Inicial</label>`
            campo += `<input class="form-control" name="saldoInicial" id="saldoInicial" type="saldoInicial" placeholder="Saldo Inicial" required></input>`
            $('#saldo-group').html(campo);
        }
    });
    
    // Validacion de formularios
    var namePattern = "^[a-z A-Z À-ÿ]{4,30}$";
    var cuentasPattern = "^[0-9.-]{20,20}";
    var setedPattern = "^[0-9.+-]{4,30}";
    
    function checkInput(idInput,pattern){
        return $(idInput).val().match(pattern) ? true : false;
    }
    function checkSelect(idSelect){
        return $(idSelect).val() ? true : false;
    }
        // Registro de Transacciones   
    $("#registroTransacciones").keyup(function (e) { 
        // Uppercase Nombres de beneficiarios y cliente
        $("#nombre").keyup(function (e) {      
            $(this).val($(this).val().toUpperCase()); 
        });
        $("#nombreCliente").keyup(function (e) {      
            $(this).val($(this).val().toUpperCase()); 
        });
        // Fin Uppercase Nombres de beneficiarios y cliente
        if(checkInput("#pesos",setedPattern) && checkSelect("#nacionalidad") && checkInput("#numeroCedula",setedPattern) && checkInput("#nombre",namePattern) && checkInput("#cuenta",cuentasPattern) && checkSelect('#tipoCuenta') && checkSelect("#nacionalidadCliente") && checkInput("#numeroCedulaCliente",setedPattern) && checkInput("#nombreCliente",namePattern) && checkInput("#telefono",setedPattern)){
            $("#botonRegistrar").removeAttr("disabled");
        }else{
            $("#botonRegistrar").attr("disabled", "disabled");
        }
    });
        // Fin Registro de transacciones
    
    // Fin de validaciones

    // Presentacion de Giros con ventanas modales

    $('.preview-btn').click(function(){
        var transaccion = $(this).attr('transaccion');
        console.log('Fuera de Ajax');
        $.ajax({
            type: "POST",
            url: "/../application/controllers/Transacciones.php/previewAjax",
            data: {'transaccion' : transaccion},
            success: function (response) {
                alert('Envio Exitoso');
                console.log("Log de Ajax");
            }
        });
    });
    $('.bank-btn').click(function(){
        var transaccion = $(this).attr('transaccion');
        alert ('Bank button ' + transaccion);
    });

    // Pruebas con el valos flotante en pesos
    $("#pesos").focusout(function () {
        if(parseFloat($(this).val()) > $('#disponible').val()){
            $(this).val(null);
            alert('El monto de la transaccion no puede ser mayor al saldo disponible');     
        }else{
            $(this).val(parseFloat($(this).val()).toFixed(2));
        }
        if($(this).val() < 0){
            alert('El monto a recargar no puede ser menor que 0');
            $(this).val(null);
        }
    });

    // Envio y validacion del registro de usuarios
    $('#registroPersonal').submit(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "post",
            url: this.action,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if(response['error']){
                    let htmlString = `<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <center>` 
                    + response['mensaje'] + 
                    `</center></div>`;
                    $('#mensaje').html(htmlString);
                    // $('#error').fadeOut(5000);
                    // alert(response['mensaje']);
                }else{
                    let htmlString = `<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <center>` 
                    + response['mensaje'] + 
                    `</center></div>`;
                    $('#mensaje').html(htmlString);
                    // window.location.replace("http://127.0.0.1/GirosVzla");
                    setTimeout(function(){window.location.replace(BASE_URL+"Usuarios/listado")},2000);
                }
            }
        });
    });

    // Envio y validacion del registro de transaccion
    $('#registroTransacciones').submit(function (e) { 
        $('#botonRegistrar').attr('disabled',true);
        e.preventDefault();
        $.ajax({
            type: "post",
            url: this.action,
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if(response['error']){
                    let htmlString = `<div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <center>` 
                    + response['mensaje'] + 
                    `</center></div>`;
                    $('#mensaje').html(htmlString);
                    $('#botonRegistrar').attr('disabled',false);
                }else{
                    let htmlString = `<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <center>` 
                    + response['mensaje'] + 
                    `</center></div>`;
                    $('#mensaje').html(htmlString);
                    setTimeout(function(){window.location.replace(BASE_URL+"Transacciones/nuevaTransaccion?transaccion="+response['giro'])},1000);
                }
            }
        });
    });

    // Determinar banco en registro de transacciones
    $('#cuenta').keyup(function(){
        if($(this).val().length > 3){
            var banco = determinarBanco($(this).val());
            htmlString = `<br><strong class="text-success">` + banco + `</strong>`;
            $('#banco').html(htmlString);
        }else{
            $('#banco').html(null);
        }
    });
    $('#grupoTasa').change(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "Transacciones/getTasa2",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                let htmlString = `La tasa de cambio actual es ` + response['tasa']['valor'];
                $('#tasa-actual').html(htmlString);
            }
        });
    });
    function determinarBanco(cuenta){
        switch (cuenta.substr(0,4)) {
            case '0156':
                    var banco = '100% Banco';
                    break;
                case '0114':
                    var banco = 'Bancaribe';
                    break;
                case '0175':
                    var banco = 'Banco Bicentenario';
                    break;
                case '0102':
                    var banco = 'Banco de Venezuela';
                    break;
                case '0163':
                    var banco = 'Banco del Tesoro';
                    break;
                case '0115':
                    var banco = 'Banco Exterior';
                    break;
                case '0151':
                    var banco = 'Banco Fondo Comun';
                    break;
                case '0105':
                    var banco = 'Banco Mercantil';
                    break;
                case '0191':
                    var banco = 'Banco Nacional de Creditos';
                    break;
                case '0116':
                    var banco = 'Banco Occidental de Descuentos';
                    break;
                case '0108':
                    var banco = 'Banco Provincial';
                    break;
                case '0168':
                    var banco = 'Bancrecer';
                    break;
                case '0134':
                    var banco = 'Banesco';
                    break;
                case '0137':
                    var banco = 'Sofitasa';
                    break;
                case '0104':
                    var banco = 'Banco Venezolano de Credito';
                    break;
                case '0128':
                    var banco = 'Banco Caroni';
                    break;
                case '0138':
                    var banco = 'Banco Plaza';
                    break;
                case '0146':
                    var banco = 'Bangente';
                    break;
                case '0157':
                    var banco = 'Banco del Sur';
                    break;
                case '0166':
                    var banco = 'Banco Agricola';
                    break;
                case '0169':
                    var banco = 'Mi Banco';
                    break;
                case '0171':
                    var bando = 'Banco Activo';
                    break;
                case '0172':
                    var banco = 'Banco Bancamiga';
                    break;
                case '0173':
                    var banco = 'Banco Internacional de Desarrollo';
                    break;
                case '0174':
                    var banco = 'Banplus';
                    break;
                case '0176':
                    var banco = 'Banco Espiritu Santo';
                    break;
                case '0190':
                    var banco = 'CityBank';
                    break;
                case '0177':
                    var banco = 'Banco de las Fuernas Armadas Bolivarianas';
                    break;
                case '0601':
                    var banco = 'IMCP';
                    break;
                default:
                    var banco = 'El banco introducido es erroneo'
                    break;
        }
        return banco;
    }
})

