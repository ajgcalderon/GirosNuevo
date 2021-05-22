<?php if($cliente){
    ?>
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input class="form-control" name="nombreCliente" id="nombreCliente" type="text" placeholder="Nombre del cliente" value="<?php echo $cliente->nombre;?>" required>
    </div>
<?php }else{?>
    <div class="form-group">
        <label class="control-label">Nombre</label>
        <input class="form-control" name="nombreCliente" id="nombreCliente" type="text" placeholder="Nombre del cliente" required>
    </div>
<?php }?>
<div class="form-group form-inline">
    <label class="control-label">Telefono</label>
    <select class="form-control" name="codArea" id="codArea" >
        <option value="+57">+57 - Colombia</option>
        <option value="+58">+58 - Venezuela</option>
    </select>
    <input class="form-control" name="telefono" id="telefono" type="text" placeholder="Numero de telefono" required>
</div>