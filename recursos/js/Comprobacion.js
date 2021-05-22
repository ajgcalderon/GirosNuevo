$(function(){
    var namePattern = "^[a-z A-Z]{4,30}$";
    var numberPattern = "^[0-9.,]{1,20}$"
    var emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$";
    
    function checkInput(idInput, pattern) {
        return $(idInput).val().match(pattern) ? true : false;
    }

    function checkRadioBox(nameRadioBox) {
        return $(nameRadioBox).is(":checked") ? true : false;
    }

    function checkSelect(idSelect) {
        return $(idSelect).val() ? true : false;
    }

    function enableSubmit (idForm) {
    $(idForm + " button.submit").removeAttr("disabled");
    }
        
    function disableSubmit (idForm) {
    $(idForm + " button.submit").attr("disabled", "disabled");
    }

    function validar(idForm){
        // var referencia = $('#referencia');
        // var monto = $('#monto');
        // var fecha = $('#fecha');
        // var tasa = $('#tasa');
        // var pesos = $('#pesos');
        // var numeroCedula = $('#numeroCedula');
        // var nombre = $('#nombre');
        // var cuenta = $('#cuenta');
        // var banco = $('#banco');
        // var tipoCuenta = $('#tipoCuenta');
        
        $(idForm + " *").on("change keydown",function(){
            if (checkInput("#referencia",namePattern) && checkInput("#monto",numberPattern) && checkInput("#tasa",numberPattern) && checkInput("#pesos",numberPattern) && checkInput("#numeroCedula",numberPattern) && checkInput("#nombre",namePattern) && checkInput("#cuenta",numberPattern) && checkSelect("#banco") && checkSelect("#tipoCuenta")) {
                enableSubmit(idForm);
            }else {
                disableSubmit(idForm);
            }
        });
    }
    validar('registroTransacciones');
});