<div class="form-group">
    <label>Nombre</label>
    <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre" required>
</div>
<div class="form-group">
    <label>Apellido</label>
    <input class="form-control" name="apellido" id="apellido" type="text" placeholder="Apellido" required>
</div>
<div class="form-group form-inline">
    <label>Cedula</label>
    <select class="form-control" name="nacionalidad" id="nacionalidad">
        <option value="V">V</option>
        <option value="E">E</option>
    </select>
    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="Numero de Cedula" required>
</div>
<div class="form-group">
    <label>Nombre de Usuario</label>
    <input class="form-control" name="nombreUsuario" id="nombreUsuario" type="text" placeholder="Nombre de Usuario" required>
</div>
<div class="form-group">
    <label>Contraseña</label>
    <input class="form-control" name="password" id="password" type="password" placeholder="Contraseña" required>
</div>
<div class="form-group">
    <label>Comision</label>
    <input class="form-control" name="comision" id="comision" type="text" placeholder="Comision" required>
</div>
<?php if($this->session->userdata('tipo')==USUARIO_ADMINISTRADOR){?>
<div class="form-group">
    <label class="control-label" for="distribuidor">Distribuidor</label>
    <select name="distribuidor" id="distribuidor" class="form-control">
        <option value="">Seleccione un Distribuidor</option>
        <?php foreach ($distribuidores as $distribuidor) { ?>
            <option value="<?php echo $distribuidor->grupo;?>"><?php echo $distribuidor->nombre . ' ' . $distribuidor->apellido . ' (' . $distribuidor->grupo . ')';?></option>
        <?php }?>
    </select>
</div>
        <?php }else{?>
            <input type="text" name="distribuidor" value="<?php echo $this->session->userdata('grupo');?>" style="display:none">
        <?php }?>
