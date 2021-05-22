$(function(){

    var namePattern = "^[a-z A-Z Ññ]{4,30}$";
    var numberPattern = "^[0-9.,]{1,15}$";
    var emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$";
     
    function checkInput(idInput, pattern) {
        return $(idInput).val().match(pattern) ? true : false;
    }
    
    function checkSelect(idSelect) {
        return $(idSelect).val() ? true : false;
    }
    
    function checkRadioBox(nameRadioBox) {
        return $(nameRadioBox).is(":checked") ? true : false;
    }
    
    function enableSubmit (idForm) {
        $(idForm + " button.submit").removeAttr("disabled");
    }
            
    function disableSubmit (idForm) {
        $(idForm + " button.submit").attr("disabled", "disabled");
    }
    
    function validarFomulario(idForm,idInputs) {
        $(idForm).on('change keydown',function () {
           idInputs.forEach(function(value,index) {
               
           }); 
           
        });
    }
    
    function checkForm (idForm) {
        $(idForm + " *").on("change keydown", function() {
            if (checkInput("#nombre", namePattern) && checkInput("#apellidos", namePattern) && checkInput("#email", emailPattern) && checkSelect("#edad") && checkTextarea("#comentario") && checkRadioBox("#legal") &&checkRadioBox("[name=boletin]"))
            {
                enableSubmit(idForm);
            } else {
                disableSubmit(idForm);
            }
        });
    }
    
    function validar(idForm){
        $(idForm).on("submit",function(){
            if(checkInput("#monto",numberPattern)){
                alert('El valor es valido');
            }else{
                alert('Por favor ingrese otro Valor');
                $('#monto').focus();
                return false;
            }
        });
    }
    
    
    /* Validacion de formularios */
    function validarRadio(nameRadioBox){
        if(!checkRadioBox(nameRadioBox)){
            alert('Debe seleccionar al menos una opcion');
            return false;
        }
    }
    
    function validarNumero(idInput) {
        if(!checkInput(idInput,numberPattern)){
            alert('Valor invalido, El valor debe ser un valor numerico');
            $(idInput).focus();
            return false;
        }
        return true;
    }
    
    function validarTexto(idInput) {
        if(!checkInput(idInput,namePattern)){
            alert('Valor invalido');
            $(idInput).focus();
            return false;
        }
        return true;
    }
    
    function validarSelect(idSelect){
        if(!checkSelect(idSelect)){
            alert('Por favor haz una seleccion');
            $(idSelect).focus();
            return false;
        }
        return true;
    }
    
    function validarFormularios(){
        $('#registrarTransferencias').on("submit",function(){
            if(!validarNumero('#referencia')){
                return false;
            }
            validarSelect('#cuenta');
            validarSelect('#transaccion');
        });
        $('#recargarCuentas').on("submit",function(){
            validarRadio('[name=cuentas[]]');
            validarNumero('#monto');
        });
        $('#registrarCuenta').on("submit",function(){
            validarNumero('#numero');
            validarSelect('#banco');
            validarSelect('#tipoCuenta');
            validarTexto('#nombre');
            validarSelect('#nacionalidad');
            validarNumero('#numeroCedula');
            validarSelect('#girador');
        });
        $('#registrarReclamos').on('submit',function(){
            validarNumero('#referencia');
        });
        $('#asignarTransacciones').on('submit',function(){
            validarRadio('[name=transacciones[]]');
            validarSelect('#girador');
        });
        $('#registroTransacciones').on('submit',function(){
            validarNumero('#referencia');
            validarNumero('#monto');
            validarNumero('#pesos');
            validarSelect('#nacionalidad');
            validarNumero('#numeroCedula');
            validarTexto('#nombre');
            validarNumero('#cuenta');
            validarSelect('#tipoCuenta');
        });
        $('#registrarDevolucion').on('submit',function(){
            validarNumero('#referencia');
        });
        // $('#transferirAGirador').on('submit',function(){
        //     validarNumero('#monto');
        //     validarSelect('#girador');
        //     validarSelect('#cuentas');
        // });
    }
    $('#changePass').keyup(function (e) { 
        if($('#password').val() != $('#passwordConfirm').val()){
            $('#btn-submit').attr('disabled', true);    
        }else{
            $('#btn-submit').removeAttr('disabled');
        }
    });
    $('#passwordConfirm').keyup(function (e) { 
        if($('#password').val() != $('#passwordConfirm').val()){
            htmlString = `<div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <center> Los campos no coinciden </center></div>`;
            $('#mensaje').html(htmlString);     
        }else{
            htmlString = null;
            $('#mensaje').html(htmlString);   
        }
    });
    $('#changePass').submit(function (e) { 
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
                }else{
                    let htmlString = `<div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <center>` 
                    + response['mensaje'] + 
                    `</center></div>`;
                    $('#mensaje').html(htmlString);
                    
                    setTimeout(function(){window.location.replace(BASE_URL)},2000);
                }
            }
        });
    });
});
