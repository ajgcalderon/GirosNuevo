<?php if($Beneficiario){
    ?>
    <div class="form-group">
        <label class="control-label">Nombre del Beneficiario</label>
        <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre del Beneficiario" value="<?php echo $Beneficiario->nombre;?>" required>
    </div>
    <div class="form-group" id="cuenta-group">
        <label class="control-label" for="cuenta">Numero de Cuenta</label>
        <input class="form-control" name="cuenta" id="cuenta" type="number" placeholder="Numero de Cuenta" minlength="20" maxlength="20" value="<?php echo $Beneficiario->cuenta;?>" required>
        <div id="banco"><strong class="text-success"><?php echo $Beneficiario->banco; ?></strong></div>
    </div>
    <div class="form-group">
        <label class="control-label">Tipo de Cuenta</label>
        <select class="form-control" name="tipoCuenta" id="tipoCuenta" required>
            <option value="Seleccion">Seleccione un Tipo de Cuenta</option>
            <option value="Ahorros" <?php if($Beneficiario->tipo == "Ahorros"){echo 'selected';}?>>Ahorros</option>
            <option value="Corriente" <?php if($Beneficiario->tipo == "Corriente"){echo 'selected';}?>>Corriente</option>
        </select>
    </div>
<?php }else{?>
    <div class="form-group">
        <label class="control-label">Nombre del Beneficiario</label>
        <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre del Beneficiario" required>
    </div>
    <div class="form-group" id="cuenta-group">
        <label class="control-label" for="cuenta">Numero de Cuenta</label>
        <input class="form-control" name="cuenta" id="cuenta" type="number" placeholder="Numero de Cuenta" minlength="20" maxlength="20" required>
    </div>
    <div class="form-group">
        <label class="control-label">Tipo de Cuenta</label>
        <select class="form-control" name="tipoCuenta" id="tipoCuenta" required>
            <option value="Seleccion">Seleccione un Tipo de Cuenta</option>
            <option value="Ahorros">Ahorros</option>
            <option value="Corriente">Corriente</option>
        </select>
    </div>
<?php }?>