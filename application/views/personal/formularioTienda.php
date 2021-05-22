<div class="form-group">
    <label >Nombre</label>
    <input class="form-control" name="nombre" id="nombre" type="text" placeholder="Nombre" required>
</div>
<div class="form-group">
    <label>Apellido</label>
    <input class="form-control" name="apellido" id="apellido" type="text" placeholder="Apellido" required>
</div>
<div class="form-group form-inline">
    <label>Rif</label>
    <select class="form-control" name="nacionalidad" id="nacionalidad" readonly>
        <option value="J">J</option>
    </select>
    <input class="form-control" name="numeroCedula" id="numeroCedula" type="text" placeholder="RIF">
</div>
<?php if($this->session->userdata('tipo')==USUARIO_ADMINISTRADOR){?>

<div class="form-group">
    <label class="control-label" for="tipoTienda">Tipo Tienda</label>
    <select name="tipoTienda" id="tipoTienda" class="form-control" required>
        <option value="">Seleccione un tipo de Tienda</option>
        <option value="<?php echo GRUPO_CONSIGNACION;?>"> Consignación</option>
        <option value="<?php echo GRUPO_EMPRESA;?>"> Recaudo</option>
    </select>
</div>
<div class="form-group" id="div_grupo"> 
    <label class="control-label" for="grupo">Distribuidor</label>
    <select name="grupo" id="grupo" class="form-control" >
        <option value="">Seleccione un Distribuidor</option>
        <?php foreach ($distribuidores as $distribuidor) { ?>
            <option value="<?php echo $distribuidor->grupo;?>"><?php echo $distribuidor->nombre . ' ' . $distribuidor->apellido . ' (' . $distribuidor->grupo . ')';?></option>
        <?php }?>
    </select>
</div>
        <?php }else{?>
            <input type="text" name="grupo" value="<?php echo $this->session->userdata('grupo');?>" style="display:none">
        <?php }?>
<?php if($this->session->userdata('tipo')==USUARIO_ADMINISTRADOR || $this->session->userdata('tipo')==USUARIO_DISTRIBUIDOR){ ?>
<div class="form-group">
    <label for="subdistribuidor">Subdistribuidor</label>
    <select name="subdistribuidor" id="subdistribuidor" class="form-control">
        <option value="">Seleccione un subdistribuidor</option>
        <?php foreach ($subdistribuidores as $subdistribuidor ) { ?>
            <option value="<?php echo $subdistribuidor->subgrupo;?>"><?php echo $subdistribuidor->nombre . ' ' . $subdistribuidor->apellido . ' (' .  $subdistribuidor->subgrupo . ')';?></option>
        <?php }?>
    </select>
</div>
        <?php }else{?>
            <input type="text" name="subdistribuidor" value="<?php echo $this->session->userdata('subgrupo');?>" style="display: none">
        <?php }?>
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
    <input class="form-control" name="comision" id="comision" type="comision" placeholder="Comision" required>
</div>
<!-- Saldo inicial tiendas por recarga -->
<div class="form-group" id="saldo-group">
</div>