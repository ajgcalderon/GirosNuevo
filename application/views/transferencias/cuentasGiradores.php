<label for="cuentaAcreditada">Cuentas</label>
<select name="cuentaAcreditada" id="cuentaAcreditada" class="form-control">
    <option value="">Seleccione una Cuenta</option>
    <?php foreach ($cuentas as $cuenta) {?>
        <option value="<?php echo $cuenta->numero;?>"><?php echo $cuenta->banco . ' - ' . $cuenta->tipo . ' - ' . substr($cuenta->numero,0,4) . '************' . substr($cuenta->numero,16,4);?></option>
    <?php }?>
</select>
